<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $models = DB::table('model')->whereNotNull('name')->where('name', '!=', '')->orderByDesc('createdate')->get()->toArray();
        $details = DB::table('mdetails')->get();
        $payments = DB::table('payments')
            ->leftJoin('model', 'model.id', '=', 'payments.mid')
            ->orderByDesc('payments.paymentdate')
            ->limit(10)
            ->get(['payments.*', 'model.name as model_name', 'model.surname as model_surname']);
        $active_payments = DB::table('payments')->where('validdate', '>=', now())->get();
        $photos = DB::table('mphoto')->where('main', 'y')->get();
        $active_models = $inactive_models = $verified_models = 0;
        foreach ($models as $model) {
            $model->details = self::getDetails($model->id, $details);
            $main = self::getMainPhoto($model->id, $photos);
            $model->main_photo_url = $main ? route('models.photo.show', ['id' => $model->id, 'pid' => $main->id]) : null;
            if ($model->details && $model->details->verified) {
                $verified_models++;
            }
            if (self::getPayment($model->id, $active_payments)) {
                $active_models++;
            } else {
                $inactive_models++;
            }
        }
        return view('home', compact('models', 'details', 'active_models', 'inactive_models', 'verified_models', 'payments'));
    }

    public function models()
    {
        $status = request('status');
        $area = request('area');
        $query = DB::table('model')->whereNotNull('name')->where('name', '!=', '');
        if ($area) {
            $query->whereExists(function ($q) use ($area) {
                $q->select(DB::raw(1))->from('mlocation')->whereColumn('mlocation.mid', 'model.id')->where('pid', $area);
            });
        }
        if ($status === 'paid') {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('payments')->whereColumn('payments.mid', 'model.id')->where('validdate', '>=', now());
            });
        } elseif ($status === 'inactive') {
            $query->whereNotExists(function ($q) {
                $q->select(DB::raw(1))->from('payments')->whereColumn('payments.mid', 'model.id')->where('validdate', '>=', now());
            });
        }
        if (request('verified') === '1') {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))->from('mdetails')->whereColumn('mdetails.mid', 'model.id')->where('verified', 1);
            });
        }
        $models = $query->orderByDesc('createdate')->get();
        $details = DB::table('mdetails')->get();
        $locations = DB::table('mlocation')->get();
        $models_location = DB::table('ltown')->get();
        $main_photos = DB::table('mphoto')->where('main', 'y')->get();
        $payments = DB::table('payments')->orderByDesc('paymentdate')->orderByDesc('id')->get();
        $provinces = DB::table('lprovince')->orderBy('name')->get();
        return view('models', compact('models', 'details', 'locations', 'models_location', 'main_photos', 'payments', 'provinces'));
    }

    public function others()
    {
        $models = DB::table('model')
            ->where(function ($query) {
                $query->where('name', '')->orWhereNull('name');
            })
            ->orderByDesc('createdate')
            ->get();
        return view('others', compact('models'));
    }

    public function model_create()
    {
        return view('models.create', array_merge($this->profileLookups(), [
            'model' => null,
            'model_details' => null,
            'model_location' => null,
            'model_login' => null,
        ]));
    }

    public function model_store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->profileRules());
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator)->with('error', 'Check the highlighted fields.');
        }
        $locationError = $this->locationError($request);
        if ($locationError) {
            return back()->withInput()->withErrors(['town' => $locationError])->with('error', 'Check the highlighted fields.');
        }
        $id = DB::transaction(function () use ($request) {
            $id = DB::table('model')->insertGetId([
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'createdate' => now(),
                'modifydate' => now(),
            ]);
            $this->persistProfile($request, $id);
            return $id;
        });
        return redirect()->route('models.details', $id)->with('success', 'Model created');
    }

    public function model_details($id)
    {
        $model = DB::table('model')->find($id);
        if (! $model) {
            abort(404);
        }
        $model_details = DB::table('mdetails')->where('mid', $id)->first();
        $model_location = DB::table('mlocation')->where('mid', $id)->first();
        $model_login = DB::table('login')->where('uid', $id)->first();
        $model_photos = DB::table('mphoto')->where('mid', $id)->orderByDesc('id')->get();
        $model_payment = DB::table('payments')->where('mid', $id)->orderByDesc('paymentdate')->orderByDesc('id')->first();
        $payment_current = DB::table('payments')->where('mid', $id)->where('validdate', '>=', now())->exists();
        return view('model-details', array_merge(
            compact('model', 'model_details', 'model_location', 'model_login', 'model_photos', 'model_payment', 'payment_current'),
            $this->profileLookups()
        ));
    }

    public function model_update(Request $request, $id)
    {
        if (! DB::table('model')->where('id', $id)->exists()) {
            abort(404);
        }
        $validator = Validator::make($request->all(), $this->profileRules());
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator)->with('error', 'Check the highlighted fields.');
        }
        $locationError = $this->locationError($request);
        if ($locationError) {
            return back()->withInput()->withErrors(['town' => $locationError])->with('error', 'Check the highlighted fields.');
        }
        try {
            DB::transaction(function () use ($request, $id) {
                DB::table('model')->where('id', $id)->update([
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'email' => $request->input('email'),
                    'mobile' => $request->input('mobile'),
                    'modifydate' => now(),
                ]);
                $this->persistProfile($request, $id);
            });
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->withInput()->with('error', 'Details could not be saved.');
        }
        return back()->with('success', 'Details Updated');
    }

    public function model_listing(Request $request, $id)
    {
        if (! DB::table('model')->where('id', $id)->exists()) {
            abort(404);
        }
        $current = DB::table('payments')->where('mid', $id)->where('validdate', '>=', now())->orderByDesc('validdate')->first();
        if ($request->input('show') === '1') {
            $until = now()->addWeeks(4);
            if (! $current) {
                DB::table('payments')->insert([
                    'mid' => $id,
                    'paymentdate' => now(),
                    'validdate' => $until,
                    'transactionid' => null,
                    'subscriptionid' => null,
                ]);
            } else {
                $until = \Carbon\Carbon::parse($current->validdate);
            }
            return back()->with('success', 'Profile is active on the public site until '.$until->format('d M Y, H:i').'. A main photo is also required.');
        }
        DB::table('payments')->where('mid', $id)->where('validdate', '>=', now())->update([
            'validdate' => now()->subSecond(),
        ]);
        return back()->with('success', 'Profile is hidden on the public site. The current payment now ends immediately.');
    }

    public function payment_store(Request $request, $id)
    {
        if (! DB::table('model')->where('id', $id)->exists()) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'paymentdate' => 'required|date',
            'validdate' => 'required|date|after_or_equal:paymentdate',
            'transactionid' => 'nullable|string|max:255',
            'subscriptionid' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator)->with('error', 'Check the highlighted fields.');
        }
        DB::table('payments')->insert([
            'mid' => $id,
            'paymentdate' => $request->input('paymentdate'),
            'validdate' => $request->input('validdate'),
            'transactionid' => $request->input('transactionid'),
            'subscriptionid' => $request->input('subscriptionid'),
        ]);
        return back()->with('success', 'Payment recorded');
    }

    public function model_photo_update(Request $request, $id)
    {
        if (! DB::table('model')->where('id', $id)->exists()) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($validator->fails()) {
            return $this->photoFailure($request, $validator->errors()->first());
        }
        $uploaded = $request->file('photo');
        $info = @getimagesize($uploaded->getRealPath());
        if (! $info || $info[0] < 1 || $info[1] < 1 || ($info[0] * $info[1]) > 24000000) {
            return $this->photoFailure($request, 'Photo is too large to process.');
        }
        $src = $this->imageFromUpload($uploaded->getRealPath(), (int) $info[2]);
        if (! $src) {
            return $this->photoFailure($request, 'Photo could not be read.');
        }
        $name = (string) Str::uuid().'.webp';
        $dir = storage_path('app/models/'.$id);
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            imagedestroy($src);
            return $this->photoFailure($request, 'Photo could not be stored.');
        }
        $dest = $dir.DIRECTORY_SEPARATOR.$name;
        $written = imagewebp($src, $dest, 80);
        imagedestroy($src);
        if (! $written) {
            return $this->photoFailure($request, 'Photo could not be stored.');
        }
        $frontDir = $this->frontPhotoDirectory($id);
        if ($frontDir !== null) {
            if (! is_dir($frontDir) && ! mkdir($frontDir, 0755, true) && ! is_dir($frontDir)) {
                $frontDir = null;
            }
            if ($frontDir !== null && ! @copy($dest, $frontDir.DIRECTORY_SEPARATOR.$name)) {
                @unlink($dest);
                return $this->photoFailure($request, 'Photo could not be stored.');
            }
        }
        try {
            DB::transaction(function () use ($request, $id, $name) {
                DB::table('model')->where('id', $id)->lockForUpdate()->first();
                $photoId = DB::table('mphoto')->insertGetId(['mid' => $id, 'photo' => $name, 'main' => null]);
                $hasOtherMain = DB::table('mphoto')->where('mid', $id)->where('id', '!=', $photoId)->where('main', 'y')->exists();
                if ($request->boolean('main') || ! $hasOtherMain) {
                    DB::table('mphoto')->where('mid', $id)->where('id', '!=', $photoId)->update(['main' => null]);
                    DB::table('mphoto')->where('id', $photoId)->update(['main' => 'y']);
                }
            });
        } catch (\Throwable $e) {
            @unlink($dest);
            $frontDir = $this->frontPhotoDirectory($id);
            if ($frontDir !== null) {
                @unlink($frontDir.DIRECTORY_SEPARATOR.$name);
            }
            Log::error($e);
            return $this->photoFailure($request, 'Photo could not be stored.');
        }
        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Photo Updated');
    }

    public function show_photo($id, $pid)
    {
        $photo = DB::table('mphoto')->where('id', $pid)->where('mid', $id)->first();
        if (! $photo || ! $photo->photo || strpos($photo->photo, '/') !== false || strpos($photo->photo, '\\') !== false) {
            abort(404);
        }
        $path = $this->photoFile($id, $photo->photo);
        if ($path === null) {
            abort(404);
        }
        $type = 'application/octet-stream';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'webp') {
            $type = 'image/webp';
        } elseif ($ext === 'jpg' || $ext === 'jpeg') {
            $type = 'image/jpeg';
        } elseif ($ext === 'png') {
            $type = 'image/png';
        }
        return response()->file($path, [
            'Content-Type' => $type,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function delete_photo(Request $request, $mid, $pid)
    {
        $photo = DB::table('mphoto')->where('id', $pid)->where('mid', $mid)->first();
        if (! $photo) {
            abort(404);
        }
        DB::table('mphoto')->where('id', $pid)->where('mid', $mid)->delete();
        if ($photo->photo && strpos($photo->photo, '/') === false && strpos($photo->photo, '\\') === false) {
            $name = basename($photo->photo);
            @unlink(storage_path('app/models/'.$mid.DIRECTORY_SEPARATOR.$name));
            $frontDir = $this->frontPhotoDirectory($mid);
            if ($frontDir !== null) {
                @unlink($frontDir.DIRECTORY_SEPARATOR.$name);
            }
        }
        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Photo deleted');
    }

    public function body_types()
    {
        $body_types = DB::table('mbodytype')->orderBy('name')->get();
        return view('tables', compact('body_types'));
    }

    public function body_type_store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('mbodytype')->insert(['name' => $request['name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Body Type Created');
    }

    public function body_type_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['edit_name' => 'required'], ['edit_name.required' => ' The name field is required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('mbodytype')->where('id', $id)->update(['name' => $request['edit_name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Body Type Updated');
    }

    public function body_type_delete($id): RedirectResponse
    {
        if ($body = DB::table('mbodytype')->find($id)) {
            if (DB::table('mdetails')->where('btype', $body->id)->count() > 0)
                return back()->with('error', 'Body Type cannot be deleted because profiles still use it');
            if (DB::table('mbodytype')->where('id', $id)->delete())
                return back()->with('success', 'Body Type Deleted');
        }
        return back()->with('error', 'Body Type Not Found');
    }

    public function bust_sizes()
    {
        $bust_sizes = DB::table('mbust')->orderBy('name')->get();
        return view('tables', compact('bust_sizes'));
    }

    public function bust_size_store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('mbust')->insert(['name' => $request['name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Bust Size Created');
    }

    public function bust_size_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['edit_name' => 'required'], ['edit_name.required' => ' The name field is required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('mbust')->where('id', $id)->update(['name' => $request['edit_name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Bust Size Updated');
    }

    public function bust_size_delete($id): RedirectResponse
    {
        if ($bust = DB::table('mbust')->find($id)) {
            if (DB::table('mdetails')->where('bust', $bust->name)->count() > 0)
                return back()->with('error', 'Bust Size cannot be deleted because profiles still use it');
            if (DB::table('mbust')->where('id', $id)->delete())
                return back()->with('success', 'Bust Size Deleted');
        }
        return back()->with('error', 'Bust Size Not Found');
    }

    public function ethnics()
    {
        $ethnics = DB::table('methnic')->orderBy('name')->get();
        return view('tables', compact('ethnics'));
    }

    public function ethnic_store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('methnic')->insert(['name' => $request['name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Ethnic Created');
    }

    public function ethnic_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['edit_name' => 'required'], ['edit_name.required' => ' The name field is required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('methnic')->where('id', $id)->update(['name' => $request['edit_name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Ethnic Updated');
    }

    public function ethnic_delete($id): RedirectResponse
    {
        if ($ethnic = DB::table('methnic')->find($id)) {
            if (DB::table('mdetails')->where('ethnic', $ethnic->id)->count() > 0)
                return back()->with('error', 'Cannot delete Ethnic because profiles still use it');
            if (DB::table('methnic')->where('id', $id)->delete())
                return back()->with('success', 'Ethnic Deleted Successfully');
        }
        return back()->with('error', 'Ethnic Not Found');
    }

    public function orientations()
    {
        $orientations = DB::table('morientation')->orderBy('name')->get();
        return view('tables', compact('orientations'));
    }

    public function orientation_store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['name' => 'required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('morientation')->insert(['name' => $request['name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Orientation Created');
    }

    public function orientation_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['edit_name' => 'required'], ['edit_name.required' => ' The name field is required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('morientation')->where('id', $id)->update(['name' => $request['edit_name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Orientation Updated');
    }

    public function orientation_delete($id): RedirectResponse
    {
        if ($orientation = DB::table('morientation')->find($id)) {
            if (DB::table('mdetails')->where('orientation', $orientation->id)->count() > 0)
                return back()->with('error', 'Cannot delete Orientation because profiles still use it');
            if (DB::table('morientation')->where('id', $id)->delete())
                return back()->with('success', 'Orientation Deleted Successfully');
        }
        return back()->with('error', 'Orientation Not Found');
    }

    public function towns()
    {
        $towns = DB::table('ltown')->orderBy('name')->get();
        $provinces = DB::table('lprovince')->orderBy('name')->get();
        $countries = DB::table('lcountry')->orderBy('name')->get();
        return view('tables', compact('towns', 'provinces', 'countries'));
    }

    public function town_store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['country' => 'required', 'province' => 'required', 'name' => 'required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('ltown')->insert(['cid' => $request['country'], 'pid' => $request['province'], 'name' => $request['name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Town Created');
    }

    public function town_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), ['edit_country' => 'required', 'edit_province' => 'required', 'edit_name' => 'required'],
            ['edit_country.required' => ' The country field is required', 'edit_province.required' => ' The provinces field is required', 'edit_name.required' => ' The name field is required']);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');
        try {
            DB::table('ltown')->where('id', $id)->update(['cid' => $request['edit_country'], 'pid' => $request['edit_province'], 'name' => $request['edit_name']]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Town Updated');
    }

    public function town_delete($id): RedirectResponse
    {
        if ($town = DB::table('ltown')->find($id)) {
            if (DB::table('mlocation')->where('lid', $town->id)->count() > 0)
                return back()->with('error', 'Cannot Delete Town because profiles still use it');
            if (DB::table('ltown')->where('id', $id)->delete())
                return back()->with('success', 'Town Deleted Successfully');
        }
        return back()->with('error', 'Town Not Found');
    }

    private function profileLookups(): array
    {
        return [
            'bodies' => DB::table('mbodytype')->orderBy('name')->get(),
            'busts' => DB::table('mbust')->orderBy('name')->get(),
            'orientations' => DB::table('morientation')->orderBy('name')->get(),
            'ethnics' => DB::table('methnic')->orderBy('name')->get(),
            'countries' => DB::table('lcountry')->orderBy('name')->get(),
            'provinces' => DB::table('lprovince')->orderBy('name')->get(),
            'towns' => DB::table('ltown')->orderBy('name')->get(),
        ];
    }

    private function profileRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile' => 'required|string|max:65000',
            'screenname' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:99',
            'weight' => 'nullable|integer|min:1|max:999',
            'height' => 'nullable|integer|min:1|max:999',
            'bust' => 'nullable|exists:mbust,name',
            'btype' => 'required|integer|exists:mbodytype,id',
            'orientation' => 'required|integer|exists:morientation,id',
            'ethnic' => 'required|integer|exists:methnic,id',
            'country' => 'required|integer|exists:lcountry,id',
            'province' => 'required|integer|exists:lprovince,id',
            'town' => 'required|integer|exists:ltown,id',
            'smoke' => 'nullable|in:y',
            'drink' => 'nullable|in:y',
            'travel' => 'nullable|in:y',
            'verified' => 'nullable|in:1',
            'top' => 'nullable|in:1',
            'imageverified' => 'nullable|in:1',
        ];
    }

    private function locationError(Request $request): ?string
    {
        $province = DB::table('lprovince')->where('id', $request->input('province'))->where('cid', $request->input('country'))->first();
        if (! $province) {
            return 'Province does not belong to the selected country.';
        }
        $town = DB::table('ltown')->where('id', $request->input('town'))->where('pid', $request->input('province'))->where('cid', $request->input('country'))->first();
        if (! $town) {
            return 'Town does not belong to the selected province.';
        }
        return null;
    }

    private function persistProfile(Request $request, $id): void
    {
        $details = [
            'profile' => $request->input('profile'),
            'screenname' => $request->input('screenname'),
            'age' => $request->input('age'),
            'bust' => $request->input('bust'),
            'btype' => $request->input('btype'),
            'orientation' => $request->input('orientation'),
            'ethnic' => $request->input('ethnic'),
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),
            'smoke' => $request->input('smoke') === 'y' ? 'y' : 'n',
            'drink' => $request->input('drink') === 'y' ? 'y' : 'n',
            'travel' => $request->input('travel') === 'y' ? 'y' : 'n',
            'verified' => $request->input('verified') === '1' ? 1 : 0,
            'top' => $request->input('top') === '1' ? 1 : 0,
            'imageverified' => $request->input('imageverified') === '1' ? 1 : 0,
        ];
        if (DB::table('mdetails')->where('mid', $id)->exists()) {
            DB::table('mdetails')->where('mid', $id)->update($details);
        } else {
            $details['mid'] = $id;
            DB::table('mdetails')->insert($details);
        }
        $location = ['lid' => $request->input('town'), 'pid' => $request->input('province'), 'cid' => $request->input('country')];
        if (DB::table('mlocation')->where('mid', $id)->exists()) {
            DB::table('mlocation')->where('mid', $id)->update($location);
        } else {
            $location['mid'] = $id;
            DB::table('mlocation')->insert($location);
        }
        if (DB::table('login')->where('uid', $id)->exists()) {
            DB::table('login')->where('uid', $id)->update(['username' => $request->input('username')]);
        } else {
            DB::table('login')->insert([
                'uid' => $id,
                'username' => $request->input('username'),
                'datecreated' => now(),
                'datemodified' => now(),
            ]);
        }
        if ($request->filled('password')) {
            DB::table('login')->where('uid', $id)->update([
                'password' => Hash::make($request->input('password')),
                'datemodified' => now(),
            ]);
        }
    }

    private function frontPhotoDirectory($modelId): ?string
    {
        $parent = dirname(base_path());
        $candidates = [
            $parent.DIRECTORY_SEPARATOR.'LG_Front'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'models',
            $parent.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'models',
        ];
        foreach ($candidates as $root) {
            $realRoot = realpath($root);
            if ($realRoot !== false) {
                return $realRoot.DIRECTORY_SEPARATOR.$modelId;
            }
        }
        return null;
    }

    private function photoFile($modelId, $filename): ?string
    {
        $name = basename((string) $filename);
        if ($name === '' || $name !== (string) $filename) {
            return null;
        }
        $candidates = [
            storage_path('app/models/'.$modelId.DIRECTORY_SEPARATOR.$name),
        ];
        $frontDir = $this->frontPhotoDirectory($modelId);
        if ($frontDir !== null) {
            $candidates[] = $frontDir.DIRECTORY_SEPARATOR.$name;
        }
        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }
        return null;
    }

    private function photoFailure(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 422);
        }
        return back()->with('error', $message);
    }

    private function imageFromUpload(string $path, int $type)
    {
        if ($type === IMAGETYPE_JPEG) {
            return @imagecreatefromjpeg($path);
        }
        if ($type === IMAGETYPE_PNG) {
            $image = @imagecreatefrompng($path);
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            return $image;
        }
        if ($type === IMAGETYPE_WEBP) {
            return @imagecreatefromwebp($path);
        }
        return false;
    }

    public static function getLogin($mid)
    {
        return DB::table('login')->where('uid', $mid)->first();
    }

    public static function getMainPhoto($mid, $photoArr)
    {
        foreach ($photoArr as $photo)
            if ($photo->mid == $mid)
                return $photo;
        return null;
    }

    public static function getDetails($mid, $detailsArr)
    {
        foreach ($detailsArr as $details)
            if ($details->mid == $mid)
                return $details;
        return null;
    }

    public static function getLocation($mid, $locArr, $townArr)
    {
        foreach ($locArr as $loc)
            if ($loc->mid == $mid)
                foreach ($townArr as $town)
                    if ($town->id == $loc->lid)
                        return $town;
        return null;
    }

    public static function getPayment($mid, $paymentArr)
    {
        foreach ($paymentArr as $payment)
            if ($payment->mid == $mid)
                return $payment;
        return null;
    }

    public static function getNextPaymentDate($date)
    {
        $today = date('Y-m-d');
        $date = date('Y-m-d', strtotime($date));
        while ($date <= $today) {
            $date = date('Y-m-d', strtotime($date."+28 days"));
        }
        return strtotime($date);
    }
}

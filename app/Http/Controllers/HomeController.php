<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $models = DB::table('model')->where('name', '!=', null)->orderByDesc('createdate')->get()->toArray();
        $details = DB::table('mdetails')->get();
        $payments = DB::table('payments')->orderByDesc('paymentdate')->limit(10)->get();
        $active_payments = DB::table('payments')->where('validdate', '>=', now())->get();
        $photos = DB::table('mphoto')->where('main', 'y')->get();
        $active_models = $inactive_models = $verified_models = 0;
        foreach ($models as $model) {
            $model->details = self::getDetails($model->id, $details);
            $model->main_photo = self::getMainPhoto($model->id, $photos)->photo ?? null;
            if ($model->details && $model->details->verified)
                $verified_models++;
            if (self::getPayment($model->id, $active_payments))
                $active_models ++;
            else
                $inactive_models++;
        }
        return view('home', compact('models', 'details', 'active_models', 'inactive_models', 'verified_models', 'payments'));
    }

    public function models()
    {
        $models = DB::table('model')->where('name', '!=', null);
        $paid = request()->has('status');
        $area = request('area');
        if ($area)
            $models = $models->join('mlocation', 'model.id', '=', 'mlocation.mid')->where('pid', $area);
        if ($paid)
            $models = $models->join('payments', 'model.id', '=', 'payments.mid')->where('validdate', '>=', now()->format('Y-m-d H:i:s'));
        $details = DB::table('mdetails')->get();
        $locations = DB::table('mlocation')->get();
        $models_location = DB::table('ltown')->get();
        $main_photos = DB::table('mphoto')->where('main', 'y')->get();
        $payments = DB::table('payments')->where('validdate', '>=', now())->get();
        $provinces = DB::table('lprovince')->orderBy('name')->get();
        $models = $models->select('model.*')->orderByDesc('createdate')->get();

        return view('models', compact(['models', 'details', 'locations', 'models_location', 'main_photos', 'payments', 'provinces']));
    }

    public function others()
    {
        $models = DB::table('model')
            ->where('name', '')
            ->orWhere('name', null)
            ->orderByDesc('createdate')
            ->get();
        return view('others', compact('models'));
    }

    public function model_details($id)
    {
        $model = DB::table('model')->find($id);
        $model_details = DB::table('mdetails')->where('mid', $id)->first();
        $model_location = DB::table('mlocation')->where('mid', $id)->first();
        $model_main_photo = DB::table('mphoto')->where('mid', $id)->where('main', 'y')->first();
        $model_photos = DB::table('mphoto')->where('mid', $id)->get();
        $model_payment = DB::table('payments')->where('mid', $id)->first();
        $bodies = DB::table('mbodytype')->orderBy('name')->get();
        $busts = DB::table('mbust')->orderBy('name')->get();
        $orientations = DB::table('morientation')->orderBy('name')->get();
        $ethnics = DB::table('methnic')->orderBy('name')->get();
        $countries = DB::table('lcountry')->orderBy('name')->get();
        $provinces = DB::table('lprovince')->orderBy('name')->get();
        $towns = DB::table('ltown')->orderBy('name')->get();

        return view('model-details', compact(['model', 'model_details', 'model_location', 'model_main_photo', 'model_photos', 'model_payment', 'bodies', 'busts', 'orientations', 'ethnics', 'countries', 'provinces', 'towns']));
    }

    public static function model_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'profile' => 'required',
            'age' => 'required',
            'screenname' => 'required',
            'btype' => 'required',
            'mobile' => 'required',
            'orientation' => 'required',
            'ethnic' => 'required',
            'country' => 'required',
            'province' => 'required',
            'town' => 'required',
        ]);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');

        try {
            DB::table('model')->where('id', $id)->update([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'mobile' => $request['mobile'],
                'modifydate' => now()
            ]);

            DB::table('mdetails')->where('mid', $id)->update([
                'profile' => $request['profile'],
                'screenname' => $request['screenname'],
                'age' => $request['age'],
                'bust' => $request['bust'],
                'btype' => $request['btype'],
                'orientation' => $request['orientation'],
                'ethnic' => $request['ethnic'],
                'weight' => $request['weight'],
                'height' => $request['height'],
                'smoke' => $request->has('smoke') ? 'y' : 'n',
                'travel' => $request->has('travel') ? 'y' : 'n',
                'drink' => $request->has('drink') ? 'y' : 'n',
                'verified' => $request->has('verified') ? 1 : 0,
                'top' => $request->has('top') ? 1 : 0,
                'imageverified' => $request->has('imageverified') ? 1 : 0
            ]);

            DB::table('mlocation')->where('mid', $id)->update([
                'lid' => $request['town'],
                'pid' => $request['province'],
                'cid' => $request['country']
            ]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Details Updated');
    }

    public static function model_photo_update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|max:1024'
        ]);
        if ($validator->fails()) return back()->withInput()->withErrors($validator)->with('error', 'Validation Error');

        $file = $request->file('photo');
        $model_main_photo = DB::table('mphoto')->where('mid', $id)->where('main', 'y')->first();
        if (! $model_main_photo) {
            $name = 'main_photo';
        } else {
            $name = $request->has('main') ? 'main_photo' : 'photo_new';
        }

        try {
            Http::attach($name, file_get_contents($file), $file->getClientOriginalName())
                ->post('https://lushgirls.co.za/image-upload.php?mid=' . $id);
//                ->post('http://localhost:3000/image-upload.php?mid=' . $id);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Photo Updated');
    }

    public function delete_photo($mid, $pid): Response
    {
        return Http::get('https://lushgirls.co.za/delete-photo.php?delete=1&mid='.$mid.'&id='.$pid);
//        return Http::get('http://localhost:3000/delete-photo.php?delete=1&mid='.$mid.'&id='.$pid);
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
                return back()->with('error', 'Body Type cannot be deleted');
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
                return back()->with('error', 'Bust Size cannot be deleted');
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
                return back()->with('error', 'Cannot delete Ethnic');
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
                return back()->with('error', 'Cannot delete Orientation');
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
                return back()->with('error', 'Cannot Delete Town');
            if (DB::table('ltown')->where('id', $id)->delete())
                return back()->with('success', 'Town Deleted Successfully');
        }
        return back()->with('error', 'Town Not Found');
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

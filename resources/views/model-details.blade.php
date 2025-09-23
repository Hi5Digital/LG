@extends('layouts.main')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('models') }}">Models</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{ $model->email }}</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('models.update', $model->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="profile">Profile</label>
                                        <textarea class="form-control @error('profile') is-invalid @enderror"
                                                  name="profile" id="profile" cols="30" rows="4"
                                        >{!! old('profile') ?? ($model_details ? ($model_details->profile ?
                                            nl2br(stripslashes($model_details->profile)) : '') : '') !!}</textarea>
                                        @error('profile')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" id="name" autocomplete="off" placeholder="Name"
                                               value="{{ old('name') ?? $model->name }}"
                                        >
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="surname">Surname</label>
                                        <input type="text" class="form-control @error('surname') is-invalid @enderror"
                                               name="surname" id="surname" autocomplete="off" placeholder="Surname"
                                               value="{{ old('surname') ?? $model->surname }}"
                                        >
                                        @error('surname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                               name="mobile" id="phone" autocomplete="off" placeholder="Mobile Phone"
                                               value="{{ old('mobile') ?? $model->mobile }}"
                                        >
                                        @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="screenname">Screen Name</label>
                                        <input type="text" name="screenname" class="form-control @error('screenname') is-invalid @enderror" id="screenname" autocomplete="off" placeholder="Screen Name" value="{{ old('screenname') ?? $model_details->screenname }}">
                                        @error('screenname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weight">Weight</label>
                                        <input type="number" name="weight" class="form-control"
                                               id="weight" autocomplete="off" placeholder="Weight"
                                               value="{{ old('weight') ?? ($model_details ? $model_details->weight : '') }}"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="height">Height</label>
                                        <input type="number" name="height" class="form-control"
                                               id="height" autocomplete="off" placeholder="Height"
                                               value="{{ old('height') ?? ($model_details ? $model_details->height : '') }}"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="age">Age</label>
                                        <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" id="age" autocomplete="off" placeholder="Age" value="{{ old('age') ?? ($model_details ? $model_details->age : '') }}">
                                        @error('age')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bust">Bust Size</label>
                                        <select name="bust" id="bust" class="form-control">
                                            <option value="">Select Bust Size</option>
                                            @foreach($busts as $bust)
                                                <option value="{{ $bust->name }}"
                                                    {{ old('bust') == $bust->name ? 'selected' : ($model_details ?
                                                    ($model_details->bust == $bust->name ? 'selected' : '') : '') }}
                                                >{{ ucwords($bust->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="body">Body Type</label>
                                        <select name="btype" id="body" class="form-control @error('btype') is-invalid @enderror">
                                            <option value="">Select Body Type</option>
                                            @foreach($bodies as $body)
                                                <option value="{{ $body->id }}"
                                                    {{ old('btype') == $body->id ? 'selected': ($model_details ?
                                                    ($model_details->btype == $body->id ? 'selected': '') : '') }}
                                                >{{ ucwords($body->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('btype')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="orientation">Orientation</label>
                                        <select name="orientation" id="orientation" class="form-control @error('orientation') is-invalid @enderror">
                                            <option value="">Select Orientation</option>
                                            @foreach($orientations as $orientation)
                                                <option value="{{ $orientation->id }}"
                                                    {{ old('orientation') == $orientation->id ? 'selected' :
                                                    ($model_details ? ($model_details->orientation == $orientation->id
                                                     ? 'selected' : '') : '') }}
                                                >{{ ucwords($orientation->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('orientation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ethnic">Ethnic</label>
                                        <select name="ethnic" id="orientation" class="form-control @error('ethnic') is-invalid @enderror">
                                            <option value="">Select Ethnic</option>
                                            @foreach($ethnics as $ethnic)
                                                <option value="{{ $ethnic->id }}"
                                                    {{ old('ethnic') == $ethnic->id ? 'selected' : ($model_details ?
                                                    ($model_details->ethnic == $ethnic->id ? 'selected' : '') : '') }}
                                                >{{ ucwords($ethnic->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('ethnic')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country') == $country->id ? 'selected' : ($model_location ?
                                                    ($model_location->cid == $country->id ? 'selected' : '') : '') }}
                                                >{{ ucwords($country->name) }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="province">Province</label>
                                        <select name="province" id="province" class="form-control @error('province') is-invalid @enderror">
                                            <option value="">Select Province</option>
{{--                                            @foreach($provinces as $province)--}}
{{--                                                <option value="{{ $province->id }}" {{ $model_location->pid == $province->id ? 'selected' : '' }}>{{ ucwords($province->name) }}</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                        @error('province')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="town">Town</label>
                                        <select name="town" id="town" class="form-control @error('town') is-invalid @enderror">
                                            <option value="">Select Town</option>
{{--                                            @foreach($towns as $town)--}}
{{--                                                <option value="{{ $town->id }}" {{ $model_location->lid == $town->id ? 'selected' : '' }}>{{ ucwords($town->name) }}</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                        @error('town')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-content-end">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="smoke" value="y" class="form-check-input"
                                                        {{ old('smoke') == 'y' ? 'checked' : ($model_details ?
                                                        ($model_details->smoke == 'y' ? 'checked' : '') : '') }}
                                                    >
                                                    Smoke
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="drink" value="y" class="form-check-input"
                                                        {{ old('drink') == 'y' ? 'checked' : ($model_details ?
                                                        ($model_details->drink == 'y' ? 'checked' : '') : '') }}
                                                    >
                                                    Drink
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="travel" value="y" class="form-check-input"
                                                        {{ old('travel') == 'y' ? 'checked' : ($model_details ?
                                                        ($model_details->travel == 'y' ? 'checked' : '') : '') }}
                                                    >
                                                    Travel
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex align-content-end">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="verified" value="1" class="form-check-input"
                                                        {{ old('verified') == 1 ? 'checked' : ($model_details ?
                                                        ($model_details->verified == 1 ? 'checked' : '') : '') }}
                                                    >
                                                    Verified
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="top" value="1" class="form-check-input"
                                                        {{ old('Top') == 1 ? 'checked' : ($model_details ?
                                                        ($model_details->top == 1 ? 'checked' : '') : '') }}
                                                    >
                                                    Top
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="checkbox" name="imageverified" value="1" class="form-check-input"
                                                        {{ old('imageverified') == 1 ? 'checked' : ($model_details ?
                                                        ($model_details->imageverified == 1 ? 'checked' : '') : '') }}
                                                    >
                                                    Images Verified
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex my-3 justify-content-between">
                                <a href="{{ route('models') }}" type="button" class="btn btn-dark">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Photos</h6>
                        <table class="table">
                            @foreach($model_photos as $photo)
                                <tr id="row{{$photo->id}}">
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#view{{$photo->id}}">
                                            <img src="{{ 'https://lushgirls.co.za/img/models/'.$model->id.'/'.($photo->photo ?? '') }}" alt="">
{{--                                            <img src="{{ 'http://localhost:3000/img/models/'.$model->id.'/'.($photo->photo ?? '') }}" alt="">--}}
                                        </a>
                                    </td>
                                    <td>{{ $photo->main == 'y' ? 'Main' : '' }}</td>
                                    <td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete{{$photo->id}}"><i class="icon" data-feather="trash"></i></a></td>
                                    <td>
                                        <div class="spinner-border text-primary d-none" role="status" id="spinner{{$photo->id}}">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="view{{$photo->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Photo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center mx-auto justify-content-center">
                                                <img src="{{ 'https://lushgirls.co.za/img/models/'.$model->id.'/'.($photo->photo ?? '') }}" width="220" alt="">
{{--                                                <img src="{{ 'http://localhost:3000/img/models/'.$model->id.'/'.($photo->photo ?? '') }}" width="220" alt="">--}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="delete{{$photo->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Delete</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this photo?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletePhoto('{{ $model->id }}', '{{ $photo->id }}')">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </table>
                        <div class="mt-3">
                            <form action="{{ route('models.photo.update', $model->id) }}" id="uploadForm" method="post" enctype="multipart/form-data">
                                @csrf
                                <h6 class="card-title">Upload Photo</h6>
                                <div class="form-group">
                                    <input type="file" name="photo" id="photo" class="form-control form-control-file">
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="main" id="is-main" value="1" class="form-check-input"
                                            {{ old('main') == '1' ? 'checked' : '' }}
                                        >
                                        Is main photo?
                                    </label>
                                </div>
                                <div class="d-flex my-3 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                    <div class="spinner-border text-primary d-none" role="status" id="photo-spinner">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
          $('#upl').submit(function(e) {
            e.preventDefault();
              const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 4000
              });
            const formData = new FormData($(this)[0]);
            $.ajax({
              type: 'POST',
              url: `/models/{{ $model->id }}/photo`,
              headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
              data: formData,
              contentType: false,
              processData: false,
                beforeSend: function () {
                    $('#photo-spinner').removeClass('d-none')
                },
                success: function (res) {
                    $('#photo-spinner').addClass('d-none')
                    if (parseInt(res) === 200 || parseInt(res) === 201) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Photo updated successfully'
                        });
                    }
                    else if (parseInt(res) === 400)
                        Toast.fire({
                            icon: 'error',
                            title: 'Could not upload photo, try again'
                        });
                    else
                        Toast.fire({
                            icon: 'error',
                            title: 'Photo not found'
                        })
                },
                error: function (res) {
                    $('#photo-spinner').addClass('d-none')
                    Toast.fire({
                        icon: 'error',
                        title: res?.message ?? 'An error occurred, try again'
                    })
                }
            });
          });
        });

        function deletePhoto(mid, pid) {
            $()
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });

            $.ajax({
                type: 'GET',
                url: `/models/${mid}/photo/${pid}/delete`,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { mid, id: pid },
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#spinner'+pid).removeClass('d-none')
                },
                success: function (res) {
                    $('#spinner'+pid).addClass('d-none')
                    if (parseInt(res) === 200 || parseInt(res) === 201) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Photo deleted successfully'
                        });
                        $('#row'+pid).remove()
                    }
                    else if (parseInt(res) === 400)
                        Toast.fire({
                            icon: 'error',
                            title: 'Could not delete photo, try again'
                        });
                    else
                        Toast.fire({
                            icon: 'error',
                            title: 'Photo not found'
                        })
                },
                error: function () {
                    $('#spinner'+pid).addClass('d-none')
                    $('#close-modal').click()
                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred, try again'
                    })
                }
            });
        }

        const provinces = {!! json_encode($provinces); !!};
        const towns = {!! json_encode($towns); !!};
        const mcountry = '{!! $model_location->cid ?? ''; !!}';
        const mprovince = '{!! $model_location->pid ?? ''; !!}';
        const mtown = '{!! $model_location->lid ?? ''; !!}';
        const oldcountry = '{!! old('country') ?? '' !!}';
        const oldprovince = '{!! old('province') ?? '' !!}';
        const oldtown = '{!! old('town') ?? '' !!}';

        const countryField = $('#country');
        const provinceField = $('#province');
        const townField = $('#town');

        if (mcountry) {
            provinceField.html('');
            townField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(mcountry))
            provinceField.append('<option value="">Select Province</option>')
            townField.append('<option value="">Select a Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : (mprovince ? (parseInt(mprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                provinceField.html('');
                provinceField.append('<option value="">Select Province</option>');
            }
        }
        else if (oldcountry && oldprovince) {
            provinceField.html('');
            townField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(oldcountry))
            provinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : (mprovince ? (parseInt(mprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                provinceField.html('');
                provinceField.append('<option value="">Select Province</option>');
            }
            const newtowns = towns.filter(cur => parseInt(cur['pid']) === parseInt(oldprovince))
            townField.append('<option value="">Select Town</option>')
            if (newtowns.length)
                newtowns.forEach(cur => townField.append(`<option value="${cur['id']}" ${oldtown ? (parseInt(oldtown) === parseInt(cur['id']) ? 'selected' : '') : (mtown ? (parseInt(mtown) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                townField.html('');
                townField.append('<option value="">Select Town</option>');
            }
        }
        else if (oldcountry && !oldprovince) {
            provinceField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(oldcountry))
            provinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : (mprovince ? (parseInt(mprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                provinceField.html('');
                provinceField.append('<option value="">Select Province</option>');
            }
        }

        if (mcountry && mprovince) {
            townField.html('');
            const newtowns = towns.filter(cur => parseInt(cur['pid']) === parseInt(mprovince))
            townField.append('<option value="">Select Town</option>')
            if (newtowns.length)
                newtowns.forEach(cur => townField.append(`<option value="${cur['id']}" ${oldtown ? (parseInt(oldtown) === parseInt(cur['id']) ? 'selected' : '') : (mtown ? (parseInt(mtown) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                townField.html('');
                townField.append('<option value="">Select Town</option>');
            }
        }
        else if (oldcountry && oldprovince && oldtown) {
            townField.html('');
            const newtowns = towns.filter(cur => parseInt(cur['pid']) === parseInt(oldprovince))
            townField.append('<option value="">Select Town</option>')
            if (newtowns.length)
                newtowns.forEach(cur => townField.append(`<option value="${cur['id']}" ${oldtown ? (parseInt(oldtown) === parseInt(cur['id']) ? 'selected' : '') : (mtown ? (parseInt(mtown) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                townField.html('');
                townField.append('<option value="">Select Town</option>');
            }
        }

        countryField.on('change', () => {
            provinceField.html('');
            townField.html('');
            const cid = countryField.val();
            if (cid) {
                const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(cid))
                provinceField.append('<option value="">Select Province</option>')
                // townField.append('<option value="">Select a Province</option>')
                if (newprovinces.length)
                    newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : (mprovince ? (parseInt(mprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
                else {
                    provinceField.html('');
                    provinceField.append('<option value="">Select Province</option>');
                }
                if (mprovince) {
                    townField.html('');
                    const newtowns = towns.filter(cur => parseInt(cur['pid']) === parseInt(mprovince))
                    townField.append('<option value="">Select Town</option>')
                    if (newtowns.length)
                        newtowns.forEach(cur => townField.append(`<option value="${cur['id']}" ${oldtown ? (parseInt(oldtown) === parseInt(cur['id']) ? 'selected' : '') : (mtown ? (parseInt(mtown) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
                    else {
                        townField.html('');
                        townField.append('<option value="">Select Town</option>');
                    }
                }
            } else {
                provinceField.append('<option value="">Select a Country</option>')
                townField.append('<option value="">Select a Province</option>')
            }
        })
        provinceField.on('change', () => {
            townField.html('');
            const pid = provinceField.val();
            if (pid) {
                const newtowns = towns.filter(cur => parseInt(cur['pid']) === parseInt(pid))
                townField.append('<option value="">Select Town</option>')
                if (newtowns.length)
                    newtowns.forEach(cur => townField.append(`<option value="${cur['id']}" ${oldtown ? (parseInt(oldtown) === parseInt(cur['id']) ? 'selected' : '') : (mtown ? (parseInt(mtown) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
                else {
                    townField.html('');
                    townField.append('<option value="">Select Town</option>');
                }
            } else {
                townField.append('<option value="">Select a Province</option>')
            }
        })
    </script>
@endsection

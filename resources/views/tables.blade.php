@extends('layouts.main')


@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">
                @if(isset($body_types))
                    {{ __('Body Types') }}
                @elseif(isset($bust_sizes))
                    {{ __('Bust Sizes') }}
                @elseif(isset($ethnics))
                    {{ __('Ethnics') }}
                @elseif(isset($orientations))
                    {{ __('Orientations') }}
                @elseif(isset($towns))
                    {{ __('Towns') }}
                @endif
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php @endphp
                                @if(isset($body_types))
                                    @foreach($body_types as $key => $body_type)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ ucwords($body_type->name) }}</td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit-body{{$body_type->id}}"><i class="icon" data-feather="edit" id="edit{{ $body_type->id }}"></i></a></td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#delete-body{{$body_type->id}}"><i class="icon" data-feather="trash" id="delete{{ $body_type->id }}"></i></a></td>
                                        </tr>
                                        <div class="modal fade" id="edit-body{{$body_type->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Body Type</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('body.update', $body_type->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_name" id="name" class="form-control @error('edit_name') is-invalid @enderror" value="{{ old('edit_name') ?? $body_type->name }}">
                                                                @error('edit_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="delete-body{{$body_type->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Body Type</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('body.delete', $body_type->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this body type?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($bust_sizes))
                                    @foreach($bust_sizes as $key => $bust_size)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ ucwords($bust_size->name) }}</td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit-bust{{$bust_size->id}}"><i class="icon" data-feather="edit" id="edit{{ $bust_size->id }}"></i></a></td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#delete-bust{{$bust_size->id}}"><i class="icon" data-feather="trash" id="delete{{ $bust_size->id }}"></i></a></td>
                                        </tr>
                                        <div class="modal fade" id="edit-bust{{$bust_size->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Bust Size</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('bust.update', $bust_size->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_name" id="name" class="form-control @error('edit_name') is-invalid @enderror" value="{{ old('edit_name') ?? $bust_size->name }}">
                                                                @error('edit_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="delete-bust{{$bust_size->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Bust Size</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('bust.delete', $bust_size->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this bust size?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($ethnics))
                                    @foreach($ethnics as $key => $ethnic)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ ucwords($ethnic->name) }}</td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit-ethnic{{$ethnic->id}}"><i class="icon" data-feather="edit" id="edit{{ $ethnic->id }}"></i></a></td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#delete-ethnic{{$ethnic->id}}"><i class="icon" data-feather="trash" id="delete{{ $ethnic->id }}"></i></a></td>
                                        </tr>
                                        <div class="modal fade" id="edit-ethnic{{$ethnic->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Ethnic</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('ethnics.update', $ethnic->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_name" id="name" class="form-control @error('edit_name') is-invalid @enderror" value="{{ old('edit_name') ?? $ethnic->name }}">
                                                                @error('edit_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete-ethnic{{$ethnic->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Ethnic</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('ethnics.delete', $ethnic->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this ethnic?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($orientations))
                                    @foreach($orientations as $key => $orientation)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ ucwords($orientation->name) }}</td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit-orientation{{$orientation->id}}"><i class="icon" data-feather="edit" id="edit{{ $orientation->id }}"></i></a></td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#delete-orientation{{$orientation->id}}"><i class="icon" data-feather="trash" id="delete{{ $orientation->id }}"></i></a></td>
                                        </tr>
                                        <div class="modal fade" id="edit-orientation{{$orientation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Orientation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('orientations.update', $orientation->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_name" id="name" class="form-control @error('edit_name') is-invalid @enderror" value="{{ old('edit_name') ?? $orientation->name }}">
                                                                @error('edit_name')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete-orientation{{$orientation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Orientation</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('orientations.delete', $orientation->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this orientation?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($towns))
                                    @foreach($towns as $key => $town)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ ucwords($town->name) }}</td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit-town{{$town->id}}"><i class="icon" data-feather="edit" id="edit{{ $town->id }}"></i></a></td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#delete-town{{$town->id}}"><i class="icon" data-feather="trash" id="delete{{ $town->id }}"></i></a></td>
                                        </tr>
                                        <div class="modal fade" id="edit-town{{$town->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Town</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('towns.update', $town->id) }}" method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="edit-country">Country <span class="text-danger">*</span></label>
                                                                <select name="edit_country" id="edit-country" class="form-control @error('edit_country') is-invalid @enderror">
                                                                    <option value="">Select Country</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country->id }}" {{ old('edit_country') ? ($country->id == old('edit_country') ? 'selected' : '') : ($country->id == $town->cid ? 'selected' : '') }}>{{ ucwords($country->name) }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('edit_country')
                                                                <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="edit-province">Province <span class="text-danger">*</span></label>
                                                                <select name="edit_province" id="edit-province" class="form-control @error('edit_province') is-invalid @enderror">
                                                                    <option value="">Select a Country</option>
                                                                </select>
                                                                @error('edit_province')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="edit_name" id="name" class="form-control @error('edit_name') is-invalid @enderror" value="{{ old('edit_name') ?? $town->name }}">
                                                                @error('edit_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete-town{{$town->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Town</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('towns.delete', $town->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this town?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(isset($body_types))
                            <h6 class="card-title">{{ 'Create Body Type' }}</h6>
                            <form action="{{ route('body.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        @elseif(isset($bust_sizes))
                            <h6 class="card-title">{{ 'Create Bust Size' }}</h6>
                            <form action="{{ route('bust.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        @elseif(isset($ethnics))
                            <h6 class="card-title">{{ 'Create Ethnic' }}</h6>
                            <form action="{{ route('ethnics.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        @elseif(isset($orientations))
                            <h6 class="card-title">{{ 'Create Orientation' }}</h6>
                            <form action="{{ route('orientations.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        @elseif(isset($towns))
                            <h6 class="card-title">{{ 'Create Town' }}</h6>
                            <form action="{{ route('towns.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="country">Country <span class="text-danger">*</span></label>
                                    <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $country->id == old('country') ? 'selected' : '' }}>{{ ucwords($country->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="province">Province <span class="text-danger">*</span></label>
                                    <select name="province" id="province" class="form-control @error('province') is-invalid @enderror">
                                        <option value="">Select a Country</option>
                                    </select>
                                    @error('province')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const provinces = {!! json_encode($provinces ?? ''); !!};

        const oldcountry = '{!! old('country') ?? '' !!}';
        const oldprovince = '{!! old('province') ?? '' !!}';

        const oldEditCountry = '{!! old('edit_country') ?? '' !!}';
        const oldEditProvince = '{!! old('edit_province') ?? '' !!}';

        const lcountry = '{!! $town->cid ?? ''; !!}';
        const lprovince = '{!! $town->pid ?? ''; !!}';

        const countryField = $('#country');
        const provinceField = $('#province');

        const editCountryField = $('#edit-country');
        const editProvinceField = $('#edit-province');

        if (lcountry) {
            editProvinceField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(lcountry))
            editProvinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => editProvinceField.append(`<option value="${cur['id']}" ${oldEditProvince ? (parseInt(oldEditProvince) === parseInt(cur['id']) ? 'selected' : '') : (lprovince ? (parseInt(lprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                editProvinceField.html('');
                editProvinceField.append('<option value="">Select Province</option>');
            }
        }
        else if (oldEditCountry && oldEditProvince) {
            editProvinceField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(oldEditCountry))
            editProvinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => editProvinceField.append(`<option value="${cur['id']}" ${oldEditProvince ? (parseInt(oldEditProvince) === parseInt(cur['id']) ? 'selected' : '') : (lprovince ? (parseInt(lprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                editProvinceField.html('');
                editProvinceField.append('<option value="">Select Province</option>');
            }
        }
        else if (oldEditCountry && !oldEditProvince) {
            editProvinceField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(oldEditCountry))
            editProvinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => editProvinceField.append(`<option value="${cur['id']}" ${oldEditProvince ? (parseInt(oldEditProvince) === parseInt(cur['id']) ? 'selected' : '') : (lprovince ? (parseInt(lprovince) === parseInt(cur['id']) ? 'selected' : '') : '')}>${cur['name']}</option>`));
            else {
                editProvinceField.html('');
                editProvinceField.append('<option value="">Select Province</option>');
            }
        }

        editCountryField.on('change', () => {
            editProvinceField.html('');
            const cid = editCountryField.val();
            if (cid) {
                const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(cid))
                editProvinceField.append('<option value="">Select Province</option>')
                if (newprovinces.length)
                    newprovinces.forEach(cur => editProvinceField.append(`<option value="${cur['id']}" ${oldEditProvince ? (parseInt(oldEditProvince) === parseInt(cur['id']) ? 'selected' : '') : (lprovince ? (parseInt(lprovince) === parseInt(cur['id']) ? 'selected' : '' ) : '')}>${cur['name']}</option>`));
                else {
                    editProvinceField.html('');
                    editProvinceField.append('<option value="">Select Province</option>');
                }
            } else
                editProvinceField.append('<option value="">Select a Country</option>')
        })

        // Create Town
        if (oldcountry && oldprovince) {
            provinceField.html('');
            const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(oldcountry))
            provinceField.append('<option value="">Select Province</option>')
            if (newprovinces.length)
                newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : ''}>${cur['name']}</option>`));
            else {
                provinceField.html('');
                provinceField.append('<option value="">Select Province</option>');
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

        countryField.on('change', () => {
            provinceField.html('');
            const cid = countryField.val();
            if (cid) {
                const newprovinces = provinces.filter(cur => parseInt(cur['cid']) === parseInt(cid))
                provinceField.append('<option value="">Select Province</option>')
                if (newprovinces.length)
                    newprovinces.forEach(cur => provinceField.append(`<option value="${cur['id']}" ${oldprovince ? (parseInt(oldprovince) === parseInt(cur['id']) ? 'selected' : '') : ''}>${cur['name']}</option>`));
                else {
                    provinceField.html('');
                    provinceField.append('<option value="">Select Province</option>');
                }
            } else
                provinceField.append('<option value="">Select a Country</option>')
        })

    </script>
@endsection

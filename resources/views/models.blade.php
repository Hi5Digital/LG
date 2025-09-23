@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Models</h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="stretch-card">
            <div class="card">
                <div class="card-header">
                    <form action="">
                        <div class="d-flex justify-content-end align-content-end">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="status" {{ request()->has('status') ? 'checked' : '' }} value="paid" class="form-check-input">
                                    Paid
                                </label>
                            </div>
                            <select class="form-select w-15" name="area">
                                <option value="">Select Area</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('area') == $province->id ? 'selected' : '' }}>{{ ucwords($province->name) }}</option>
                                @endforeach
                            </select>
                            <div class="ml-2">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTableModels" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Photo</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Screen Name</th>
                                <th>Location</th>
                                <th>Payment</th>
                                <th>Payment Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php use App\Http\Controllers\HomeController as MD; @endphp
                            @foreach($models as $model)
                                <tr class="model-rows" data-url="{{ route('models.details', $model->id) }}">
                                    <td><img src="{{ 'https://lushgirls.co.za/img/models/'.$model->id.'/'.(MD::getMainPhoto($model->id, $main_photos)->photo ?? '') }}" alt="{{ MD::getDetails($model->id, $details)->screenname ?? '' }}"></td>
{{--                                    <td><img src="{{ 'http://localhost/20210809/img/models/'.$model->id.'/'.(MD::getMainPhoto($model->id, $main_photos)->photo ?? '') }}" alt="trisha"></td>--}}
{{--                                    <td><img src="{{ 'http://localhost/20210809/'.(MD::getMainPhoto($model->id, $main_photos)->path ?? '') }}" alt=""></td>--}}
                                    <td>{{ $model->id }}</td>
                                    <td>{{ $model->name.' '.$model->surname }}</td>
                                    <td>{{ $model->email }}</td>
                                    <td>{{ $details ? (MD::getDetails($model->id, $details)->screenname ?? '--------') : '--------' }}</td>
                                    <td>{{ ($models_location && $locations) ? (MD::getLocation($model->id, $locations, $models_location)->name ?? '--------') : '---------' }}</td>
                                    <td><span class="badge {{ MD::getPayment($model->id, $payments) ? 'badge-success' : 'badge-danger-muted' }}">{{ MD::getPayment($model->id, $payments) ? 'active' : 'not active' }}</span></td>
                                    <td>{{ MD::getPayment($model->id, $payments) ? date('Y/m/d', strtotime(MD::getPayment($model->id, $payments)->paymentdate)) : '--------' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.model-rows').css( 'cursor', 'pointer' ).click(function () {
                window.location.href = $(this).data('url');
            });

            $('#dataTableModels').DataTable({
                "aLengthMenu": [
                    [100, 250, 500, -1],
                    [100, 250, 500, "All"]
                ],
                "iDisplayLength": 100,
                "language": {
                    search: ""
                }
            }).each(function() {
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search');
                search_input.removeClass('form-control-sm');
                // LENGTH - Inline-Form control
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.removeClass('form-control-sm');
            });
        })
    </script>
@endsection

@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Models</h4>
        </div>
        <a href="{{ route('models.create') }}" class="btn btn-primary">New model</a>
        <button type="button" class="btn btn-secondary btn-sm" id="clearFilters">Clear column search</button>
    </div>

    <div class="col-md-12">
        <div class="stretch-card">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('models') }}" method="get">
                        <div class="d-flex justify-content-end align-items-center">
                            <select class="form-control w-auto mr-2" name="status">
                                <option value="">All models</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <select class="form-control w-auto mr-2" name="verified">
                                <option value="">All</option>
                                <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                            </select>
                            <select class="form-control w-auto mr-2" name="area">
                                <option value="">All provinces</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ (string) request('area') === (string) $province->id ? 'selected' : '' }}>{{ ucwords($province->name) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary">Filter</button>
                            @if(request('status') || request('verified') || request('area'))
                                <a href="{{ route('models') }}" class="btn btn-link">Clear</a>
                            @endif
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
                                <th>Public</th>
                                <th>Payment Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php use App\Http\Controllers\HomeController as MD; @endphp
                            @foreach($models as $model)
                                @php
                                    $pay = MD::getPayment($model->id, $payments);
                                    $active = false;
                                    foreach ($payments as $payment) {
                                        if ((int) $payment->mid === (int) $model->id && $payment->validdate && \Carbon\Carbon::parse($payment->validdate)->gte(now())) {
                                            $active = true;
                                            break;
                                        }
                                    }
                                    $main = MD::getMainPhoto($model->id, $main_photos);
                                @endphp
                                <tr class="model-rows" data-url="{{ route('models.details', $model->id) }}">
                                    <td><img src="{{ $main ? route('models.photo.show', ['id' => $model->id, 'pid' => $main->id]) : asset('assets/images/avatar.png') }}" alt="{{ optional(MD::getDetails($model->id, $details))->screenname ?? '' }}" width="40"></td>
                                    <td>{{ $model->id }}</td>
                                    <td><a href="{{ route('models.details', $model->id) }}">{{ $model->name.' '.$model->surname }}</a></td>
                                    <td>{{ $model->email }}</td>
                                    <td>{{ optional(MD::getDetails($model->id, $details))->screenname ?? '--------' }}</td>
                                    <td>{{ optional(MD::getLocation($model->id, $locations, $models_location))->name ?? '--------' }}</td>
                                    <td><span class="badge {{ $active ? 'badge-success' : 'badge-danger-muted' }}">{{ $active ? 'Active' : 'Inactive' }}</span></td>
                                    <td>
                                        <form method="post" action="{{ route('models.listing', $model->id) }}">
                                            @csrf
                                            <input type="hidden" name="show" value="0">
                                            <label class="mb-0">
                                                <input type="checkbox" name="show" value="1" {{ $active ? 'checked' : '' }} onchange="this.form.submit()" aria-label="Show on the public site">
                                                {{ $active ? 'Shown' : 'Hidden' }}
                                            </label>
                                        </form>
                                    </td>
                                    <td>{{ $pay && $pay->paymentdate ? \Carbon\Carbon::parse($pay->paymentdate)->format('d M Y') : '--------' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search ID"></th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search Name"></th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search Email"></th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search Screen Name"></th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search Location"></th>
                                <th>
                                    <select class="form-control form-control-sm">
                                        <option value="">All</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control form-control-sm">
                                        <option value="">All</option>
                                        <option value="Shown">Shown</option>
                                        <option value="Hidden">Hidden</option>
                                    </select>
                                </th>
                                <th><input type="text" class="form-control form-control-sm" placeholder="Search Date"></th>
                            </tr>
                            </tfoot>
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
            var table = $('#dataTableModels').DataTable({
                "aLengthMenu": [
                    [50, 100, 250, 500, -1],
                    [50, 100, 250, 500, "All"]
                ],
                "iDisplayLength": 100,
                "order": [[1, 'desc']],
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                "language": {
                    search: ""
                },
                initComplete: function () {
                    this.api().columns().every(function (index) {
                        if (index === 0) {
                            return;
                        }
                        var column = this;
                        var input = $('#dataTableModels tfoot th').eq(index).find('input, select');
                        input.on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    });
                }
            });

            $('#dataTableModels tbody').on('click', 'tr', function (event) {
                if ($(event.target).closest('a, button, input, select').length) {
                    return;
                }
                var url = $(this).data('url');
                if (url) {
                    window.location.href = url;
                }
            });

            $('#clearFilters').on('click', function () {
                table.search('');
                table.columns().every(function () {
                    this.search('');
                });
                $('#dataTableModels tfoot input').val('');
                $('#dataTableModels tfoot select').val('');
                table.draw();
            });

            var search_input = $('#dataTableModels').closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Search all columns...');
            search_input.removeClass('form-control-sm');
        })
    </script>
@endsection

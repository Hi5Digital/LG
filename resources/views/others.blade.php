@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Others</h4>
    </div>
</div>

<div class="col-md-12">
    <div class="stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableModels" class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Date Joined</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php use App\Http\Controllers\HomeController as MD; @endphp
                        @foreach($models as $model)
                            <tr class="model-rows">
                                <td>{{ $model->id }}</td>
                                <td>{{ MD::getLogin($model->id) ? MD::getLogin($model->id)->username : '--------' }}</td>
                                <td>{{ Carbon\Carbon::make($model->createdate)->format('Y-m-d') }}</td>
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

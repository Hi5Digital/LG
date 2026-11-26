@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Dashboard</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-md-3 grid-margin stretch-card">
                    <a href="{{ route('models') }}" class="card text-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h5 class="card-title mb-0">Models</h5>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5 text-center">
                                    <h2 class="mb-2">{{ number_format(count($models)) }}</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <a href="{{ route('models', ['status' => 'paid']) }}" class="card text-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h5 class="card-title mb-0">Active Models</h5>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5 text-center">
                                    <h2 class="mb-2">{{ number_format($active_models) }}</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <a href="{{ route('models', ['status' => 'inactive']) }}" class="card text-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h5 class="card-title mb-0">Inactive Models</h5>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5 text-center">
                                    <h2 class="mb-2">{{ number_format($inactive_models) }}</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <a href="{{ route('models', ['verified' => '1']) }}" class="card text-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h5 class="card-title mb-0">Verified Models</h5>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5 text-center">
                                    <h2 class="mb-2">{{ number_format($verified_models) }}</h2>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

{{--    <div class="row">--}}
{{--        <div class="col-12 col-xl-12 grid-margin stretch-card">--}}
{{--            <div class="card overflow-hidden">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">--}}
{{--                        <h5 class="card-title mb-0">Payments</h5>--}}
{{--                    </div>--}}
{{--                    <div class="flot-wrapper">--}}
{{--                        <div id="flotChart1" class="flot-chart"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row">
        <div class="col-md-5 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h5 class="card-title mb-0">Recent Sign Ups</h5>
                    </div>
                    <div class="d-flex flex-column">
                        @foreach(array_splice($models, 0, 6) as $model)
                            <a href="{{ route('models.details', $model->id) }}" class="d-flex align-items-center border-bottom pt-3 pb-3">
                                <div class="mr-3">
                                    <img src="{{ $model->main_photo_url ?? asset('assets/images/avatar.png') }}" class="rounded-circle wd-35 ht-35" alt="">
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="text-body mb-2">{{ $model->name.' '.$model->surname }}</h6>
                                        <p class="text-muted tx-12">{{ \Carbon\Carbon::make($model->createdate)->format('d M, Y') }} <small>{{ \Carbon\Carbon::make($model->createdate)->format('H:ia') }}</small></p>
                                    </div>
                                    @php $profile = optional($model->details)->profile ?? ''; @endphp
                                    <p class="text-muted tx-13">{{ \Illuminate\Support\Str::limit($profile, 20) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h5 class="card-title mb-0">Recent Payments</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="pt-0">Model</th>
                                <th class="pt-0">Transaction ID</th>
                                <th class="pt-0">Subscription ID</th>
                                <th class="pt-0">Payment Date</th>
                                <th class="pt-0">Next cycle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        @if($payment->mid)
                                            <a href="{{ route('models.details', $payment->mid) }}">{{ trim(($payment->model_name ?? '').' '.($payment->model_surname ?? '')) ?: '--------' }}</a>
                                        @else
                                            --------
                                        @endif
                                    </td>
                                    <td>{{ $payment->transactionid ?? '--------' }}</td>
                                    <td>{{ $payment->subscriptionid ?? '--------' }}</td>
                                    <td>{{ $payment->paymentdate ? \Carbon\Carbon::parse($payment->paymentdate)->format('d M Y') : '--------' }}</td>
                                    <td>{{ $payment->paymentdate ? date('d M Y', \App\Http\Controllers\HomeController::getNextPaymentDate($payment->paymentdate)) : '--------' }}</td>
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

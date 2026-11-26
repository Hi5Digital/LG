@extends('layouts.main')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('models') }}">Models</a></li>
            <li class="breadcrumb-item active" aria-current="page">New model</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">New model</h6>
                        <p class="text-muted">Save the profile first. Photos are added on the next screen.</p>
                        <form action="{{ route('models.store') }}" method="post" onsubmit="this.querySelector('[type=submit]').disabled=true">
                            @csrf
                            @include('models._fields')
                            <div class="d-flex my-3 justify-content-between">
                                <a href="{{ route('models') }}" class="btn btn-dark">Back</a>
                                <button type="submit" class="btn btn-primary">Save model</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('models._location-script')
@endsection

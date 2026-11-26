@extends('layouts.main')

@section('content')
    @php
        $profileTitle = trim(($model->name ?? '').' '.($model->surname ?? ''));
    @endphp
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
                        <h6 class="card-title">{{ $profileTitle !== '' ? $profileTitle : 'Incomplete profile' }}</h6>
                        <form action="{{ route('models.update', $model->id) }}" method="post" onsubmit="this.querySelector('[type=submit]').disabled=true">
                            @csrf
                            @method('PUT')
                            @include('models._fields')
                            <div class="d-flex my-3 justify-content-between">
                                <a href="{{ route('models') }}" class="btn btn-dark">Back</a>
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
                        <h6 class="card-title">Payment</h6>
                        @if($model_payment)
                            <p class="mb-1">Payment date: {{ \Carbon\Carbon::parse($model_payment->paymentdate)->format('d M Y, H:i') }}</p>
                            <p class="mb-1">Valid until: {{ \Carbon\Carbon::parse($model_payment->validdate)->format('d M Y, H:i') }}</p>
                            <p class="mb-1">Transaction id: {{ $model_payment->transactionid ?: '--------' }}</p>
                            <p class="mb-3">Subscription id: {{ $model_payment->subscriptionid ?: '--------' }}</p>
                        @else
                            <p>No payment recorded.</p>
                        @endif
                        <p class="text-muted">Valid until is the date the profile stays active. It is not the dashboard's 28-day cycle.</p>
                        <form method="post" action="{{ route('models.payment.store', $model->id) }}">
                            @csrf
                            <div class="form-group">
                                <label for="paymentdate">Payment date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="paymentdate" id="paymentdate" class="form-control @error('paymentdate') is-invalid @enderror" value="{{ old('paymentdate') }}" required>
                                @error('paymentdate')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="validdate">Valid until <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="validdate" id="validdate" class="form-control @error('validdate') is-invalid @enderror" value="{{ old('validdate') }}" required>
                                @error('validdate')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="transactionid">Transaction id</label>
                                <input type="text" name="transactionid" id="transactionid" maxlength="255" class="form-control @error('transactionid') is-invalid @enderror" value="{{ old('transactionid') }}">
                                @error('transactionid')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="subscriptionid">Subscription id</label>
                                <input type="text" name="subscriptionid" id="subscriptionid" maxlength="255" class="form-control @error('subscriptionid') is-invalid @enderror" value="{{ old('subscriptionid') }}">
                                @error('subscriptionid')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Record payment</button>
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
                        @if($model_photos->isEmpty())
                            <p>No photos yet.</p>
                        @endif
                        <table class="table">
                            @foreach($model_photos as $photo)
                                <tr id="row{{ $photo->id }}">
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#view{{ $photo->id }}">
                                            <img src="{{ route('models.photo.show', ['id' => $model->id, 'pid' => $photo->id]) }}" alt="" width="80">
                                        </a>
                                    </td>
                                    <td>{{ $photo->main == 'y' ? 'Main' : '' }}</td>
                                    <td><a href="javascript:void(0)" data-toggle="modal" data-target="#delete{{ $photo->id }}"><i class="icon" data-feather="trash"></i> Delete</a></td>
                                </tr>
                                <div class="modal fade" id="view{{ $photo->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Photo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ route('models.photo.show', ['id' => $model->id, 'pid' => $photo->id]) }}" width="220" alt="">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="delete{{ $photo->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to delete this photo?</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <form method="post" action="{{ route('models.photo.delete', ['mid' => $model->id, 'pid' => $photo->id]) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
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
                                <p class="text-muted">JPG, PNG, or WebP. Maximum 2 MB. The first photo is set as the main photo.</p>
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                                    <img id="photo-preview" alt="" class="d-none mt-2" width="160">
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="main" id="is-main" value="1" class="form-check-input" {{ old('main') == '1' ? 'checked' : '' }}>
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
        $('#photo').on('change', function () {
            const file = this.files && this.files[0];
            const preview = $('#photo-preview');
            if (!file) {
                preview.addClass('d-none').attr('src', '');
                return;
            }
            preview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
        });

        $('#uploadForm').submit(function (e) {
            e.preventDefault();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
            const form = this;
            const button = $(form).find('[type=submit]');
            const formData = new FormData(form);
            button.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: form.action,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: formData,
                contentType: false,
                processData: false,
                timeout: 30000,
                beforeSend: function () {
                    $('#photo-spinner').removeClass('d-none');
                },
                success: function (res) {
                    if (res && res.ok) {
                        Toast.fire({ icon: 'success', title: 'Photo updated' });
                        window.location.reload();
                        return;
                    }
                    Toast.fire({ icon: 'error', title: 'Photo could not be stored.' });
                },
                error: function (xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred, try again';
                    Toast.fire({ icon: 'error', title: message });
                },
                complete: function () {
                    $('#photo-spinner').addClass('d-none');
                    button.prop('disabled', false);
                }
            });
        });
    </script>
    @include('models._location-script')
@endsection

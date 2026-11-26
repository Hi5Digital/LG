@extends('layouts.main')

@section('content')
    <h4 class="mb-3">Account</h4>
    <div class="card"><div class="card-body">
        <form method="post" action="{{ route('account.update') }}">
            @csrf
            @method('PUT')
            <p class="text-muted">Current password is required only when you change the email or the password. A new password needs at least 8 characters.</p>
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" maxlength="255" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" maxlength="255" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            </div>
            <div class="form-group">
                <label for="current_password">Current password</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="form-control @error('current_password') is-invalid @enderror">
                @error('current_password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            </div>
            <div class="form-group">
                <label for="password">New password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control">
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div></div>
@endsection

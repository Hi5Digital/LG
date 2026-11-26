<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="profile">Profile <span class="text-danger">*</span></label>
            <textarea class="form-control @error('profile') is-invalid @enderror" name="profile" id="profile" cols="30" rows="4" style="white-space: pre-wrap" required>{{ old('profile', optional($model_details)->profile) }}</textarea>
            @error('profile')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" maxlength="255" autocomplete="off" placeholder="Name" value="{{ old('name', optional($model)->name) }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="surname">Surname <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" id="surname" maxlength="255" autocomplete="off" placeholder="Surname" value="{{ old('surname', optional($model)->surname) }}" required>
            @error('surname')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" maxlength="255" autocomplete="off" placeholder="Email" value="{{ old('email', optional($model)->email) }}">
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">Phone <span class="text-danger">*</span></label>
            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="phone" maxlength="255" autocomplete="tel" placeholder="Mobile Phone" value="{{ old('mobile', optional($model)->mobile) }}" required>
            @error('mobile')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="username">Site username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" maxlength="255" autocomplete="off" placeholder="Site username" value="{{ old('username', optional($model_login)->username) }}">
            <small class="form-text text-muted">The public site accepts this username or the profile email.</small>
            @error('username')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="model_password">New password</label>
            <input type="password" name="password" id="model_password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" placeholder="Leave blank to keep the current password">
            <small class="form-text text-muted">Leave blank to keep the current password. Minimum 6 characters.</small>
            @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="model_password_confirmation">Confirm new password</label>
            <input type="password" name="password_confirmation" id="model_password_confirmation" class="form-control" autocomplete="new-password" placeholder="Confirm new password">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="screenname">Screen Name <span class="text-danger">*</span></label>
            <input type="text" name="screenname" class="form-control @error('screenname') is-invalid @enderror" id="screenname" maxlength="255" autocomplete="off" placeholder="Screen Name" value="{{ old('screenname', optional($model_details)->screenname) }}" required>
            @error('screenname')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="weight">Weight</label>
            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" id="weight" min="1" max="999" autocomplete="off" placeholder="Weight" value="{{ old('weight', optional($model_details)->weight) }}">
            @error('weight')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="height">Height</label>
            <input type="number" name="height" class="form-control @error('height') is-invalid @enderror" id="height" min="1" max="999" autocomplete="off" placeholder="Height" value="{{ old('height', optional($model_details)->height) }}">
            @error('height')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="age">Age <span class="text-danger">*</span></label>
            <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" id="age" min="18" max="99" autocomplete="off" placeholder="Age" value="{{ old('age', optional($model_details)->age) }}" required>
            @error('age')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="bust">Bust Size</label>
            <select name="bust" id="bust" class="form-control @error('bust') is-invalid @enderror">
                <option value="">Select Bust Size</option>
                @foreach($busts as $bust)
                    <option value="{{ $bust->name }}" {{ (string) old('bust', optional($model_details)->bust) === (string) $bust->name ? 'selected' : '' }}>{{ ucwords($bust->name) }}</option>
                @endforeach
            </select>
            @error('bust')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="body">Body Type <span class="text-danger">*</span></label>
            <select name="btype" id="body" class="form-control @error('btype') is-invalid @enderror" required>
                <option value="">Select Body Type</option>
                @foreach($bodies as $body)
                    <option value="{{ $body->id }}" {{ (string) old('btype', optional($model_details)->btype) === (string) $body->id ? 'selected' : '' }}>{{ ucwords($body->name) }}</option>
                @endforeach
            </select>
            @error('btype')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="orientation">Orientation <span class="text-danger">*</span></label>
            <select name="orientation" id="orientation" class="form-control @error('orientation') is-invalid @enderror" required>
                <option value="">Select Orientation</option>
                @foreach($orientations as $orientation)
                    <option value="{{ $orientation->id }}" {{ (string) old('orientation', optional($model_details)->orientation) === (string) $orientation->id ? 'selected' : '' }}>{{ ucwords($orientation->name) }}</option>
                @endforeach
            </select>
            @error('orientation')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="ethnic">Ethnic <span class="text-danger">*</span></label>
            <select name="ethnic" id="ethnic" class="form-control @error('ethnic') is-invalid @enderror" required>
                <option value="">Select Ethnic</option>
                @foreach($ethnics as $ethnic)
                    <option value="{{ $ethnic->id }}" {{ (string) old('ethnic', optional($model_details)->ethnic) === (string) $ethnic->id ? 'selected' : '' }}>{{ ucwords($ethnic->name) }}</option>
                @endforeach
            </select>
            @error('ethnic')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="country">Country <span class="text-danger">*</span></label>
            <select name="country" id="country" class="form-control @error('country') is-invalid @enderror" required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ (string) old('country', optional($model_location)->cid) === (string) $country->id ? 'selected' : '' }}>{{ ucwords($country->name) }}</option>
                @endforeach
            </select>
            @error('country')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="province">Province <span class="text-danger">*</span></label>
            <select name="province" id="province" class="form-control @error('province') is-invalid @enderror" required>
                <option value="">Select Province</option>
                @foreach($provinces as $province)
                    @if((string) $province->cid === (string) old('country', optional($model_location)->cid))
                        <option value="{{ $province->id }}" {{ (string) $province->id === (string) old('province', optional($model_location)->pid) ? 'selected' : '' }}>{{ ucwords($province->name) }}</option>
                    @endif
                @endforeach
            </select>
            @error('province')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="town">Town <span class="text-danger">*</span></label>
            <select name="town" id="town" class="form-control @error('town') is-invalid @enderror" required>
                <option value="">Select Town</option>
                @foreach($towns as $town)
                    @if((string) $town->pid === (string) old('province', optional($model_location)->pid))
                        <option value="{{ $town->id }}" {{ (string) $town->id === (string) old('town', optional($model_location)->lid) ? 'selected' : '' }}>{{ ucwords($town->name) }}</option>
                    @endif
                @endforeach
            </select>
            @error('town')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="d-flex align-content-end">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" name="smoke" value="y" class="form-check-input" {{ old('smoke') == 'y' ? 'checked' : (optional($model_details)->smoke == 'y' ? 'checked' : '') }}>
                        Smoke
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" name="drink" value="y" class="form-check-input" {{ old('drink') == 'y' ? 'checked' : (optional($model_details)->drink == 'y' ? 'checked' : '') }}>
                        Drink
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" name="travel" value="y" class="form-check-input" {{ old('travel') == 'y' ? 'checked' : (optional($model_details)->travel == 'y' ? 'checked' : '') }}>
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
                        <input type="checkbox" name="verified" value="1" class="form-check-input" {{ old('verified') == 1 ? 'checked' : (optional($model_details)->verified == 1 ? 'checked' : '') }}>
                        Verified
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" name="top" value="1" class="form-check-input" {{ old('top') == 1 ? 'checked' : (optional($model_details)->top == 1 ? 'checked' : '') }}>
                        Top
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" name="imageverified" value="1" class="form-check-input" {{ old('imageverified') == 1 ? 'checked' : (optional($model_details)->imageverified == 1 ? 'checked' : '') }}>
                        Images Verified
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

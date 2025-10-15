
@extends('layouts.app')
@section('title', __('messages.create_user'))

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">{{ __('messages.create_user') }}</h5>
          <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.name') }}</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.email') }}</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.phone') }}</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
              </div>

              <div class="col-md-4">
                <label class="form-label">{{ __('messages.password') }}</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.password_confirmation') }}</label>
                <input type="password" class="form-control" name="password_confirmation" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.pin_code') }}</label>
                <input type="text" class="form-control" name="pin_code" value="{{ old('pin_code') }}" maxlength="4" required>
              </div>

              <div class="col-md-4">
                <label class="form-label">{{ __('messages.balance') }}</label>
                <input type="number" step="0.01" class="form-control" name="balance" value="{{ old('balance', 0) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label">{{ __('messages.daily_limit') }}</label>
                <input type="number" step="0.01" class="form-control" name="daily_limit" value="{{ old('daily_limit', 5000) }}">
              </div>
              <div class="col-md-4">
                <label class="form-label d-block">{{ __('messages.status') }}</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                  <label class="form-check-label" for="is_active">{{ __('messages.active') }}</label>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">{{ __('messages.permissions') }}</label>
                <div class="row">
                  @foreach($permissions as $permission)
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                        <label class="form-check-label">{{ $permission->display_name }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="mt-4 d-flex gap-2">
              <button class="btn btn-primary" type="submit" onclick="this.form && this.form.submit()">{{ __('messages.save') }}</button>
              <a class="btn btn-secondary" href="{{ url()->previous() }}">{{ __('messages.cancel') }}</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

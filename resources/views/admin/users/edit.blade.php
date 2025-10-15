
@extends('layouts.app')
@section('title', __('messages.edit_user'))
@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">{{ __('messages.edit_user') }}</h5>
          <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">{{ __('messages.name') }}</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">{{ __('messages.email') }}</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">{{ __('messages.phone') }}</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="is_active" class="form-select">
                  <option value="1" {{ $user->is_active ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                  <option value="0" {{ !$user->is_active ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
              </div>
              <div class="col-md-12">
                <label class="form-label">{{ __('messages.permissions') }}</label>
                <div class="row">
                  @foreach($permissions as $permission)
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                          {{ in_array($permission->id, $userPermissions ?? []) ? 'checked' : '' }}>
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

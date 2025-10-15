
@extends('layouts.app')
@section('title', __('messages.manage_permissions'))
@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3">{{ __('messages.manage_permissions') }} - {{ $user->name }}</h5>
          <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}">
            @csrf
            @method('PUT')
            <div class="row">
              @foreach($permissions as $permission)
                <div class="col-md-3">
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                      {{ in_array($permission->id, $userPermissions ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $permission->display_name }}</label>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="mt-3">
              <button class="btn btn-primary" type="submit" onclick="this.form && this.form.submit()">{{ __('messages.save') }}</button>
              <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

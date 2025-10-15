
@extends('layouts.app')
@section('title', __('messages.user_details'))
@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('messages.user_details') }}</h5>
          <div class="d-flex gap-2">
            @permission('edit_users')
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm">{{ __('messages.edit') }}</a>
            @endpermission
            @permission('assign_roles')
            <a href="{{ route('admin.users.permissions', $user) }}" class="btn btn-outline-secondary btn-sm">{{ __('messages.manage_permissions') }}</a>
            @endpermission
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-3">{{ __('messages.profile') }}</h6>
              <p class="mb-1"><strong>ID:</strong> {{ $user->id }}</p>
              <p class="mb-1"><strong>{{ __('messages.name') }}:</strong> {{ $user->name }}</p>
              <p class="mb-1"><strong>{{ __('messages.email') }}:</strong> {{ $user->email }}</p>
              <p class="mb-1"><strong>{{ __('messages.status') }}:</strong> {{ $user->is_active ? __('messages.active') : __('messages.inactive') }}</p>
              <p class="mb-1"><strong>{{ __('messages.joined') }}:</strong> {{ $user->created_at }}</p>
              <p class="mb-1"><strong>{{ __('messages.permissions') }}:</strong> {{ $user->permissions->pluck('display_name')->implode(', ') ?: '-' }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-3">{{ __('messages.statistics') }}</h6>
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ __('messages.total_transactions') }}
                  <span class="badge bg-primary rounded-pill">{{ $stats['total_transactions'] ?? 0 }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ __('messages.total_spent') }}
                  <span class="badge bg-danger rounded-pill">{{ number_format($stats['total_spent'] ?? 0, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ __('messages.total_received') }}
                  <span class="badge bg-success rounded-pill">{{ number_format($stats['total_received'] ?? 0, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ __('messages.active_cards') }}
                  <span class="badge bg-info rounded-pill">{{ $stats['active_cards'] ?? 0 }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.back') }}</a>
      </div>
    </div>
  </div>
</div>
@endsection

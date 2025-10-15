@extends('layouts.app')

@section('title', __('messages.users_management'))

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-md me-3">
                                <i class="material-icons opacity-10">people</i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ __('messages.users_management') }}</h5>
                                <p class="text-sm text-muted mb-0">{{ __('messages.manage_all_users') }}</p>
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('create_users'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="material-icons text-sm">add</i>
                            {{ __('messages.create_user') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="material-icons">check_circle</i></span>
        <span class="alert-text">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.users.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('messages.search_users') }}"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">{{ __('messages.all_statuses') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        {{ __('messages.active') }}
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('messages.inactive') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="permission" class="form-select">
                                    <option value="">{{ __('messages.all_permissions') }}</option>
                                    @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}" {{ request('permission') == $permission->name ? 'selected' : '' }}>
                                        {{ $permission->display_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="material-icons text-sm">search</i>
                                        {{ __('messages.search') }}
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="material-icons text-sm">clear</i>
                                    </a>
                                    @if(auth()->user()->hasPermission('export_reports'))
                                    <a href="{{ route('admin.users.export') }}" class="btn btn-outline-success">
                                        <i class="material-icons text-sm">download</i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('messages.user') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.contact') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.balance') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.permissions') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.status') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.joined') }}
                                    </th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-primary">
                                                    <span class="text-white text-xs">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">ID: {{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $user->phone }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($user->balance, 2) }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ __('messages.limit') }}: ${{ number_format($user->daily_limit, 2) }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($user->permissions->take(3) as $permission)
                                            <span class="badge badge-sm bg-gradient-info">{{ $permission->display_name }}</span>
                                            @empty
                                            <span class="text-xs text-secondary">{{ __('messages.no_permissions') }}</span>
                                            @endforelse
                                            @if($user->permissions->count() > 3)
                                            <span class="badge badge-sm bg-gradient-secondary">+{{ $user->permissions->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if(auth()->user()->hasPermission('edit_users'))
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="badge badge-sm bg-gradient-{{ $user->is_active ? 'success' : 'danger' }} border-0">
                                                {{ $user->is_active ? __('messages.active') : __('messages.inactive') }}
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge badge-sm bg-gradient-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? __('messages.active') : __('messages.inactive') }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $user->created_at->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" type="button" data-bs-toggle="dropdown">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(auth()->user()->hasPermission('view_users'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">visibility</i>
                                                        {{ __('messages.view_details') }}
                                                    </a>
                                                </li>
                                                @endif
                                                @if(auth()->user()->hasPermission('edit_users'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">edit</i>
                                                        {{ __('messages.edit') }}
                                                    </a>
                                                </li>
                                                @endif
                                                @if(auth()->user()->hasPermission('manage_permissions'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.users.permissions', $user) }}" onclick="event.preventDefault(); window.location.assign(this.href);">
                                                        <i class="material-icons text-sm me-2">security</i>
                                                        {{ __('messages.manage_permissions') }}
                                                    </a>
                                                </li>
                                                @endif
                                                @if(auth()->user()->hasPermission('delete_users') && $user->id !== auth()->id())
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                          onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="material-icons text-sm me-2">delete</i>
                                                            {{ __('messages.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="material-icons text-muted" style="font-size: 48px;">people_outline</i>
                                        <p class="text-muted mt-3">{{ __('messages.no_users_found') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

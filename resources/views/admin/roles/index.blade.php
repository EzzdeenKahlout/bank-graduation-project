@extends('layouts.app')

@section('title', __('messages.manage_roles'))

@section('content')
<style>
    .roles-page {
        padding: 2rem 0;
    }

    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-create {
        padding: 0.75rem 1.5rem;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
    }

    .roles-table {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem;
        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        border-bottom: 1px solid #e0e0e0;
    }

    th {
        background: #f8f9fa;
        color: #667eea;
        font-weight: bold;
    }

    .btn-edit, .btn-delete {
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        margin: 0 0.25rem;
    }

    .btn-edit {
        background: #4ade80;
        color: white;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        cursor: pointer;
    }

    .permissions-badge {
        background: #e0e0e0;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        font-size: 0.9rem;
        margin: 0 0.25rem;
    }
</style>

<div class="roles-page">
    <div class="page-header">
        <h1>🔐 {{ __('messages.manage_roles') }}</h1>
        <a href="{{ route('admin.roles.create') }}" class="btn-create">
            + {{ __('messages.create_role') }}
        </a>
    </div>

    <div class="roles-table">
        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.role_name') }}</th>
                    <th>{{ __('messages.display_name') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.permissions_count') }}</th>
                    <th>{{ __('messages.users_count') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td><strong>{{ $role->name }}</strong></td>
                    <td>{{ $role->display_name }}</td>
                    <td>{{ $role->description }}</td>
                    <td>
                        <span class="permissions-badge">
                            {{ $role->permissions->count() }} {{ __('messages.permissions') }}
                        </span>
                    </td>
                    <td>{{ $role->users->count() }} {{ __('messages.users') }}</td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn-edit">
                            {{ __('messages.edit') }}
                        </a>

                        @if($role->name !== 'super_admin')
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('{{ __('messages.confirm_delete_role') }}')">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection



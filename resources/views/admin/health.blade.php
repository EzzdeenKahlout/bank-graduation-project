
@extends('layouts.app')
@section('title', __('messages.system_health'))

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('messages.system_health') }}</h5>
          <div class="d-flex gap-2">
            <a href="{{ route('admin.health') }}" class="btn btn-outline-primary btn-sm">
              <i class="material-icons text-sm">refresh</i> {{ __('messages.refresh') }}
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">{{ __('messages.back') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Checks --}}
  <div class="row">
    @foreach($health as $name => $result)
      <div class="col-md-3 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <strong>
                @switch($name)
                  @case('database') {{ __('messages.database') }} @break
                  @case('cache') {{ __('messages.cache') }} @break
                  @case('storage') {{ __('messages.storage') }} @break
                  @case('queue') {{ __('messages.queue') }} @break
                  @default {{ ucfirst($name) }}
                @endswitch
              </strong>
              @if(($result['status'] ?? '') === 'healthy')
                <span class="badge bg-success">{{ __('messages.ok') }}</span>
              @else
                <span class="badge bg-danger">{{ __('messages.error') }}</span>
              @endif
            </div>
            <p class="text-sm text-muted mb-0">{{ $result['message'] ?? '-' }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- System info --}}
  <div class="row mt-2">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-3">{{ __('messages.system_info') }}</h6>
          <div class="table-responsive">
            <table class="table table-sm">
              <tbody>
                <tr><th>{{ __('messages.php_version') }}</th><td>{{ PHP_VERSION }}</td></tr>
                <tr><th>{{ __('messages.laravel_version') }}</th><td>{{ app()->version() }}</td></tr>
                <tr><th>{{ __('messages.app_env') }}</th><td>{{ app()->environment() }}</td></tr>
                <tr><th>{{ __('messages.app_debug') }}</th><td>{{ config('app.debug') ? 'true' : 'false' }}</td></tr>
                <tr><th>{{ __('messages.cache_driver') }}</th><td>{{ config('cache.default') }}</td></tr>
                <tr><th>{{ __('messages.queue_driver') }}</th><td>{{ config('queue.default') }}</td></tr>
                <tr><th>{{ __('messages.session_driver') }}</th><td>{{ config('session.driver') }}</td></tr>
                <tr><th>{{ __('messages.db_connection') }}</th><td>{{ config('database.default') }}</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

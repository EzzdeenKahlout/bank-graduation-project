
@extends('layouts.app')

@section('title', __('messages.transactions_list'))

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('messages.transactions_list') }}</h5>
          @permission('view_transactions')
          <a href="{{ route('admin.transactions.export') }}" class="btn btn-outline-primary btn-sm">{{ __('messages.export_csv') }}</a>
          @endpermission
        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3">
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.search') }}</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_by') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.type') }}</label>
              <select name="type" class="form-select">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="debit" {{ request('type')=='debit'?'selected':'' }}>{{ __('messages.debit') }}</option>
                <option value="credit" {{ request('type')=='credit'?'selected':'' }}>{{ __('messages.credit') }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.status') }}</label>
              <select name="status" class="form-select">
                <option value="">{{ __('messages.all_statuses') }}</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>{{ __('messages.completed') }}</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>{{ __('messages.pending') }}</option>
                <option value="failed" {{ request('status')=='failed'?'selected':'' }}>{{ __('messages.failed') }}</option>
              </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button class="btn btn-primary w-100" type="submit">{{ __('messages.apply_filters') }}</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <a class="btn btn-secondary w-100" href="{{ route('admin.transactions.index') }}">{{ __('messages.reset') }}</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  @isset($stats)
  <div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.total') }}</strong><div class="h5 mb-0">{{ $stats['total'] ?? 0 }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.completed') }}</strong><div class="h5 mb-0">{{ $stats['completed'] ?? 0 }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.pending') }}</strong><div class="h5 mb-0">{{ $stats['pending'] ?? 0 }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.failed') }}</strong><div class="h5 mb-0">{{ $stats['failed'] ?? 0 }}</div></div></div></div>
  </div>
  @endisset

  {{-- Table --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>#</th>
                  <th>{{ __('messages.transaction_id') }}</th>
                  <th>{{ __('messages.user') }}</th>
                  <th>{{ __('messages.type') }}</th>
                  <th>{{ __('messages.status') }}</th>
                  <th>{{ __('messages.amount') }}</th>
                  <th>{{ __('messages.created_at') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $tx)
                  <tr>
                    <td>{{ $tx->id }}</td>
                    <td>{{ $tx->transaction_id }}</td>
                    <td>{{ optional($tx->user)->name }}</td>
                    <td>{{ $tx->type }}</td>
                    <td>{{ $tx->status }}</td>
                    <td>{{ number_format((float)$tx->amount,2) }}</td>
                    <td>{{ $tx->created_at }}</td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center text-muted py-4">{{ __('messages.no_results') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        @if(method_exists($transactions,'links'))
          <div class="card-footer">{{ $transactions->withQueryString()->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

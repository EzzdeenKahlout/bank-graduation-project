
@extends('layouts.app')

@section('title', __('messages.cards_list'))

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('messages.cards_list') }}</h5>
            @permission('view_cards')
            <a href="{{ route('admin.cards.export') }}" class="btn btn-outline-primary btn-sm">
              {{ __('messages.export_csv') }}
            </a>
            @endpermission
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admin.cards.index') }}" class="row g-3">
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.status') }}</label>
              <select name="status" class="form-select">
                <option value="">{{ __('messages.all_statuses') }}</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>{{ __('messages.active') }}</option>
                <option value="blocked" {{ request('status')=='blocked'?'selected':'' }}>{{ __('messages.blocked') }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.type') }}</label>
              <select name="card_type" class="form-select">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="debit" {{ request('card_type')=='debit'?'selected':'' }}>{{ __('messages.debit') }}</option>
                <option value="credit" {{ request('card_type')=='credit'?'selected':'' }}>{{ __('messages.credit') }}</option>
                <option value="virtual" {{ request('card_type')=='virtual'?'selected':'' }}>{{ __('messages.virtual') }}</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">{{ __('messages.search') }}</label>
              <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_by') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
              <button class="btn btn-primary w-100" type="submit">{{ __('messages.apply_filters') }}</button>
              <a class="btn btn-secondary" href="{{ route('admin.cards.index') }}">{{ __('messages.reset') }}</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  @isset($stats)
  <div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.total_cards') }}</strong><div class="h5 mb-0">{{ $stats['total'] ?? 0 }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.active_cards') }}</strong><div class="h5 mb-0">{{ $stats['active'] ?? 0 }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><strong>{{ __('messages.blocked_cards') }}</strong><div class="h5 mb-0">{{ $stats['blocked'] ?? 0 }}</div></div></div></div>
  </div>
  @endisset

  {{-- Table --}}

  {{-- Pending requests --}}
  @if(isset($pending) && $pending->count())
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-3">{{ __('messages.pending_card_requests') }}</h6>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('messages.user') }}</th>
                  <th>{{ __('messages.card_holder_name') }}</th>
                  <th>{{ __('messages.type') }}</th>
                  <th>{{ __('messages.reason') }}</th>
                  <th>{{ __('messages.created_at') }}</th>
                  <th>{{ __('messages.actions') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pending as $req)
                  <tr>
                    <td>{{ $req->id }}</td>
                    <td>{{ optional($req->user)->name }} ({{ optional($req->user)->email }})</td>
                    <td>{{ $req->card_holder_name }}</td>
                    <td>{{ $req->card_type }}</td>
                    <td>{{ $req->reason ?? '-' }}</td>
                    <td>{{ $req->created_at }}</td>
                    <td>
                      <form method="POST" action="{{ route('admin.cards.requests.approve', $req) }}" onsubmit="return confirm('{{ __('messages.confirm_approve_card_request') }}');">
                        @csrf
                        <button class="btn btn-success btn-sm" type="submit">{{ __('messages.approve') }}</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th>#</th>
                  <th>{{ __('messages.card_number') }}</th>
                  <th>{{ __('messages.type') }}</th>
                  <th>{{ __('messages.status') }}</th>
                  <th>{{ __('messages.user') }}</th>
                  <th>{{ __('messages.created_at') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse($cards as $card)
                  <tr>
                    <td>{{ $card->id }}</td>
                    <td>{{ $card->masked_number ?? $card->card_number }}</td>
                    <td>{{ $card->card_type }}</td>
                    <td>{{ $card->status }}</td>
                    <td>{{ optional($card->user)->name }}</td>
                    <td>{{ $card->created_at }}</td>
                    <td>
                      <a href="{{ route('admin.cards.show', $card) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.details') }}</a>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center text-muted py-4">{{ __('messages.no_results') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        @if(method_exists($cards,'links'))
          <div class="card-footer">{{ $cards->withQueryString()->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

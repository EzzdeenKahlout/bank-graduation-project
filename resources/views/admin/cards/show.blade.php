
@extends('layouts.app')
@section('title', __('messages.card_details'))

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('messages.card_details') }}</h5>
          <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">{{ __('messages.back') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="mb-3">{{ __('messages.card_information') }}</h6>
          <p class="mb-1"><strong>{{ __('messages.card_number') }}:</strong> {{ $card->masked_number ?? $card->card_number }}</p>
          <p class="mb-1"><strong>{{ __('messages.card_holder_name') }}:</strong> {{ $card->card_holder_name }}</p>
          <p class="mb-1"><strong>{{ __('messages.type') }}:</strong> {{ $card->card_type }}</p>
          <p class="mb-1"><strong>{{ __('messages.status') }}:</strong> {{ $card->status }}</p>
          <p class="mb-1"><strong>{{ __('messages.expiry_date') }}:</strong> {{ $card->expiry_date }}</p>
          @if($card->card_type === 'credit')
          <p class="mb-1"><strong>{{ __('messages.credit_limit') }}:</strong> {{ number_format((float)$card->credit_limit,2) }}</p>
          @endif
          <p class="mb-1"><strong>{{ __('messages.user') }}:</strong> {{ optional($card->user)->name }} ({{ optional($card->user)->email }})</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
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
              {{ __('messages.last_transaction') }}
              <span class="badge bg-secondary rounded-pill">
                @if(!empty($stats['last_transaction']))
                  {{ optional($stats['last_transaction'])->reference_number }} - {{ optional($stats['last_transaction'])->status }}
                @else
                  -
                @endif
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-3">{{ __('messages.recent_transactions') }}</h6>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('messages.transaction_id') }}</th>
                  <th>{{ __('messages.type') }}</th>
                  <th>{{ __('messages.status') }}</th>
                  <th>{{ __('messages.amount') }}</th>
                  <th>{{ __('messages.created_at') }}</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $userId = $card->user_id;
                  $recent = \App\Models\Transaction::where(function($q) use ($userId){ $q->where('sender_id',$userId)->orWhere('receiver_id',$userId);})->latest()->limit(10)->get();
                @endphp
                @forelse($recent as $tx)
                  <tr>
                    <td>{{ $tx->id }}</td>
                    <td>{{ $tx->reference_number }}</td>
                    <td>{{ $tx->transaction_type }}</td>
                    <td>{{ $tx->status }}</td>
                    <td>{{ number_format((float)$tx->amount,2) }}</td>
                    <td>{{ $tx->created_at }}</td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center text-muted py-3">{{ __('messages.no_results') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

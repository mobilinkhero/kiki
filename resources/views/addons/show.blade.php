@extends('layouts.tenant')

@section('title', $addon->name)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-4">
                <a href="{{ route('tenant.addons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Addons
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="{{ $addon->icon }} fa-5x text-primary mb-3"></i>
                            <h1 class="h2">{{ $addon->name }}</h1>
                            @if($addon->badge)
                                <span class="badge bg-primary">{{ $addon->badge }}</span>
                            @endif
                        </div>

                        <div class="addon-description mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $addon->description }}</p>
                        </div>

                        @if($addon->type === 'credits')
                            <div class="addon-features mb-4">
                                <h5>What You Get</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <strong>{{ number_format($addon->credit_amount) }}</strong> AI Credits
                                    </li>
                                    @if($addon->bonus_amount)
                                        <li class="mb-2">
                                            <i class="fas fa-gift text-warning me-2"></i>
                                            <strong>{{ number_format($addon->bonus_amount) }}</strong> Bonus Credits
                                        </li>
                                    @endif
                                    <li class="mb-2">
                                        <i class="fas fa-infinity text-info me-2"></i>
                                        Credits never expire
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-bolt text-danger me-2"></i>
                                        Instant activation after payment
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if($userPurchases->isNotEmpty())
                            <div class="purchase-history mb-4">
                                <h5>Your Recent Purchases</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Credits</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userPurchases as $purchase)
                                                <tr>
                                                    <td>{{ $purchase->created_at->format('M d, Y') }}</td>
                                                    <td>{{ number_format($purchase->credits_received + $purchase->bonus_received) }}
                                                    </td>
                                                    <td>PKR {{ number_format($purchase->amount_paid) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $purchase->status === 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($purchase->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="text-primary mb-0">PKR {{ number_format($addon->price) }}</h2>
                            <small class="text-muted">One-time payment</small>
                        </div>

                        @if($addon->type === 'credits')
                            <div class="alert alert-info text-center mb-4">
                                <div class="h4 mb-0">{{ number_format($addon->total_credits) }}</div>
                                <small>Total Credits</small>
                            </div>
                        @endif

                        <form action="{{ route('tenant.addons.purchase', $addon) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-shopping-cart me-2"></i>Purchase Now
                            </button>
                        </form>

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Secure payment via APG
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
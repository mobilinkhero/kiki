@extends('layouts.tenant')

@section('title', 'My Addon Purchases')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-2">My Addon Purchases</h1>
                <p class="text-muted">View your purchase history and current balance</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-gradient-primary text-white shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-coins fa-3x me-3"></i>
                            <div>
                                <small class="d-block opacity-75">Current Balance</small>
                                <h2 class="mb-0">{{ number_format($creditBalance, 2) }}</h2>
                                <small>AI Credits</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-gradient-success text-white shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-3x me-3"></i>
                            <div>
                                <small class="d-block opacity-75">Total Purchases</small>
                                <h2 class="mb-0">{{ $purchases->total() }}</h2>
                                <small>Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <a href="{{ route('tenant.addons.index') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>Buy More Credits
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Purchase History</h5>
                    </div>
                    <div class="card-body">
                        @if($purchases->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No purchases yet</p>
                                <a href="{{ route('tenant.addons.index') }}" class="btn btn-primary">
                                    Browse Addons
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Addon</th>
                                            <th>Credits Received</th>
                                            <th>Amount Paid</th>
                                            <th>Status</th>
                                            <th>Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <strong>{{ $purchase->addonService->name }}</strong>
                                                    @if($purchase->addonService->badge)
                                                        <span class="badge bg-primary ms-2">{{ $purchase->addonService->badge }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ number_format($purchase->credits_received) }}
                                                    </span>
                                                    @if($purchase->bonus_received)
                                                        <span class="badge bg-warning text-dark">
                                                            +{{ number_format($purchase->bonus_received) }} bonus
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>PKR {{ number_format($purchase->amount_paid) }}</td>
                                                <td>
                                                    @if($purchase->status === 'completed')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check me-1"></i>Completed
                                                        </span>
                                                    @elseif($purchase->status === 'pending')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Pending
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times me-1"></i>Failed
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($purchase->invoice)
                                                        <a href="{{ route('tenant.invoices.show', $purchase->invoice_id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $purchases->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
    </style>
@endsection
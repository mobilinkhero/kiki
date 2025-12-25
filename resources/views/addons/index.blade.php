@extends('layouts.tenant')

@section('title', 'Addon Services')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Addon Services</h1>
                        <p class="text-muted">Enhance your experience with premium addons</p>
                    </div>
                    @if($userCredits !== null)
                        <div class="credit-balance-card">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-coins text-warning fa-2x me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Your Balance</small>
                                    <h4 class="mb-0">{{ number_format($userCredits, 2) }} <small>credits</small></h4>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($addons->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                No addon services available at the moment.
            </div>
        @else
            @foreach($addons as $category => $categoryAddons)
                <div class="addon-category mb-5">
                    <h2 class="h4 mb-4">
                        <i class="fas fa-tag me-2"></i>{{ $category }}
                    </h2>

                    <div class="row g-4">
                        @foreach($categoryAddons as $addon)
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card addon-card h-100 shadow-sm hover-shadow">
                                    @if($addon->badge)
                                        <div class="addon-badge badge bg-primary">{{ $addon->badge }}</div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <div class="addon-icon text-center mb-3">
                                            <i class="{{ $addon->icon }} fa-3x text-primary"></i>
                                        </div>

                                        <h5 class="card-title text-center">{{ $addon->name }}</h5>
                                        <p class="card-text text-muted small flex-grow-1">{{ $addon->description }}</p>

                                        @if($addon->type === 'credits')
                                            <div class="credit-info text-center mb-3">
                                                <div class="badge bg-success mb-2">
                                                    {{ number_format($addon->credit_amount) }} Credits
                                                </div>
                                                @if($addon->bonus_amount)
                                                    <div class="text-success small">
                                                        <i class="fas fa-gift"></i>
                                                        + {{ number_format($addon->bonus_amount) }} Bonus
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="addon-price text-center mb-3">
                                            <h3 class="text-primary mb-0">
                                                PKR {{ number_format($addon->price) }}
                                            </h3>
                                        </div>

                                        <form action="{{ route('tenant.addons.purchase', ['addon' => $addon->slug]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Purchase Now
                                            </button>
                                        </form>

                                        <a href="{{ route('tenant.addons.show', $addon->slug) }}" class="btn btn-link btn-sm mt-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <style>
        .addon-card {
            position: relative;
            transition: transform 0.2s;
            border-radius: 12px;
            overflow: hidden;
        }

        .addon-card:hover {
            transform: translateY(-5px);
        }

        .hover-shadow:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }

        .addon-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .credit-balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .addon-icon i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endsection
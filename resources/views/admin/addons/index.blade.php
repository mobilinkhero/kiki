@extends('layouts.admin')

@section('title', 'Addon Services Management')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Addon Services</h1>
                        <p class="text-muted">Manage addon services available for purchase</p>
                    </div>
                    <a href="{{ route('admin.addons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Addon
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if($addons->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No addon services created yet</p>
                        <a href="{{ route('admin.addons.create') }}" class="btn btn-primary">
                            Create Your First Addon
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Credits</th>
                                    <th>Status</th>
                                    <th>Purchases</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addons as $addon)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $addon->icon }} fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <strong>{{ $addon->name }}</strong>
                                                    @if($addon->badge)
                                                        <span class="badge bg-primary ms-2">{{ $addon->badge }}</span>
                                                    @endif
                                                    @if($addon->is_featured)
                                                        <span class="badge bg-warning text-dark ms-1">Featured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $addon->category ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($addon->type) }}</span>
                                        </td>
                                        <td>PKR {{ number_format($addon->price) }}</td>
                                        <td>
                                            @if($addon->type === 'credits')
                                                {{ number_format($addon->credit_amount) }}
                                                @if($addon->bonus_amount)
                                                    <small class="text-success">+{{ number_format($addon->bonus_amount) }}</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.addons.toggle-active', $addon) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-{{ $addon->is_active ? 'success' : 'secondary' }}">
                                                    <i class="fas fa-{{ $addon->is_active ? 'check' : 'times' }}"></i>
                                                    {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $addon->purchases->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.addons.edit', $addon) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this addon?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $addons->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
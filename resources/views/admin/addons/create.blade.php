@extends('layouts.admin')

@section('title', 'Create Addon Service')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.addons.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Addons
            </a>
            <h1 class="h3">Create New Addon Service</h1>
        </div>
    </div>

    <form action="{{ route('admin.addons.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="AI Credits - Starter Pack" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug (URL-friendly name)</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug') }}" placeholder="ai-credits-starter">
                            <small class="text-muted">Leave empty to auto-generate from name</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Describe what this addon provides...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="AI" {{ old('category') === 'AI' ? 'selected' : '' }}>AI Services</option>
                                    <option value="SMS" {{ old('category') === 'SMS' ? 'selected' : '' }}>SMS Credits</option>
                                    <option value="Support" {{ old('category') === 'Support' ? 'selected' : '' }}>Premium Support</option>
                                    <option value="Features" {{ old('category') === 'Features' ? 'selected' : '' }}>Features</option>
                                    <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="addonType" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="credits" {{ old('type') === 'credits' ? 'selected' : '' }}>Credits (AI, SMS, etc.)</option>
                                    <option value="feature" {{ old('type') === 'feature' ? 'selected' : '' }}>Feature Access</option>
                                    <option value="one_time" {{ old('type') === 'one_time' ? 'selected' : '' }}>One-Time Service</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4" id="creditsFields">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Credits Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Credit Amount</label>
                                <input type="number" name="credit_amount" step="0.01" class="form-control @error('credit_amount') is-invalid @enderror" 
                                       value="{{ old('credit_amount') }}" placeholder="1000">
                                @error('credit_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bonus Credits (Optional)</label>
                                <input type="number" name="bonus_amount" step="0.01" class="form-control @error('bonus_amount') is-invalid @enderror" 
                                       value="{{ old('bonus_amount') }}" placeholder="100">
                                @error('bonus_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Price (PKR) <span class="text-danger">*</span></label>
                            <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" placeholder="2000.00" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Display Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Icon (Font Awesome class)</label>
                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                   value="{{ old('icon', 'fas fa-coins') }}" placeholder="fas fa-coins">
                            <small class="text-muted">e.g., fas fa-coins, fas fa-star</small>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Badge (Optional)</label>
                            <input type="text" name="badge" class="form-control @error('badge') is-invalid @enderror" 
                                   value="{{ old('badge') }}" placeholder="Popular, Best Value">
                            @error('badge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', 0) }}" placeholder="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="isFeatured" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i>Create Addon Service
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('addonType').addEventListener('change', function() {
    const creditsFields = document.getElementById('creditsFields');
    creditsFields.style.display = this.value === 'credits' ? 'block' : 'none';
});

// Initial state
document.getElementById('addonType').dispatchEvent(new Event('change'));
</script>
@endsection

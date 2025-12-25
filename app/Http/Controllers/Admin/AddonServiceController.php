<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddonServiceController extends Controller
{
    /**
     * Display all addon services
     */
    public function index()
    {
        $addons = AddonService::orderBy('category')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.addons.index', compact('addons'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.addons.create');
    }

    /**
     * Store new addon service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:addon_services,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:credits,feature,one_time',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'credit_amount' => 'nullable|numeric|min:0',
            'bonus_amount' => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'icon' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $addon = AddonService::create($validated);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Addon service created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(AddonService $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    /**
     * Update addon service
     */
    public function update(Request $request, AddonService $addon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:addon_services,slug,' . $addon->id,
            'description' => 'nullable|string',
            'type' => 'required|in:credits,feature,one_time',
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'credit_amount' => 'nullable|numeric|min:0',
            'bonus_amount' => 'nullable|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'icon' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $addon->update($validated);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Addon service updated successfully!');
    }

    /**
     * Delete addon service
     */
    public function destroy(AddonService $addon)
    {
        // Check if addon has any purchases
        if ($addon->purchases()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete addon with existing purchases. Deactivate it instead.');
        }

        $addon->delete();

        return redirect()->route('admin.addons.index')
            ->with('success', 'Addon service deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(AddonService $addon)
    {
        $addon->update(['is_active' => !$addon->is_active]);

        $status = $addon->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Addon service {$status} successfully!");
    }
}

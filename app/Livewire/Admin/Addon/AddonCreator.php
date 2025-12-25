<?php

namespace App\Livewire\Admin\Addon;

use App\Models\AddonService;
use Livewire\Component;
use Illuminate\Support\Str;

class AddonCreator extends Component
{
    public $addonId;
    public $isUpdate = false;

    // Form fields
    public $name;
    public $slug;
    public $description;
    public $type = 'credits';
    public $category = 'AI';
    public $price;
    public $credit_amount;
    public $bonus_amount;
    public $duration_days;
    public $icon = 'fas fa-coins';
    public $badge;
    public $sort_order = 0;
    public $is_active = true;
    public $is_featured = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:addon_services,slug,' . $this->addonId,
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
        ];
    }

    public function mount($addonId = null)
    {
        if ($addonId) {
            $this->addonId = $addonId;
            $this->isUpdate = true;
            $this->loadAddon();
        }
    }

    public function loadAddon()
    {
        $addon = AddonService::findOrFail($this->addonId);

        $this->name = $addon->name;
        $this->slug = $addon->slug;
        $this->description = $addon->description;
        $this->type = $addon->type;
        $this->category = $addon->category;
        $this->price = $addon->price;
        $this->credit_amount = $addon->credit_amount;
        $this->bonus_amount = $addon->bonus_amount;
        $this->duration_days = $addon->duration_days;
        $this->icon = $addon->icon;
        $this->badge = $addon->badge;
        $this->sort_order = $addon->sort_order;
        $this->is_active = $addon->is_active;
        $this->is_featured = $addon->is_featured;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'name' && !$this->isUpdate) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'category' => $this->category,
            'price' => $this->price,
            'credit_amount' => $this->credit_amount,
            'bonus_amount' => $this->bonus_amount,
            'duration_days' => $this->duration_days,
            'icon' => $this->icon,
            'badge' => $this->badge,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        if ($this->isUpdate) {
            $addon = AddonService::findOrFail($this->addonId);
            $addon->update($data);
            $message = 'Addon updated successfully!';
        } else {
            AddonService::create($data);
            $message = 'Addon created successfully!';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        return redirect()->route('admin.addons.index');
    }

    public function render()
    {
        return view('livewire.admin.addon.addon-creator');
    }
}

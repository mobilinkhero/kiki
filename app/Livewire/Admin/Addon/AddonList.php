<?php

namespace App\Livewire\Admin\Addon;

use App\Models\AddonService;
use Livewire\Component;
use Livewire\WithPagination;

class AddonList extends Component
{
    use WithPagination;

    public $confirmingDeletion = false;
    public $addonToDelete = null;

    public function editAddon($id)
    {
        return redirect()->route('admin.addons.edit', $id);
    }

    public function toggleActive($id)
    {
        $addon = AddonService::findOrFail($id);
        $addon->update(['is_active' => !$addon->is_active]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $addon->is_active ? 'Addon activated successfully!' : 'Addon deactivated successfully!'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->addonToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        if ($this->addonToDelete) {
            $addon = AddonService::findOrFail($this->addonToDelete);

            if ($addon->purchases()->exists()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Cannot delete addon with existing purchases!'
                ]);
                $this->confirmingDeletion = false;
                return;
            }

            $addon->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Addon deleted successfully!'
            ]);
        }

        $this->confirmingDeletion = false;
        $this->addonToDelete = null;
    }

    public function render()
    {
        $addons = AddonService::with('purchases')
            ->orderBy('category')
            ->orderBy('sort_order')
            ->paginate(12);

        return view('livewire.admin.addon.addon-list', [
            'addons' => $addons
        ]);
    }
}

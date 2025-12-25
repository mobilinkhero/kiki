<?php

namespace App\Livewire\Tenant\Addon;

use App\Models\AddonService;
use App\Models\AiCredit;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddonMarketplace extends Component
{
    public $userCredits = null;

    public function mount()
    {
        if (Auth::check()) {
            $aiCredit = AiCredit::where('user_id', Auth::id())
                ->where('tenant_id', tenant_id())
                ->first();
            $this->userCredits = $aiCredit ? $aiCredit->available_balance : 0;
        }
    }

    public function render()
    {
        $addons = AddonService::active()
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get()
            ->groupBy('category');

        return view('livewire.tenant.addon.addon-marketplace', [
            'addons' => $addons
        ]);
    }
}

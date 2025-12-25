<?php

namespace App\Http\Controllers;

use App\Models\AddonService;
use App\Models\AiCredit;
use App\Models\Invoice\Invoice;
use App\Models\UserAddonPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddonServiceController extends Controller
{
    /**
     * Display addon marketplace
     */
    public function index()
    {
        // Query from central database since addons are global
        $addons = AddonService::on('mysql')
            ->active()
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get()
            ->groupBy('category');

        // Get user's current credit balance
        $userCredits = null;
        if (Auth::check()) {
            $aiCredit = AiCredit::where('user_id', Auth::id())
                ->where('tenant_id', tenant_id())
                ->first();
            $userCredits = $aiCredit ? $aiCredit->available_balance : 0;
        }

        return view('addons.index', compact('addons', 'userCredits'));
    }

    /**
     * Show single addon details
     */
    public function show(string $addon)
    {
        // Query from central database since addons are global
        $addon = AddonService::on('mysql')->where('slug', $addon)->firstOrFail();

        if (!$addon->is_active) {
            abort(404);
        }

        // Get user's purchase history for this addon
        $userPurchases = [];
        if (Auth::check()) {
            $userPurchases = UserAddonPurchase::forUser(Auth::id(), tenant_id())
                ->where('addon_service_id', $addon->id)
                ->completed()
                ->latest()
                ->take(5)
                ->get();
        }

        return view('addons.show', compact('addon', 'userPurchases'));
    }

    /**
     * Purchase an addon
     */
    public function purchase(Request $request, string $addon)
    {
        $addon = AddonService::where('slug', $addon)->firstOrFail();

        if (!$addon->is_active) {
            return redirect()->back()->with('error', 'This addon is not available.');
        }

        // Create invoice
        $invoice = Invoice::create([
            'user_id' => Auth::id(),
            'tenant_id' => tenant_id(),
            'invoice_number' => 'ADDON-' . time() . '-' . strtoupper(substr(md5(uniqid()), 0, 6)),
            'type' => 'addon_service',
            'total' => $addon->price,
            'currency_id' => 1, // PKR
            'status' => Invoice::STATUS_NEW,
            'metadata' => [
                'addon_service_id' => $addon->id,
                'addon_name' => $addon->name,
                'addon_type' => $addon->type,
                'credit_amount' => $addon->credit_amount,
                'bonus_amount' => $addon->bonus_amount,
            ],
        ]);

        // Create purchase record (pending until payment)
        UserAddonPurchase::create([
            'user_id' => Auth::id(),
            'tenant_id' => tenant_id(),
            'addon_service_id' => $addon->id,
            'invoice_id' => $invoice->id,
            'amount_paid' => $addon->price,
            'credits_received' => $addon->credit_amount,
            'bonus_received' => $addon->bonus_amount,
            'status' => 'pending',
        ]);

        // Redirect to payment gateway (APG)
        return redirect()->route('tenant.payment.apg.checkout', [
            'invoice' => $invoice->id
        ]);
    }

    /**
     * Show user's purchase history
     */
    public function myPurchases()
    {
        $purchases = UserAddonPurchase::forUser(Auth::id(), tenant_id())
            ->with(['addonService', 'invoice'])
            ->latest()
            ->paginate(20);

        // Get current credit balance
        $aiCredit = AiCredit::where('user_id', Auth::id())
            ->where('tenant_id', tenant_id())
            ->first();

        $creditBalance = $aiCredit ? $aiCredit->available_balance : 0;

        return view('addons.my-purchases', compact('purchases', 'creditBalance'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPaymentMethod;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentMethodController extends Controller
{
    /**
     * Display list of FIX official receiving payment methods.
     */
    public function index(): View
    {
        $methods = AdminPaymentMethod::latest()->get();

        return view('admin.payment-methods.index', compact('methods'));
    }

    /**
     * Store new admin payment receiving method.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'method_type' => ['required', 'string', 'in:bkash,nagad,rocket,upay,bank,offline'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'number_type' => ['nullable', 'string', 'in:merchant,personal,agent'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            AdminPaymentMethod::where('is_default', true)->update(['is_default' => false]);
        }

        $method = AdminPaymentMethod::create([
            'method_type' => $validated['method_type'],
            'account_number' => $validated['account_number'] ?? ($validated['method_type'] === 'offline' ? 'CASH-COUNTER' : ''),
            'account_name' => $validated['account_name'] ?? 'FIX Operations',
            'number_type' => $validated['number_type'] ?? 'merchant',
            'bank_name' => $validated['bank_name'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => true,
            'is_default' => $isDefault || AdminPaymentMethod::count() === 0,
        ]);

        ActivityLogService::logSystem('admin.payment_method_created', "Created official receiving method {$method->formatted_method}.");

        return back()->with('success', "Payment method '{$method->formatted_method}' added successfully!");
    }

    /**
     * Update existing admin receiving payment method.
     */
    public function update(Request $request, AdminPaymentMethod $adminPaymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'method_type' => ['required', 'string', 'in:bkash,nagad,rocket,upay,bank,offline'],
            'account_number' => ['nullable', 'string', 'max:60'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'number_type' => ['nullable', 'string', 'in:merchant,personal,agent'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'branch_name' => ['nullable', 'string', 'max:100'],
            'routing_number' => ['nullable', 'string', 'max:50'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $isDefault = $request->boolean('is_default');
        if ($isDefault) {
            AdminPaymentMethod::where('id', '!=', $adminPaymentMethod->id)->update(['is_default' => false]);
        }

        $adminPaymentMethod->update([
            'method_type' => $validated['method_type'],
            'account_number' => $validated['account_number'] ?? $adminPaymentMethod->account_number,
            'account_name' => $validated['account_name'] ?? $adminPaymentMethod->account_name,
            'number_type' => $validated['number_type'] ?? $adminPaymentMethod->number_type,
            'bank_name' => $validated['bank_name'] ?? null,
            'branch_name' => $validated['branch_name'] ?? null,
            'routing_number' => $validated['routing_number'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $adminPaymentMethod->is_active,
            'is_default' => $isDefault,
        ]);

        ActivityLogService::logSystem('admin.payment_method_updated', "Updated official receiving method {$adminPaymentMethod->formatted_method}.");

        return back()->with('success', "Payment method '{$adminPaymentMethod->formatted_method}' updated successfully!");
    }

    /**
     * Delete an admin payment method.
     */
    public function destroy(AdminPaymentMethod $adminPaymentMethod): RedirectResponse
    {
        $name = $adminPaymentMethod->formatted_method;
        $adminPaymentMethod->delete();

        ActivityLogService::logSystem('admin.payment_method_deleted', "Deleted official receiving method {$name}.");

        return back()->with('success', "Payment method '{$name}' deleted.");
    }

    /**
     * Set a method as default.
     */
    public function setDefault(AdminPaymentMethod $adminPaymentMethod): RedirectResponse
    {
        AdminPaymentMethod::where('id', '!=', $adminPaymentMethod->id)->update(['is_default' => false]);
        $adminPaymentMethod->forceFill(['is_default' => true, 'is_active' => true])->save();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Default receiving payment method updated to {$adminPaymentMethod->formatted_method}.");
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(AdminPaymentMethod $adminPaymentMethod): RedirectResponse
    {
        $adminPaymentMethod->update(['is_active' => ! $adminPaymentMethod->is_active]);

        $status = $adminPaymentMethod->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Payment method {$adminPaymentMethod->formatted_method} {$status}.");
    }
}

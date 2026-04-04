<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccounts;
use App\Models\Cheque;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ChequeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cheques = Cheque::with('bankAccount')->latest()->paginate(10);
        $bankAccounts = BankAccounts::all();
        return view('cheque-detail.index', compact('cheques', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request): ?RedirectResponse
    {
        // ✅ VALIDATION
        $validator = Validator::make($request->all(), [
            'cheque_no' => 'required|string|max:255|unique:cheques,cheque_no',
            'cheque_date' => 'required|date',
            'cheque_exp_date' => 'nullable|date',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'cheque_amount' => 'required|numeric|min:0',
            'cheque_type_combined' => 'required|string|max:255', // updated field
            'cheque_type' => 'required|in:received,issued',
            'status' => 'required|in:pending,deposited,cleared,bounced,cancelled',
            'remarks' => 'nullable|string|max:1000',
            'deposit_date' => 'nullable|date',
            'realization_date' => 'nullable|date',
            'bounce_date' => 'nullable|date',
            'bank_charges' => 'nullable|numeric|min:0',
            'penalty_amount' => 'nullable|numeric|min:0',
            'reference_no' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ Fetch bank details
            $bank = BankAccounts::findOrFail($request->bank_account_id);

            // ✅ Prepare cheque data
            $data = $request->only([
                'cheque_no',
                'cheque_date',
                'cheque_exp_date',
                'cheque_amount',
                'cheque_type',
                'status',
                'remarks',
                'deposit_date',
                'realization_date',
                'bounce_date',
                'bank_charges',
                'penalty_amount',
                'reference_no',
            ]);

            // Set bank-related fields
            $data['bank_account_id'] = $bank->id;
            $data['bank_name'] = $bank->bank_name;
            $data['company_name'] = $bank->company_name;
            $data['branch_name'] = $bank->branch_name;

            // Use combined cheque type for Cash cheques
            $data['cheque_type_cross_cheque'] = $request->cheque_type_combined;

            // Track user who received the cheque
            if (Auth::check()) {
                $data['received_by'] = Auth::id();
            }

            // ✅ Handle status-based dates if not manually entered
            switch ($data['status']) {
                case 'deposited':
                    $data['deposit_date'] = $data['deposit_date'] ?? now();
                    break;

                case 'cleared':
                    $data['deposit_date'] = $data['deposit_date'] ?? now();
                    $data['realization_date'] = $data['realization_date'] ?? now();
                    break;

                case 'bounced':
                    $data['bounce_date'] = $data['bounce_date'] ?? now();
                    break;
            }

            // Default financial fields if not provided
            $data['bank_charges'] = $data['bank_charges'] ?? 0;
            $data['penalty_amount'] = $data['penalty_amount'] ?? 0;

            // ✅ Create cheque record
            $cheque = Cheque::create($data);

            // ✅ Log activity
            $this->logActivity(
                'created',
                oldValues: null,
                newValues: $cheque->toArray(),
                cheque: $cheque
            );

            DB::commit();

            return redirect()->back()->with('success', 'Cheque created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Cheque store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Please try again.')
                ->withInput();
        }
    }

    /**
     * Activity logging helper
     */
    protected function logActivity($action, $oldValues = null, $newValues = null, $cheque = null): void
    {
        try {
            ActivityLog::create([
                'module' => 'cheque',
                'module_id' => $cheque?->id,
                'action' => $action,
                'user_id' => Auth::id(),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'description' => "Cheque #{$cheque?->cheque_no} {$action}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status' => $cheque?->status,
            ]);
        } catch (Exception $e) {
            // Prevent logging failure from breaking the app
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cheque $cheque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cheque $cheque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cheque $cheque): RedirectResponse
    {
        // ✅ VALIDATION (similar to store but allow same cheque_no for this record)
        $validator = Validator::make($request->all(), [
            'cheque_no' => 'required|string|max:255|unique:cheques,cheque_no,' . $cheque->id,
            'cheque_date' => 'required|date',
            'cheque_exp_date' => 'nullable|date',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'cheque_amount' => 'required|numeric|min:0',
            'cheque_type_combined' => 'required|string|max:255',
            'cheque_type' => 'required|in:received,issued',
            'status' => 'required|in:pending,deposited,cleared,bounced,cancelled',
            'remarks' => 'nullable|string|max:1000',
            'deposit_date' => 'nullable|date',
            'realization_date' => 'nullable|date',
            'bounce_date' => 'nullable|date',
            'bank_charges' => 'nullable|numeric|min:0',
            'penalty_amount' => 'nullable|numeric|min:0',
            'reference_no' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // ✅ Fetch bank details
            $bank = BankAccounts::findOrFail($request->bank_account_id);

            // ✅ Prepare cheque data
            $data = $request->only([
                'cheque_no',
                'cheque_date',
                'cheque_exp_date',
                'cheque_amount',
                'cheque_type',
                'status',
                'remarks',
                'deposit_date',
                'realization_date',
                'bounce_date',
                'bank_charges',
                'penalty_amount',
                'reference_no',
            ]);

            // Set bank-related fields
            $data['bank_account_id'] = $bank->id;
            $data['bank_name'] = $bank->bank_name;
            $data['company_name'] = $bank->company_name;
            $data['branch_name'] = $bank->branch_name;

            // Use combined cheque type for Cash cheques
            $data['cheque_type_cross_cheque'] = $request->cheque_type_combined;

            // ✅ Handle status-based dates if not manually entered
            switch ($data['status']) {
                case 'deposited':
                    $data['deposit_date'] = $data['deposit_date'] ?? now();
                    break;

                case 'cleared':
                    $data['deposit_date'] = $data['deposit_date'] ?? now();
                    $data['realization_date'] = $data['realization_date'] ?? now();
                    break;

                case 'bounced':
                    $data['bounce_date'] = $data['bounce_date'] ?? now();
                    break;
            }

            // Default financial fields if not provided
            $data['bank_charges'] = $data['bank_charges'] ?? 0;
            $data['penalty_amount'] = $data['penalty_amount'] ?? 0;

            // ✅ Update cheque record
            $cheque->update($data);

            DB::commit();

            return redirect()->back()->with('success', 'Cheque updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Cheque update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Update failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cheque $cheque): RedirectResponse
    {
        try {
            $cheque->delete();
            return redirect()->back()->with('success', 'Cheque deleted successfully!');
        } catch (Exception $e) {
            Log::error('Cheque delete error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $cheque->id,
            ]);

            return redirect()->back()->with('error', 'Failed to delete cheque.');
        }
    }
}

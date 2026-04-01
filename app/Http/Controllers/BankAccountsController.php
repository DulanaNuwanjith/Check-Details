<?php

namespace App\Http\Controllers;

use App\Models\BankAccounts;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BankAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankAccounts = BankAccounts::all();
        return view('bank-account.index', compact('bankAccounts'));
    }

    /**
     * Store a newly created resource or update an existing one.
     * @throws Throwable
     */
    public function store(Request $request): ?RedirectResponse
    {
        // Check if this is an update (hidden input "_method" = PATCH)
        $isUpdate = $request->has('_method') && $request->_method === 'PATCH';
        $id = $isUpdate ? $request->route('bank_accounts') : null;

        // Validation rules
        $rules = [
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number' . ($id ? ",$id" : ''),
            'account_type' => 'required|in:current,savings,business',
            'is_active' => 'required|boolean',
            'remarks' => 'nullable|string|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            if ($isUpdate && $id) {
                // Update an existing account
                $bankAccount = BankAccounts::findOrFail($id);
                $bankAccount->update([
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_name' => $request->account_name,
                    'account_number' => $request->account_number,
                    'account_type' => $request->account_type,
                    'is_active' => $request->is_active,
                    'remarks' => $request->remarks,
                ]);

                $message = 'Bank account updated successfully.';
            } else {
                // Create a new account
                BankAccounts::create([
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_name' => $request->account_name,
                    'account_number' => $request->account_number,
                    'account_type' => $request->account_type,
                    'is_active' => $request->is_active,
                    'remarks' => $request->remarks,
                ]);

                $message = 'Bank account added successfully.';
            }

            DB::commit();

            return redirect()->route('bank-accounts.index')
                ->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('BankAccount store/update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred. Please try again.')
                ->withInput();
        }
    }

    /**
     * Toggle the active/inactive status of a bank account.
     *
     * @param int $id
     * @return RedirectResponse|null
     */
    public function toggleStatus(int $id): ?RedirectResponse
    {
        try {
            $bankAccount = BankAccounts::findOrFail($id);
            $bankAccount->is_active = !$bankAccount->is_active;
            $bankAccount->save();

            $status = $bankAccount->is_active ? 'activated' : 'deactivated';

            return redirect()->route('bank-accounts.index')
                ->with('success', "Bank account {$status} successfully.");
        } catch (Exception $e) {
            Log::error('BankAccount toggle status error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $id,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to change bank account status.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccounts $bankAccounts): ?RedirectResponse
    {
        try {
            $bankAccounts->delete();
            return redirect()->route('bank-accounts.index')
                ->with('success', 'Bank account deleted successfully.');
        } catch (Exception $e) {
            Log::error('BankAccount delete error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'id' => $bankAccounts->id,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete bank account.');
        }
    }
}

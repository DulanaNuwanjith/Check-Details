<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChequeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cheques = Cheque::latest()->paginate(10);
        return view('cheque-detail.index', compact('cheques'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ VALIDATION
        $validated = $request->validate([
            'cheque_no'      => 'required|string|max:255|unique:cheques,cheque_no',
            'cheque_date'    => 'required|date',
            'bank_name'      => 'required|string|max:255',
            'cheque_amount'  => 'required|numeric|min:0',

            'cheque_type'    => 'required|in:received,issued',
            'status'         => 'required|in:pending,deposited,cleared,bounced,cancelled',

            'remarks'        => 'nullable|string|max:1000',
        ]);

        // ✅ BUSINESS LOGIC
        $data = $validated;

        // Auto-assign user (if logged in)
        if (Auth::check()) {
            $data['received_by'] = Auth::id();
        }

        // Handle status-based dates
        switch ($data['status']) {
            case 'deposited':
                $data['deposit_date'] = now();
                break;

            case 'cleared':
                $data['deposit_date'] = now();
                $data['realization_date'] = now();
                break;

            case 'bounced':
                $data['bounce_date'] = now();
                break;
        }

        // Default financial fields
        $data['bank_charges'] = 0;
        $data['penalty_amount'] = 0;

        // ✅ CREATE RECORD
        $cheque = Cheque::create($data);

        // (Optional) Activity Log Hook
        // You can implement later
        /*
        activity_log([
            'module' => 'cheque',
            'module_id' => $cheque->id,
            'action' => 'created',
            'new_values' => $cheque->toArray(),
            'user_id' => Auth::id()
        ]);
        */

        return redirect()
            ->back()
            ->with('success', 'Cheque created successfully!');
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
    public function update(Request $request, Cheque $cheque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cheque $cheque)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccounts;
use App\Models\Cheque;

class DashboardController extends Controller
{
    public function index()
    {
        // Bank accounts summary
        $totalAccounts = BankAccounts::count();
        $activeAccounts = BankAccounts::where('is_active', 1)->count();
        $inactiveAccounts = $totalAccounts - $activeAccounts;

        // Cheque summary
        $totalCheques = Cheque::count();
        $pendingCheques = Cheque::where('status', 'pending')->count();
        $realizedCheques = Cheque::where('status', 'realized')->count();
        $bouncedCheques = Cheque::where('status', 'bounced')->count();

        // Latest activity logs
        $activities = ActivityLog::latest()->take(10)->get();

        return view('dashboard', compact(
            'totalAccounts',
            'activeAccounts',
            'inactiveAccounts',
            'totalCheques',
            'pendingCheques',
            'realizedCheques',
            'bouncedCheques',
            'activities'
        ));
    }
}

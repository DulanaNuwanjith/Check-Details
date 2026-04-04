<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankAccounts;
use App\Models\Cheque;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary Counts
        $totalAccounts = BankAccounts::count();
        $activeAccounts = BankAccounts::where('is_active', 1)->count();
        $inactiveAccounts = $totalAccounts - $activeAccounts;

        $totalCheques = Cheque::count();
        $pendingCheques = Cheque::where('status', 'pending')->count();
        $realizedCheques = Cheque::where('status', 'cleared')->count(); // 'cleared' is realized
        $bouncedCheques = Cheque::where('status', 'bounced')->count();

        // Financial Metrics
        $totalReceivedValue = (float) Cheque::where('cheque_type', 'received')->sum('cheque_amount');
        $totalIssuedValue = (float) Cheque::where('cheque_type', 'issued')->sum('cheque_amount');
        $netBalance = $totalReceivedValue - $totalIssuedValue;

        // User Metrics
        $userCount = User::count();

        // Monthly Trends (Last 6 Months)
        $months = [];
        $receivedTrends = [];
        $issuedTrends = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            $months[] = $monthName;

            $receivedTrends[] = (float) Cheque::where('cheque_type', 'received')
                ->whereYear('cheque_date', $date->year)
                ->whereMonth('cheque_date', $date->month)
                ->sum('cheque_amount');

            $issuedTrends[] = (float) Cheque::where('cheque_type', 'issued')
                ->whereYear('cheque_date', $date->year)
                ->whereMonth('cheque_date', $date->month)
                ->sum('cheque_amount');
        }

        // Recent Activity
        $activities = ActivityLog::with('user')->latest()->take(10)->get();

        // Bank Health (Accounts with most pending cheques)
        $bankHealth = BankAccounts::leftJoin('cheques', 'bank_accounts.id', '=', 'cheques.bank_account_id')
            ->select('bank_accounts.bank_name', 'bank_accounts.company_name', \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN cheques.status = "pending" THEN 1 ELSE 0 END) as pending_count'))
            ->groupBy('bank_accounts.id', 'bank_accounts.bank_name', 'bank_accounts.company_name')
            ->orderByDesc('pending_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalAccounts',
            'activeAccounts',
            'inactiveAccounts',
            'totalCheques',
            'pendingCheques',
            'realizedCheques',
            'bouncedCheques',
            'totalReceivedValue',
            'totalIssuedValue',
            'netBalance',
            'userCount',
            'months',
            'receivedTrends',
            'issuedTrends',
            'activities',
            'bankHealth'
        ));
    }
}

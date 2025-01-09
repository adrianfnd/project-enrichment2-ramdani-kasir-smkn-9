<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Stock;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalTransactions = Transaction::whereDate('created_at', today())->count();

        $totalStock = Stock::sum('quantity');

        $lastTransaction = Transaction::with('user')->latest()->first();

        $recentTransactions = Transaction::with('user', 'details.stock')
                                        ->latest()
                                        ->take(5)
                                        ->get();

        return view('admin.dashboard.index', compact('totalTransactions', 'totalStock', 'lastTransaction', 'recentTransactions'));
    }
}

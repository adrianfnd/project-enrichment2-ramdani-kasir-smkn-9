<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('report_date', 'desc')->get();
        return view('admin.report.index', compact('reports'));
    }

    public function generateMonthlyReport()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with('details.stock')
            ->get();

        $totalRevenue = $transactions->sum('total');

        $stockSummary = [];
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $stockId = $detail->stock_id;
                if (!isset($stockSummary[$stockId])) {
                    $stockSummary[$stockId] = [
                        'name' => $detail->stock->name,
                        'quantity' => 0,
                        'total_price' => 0
                    ];
                }
                $stockSummary[$stockId]['quantity'] += $detail->quantity;
                $stockSummary[$stockId]['total_price'] += $detail->price * $detail->quantity;
            }
        }

        $pdf = PDF::loadView('admin.report.monthly-report', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'stockSummary' => $stockSummary
        ]);

        $fileName = 'monthly-report-' . $startDate->format('Y-m') . '.pdf';
        Storage::put('public/reports/' . $fileName, $pdf->output());

        Report::create([
            'user_id' => auth()->id(),
            'report_date' => $endDate,
            'report_link' => 'reports/' . $fileName,
        ]);

        foreach ($transactions as $transaction) {
            $transaction->update([
                'is_printed' => true,
                'printed_at' => now()
            ]);
        }

        return redirect()->route('laporan.index')->with('success', 'Laporan bulanan berhasil dibuat');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
            
        if (Storage::exists('public/' . $report->report_link)) {
            Storage::delete('public/' . $report->report_link);
        }
        
        $report->delete();
        
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus');
    }
}
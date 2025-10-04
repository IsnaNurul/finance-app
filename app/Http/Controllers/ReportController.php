<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Exports\ProfitLossExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function profitLoss(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $report = $this->service->getProfitLossReport($year);

        return Inertia::render('Reports/ProfitLoss', [
            'report' => $report
        ]);
    }

    public function exportExcel(Request $request)
    {
        $year = $request->get('year', now()->year);
        $report = $this->service->getProfitLossReport($year);

        return Excel::download(new ProfitLossExport($report), "profit_loss_{$year}.xlsx");
    }

}

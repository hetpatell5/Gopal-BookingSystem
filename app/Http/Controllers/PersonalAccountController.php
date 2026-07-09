<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PersonalAccount;
use App\Models\Bus;
use App\Models\Passenger;

class PersonalAccountController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year');
        if (empty($year)) {
            $year = date('Y');
        }

        $busId = $request->input('bus_id');
        $selectedBus = null;
        if ($busId) {
            $selectedBus = Bus::find($busId);
        }

        // Fetch all buses since there are no Personal buses in the DB currently
        $buses = Bus::orderBy('name', 'asc')->get();

        // 1. Fetch Expenses (from personal_accounts)
        $expenseQuery = PersonalAccount::whereYear('slip_date', $year);
        if ($selectedBus) {
            $expenseQuery->where(function($q) use ($selectedBus) {
                $q->where('bus_number', 'like', '%' . $selectedBus->number . '%')
                  ->orWhere('bus_name', 'like', '%' . $selectedBus->name . '%');
            });
        }
        
        $monthlyExpenses = $expenseQuery
            ->selectRaw('MONTH(slip_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 2. Fetch Income and Commission (from passengers)
        $passengerQuery = Passenger::whereYear('journey_date', $year);
        if ($selectedBus) {
            $passengerQuery->where('bus_id', $selectedBus->id);
        }
        
        $monthlyRevenueData = $passengerQuery
            ->selectRaw('MONTH(journey_date) as month, SUM(total_amount) as revenue, SUM(commission_amount) as commission')
            ->groupBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        $monthlyTotals = collect();
        $yearlySummary = [
            'revenue' => 0,
            'commission' => 0,
            'expenses' => 0,
            'net_profit' => 0
        ];

        for ($m = 1; $m <= 12; $m++) {
            $revenue = isset($monthlyRevenueData[$m]) ? (float) $monthlyRevenueData[$m]['revenue'] : 0;
            $commission = isset($monthlyRevenueData[$m]) ? (float) $monthlyRevenueData[$m]['commission'] : 0;
            $expenses = $monthlyExpenses[$m] ?? 0;
            $net_profit = $revenue - $commission - $expenses;

            $yearlySummary['revenue'] += $revenue;
            $yearlySummary['commission'] += $commission;
            $yearlySummary['expenses'] += $expenses;
            $yearlySummary['net_profit'] += $net_profit;

            $monthlyTotals->push((object)[
                'year' => $year,
                'month' => $m,
                'revenue' => $revenue,
                'commission' => $commission,
                'expenses' => $expenses,
                'net_profit' => $net_profit
            ]);
        }

        $availableYears = PersonalAccount::selectRaw('YEAR(slip_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        $currentYear = (int) date('Y');
        $nextYear = $currentYear + 1;
        $prevYear = $currentYear - 1;
            
        foreach ([$year, $currentYear, $nextYear, $prevYear] as $y) {
            if (!in_array($y, $availableYears)) {
                $availableYears[] = (int) $y;
            }
        }
        rsort($availableYears);

        return view('personal-accounts.index', compact('monthlyTotals', 'yearlySummary', 'year', 'availableYears', 'buses', 'selectedBus', 'busId'));
    }

    public function create()
    {
        return view('personal-accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ref_no' => 'nullable|string',
            'slip_date' => 'nullable|date',
            'bus_name' => 'nullable|string',
            'bus_number' => 'nullable|string',
            'manager_name' => 'nullable|string',
            'grease_cost' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'toll_tax' => 'nullable|numeric',
            'diesel_liter' => 'nullable|numeric',
            'diesel_rate' => 'nullable|numeric',
            'diesel_amount' => 'nullable|numeric',
            'driver_salary' => 'nullable|numeric',
            'conductor_salary' => 'nullable|numeric',
            'parking' => 'nullable|numeric',
            'parchuran' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
        ]);

        $account = PersonalAccount::create($data);

        return redirect()->route('personal-accounts.index')->with('success', 'Personal Account Expense Saved Successfully!');
    }
    
    public function showMonth($year, $month)
    {
        $dailyTotals = PersonalAccount::whereYear('slip_date', $year)
            ->whereMonth('slip_date', $month)
            ->selectRaw('slip_date as date, SUM(total_amount) as total')
            ->groupBy('slip_date')
            ->orderBy('slip_date', 'desc')
            ->get();
            
        $monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
        
        return view('personal-accounts.month', compact('dailyTotals', 'monthName', 'year', 'month'));
    }

    public function showDate($date)
    {
        $expenses = PersonalAccount::whereDate('slip_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('personal-accounts.date', compact('expenses', 'date'));
    }

    public function filter(Request $request)
    {
        $query = PersonalAccount::query();

        if ($request->filled('start_date')) {
            $query->whereDate('slip_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('slip_date', '<=', $request->end_date);
        }
        if ($request->filled('bus_name')) {
            $query->where('bus_name', 'like', '%' . $request->bus_name . '%');
        }
        if ($request->filled('bus_number')) {
            $query->where('bus_number', 'like', '%' . $request->bus_number . '%');
        }
        if ($request->filled('manager_name')) {
            $query->where('manager_name', 'like', '%' . $request->manager_name . '%');
        }

        $expenses = $query->orderBy('slip_date', 'desc')->orderBy('created_at', 'desc')->get();

        return view('personal-accounts.filter', compact('expenses'));
    }

    public function edit($id)
    {
        $expense = PersonalAccount::findOrFail($id);
        
        $monthlyTotals = PersonalAccount::selectRaw('YEAR(slip_date) as year, MONTH(slip_date) as month, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
            
        return view('personal-accounts.edit', compact('expense', 'monthlyTotals'));
    }

    public function update(Request $request, $id)
    {
        $expense = PersonalAccount::findOrFail($id);
        
        $data = $request->validate([
            'slip_date' => 'nullable|date',
            'bus_name' => 'nullable|string',
            'bus_number' => 'nullable|string',
            'manager_name' => 'nullable|string',
            'grease_cost' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'toll_tax' => 'nullable|numeric',
            'diesel_liter' => 'nullable|numeric',
            'diesel_rate' => 'nullable|numeric',
            'diesel_amount' => 'nullable|numeric',
            'driver_salary' => 'nullable|numeric',
            'conductor_salary' => 'nullable|numeric',
            'parking' => 'nullable|numeric',
            'parchuran' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
        ]);

        $expense->update($data);

        return redirect()->route('personal-accounts.date', ['date' => $expense->slip_date])->with('success', 'Personal Account Expense Updated Successfully!');
    }

    public function destroy($id)
    {
        $expense = PersonalAccount::findOrFail($id);
        $date = $expense->slip_date;
        $expense->delete();

        return redirect()->route('personal-accounts.date', ['date' => $date])->with('success', 'Personal Account Expense Deleted Successfully!');
    }
}

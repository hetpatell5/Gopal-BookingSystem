<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::latest()->paginate(15);
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $contract = new Contract();
        return view('contracts.form', compact('contract'));
    }

    public function store(Request $request)
    {
        $contract = Contract::create($request->all());
        return response()->json(['success' => true, 'id' => $contract->id]);
    }

    public function edit(Contract $contract)
    {
        return view('contracts.form', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {
        $contract->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract deleted successfully');
    }
}

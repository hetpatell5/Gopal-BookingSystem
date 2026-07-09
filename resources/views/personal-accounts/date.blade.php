@extends('layouts.app')
@section('title', 'Expenses for ' . \Carbon\Carbon::parse($date)->format('d M, Y'))
@section('header', 'Expenses for ' . \Carbon\Carbon::parse($date)->format('d M, Y'))

@section('content')

<div class="flex flex-col gap-6 max-w-6xl mx-auto w-full">

    <div class="flex items-center justify-between w-full bg-white p-4 rounded-md shadow-sm border border-gray-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('personal-accounts.month', ['year' => \Carbon\Carbon::parse($date)->year, 'month' => \Carbon\Carbon::parse($date)->month]) }}" class="text-gray-500 hover:text-[#f0b44b] transition-colors"><i class="fa-solid fa-arrow-left"></i> Back to Month</a>
        </div>
        <h2 class="text-[15px] font-bold text-[#1c2238]">{{ \Carbon\Carbon::parse($date)->format('d M, Y (l)') }}</h2>
    </div>

    @if(isset($expenses) && $expenses->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($expenses as $index => $expense)
                <div class="bg-white shadow-md rounded-md border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-[15px] font-black text-[#1c2238]">SLIP #{{ $index + 1 }}</h3>
                            <div class="mt-1 flex items-center gap-3 text-[12px] text-gray-500 font-medium">
                                @if($expense->bus_name)<span><i class="fa-solid fa-bus mr-1"></i> {{ $expense->bus_name }}</span>@endif
                                @if($expense->bus_number)<span><i class="fa-solid fa-hashtag mr-1"></i> {{ $expense->bus_number }}</span>@endif
                                @if($expense->manager_name)<span><i class="fa-solid fa-user-tie mr-1"></i> {{ $expense->manager_name }}</span>@endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total</span>
                            <div class="text-[16px] font-black text-[#f0b44b]">₹ {{ number_format($expense->total_amount, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="p-0">
                        <table class="w-full text-left text-[13px]">
                            <tbody class="divide-y divide-gray-50">
                                @if($expense->grease_cost > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Grease Cost</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->grease_cost, 2) }}</td></tr>
                                @endif
                                
                                @if($expense->tax > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Tax</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->tax, 2) }}</td></tr>
                                @endif

                                @if($expense->toll_tax > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Toll Tax</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->toll_tax, 2) }}</td></tr>
                                @endif

                                @if($expense->diesel_amount > 0)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2.5 px-5 font-semibold text-gray-600">
                                        Diesel <span class="text-[11px] text-gray-400 font-normal ml-2">({{ $expense->diesel_liter }} Ltr × ₹{{ $expense->diesel_rate }})</span>
                                    </td>
                                    <td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->diesel_amount, 2) }}</td>
                                </tr>
                                @endif

                                @if($expense->driver_salary > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Driver Salary</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->driver_salary, 2) }}</td></tr>
                                @endif

                                @if($expense->conductor_salary > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Conductor Salary</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->conductor_salary, 2) }}</td></tr>
                                @endif

                                @if($expense->parking > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Parking</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->parking, 2) }}</td></tr>
                                @endif

                                @if($expense->parchuran > 0)
                                <tr class="hover:bg-gray-50"><td class="py-2.5 px-5 font-semibold text-gray-600">Parchuran</td><td class="py-2.5 px-5 text-right font-bold text-[#1c2238]">₹ {{ number_format($expense->parchuran, 2) }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                        
                        @if($expense->total_amount == 0)
                            <div class="py-4 text-center text-[12px] text-gray-400">Empty slip.</div>
                        @endif
                    </div>
                    
                    {{-- Slip Actions --}}
                    <div class="px-5 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-2 no-print">
                        <button type="button" onclick="printSlip(this)" class="text-[12px] font-bold text-gray-600 hover:text-[#1c2238] transition-colors px-3 py-1.5 border border-gray-200 rounded bg-white shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-print"></i> Print
                        </button>
                        <a href="{{ route('personal-accounts.edit', $expense->id) }}" class="text-[12px] font-bold text-gray-600 hover:text-[#f0b44b] transition-colors px-3 py-1.5 border border-gray-200 rounded bg-white shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form action="{{ route('personal-accounts.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slip?');" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[12px] font-bold text-red-500 hover:text-white hover:bg-red-500 transition-colors px-3 py-1.5 border border-red-200 rounded bg-white shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white p-8 text-center text-gray-400 text-[14px] shadow-sm rounded-md border border-gray-200">
            No expense slips found for this date.
        </div>
    @endif

</div>

<style>
    @media print {
        body > *:not(#print-wrapper) { display: none !important; }
        .no-print { display: none !important; }
        .shadow-md { box-shadow: none !important; border: 1px solid #ddd; }
        header, nav, aside { display: none !important; }
        body { background-color: white; }
    }
</style>

<script>
function printSlip(button) {
    const slipCard = button.closest('.bg-white.shadow-md');
    
    // Store original body content
    const originalContents = document.body.innerHTML;
    
    // Create a temporary container for printing
    const printWrapper = document.createElement('div');
    printWrapper.id = 'print-wrapper';
    printWrapper.style.width = '100%';
    printWrapper.style.padding = '20px';
    
    // Clone the slip card
    const clone = slipCard.cloneNode(true);
    // Remove action buttons from clone
    const actions = clone.querySelector('.no-print');
    if(actions) actions.remove();
    
    printWrapper.appendChild(clone);
    
    document.body.innerHTML = '';
    document.body.appendChild(printWrapper);
    
    window.print();
    
    // Restore
    document.body.innerHTML = originalContents;
    window.location.reload(); // Reload to restore event listeners
}
</script>
@endsection

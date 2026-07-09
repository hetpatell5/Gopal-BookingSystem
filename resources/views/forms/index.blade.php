@extends('layouts.app')
@section('title', 'Forms')
@section('header', 'Forms')

@section('content')
<div class="flex flex-col gap-6">

    {{-- ── Top bar ── --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-[13px] text-gray-500 mt-0.5">Create custom forms and collect responses.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('contracts.index') }}"
               class="inline-flex items-center gap-2 text-white font-bold text-[13px] px-5 py-2.5 rounded-none shadow-sm transition-colors"
               style="background-color: #c0001a;"
               onmouseover="this.style.backgroundColor='#a00016'"
               onmouseout="this.style.backgroundColor='#c0001a'">
                <i class="fa-solid fa-file-contract"></i> Bus Contracts
            </a>
            <a href="{{ route('forms.create') }}"
               class="inline-flex items-center gap-2 bg-[#f0b44b] text-[#1c2238] font-bold text-[13px] px-5 py-2.5 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm">
                <i class="fa-solid fa-plus"></i> Create New Form
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-[#e8f5ed] border border-[#34a853]/20 text-[#34a853] text-[13px] font-semibold rounded-none flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if($forms->isEmpty())
        {{-- Empty state --}}
        <div class="bg-white rounded-none shadow-sm p-16 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-[#f0b44b]/10 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-clipboard-list text-2xl text-[#f0b44b]"></i>
            </div>
            <h3 class="text-[17px] font-bold text-[#1c2238] mb-2">No forms yet</h3>
            <p class="text-[13px] text-gray-500 mb-6 max-w-xs">Create your first form to start collecting responses from passengers or agents.</p>
            <div class="mt-2">
                <a href="{{ route('forms.create') }}"
                   class="inline-flex items-center gap-2 bg-[#f0b44b] text-[#1c2238] font-bold text-[13px] px-6 py-2.5 rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm">
                    <i class="fa-solid fa-plus"></i> Create Form
                </a>
            </div>
        </div>
    @else

    {{-- ── Form cards row ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($forms as $form)
            <div class="bg-white rounded-none shadow-sm border-t-4 {{ $form->is_active ? 'border-[#f0b44b]' : 'border-gray-300' }} p-5 flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[15px] font-bold text-[#1c2238] truncate">{{ $form->name }}</h3>
                        @if($form->description)
                            <p class="text-[12px] text-gray-500 mt-0.5 line-clamp-2">{{ $form->description }}</p>
                        @endif
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $form->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $form->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="flex items-center gap-4 text-[12px] text-gray-500">
                    <span><i class="fa-solid fa-layer-group mr-1 text-[#f0b44b]"></i>{{ count($form->fields) }} fields</span>
                    <span><i class="fa-solid fa-inbox mr-1 text-[#f0b44b]"></i>{{ $form->responses_count }} responses</span>
                </div>

                <div class="flex items-center gap-1.5 mt-auto pt-3 border-t border-gray-100">
                    {{-- View responses --}}
                    <a href="{{ route('forms.index', ['form_id' => $form->id]) }}"
                       class="flex-1 text-center text-[12px] font-bold py-1.5 border {{ $selectedFormId == $form->id ? 'bg-[#f0b44b] text-[#1c2238] border-[#f0b44b]' : 'bg-gray-50 border-gray-200 text-gray-600 hover:border-[#f0b44b] hover:text-[#f0b44b]' }} transition-colors rounded-none">
                        Responses
                    </a>
                    {{-- Public link --}}
                    <a href="{{ route('forms.public.show', $form) }}" target="_blank"
                       class="px-2.5 py-1.5 text-[14px] text-gray-400 hover:text-[#f0b44b] transition-colors" title="Open public form">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                    {{-- Edit --}}
                    <a href="{{ route('forms.edit', $form) }}"
                       class="px-2.5 py-1.5 text-[14px] text-gray-400 hover:text-blue-600 transition-colors" title="Edit form">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('forms.destroy', $form) }}" onsubmit="return confirm('Delete this form and all its responses?')" class="flex">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-2.5 py-1.5 text-[14px] text-gray-400 hover:text-red-500 transition-colors" title="Delete form">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Responses section ── --}}
    <div class="bg-white rounded-none shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-[16px] font-bold text-[#1c2238]">Responses</h2>
                @if($selectedForm)
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[12px] font-bold rounded-full">
                        {{ $responses->count() }}
                    </span>
                @endif
            </div>

            @if($forms->count() > 1)
            <form method="GET" action="{{ route('forms.index') }}" class="flex items-center gap-2">
                <label class="text-[12px] font-semibold text-gray-500">View form:</label>
                <select name="form_id" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-none px-3 py-1.5 text-[13px] focus:outline-none focus:ring-2 focus:ring-[#f0b44b]">
                    @foreach($forms as $form)
                        <option value="{{ $form->id }}" {{ $selectedFormId == $form->id ? 'selected' : '' }}>
                            {{ $form->name }} ({{ $form->responses_count }})
                        </option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>

        @if(!$selectedForm || $responses->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 min-h-[300px] text-gray-400">
                <i class="fa-solid fa-inbox text-4xl mb-4 text-gray-300"></i>
                <p class="text-[14px]">{{ $selectedForm ? 'No responses yet for this form.' : 'Select a form to view responses.' }}</p>
            </div>
        @else
            @php
                // Collect all non-section-header field labels for columns
                $columns = collect($selectedForm->fields)
                    ->filter(fn($f) => ($f['type'] ?? '') !== 'section_header')
                    ->pluck('label')
                    ->values();
            @endphp
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 font-bold text-[11px] uppercase tracking-widest text-gray-400">#</th>
                            @foreach($columns as $col)
                                <th class="text-left px-4 py-3 font-bold text-[11px] uppercase tracking-widest text-gray-400">
                                    {{ $col }}
                                </th>
                            @endforeach
                            <th class="text-left px-4 py-3 font-bold text-[11px] uppercase tracking-widest text-gray-400">Submitted</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($responses as $i => $response)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3.5 text-gray-400 font-mono text-[11px]">{{ $i + 1 }}</td>
                                @foreach($columns as $col)
                                    <td class="px-4 py-3.5 text-gray-700">
                                        @php $val = $response->data[$col] ?? '—'; @endphp
                                        @if(is_array($val))
                                            {{ implode(', ', $val) }}
                                        @else
                                            {{ $val ?: '—' }}
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3.5 text-gray-400 whitespace-nowrap">
                                    {{ $response->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <form method="POST" action="{{ route('forms.response.destroy', $response) }}"
                                          onsubmit="return confirm('Delete this response?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash text-[12px]"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @endif
</div>
@endsection

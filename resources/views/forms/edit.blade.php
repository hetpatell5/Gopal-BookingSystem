@extends('layouts.app')
@section('title', 'Edit Form: ' . $form->name)
@section('header', 'Edit Form')

@php $isEdit = true; @endphp

@section('content')
{{-- Reuse the create builder but with $form injected --}}
<div class="flex gap-6" style="min-height:calc(100vh - 120px)">

    {{-- ── LEFT: Field Palette + Builder ── --}}
    <div class="flex flex-col gap-4" style="flex:1 1 55%; min-width:0">

        {{-- Form meta --}}
        <div class="bg-white rounded-none shadow-sm p-6 border-t-4 border-[#f0b44b]">
            <input type="text" id="formName" placeholder="Form Title *"
                   value="{{ $form->name }}"
                   class="w-full text-[20px] font-bold text-[#1c2238] border-none outline-none placeholder-gray-300 mb-2 bg-transparent">
            <div class="h-px bg-gray-100 mb-3"></div>
            <input type="text" id="formDescription" placeholder="Form description (optional)"
                   value="{{ $form->description ?? '' }}"
                   class="w-full text-[14px] text-gray-500 border-none outline-none placeholder-gray-300 bg-transparent">
        </div>

        {{-- Field type palette --}}
        <div class="bg-white rounded-none shadow-sm p-4">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Add Field</p>
            <div class="flex flex-wrap gap-2">
                @php
                $fieldTypes = [
                    ['type'=>'short_text',     'label'=>'Short Answer',    'icon'=>'fa-font',             'color'=>'#3b82f6'],
                    ['type'=>'long_text',       'label'=>'Paragraph',       'icon'=>'fa-align-left',       'color'=>'#6366f1'],
                    ['type'=>'email',           'label'=>'Email',           'icon'=>'fa-envelope',         'color'=>'#06b6d4'],
                    ['type'=>'number',          'label'=>'Number',          'icon'=>'fa-hashtag',          'color'=>'#8b5cf6'],
                    ['type'=>'date',            'label'=>'Date',            'icon'=>'fa-calendar',         'color'=>'#f43f5e'],
                    ['type'=>'dropdown',        'label'=>'Dropdown',        'icon'=>'fa-list',             'color'=>'#f59e0b'],
                    ['type'=>'multiple_choice', 'label'=>'Multiple Choice', 'icon'=>'fa-circle-dot',       'color'=>'#22c55e'],
                    ['type'=>'checkboxes',      'label'=>'Checkboxes',      'icon'=>'fa-square-check',     'color'=>'#14b8a6'],
                    ['type'=>'section_header',  'label'=>'Section Header',  'icon'=>'fa-heading',          'color'=>'#94a3b8'],
                ];
                @endphp
                @foreach($fieldTypes as $ft)
                    <button type="button" onclick="addField('{{ $ft['type'] }}')"
                            class="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 text-[12px] font-semibold text-gray-600 hover:border-[#f0b44b] hover:text-[#1c2238] transition-colors rounded-none">
                        <i class="fa-solid {{ $ft['icon'] }}" style="color:{{ $ft['color'] }}; font-size:11px;"></i>
                        {{ $ft['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Field list --}}
        <div id="fieldList" class="flex flex-col gap-3"></div>

        @if($errors->any())
            <div class="p-3 bg-red-50 text-red-600 text-[13px] border border-red-200 rounded-none">
                @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
            </div>
        @endif

        {{-- Save button --}}
        <div class="flex gap-3">
            <button type="button" onclick="saveForm()"
                    class="flex-1 bg-[#f0b44b] text-[#1c2238] font-bold py-3 text-[14px] rounded-none hover:bg-[#e0a43b] transition-colors shadow-sm">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
            </button>
            <a href="{{ route('forms.index') }}"
               class="px-6 py-3 border border-gray-200 text-gray-600 font-semibold text-[14px] rounded-none hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </div>

    {{-- ── RIGHT: Live Preview ── --}}
    <div style="flex:1 1 40%; min-width:280px; max-width:440px; position:sticky; top:24px; align-self:start">
        <div class="bg-[#f0ebff] rounded-none p-5">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 text-center">Live Preview</p>
            <div id="previewContainer" class="max-w-full font-sans"></div>
        </div>
    </div>
</div>

<form id="submitForm" method="POST" action="{{ route('forms.update', $form) }}" style="display:none">
    @csrf @method('PUT')
    <input type="hidden" name="name"        id="hiddenName">
    <input type="hidden" name="description" id="hiddenDesc">
    <input type="hidden" name="fields"      id="hiddenFields">
</form>

@include('forms._builder_script', ['initialFields' => $form->fields])
@endsection

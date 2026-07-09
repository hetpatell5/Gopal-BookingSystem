@extends('layouts.app')

@section('title', 'WhatsApp Broadcast')
@section('header', 'WhatsApp Broadcast')

@section('content')
<div class="max-w-6xl mx-auto pb-12">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-[#e8f5ed] border-l-4 border-[#34a853] text-[#34a853] rounded-none text-[13px] font-semibold shadow-sm flex items-center">
            <i class="fa-solid fa-circle-check mr-3 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-[#fee2e2] border-l-4 border-[#ef4444] text-[#ef4444] rounded-none text-[13px] font-semibold shadow-sm">
            <div class="flex items-center mb-2">
                <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="list-disc pl-9">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-[#fee2e2] border-l-4 border-[#ef4444] text-[#ef4444] rounded-none text-[13px] font-semibold shadow-sm flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Composer -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-[14px] font-bold text-[#1c2238] flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-[#25d366]"></i> Compose Message
                    </h2>
                </div>
                
                <form id="broadcastForm" action="{{ route('broadcast.send') }}" method="POST" class="p-5">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-2">Message Content</label>
                        <textarea name="message" id="messageBox" rows="6" required
                                  class="w-full p-3 border border-gray-200 rounded-none focus:outline-none focus:ring-2 focus:ring-[#f0b44b] text-[13px] bg-white transition-colors resize-y"
                                  placeholder="Type your broadcast message here..."></textarea>
                    </div>

                    <div class="mb-5 p-3 bg-gray-50 border border-gray-100 text-[11px] text-gray-500">
                        <p class="font-bold text-[#1c2238] mb-1">API Notice:</p>
                        <p>To use automatic API sending, ensure your WhatsApp Meta API keys are configured in Settings. Free-form text requires an active 24-hour chat window.</p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" id="apiBtn" class="w-full bg-[#1c2238] hover:bg-[#2d3a5a] text-white font-bold py-3 text-[13px] rounded-none shadow-sm transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Send via Meta API
                        </button>
                        
                        <button type="button" id="generateLinksBtn" class="w-full bg-white border border-[#25d366] text-[#25d366] hover:bg-[#25d366] hover:text-white font-bold py-3 text-[13px] rounded-none shadow-sm transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-link"></i> Generate Click-to-Send Links
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Passengers List -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm border border-gray-200 h-full">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-[14px] font-bold text-[#1c2238]">Select Passengers</h2>
                    <div class="flex items-center gap-3">
                        <span class="text-[12px] text-gray-500" id="selectedCount">0 selected</span>
                        <button type="button" id="selectAllBtn" class="text-[11px] font-bold text-[#f0b44b] hover:text-[#e0a43b] uppercase tracking-wider">Select All</button>
                    </div>
                </div>
                
                <div class="p-0 overflow-y-auto max-h-[600px]">
                    @if($passengers->isEmpty())
                        <div class="p-8 text-center text-gray-500 text-[13px]">
                            No passengers with mobile numbers found.
                        </div>
                    @else
                        <table class="w-full text-left text-[13px]">
                            <thead class="bg-white sticky top-0 shadow-sm">
                                <tr>
                                    <th class="px-5 py-3 w-12 text-center">
                                        <input type="checkbox" id="masterCheckbox" class="w-4 h-4 text-[#f0b44b] focus:ring-[#f0b44b] rounded-none border-gray-300">
                                    </th>
                                    <th class="px-5 py-3 font-bold text-gray-600 uppercase tracking-widest text-[11px]">Passenger Name</th>
                                    <th class="px-5 py-3 font-bold text-gray-600 uppercase tracking-widest text-[11px]">Mobile Number</th>
                                    <th class="px-5 py-3 font-bold text-gray-600 uppercase tracking-widest text-[11px] text-right">Manual Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="passengerList">
                                @foreach($passengers as $p)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3 text-center">
                                            <input type="checkbox" name="passenger_ids[]" value="{{ $p->id }}" form="broadcastForm" class="passenger-checkbox w-4 h-4 text-[#f0b44b] focus:ring-[#f0b44b] rounded-none border-gray-300">
                                        </td>
                                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $p->traveler_name ?: 'Unnamed Passenger' }}</td>
                                        <td class="px-5 py-3 text-gray-600 mobile-number">{{ $p->passenger_mobile }}</td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="#" class="wa-link hidden text-[11px] bg-[#25d366]/10 text-[#25d366] px-3 py-1.5 rounded-none font-bold hover:bg-[#25d366] hover:text-white transition-colors" target="_blank">
                                                <i class="fa-brands fa-whatsapp mr-1"></i> Send
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const masterCheckbox = document.getElementById('masterCheckbox');
        const checkboxes = document.querySelectorAll('.passenger-checkbox');
        const selectedCountLabel = document.getElementById('selectedCount');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const generateLinksBtn = document.getElementById('generateLinksBtn');
        const messageBox = document.getElementById('messageBox');
        
        function updateCount() {
            const count = document.querySelectorAll('.passenger-checkbox:checked').length;
            selectedCountLabel.textContent = `${count} selected`;
            masterCheckbox.checked = count === checkboxes.length && count > 0;
        }

        if(masterCheckbox) {
            masterCheckbox.addEventListener('change', (e) => {
                checkboxes.forEach(cb => cb.checked = e.target.checked);
                updateCount();
            });
        }

        if(selectAllBtn) {
            selectAllBtn.addEventListener('click', () => {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(cb => cb.checked = !allChecked);
                updateCount();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateCount);
        });

        // Generate WA.me links for Option A
        if(generateLinksBtn) {
            generateLinksBtn.addEventListener('click', () => {
                const msg = encodeURIComponent(messageBox.value);
                if(!msg) {
                    alert('Please type a message first.');
                    return;
                }

                checkboxes.forEach(cb => {
                    const row = cb.closest('tr');
                    const linkBtn = row.querySelector('.wa-link');
                    if (cb.checked) {
                        let mobile = row.querySelector('.mobile-number').textContent.replace(/\D/g, '');
                        if(mobile.length === 10) mobile = '91' + mobile; // Default to India
                        
                        linkBtn.href = `https://wa.me/${mobile}?text=${msg}`;
                        linkBtn.classList.remove('hidden');
                        linkBtn.classList.add('inline-block');
                    } else {
                        linkBtn.classList.add('hidden');
                        linkBtn.classList.remove('inline-block');
                    }
                });
            });
        }
    });
</script>
@endsection

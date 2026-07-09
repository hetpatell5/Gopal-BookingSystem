import sys

with open('resources/views/accounting/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

start_idx = content.find('{{-- ── PERSONAL TABLE ── --}}')
if start_idx != -1:
    new_content = content[:start_idx] + '''{{-- ── PERSONAL BUSES ── --}}
  @if($personalBuses->count() && $activeType !== 'Commission')
  <div>
    <div class="ld-sec-hdr"><i class="fa-solid fa-bus text-[#f0b44b] mr-2"></i> Personal Buses</div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 bg-gray-50/50">
      @foreach($personalBuses as $bus)
        <a href="{{ route('accounting.show', $bus->id) }}?{{ request()->getQueryString() }}" class="block bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:-translate-y-1 hover:border-[#f0b44b] transition-all group">
          <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-[#f0b44b] group-hover:bg-[#f0b44b] group-hover:text-white transition-colors shadow-sm">
                  <i class="fa-solid fa-bus text-lg"></i>
              </div>
              <div>
                  <h3 class="font-bold text-[#1c2238] text-[16px] leading-tight mb-1">{{ $bus->name }}</h3>
                  <p class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded inline-block">{{ $bus->plate_number }}</p>
              </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
              <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">View Ledger</span>
              <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#f0b44b] transition-colors"></i>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- ── COMMISSION BUSES ── --}}
  @if($commissionBuses->count() && $activeType !== 'Personal')
  <div>
    <div class="ld-sec-hdr"><i class="fa-solid fa-handshake text-[#6366f1] mr-2"></i> Commission Buses</div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 bg-gray-50/50">
      @foreach($commissionBuses as $bus)
        <a href="{{ route('accounting.show', $bus->id) }}?{{ request()->getQueryString() }}" class="block bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg hover:-translate-y-1 hover:border-[#6366f1] transition-all group">
          <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-[#6366f1] group-hover:bg-[#6366f1] group-hover:text-white transition-colors shadow-sm">
                  <i class="fa-solid fa-handshake text-lg"></i>
              </div>
              <div>
                  <h3 class="font-bold text-[#1c2238] text-[16px] leading-tight mb-1">{{ $bus->name }}</h3>
                  <p class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded inline-block">{{ $bus->plate_number }}</p>
              </div>
          </div>
          <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
              <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">View Ledger</span>
              <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-[#6366f1] transition-colors"></i>
          </div>
        </a>
      @endforeach
    </div>
  </div>
  @endif

</div>
@endsection
'''
    with open('resources/views/accounting/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print('Done')
else:
    print('Not found')

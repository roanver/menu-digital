@if($hasHours)
@php
    // Status text
    if ($isOpen) {
        $statusText = 'Abierto ahora' . ($closesAt ? ' · cierra a las ' . $closesAt : '');
    } else {
        $statusText = 'Cerrado' . ($nextOpening ? ' · abre ' . $nextOpening : '');
    }

    // Colors based on dark prop
    $panelBg     = $dark ? 'rgba(0,0,0,.55)'  : 'rgba(255,255,255,.96)';
    $panelBorder = $dark ? 'rgba(255,255,255,.1)' : 'rgba(0,0,0,.08)';
    $panelText   = $dark ? '#E5E7EB'           : '#111827';
    $panelMuted  = $dark ? '#9CA3AF'           : '#6B7280';
    $panelToday  = $dark ? '#fff'              : '#111827';
    $panelClosedColor = $dark ? '#6B7280'      : '#9CA3AF';
@endphp

<div x-data="{ open: false }" style="display:inline-block;">
    {{-- Status line --}}
    <button @click="open = !open"
            style="display:inline-flex;align-items:center;gap:6px;background:none;border:none;padding:0;cursor:pointer;font-size:12px;font-weight:600;letter-spacing:.02em;color:inherit;opacity:.85;line-height:1.4;">
        <span style="width:7px;height:7px;border-radius:50%;flex:0 0 auto;background:{{ $isOpen ? '#22C55E' : '#9CA3AF' }};{{ $isOpen ? 'box-shadow:0 0 0 2px rgba(34,197,94,.25)' : '' }}"></span>
        <span>{{ $statusText }}</span>
        <svg x-show="!open" style="width:12px;height:12px;flex:0 0 auto;opacity:.6;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        <svg x-show="open"  style="width:12px;height:12px;flex:0 0 auto;opacity:.6;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>

    {{-- Weekly schedule panel --}}
    <div x-show="open" x-transition
         style="position:absolute;z-index:50;margin-top:6px;min-width:210px;border-radius:12px;background:{{ $panelBg }};border:1px solid {{ $panelBorder }};backdrop-filter:blur(12px);padding:10px 14px;box-shadow:0 8px 24px rgba(0,0,0,.15);">
        @foreach($weekSchedule as $day)
        <div style="display:flex;justify-content:space-between;align-items:baseline;padding:4px 0;{{ !$loop->last ? 'border-bottom:1px solid '.$panelBorder.';' : '' }}">
            <span style="font-size:12px;font-weight:{{ $day['is_today'] ? '700' : '500' }};color:{{ $day['is_today'] ? $panelToday : $panelMuted }};min-width:80px;">
                {{ $day['day'] }}{{ $day['is_today'] ? ' ·' : '' }}
            </span>
            <span style="font-size:12px;font-weight:{{ $day['is_today'] ? '600' : '400' }};color:{{ $day['is_closed'] ? $panelClosedColor : ($day['is_today'] ? $panelToday : $panelText) }};text-align:right;">
                @if($day['is_closed'])
                    Cerrado
                @elseif($day['no_record'])
                    —
                @elseif(empty($day['tramos']))
                    —
                @else
                    @foreach($day['tramos'] as $ti => $tramo)
                        {{ $tramo[0] }}–{{ $tramo[1] }}@if(!$loop->last) &nbsp;·&nbsp; @endif
                    @endforeach
                @endif
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

@extends('layouts.website')

@section('title', 'Perfect XI — FindEleven Island Rankings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/perfect-eleven.css') }}">
@endpush

@section('content')

<!-- ── PAGE HEADER ── -->
<div class="p11-page-hero">
    <div class="p11-page-hero-bg"></div>
    <div class="p11-page-hero-content">
        <div class="p11-eyebrow">{{ date('Y') }} Season — Island Best XI</div>
        <h1 class="p11-page-title">Perfect <span>XI</span></h1>
        <p class="p11-page-desc">
            The finest eleven cricketers selected from across the island, based on career performance and ranking points.
        </p>
    </div>
</div>

<!-- ── AGE GROUP TABS ── -->
<div class="p11-tabs-wrap">
    <div class="p11-tabs">
        @foreach($ageGroups as $age)
            <button class="p11-tab {{ $age === $activeAge ? 'active' : '' }}" onclick="switchAge('{{ $age }}', this)">
                {{ $age }}
            </button>
        @endforeach
    </div>
</div>

<!-- ── PERFECT XI PANELS ── -->
@foreach($ageGroups as $age)
    <div class="p11-age-panel {{ $age === $activeAge ? '' : 'hidden' }}" id="panel-{{ $age }}">

        <div class="section-divider" style="margin-top:32px;">
            <span class="divider-text">Perfect XI — {{ $age }}</span>
            <div class="divider-line"></div>
            <span class="divider-num">11 Players</span>
        </div>

        {{-- ── SQUAD LIST ── --}}
        @php
            $allPlayers = collect();
            foreach ($perfectXI[$age] as $slot) {
                foreach ($slot['players'] as $p) { $allPlayers->push($p); }
            }
        @endphp

        <div class="p11-squad-wrap">
            <div class="p11-squad-card">
                <div class="p11-squad-title">
                    <span class="p11-squad-badge">🏏</span>
                    <span>{{ $age }} Perfect XI Squad</span>
                </div>

                @if($allPlayers->isEmpty())
                    <div class="p11-squad-empty">🏅 No qualifying players yet</div>
                @else
                    <div class="p11-squad-list">
                        @foreach($allPlayers as $idx => $player)
                            @php $s = $player->stats; @endphp
                            <div class="p11-squad-row">
                                <div class="p11-squad-num">{{ $idx + 1 }}</div>
                                <div class="p11-squad-avatar">
                                    <span>{{ strtoupper(substr($player->full_name, 0, 1)) }}</span>
                                </div>
                                <div class="p11-squad-info">
                                    <div class="p11-squad-name">{{ $player->full_name }}</div>
                                    <div class="p11-squad-meta">{{ $player->school->school_name ?? '—' }}</div>
                                </div>
                                <div class="p11-squad-role">{{ $player->player_category }}</div>
                                <div class="p11-squad-pts">
                                    <span class="p11-squad-pts-val">{{ number_format($s->ranking_points ?? 0) }}</span>
                                    <span class="p11-squad-pts-lbl">pts</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p11-squad-footer">{{ $allPlayers->count() }} of 11 positions filled</div>
                @endif
            </div>
        </div>

    </div>
@endforeach

@endsection

@push('scripts')
<script>
function switchAge(age, btn) {
    // Toggle tabs
    document.querySelectorAll('.p11-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    // Toggle panels
    document.querySelectorAll('.p11-age-panel').forEach(p => p.classList.add('hidden'));
    const panel = document.getElementById('panel-' + age);
    if (panel) panel.classList.remove('hidden');
}
</script>
@endpush

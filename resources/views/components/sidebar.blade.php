@props(['title' => 'Dashboard'])

<!-- FindEleven Dashboard Layout -->
<div x-data="{ sidebarOpen: true, mobileOpen: false }" class="dashboard-layout">
    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 lg:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'sidebar-open' : 'sidebar-closed'"
           x-show="mobileOpen || window.innerWidth >= 1024"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="sidebar">

        <!-- Brand -->
        <div class="sidebar-logo">
            <svg class="sidebar-logo-img" viewBox="0 0 38 38" fill="none">
                <circle cx="19" cy="19" r="17" stroke="#C8973A" stroke-width="1.5"/>
                <circle cx="19" cy="19" r="13" stroke="rgba(200,151,58,0.3)" stroke-width="0.5"/>
                <path d="M12 23 Q10 19 13 16 Q15 13 17 14 Q19 12 21 13 Q24 12 26 15 Q29 19 27 23 Q25 27 19 28 Q13 27 12 23Z" fill="rgba(200,151,58,0.12)" stroke="#C8973A" stroke-width="0.8"/>
                <path d="M16 17 Q18 15 20 16 Q22 17 21 19 Q20 21 18 21 Q16 21 16 19Z" fill="#C8973A" opacity="0.55"/>
                <circle cx="13" cy="12" r="1" fill="#C8973A" opacity="0.7"/>
                <circle cx="19" cy="10" r="1" fill="#C8973A" opacity="0.7"/>
                <circle cx="25" cy="12" r="1" fill="#C8973A" opacity="0.7"/>
            </svg>
            <div x-show="sidebarOpen" class="sidebar-logo-text">
                <span class="sb-brand-top">FindEleven</span>
                <span class="sb-brand-sub">
                    @if(Auth::user()->role === 'ADMIN') Admin Portal
                    @elseif(Auth::user()->role === 'SCHOOL') School Portal
                    @else Player Portal
                    @endif
                </span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="sidebar-nav">
            <div class="sb-section-label" x-show="sidebarOpen">Main Menu</div>

            {{-- Admin Navigation --}}
            @if(Auth::user()->role === 'ADMIN')
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="currentColor" style="{{ request()->routeIs('admin.dashboard') ? 'color:#C8973A' : '' }}">
                        <path d="M1 6l7-5 7 5v9H10V9H6v6H1V6z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.schools.pending') }}"
                   class="sidebar-link {{ request()->routeIs('admin.schools.pending') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="8" cy="8" r="6.5"/>
                        <path d="M8 4v4l3 2"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Pending Schools</span>
                </a>

                <a href="{{ route('admin.schools.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.schools.index') || request()->routeIs('admin.schools.show') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="3" width="14" height="11" rx="0.5"/>
                        <path d="M1 7h14"/>
                        <path d="M5 1v4M11 1v4"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">All Schools</span>
                </a>

                <a href="{{ route('admin.players.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.players.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="8" cy="5" r="3"/>
                        <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">All Players</span>
                </a>

                <a href="{{ route('admin.player-categories.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.player-categories.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M2 4h12M2 8h8M2 12h10"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Player Categories</span>
                </a>

                <div class="sb-section-label" x-show="sidebarOpen" style="margin-top:8px;">Competition</div>

                <a href="{{ route('admin.tournaments.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.tournaments.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Tournaments</span>
                </a>
            @endif

            {{-- School Navigation --}}
            @if(Auth::user()->role === 'SCHOOL')
                <a href="{{ route('school.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="currentColor" style="{{ request()->routeIs('school.dashboard') ? 'color:#C8973A' : '' }}">
                        <path d="M1 6l7-5 7 5v9H10V9H6v6H1V6z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('school.players.index') }}"
                   class="sidebar-link {{ request()->routeIs('school.players.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="8" cy="5" r="3"/>
                        <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Players</span>
                </a>

                <a href="{{ route('school.matches.index') }}"
                   class="sidebar-link {{ request()->routeIs('school.matches.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="3" width="14" height="11" rx="0.5"/>
                        <path d="M1 7h14"/>
                        <path d="M5 1v4M11 1v4"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Matches</span>
                </a>

                <a href="{{ route('school.profile.index') }}"
                   class="sidebar-link {{ request()->routeIs('school.profile.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="1" width="14" height="14" rx="0.5"/>
                        <path d="M1 6h14M6 6v9"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">School Profile</span>
                </a>

                <a href="{{ route('school.help-posts.index') }}"
                   class="sidebar-link {{ request()->routeIs('school.help-posts.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                        <circle cx="8" cy="8" r="6.5"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Help Posts</span>
                </a>
            @endif

            {{-- Player Navigation --}}
            @if(Auth::user()->role === 'PLAYER')
                <a href="{{ route('player.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('player.dashboard') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="currentColor" style="{{ request()->routeIs('player.dashboard') ? 'color:#C8973A' : '' }}">
                        <path d="M1 6l7-5 7 5v9H10V9H6v6H1V6z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('player.help-posts.index') }}"
                   class="sidebar-link {{ request()->routeIs('player.help-posts.*') ? 'active' : '' }}">
                    <svg class="sidebar-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                        <circle cx="8" cy="8" r="6.5"/>
                    </svg>
                    <span x-show="sidebarOpen" class="sidebar-text">Help Posts</span>
                </a>
            @endif
        </nav>

        <!-- Go to Website Button -->
        <a href="/" class="sidebar-website-btn" x-show="sidebarOpen">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="8" cy="8" r="6.5"/>
                <path d="M1.5 8h13M8 1.5c-2 2-3 4-3 6.5s1 4.5 3 6.5M8 1.5c2 2 3 4 3 6.5s-1 4.5-3 6.5"/>
            </svg>
            Go to Website
        </a>

        <!-- Footer / User -->
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
                <div x-show="sidebarOpen" class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">{{ ucfirst(strtolower(Auth::user()->role)) }}</div>
                </div>
            </div>

            <div x-show="sidebarOpen" class="sidebar-actions">
                <a href="{{ route('profile.edit') }}" class="sidebar-action-btn" title="Settings">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4">
                        <circle cx="8" cy="8" r="2.5"/>
                        <path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.1 3.1l1.4 1.4M11.5 11.5l1.4 1.4M3.1 12.9l1.4-1.4M11.5 4.5l1.4-1.4"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="flex:1; display:flex;">
                    @csrf
                    <button type="submit" class="sidebar-action-btn" title="Logout" style="width:100%;">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4">
                            <path d="M6 2H2v12h4M11 4l4 4-4 4M15 8H6"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Toggle Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" class="sidebar-toggle-btn">
                <svg x-show="sidebarOpen" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M10 2L4 8l6 6"/>
                </svg>
                <svg x-show="!sidebarOpen" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2l6 6-6 6"/>
                </svg>
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div :class="sidebarOpen ? 'content-shifted' : 'content-full'" class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen" class="mobile-menu-btn lg:hidden">
                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M2 4h12M2 8h12M2 12h12"/>
                </svg>
            </button>

            <!-- Page Title -->
            <div class="topbar-title">
                {{ $title }}
            </div>

            <!-- Right Actions -->
            <div class="topbar-actions">
                <span class="topbar-date">{{ now()->format('D, d M Y') }}</span>
                <span class="topbar-welcome">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            {{ $slot }}
        </div>
    </div>
</div>

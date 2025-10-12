@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

$userRole = Auth::user()->role;

// Recursive role check helper
if (!function_exists('canViewMenu')) {
function canViewMenu($menuItem, $role) {
if (!isset($menuItem->roles)) return true; // no roles => visible to all
return in_array($role, $menuItem->roles);
}
}
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  style="background-image: url('{{ asset('assets/img/dswd-bg.png') }}'); background-size: cover; background-repeat: no-repeat; color: #fff;">

  <div class="app-brand demo mt-4">
    <a href="{{ url('dashboard') }}" class="app-brand-link">
      <img src="{{ asset('assets/img/logo-dswd.png') }}" alt="DSWD Logo" height="68" style="margin: 30px 0;" />
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="menu-toggle-icon d-xl-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

    {{-- Skip menu if user role not allowed --}}
    @if(!canViewMenu($menu, $userRole))
    @continue
    @endif

    {{-- Menu headers --}}
    @if(isset($menu->menuHeader))
    <li class="menu-header mt-7">
      <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
    </li>
    @else
    @php
    $activeClass = null;
    $currentRouteName = Route::currentRouteName();
    $active = 'active open';

    if($currentRouteName === $menu->slug) $activeClass = 'active';
    elseif(isset($menu->submenu)){
    $activeClass = '';
    foreach($menu->submenu as $sub){
    if(isset($sub->slug) && str_contains($currentRouteName, $sub->slug)){
    $activeClass = $active;
    }
    }
    }
    @endphp

    <li class="menu-item {{ $activeClass }}">
      <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
        @if(isset($menu->target)) target="{{ $menu->target }}" @endif>
        @isset($menu->icon)
        <i class="{{ $menu->icon }}"></i>
        @endisset
        <div>{{ $menu->name ?? '' }}</div>
      </a>

      {{-- Submenu --}}
      @isset($menu->submenu)
      @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
      @endisset
    </li>
    @endif
    @endforeach
  </ul>
</aside>
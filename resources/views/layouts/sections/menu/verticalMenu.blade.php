@php
use Illuminate\Support\Facades\Route;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu"
  style="background-image: url('{{ asset('assets/img/dswd-bg.png') }}');
         background-size: cover;
         background-repeat: no-repeat;
         color: #fff;">

  <div class="app-brand demo mt-4">
    <a href="{{ url('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo me-1">
        <img src="{{ asset('assets/img/logo-dswd.png') }}" alt="DSWD Logo" height="68" style="margin: 30px 0;" />
      </span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="menu-toggle-icon d-xl-block align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

    {{-- Skip menu if user cannot view --}}
    @if (!userCanView($menu))
    @continue
    @endif

    {{-- Menu header --}}
    @if (isset($menu->menuHeader))
    <li class="menu-header mt-7">
      <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
    </li>
    @else
    @php
    $activeClass = '';
    $currentRouteName = Route::currentRouteName();

    if ($currentRouteName === $menu->slug) {
    $activeClass = 'active';
    } elseif (isset($menu->submenu)) {
    foreach ($menu->submenu as $sub) {
    if (isset($sub->slug) && str_contains($currentRouteName, $sub->slug)) {
    $activeClass = 'active open';
    break;
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
        <div>{{ $menu->name }}</div>
        @isset($menu->badge)
        <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
        @endisset
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

<style>
  .layout-menu .menu-inner .menu-item a,
  .layout-menu .menu-inner .menu-header-text,
  .layout-menu .menu-inner .menu-item i {
    color: #ffffff !important;
  }

  .layout-menu .menu-inner .menu-item a:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff !important;
  }

  .layout-menu .menu-item::before,
  .layout-menu .menu-item .menu-link::before,
  .layout-menu .menu-toggle::after {
    color: #ffffff !important;
    background-color: #ffffff !important;
  }

  .layout-menu .menu-item.active::before,
  .layout-menu .menu-item.open::before {
    background-color: #ffffff !important;
  }

  .layout-menu .menu-item.active {
    border-left: 3px solid #ffffff !important;
  }
</style>
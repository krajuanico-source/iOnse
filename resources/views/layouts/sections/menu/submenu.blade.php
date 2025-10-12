@php
use Illuminate\Support\Facades\Route;
@endphp
<ul class="menu-sub">
  @foreach ($menu as $submenu)

  {{-- Skip submenu if user cannot view --}}
  @if (!userCanView($submenu))
  @continue
  @endif

  @php
  $activeClass = '';
  $currentRouteName = Route::currentRouteName();

  if ($currentRouteName === $submenu->slug) {
  $activeClass = 'active';
  } elseif (isset($submenu->submenu)) {
  foreach ($submenu->submenu as $sub) {
  if (isset($sub->slug) && str_contains($currentRouteName, $sub->slug)) {
  $activeClass = 'active open';
  break;
  }
  }
  }
  @endphp

  <li class="menu-item {{ $activeClass }}">
    <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}"
      class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
      @if(isset($submenu->target)) target="{{ $submenu->target }}" @endif>
      @isset($submenu->icon)<i class="{{ $submenu->icon }}"></i>@endisset
      <div>{{ $submenu->name }}</div>
    </a>

    {{-- Recursive submenu --}}
    @isset($submenu->submenu)
    @include('layouts.sections.menu.submenu', ['menu' => $submenu->submenu])
    @endisset
  </li>
  @endforeach
</ul>
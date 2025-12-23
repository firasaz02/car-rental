<nav class="navbar">
  <div class="nav-brand">
    <h1 style="margin:0; font-size:18px;">🚗 car location</h1>
  </div>
  <ul class="nav-menu">
    <li>
      <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a>
    </li>
    <li>
      <a href="{{ route('map') }}" class="nav-link {{ Request::is('map') ? 'active' : '' }}">Live Map</a>
    </li>
    <li>
      <a href="/vehicles" class="nav-link {{ Request::is('vehicles') ? 'active' : '' }}">Vehicles</a>
    </li>
    <li>
      <a href="{{ route('reports') }}" class="nav-link {{ Request::is('reports') ? 'active' : '' }}">Reports</a>
    </li>
    <li>
      <a href="{{ route('settings') }}" class="nav-link {{ Request::is('settings') ? 'active' : '' }}">Settings</a>
    </li>
    <li>
      <a href="{{ route('about') }}" class="nav-link {{ Request::is('about') ? 'active' : '' }}">About</a>
    </li>
    <li>
      <a href="{{ route('contact') }}" class="nav-link {{ Request::is('contact') ? 'active' : '' }}">Contact</a>
    </li>
  </ul>
</nav>

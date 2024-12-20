<html>
    @include('layouts.sidebar-style')
  <body><div class="area"></div>
      <nav class="main-menu">
            <ul>
                <li class="has-subnav">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fa-solid fa-layer-group fa-2x"></i>
                        <span class="nav-text">
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="has-subnav">
                    <a href="{{ route('kelas.index') }}">
                        <i class="fa-solid fa-list fa-2x"></i>
                        <span class="nav-text">
                            Kelas
                        </span>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-right-from-bracket fa-2x"></i>
                        <span class="nav-text">
                            Keluar
                        </span>
                    </a>
                </li> 
            </ul>
        </nav>
  </body>
    </html>
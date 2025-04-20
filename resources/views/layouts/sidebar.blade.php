<nav class="col-12 col-md-3 col-lg-2 sidebar d-flex flex-column p-3">
  @if (auth()->user()->role === 'admin')
    <h4 class="text-center">Halaman Admin</h4>
    @else
    <h4 class="text-center">Halaman Reviewer</h4>
    @endif
    
      @auth
      @if (auth()->user()->role === 'admin')
      <ul class="nav flex-column">
        @include('layouts.admin')
        @elseif(auth()->user()->role === 'reviewer')
        @include('layouts.reviewer')
      @endif
      <li class="nav-link">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out"></i> Logout</a>
      </li>
      @endauth
    </ul>
  </nav>
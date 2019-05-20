@if($url_back = url()->previous())
  @if($url_back !== $url_home)
    <small class="d-inline-block px-2">
      <a class="nav-link p-0" href="{{ $url_home }}">
        {{ __('Home') }}
      </a>
    </small>
  @endif

  <small class="d-inline-block px-2 {{ $url_back !== $url_home ? 'border-left' : '' }}">
    <a class="nav-link d-inline-block p-0" href="{{ $url_back }}">
      {{ ($url_back !== $url_home ? '' : '<< ') . __('Back') }}
    </a>
  </small>
@endif
@unless(-1 === ($bl = $crumb['bl'] ?? 1))
  <small class="d-inline-block px-2{{$bl ? ' border-left' : ''}}">
    <a class="nav-link d-inline-block p-0" href="{{ $crumb['url'] }}">
      @if($crumb['text'] === 'Delete')
        <span style="color: #cc3300;">
          {{ $crumb['text'] }}
        </span>
      @else
        <span>
          {{ $crumb['text'] }}
        </span>
      @endif
    </a>
  </small>
@endunless
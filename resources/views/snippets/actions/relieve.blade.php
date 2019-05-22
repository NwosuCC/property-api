<small class="d-inline-block p-0">
  {{-- [Approve | Decline | View] Buttons --}}
  @if($house && $house->is_expired)
    <button class="fa fa-times mx-1 py-0 px-2 btn btn-light" style="color: red;"
            onclick="MFA.relieve({{$house->getAssignParams($user)}})">
    </button>
  @else
    <button class="fa fa-times mx-1 py-0 px-2 btn btn-light" style="color: lightgrey;"></button>
  @endif

  <a class="nav-link py-0 px-2 d-inline-block btn btn-sm btn-link" href="{{ $house->route->show }}">
    View
  </a>
</small>


{{-- Include scripts once - at index === 0 :: Ensure $$index is passed in --}}
@if($index === 0)
  @push('actions-scripts')
  <script src="{{ asset('js/actions-scripts.js') }}" defer></script>
  @endpush
@endif
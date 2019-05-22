<small class="d-inline-block">
  {{-- [Approve | Decline | View] Buttons --}}
  @unless($house && $house->is_rented)
    <button class="fa fa-check mx-1 py-0 px-2 btn btn-light" style="color: green;"
            onclick="MFA.approve({{$house->getAssignParams($user)}})">
    </button>
    <button class="fa fa-times mx-1 py-0 px-2 btn btn-light" style="color: red;"
            onclick="MFA.decline({{$house->getAssignParams($user)}})">
    </button>
    <a class="nav-link py-0 px-2 d-inline-block btn btn-sm btn-link" href="{{ $house->route->show }}">
      View
    </a>
  @else
    <button class="fa fa-check mx-1 py-0 px-2 btn btn-light" style="color: lightgrey;"></button>
    <button class="fa fa-times mx-1 py-0 px-2 btn btn-light" style="color: lightgrey;"></button>
    <a class="nav-link py-0 px-2 d-inline-block btn btn-sm btn-link" href="#" style="color: lightgrey;">View</a>
  @endunless
</small>


{{-- Include scripts once - at index === 0 :: Ensure $$index is passed in --}}
@if($index === 0)
  @push('actions-scripts')
    <script src="{{ asset('js/actions-scripts.js') }}" defer></script>
  @endpush
@endif
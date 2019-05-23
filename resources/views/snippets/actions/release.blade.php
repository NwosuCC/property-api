<small class="d-inline-block p-0">
  {{-- [Release | View] Buttons --}}
  @if($house && $house->is_expired)
    <button class="fa fa-anchor mx-1 py-0 px-2 btn btn-light" style="color: mediumpurple;"
            title="Release" onclick="MFA.release({{$house->getActionParams($user, 'release')}})">
    </button>
  @else
    <button class="fa fa-anchor mx-1 py-0 px-2 btn btn-light" style="color: lightgrey;"></button>
  @endif

  @include('snippets.actions.view', ['url' => $house->route->show])
</small>


{{-- Include scripts once - at index === 0 :: Ensure $$index is passed in --}}
{{--
@if($index === 0)
  @push('actions-scripts')
  <script src="{{ asset('js/actions-scripts.js') }}" defer></script>
  @endpush
@endif--}}

@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters align-items-center">
              <div class="">
                <h2>
                  {{ __($user->name)  }}
                </h2>
              </div>

              <div class="ml-auto">
                <small class="d-inline-block px-2">
                  <a class="nav-link p-0" href="{{ $r1 = $user->route->applicant->index }}">
                    {{ __('Home') }}
                  </a>
                </small>

                @if($r1 !== ($r2 = url()->previous()))
                <small class="d-inline-block px-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ $r2 }}">
                    {{ __('Back') }}
                  </a>
                </small>
                @endif
              </div>
            </div>
          </div>

          <div class="card-body">
            <h5 class="mt-3 pl-2">
              Properties Occupied
            </h5>

            <div class="">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>House</th>
                  <th>Category</th>
                  <th>Expires</th>
                  <th>Status</th>
                  <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tenancies as $i => $house)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $house->title  }}</td>
                    <td>{{ $house->category->name }}</td>
                    <td>
                      <span class="d-inline-block">
                        @dayDatetime($house->expires_at)
                      </span>
                    </td>
                    <td>
                      @include('snippets.expiry-status', $house)
                    </td>
                    <td class="px-0 text-center">
                      {{-- snippet includes scripts--}}
                      @include(
                        'snippets.actions.relieve',
                        [$house, 'active' => $house->is_expired, 'index' => $loop->index]
                      )
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">
                      This user has no tenancies
                    </td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

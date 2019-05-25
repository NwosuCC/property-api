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
                @include('snippets.bread-crumb.items', ['model' => $user, 'view' => 'tenant.index'])
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
                        'snippets.actions.release',
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

      {{-- Assign Modal --}}
      @component('snippets.modal.index', ['id' => 'release', 'method' => 'PUT'])
        <div class="col-12">
          <div class="mt-3">
            {{-- Assign Action --}}
            <input type="hidden" name="action" id="action" />

            {{-- Badge --}}
            <div class="py-3 px-4 border-left-0 border-right-0" style="background-color: #e6f2ff; border: solid 1px #3c94dd;">
              <div class="mt-1">
                <table>
                  <tbody>
                  <tr>
                    <td class="font-weight-bold pr-4">Tenant</td>
                    <td>
                      <span class="param-user"></span>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold pr-4">House</td>
                    <td>
                      <span class="param-house"></span>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Prompt --}}
            <div class="mt-3 pl-1">
              <span class="param-action"></span> this property?
            </div>
          </div>
        </div>

        @push('modal-buttons')
        <button type="submit" class="btn btn-primary px-3">
          <span class="param-action"></span>
        </button>
        @endpush
      @endcomponent

    </div>
  </div>
@endsection

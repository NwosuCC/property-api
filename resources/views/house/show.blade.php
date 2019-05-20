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
                  <h3 class="m-0 p-0">
                    {{ $house->title  }}
                  </h3>
                  <div>
                    {{ $house->description  }}
                  </div>
                </h2>
              </div>

              <div class="ml-auto">
                @include('snippets.breadcurms', ['url_home' => $house->route->index])

                @can('update', $house)
                <small class="d-inline-block pl-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ $house->route->edit }}">
                    {{ __('Edit House') }}
                  </a>
                </small>
                @endcan
              </div>
            </div>
          </div>

          <div class="card-body">
            <h5 class="mt-3 pl-2">
              Tenants
            </h5>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($house->tenants as $i => $tenant)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $tenant->name  }}</td>
                    <td>{{ $tenant->email  }}</td>
                    <td>
                      {{-- Snippet --}}
                      @include('snippets.expiry-status', $house)
                    </td>
                    <td class="px-0 text-center">
                      <a class="nav-link p-0" href="{{ $tenant->route->tenant->show }}">
                        {{ __('View >>') }}
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">
                      This property has no tenants
                    </td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-body">
            <h5 class="mt-3 pl-2">
              Applicants
            </h5>

            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($house->applicants as $i => $applicant)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $applicant->name  }}</td>
                    <td>{{ $applicant->email  }}</td>
                    <td class="px-0 text-center">
                      <a class="nav-link p-0" href="{{ $applicant->route->applicant->show }}">
                        {{ __('View >>') }}
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center">
                      This property has no applicants
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

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
                @include('snippets.bread-crumb.items', ['model' => $house, 'view' => 'house.show'])
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
                      @include('snippets.expiry-status', $house)
                    </td>
                    <td class="px-0 text-center">
                      @include('snippets.actions.view', ['url' => $tenant->route->tenant->show])
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center">
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
                      @include('snippets.actions.view', ['url' => $applicant->route->applicant->show])
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

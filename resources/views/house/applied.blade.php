@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters align-items-center">
              <div class="">
                <h2>{{ __('Applications')  }}</h2>
              </div>
              @can('create', $House)
                <div class="ml-auto">
                  <small class="d-inline-block px-2">
                    <a class="nav-link p-0" href="{{ $House->route->index }}">
                      {{ __('Home') }}
                    </a>
                  </small>
                  <small class="d-inline-block px-2 border-left">
                    <a class="nav-link p-0" href="{{ $House->route->create }}">{{ __('Add Property') }}</a>
                  </small>
                </div>
              @endcan
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Interests</th>
                  <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($houses as $i => $house)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $house->title  }}</td>
                    <td>{{ $house->description  }}</td>
                    <td>{{ $house->status }}</td>
                    <td>{{ $house->users_count }}</td>
                    <td class="px-0 text-center">
                      <small class="d-inline-block">
                        <a class="nav-link p-0" href="{{ $house->route->show }}">
                          {{ __('View >>') }}
                        </a>
                      </small>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


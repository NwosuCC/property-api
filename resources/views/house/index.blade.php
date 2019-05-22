@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters align-items-center">
              <div class="">
                <h2>{{ __('Properties')  }}</h2>
              </div>
              @can('create', $House)
                <div class="ml-auto">
                  <small class="d-inline-block px-2">
                    <a class="nav-link p-0" href="{{ $House->route->create }}">{{ __('Add Property') }}</a>
                  </small>
                  <small class="d-inline-block px-2 border-left">
                    <a class="nav-link p-0" href="{{ $House->route->applied }}">{{ __('Applications') }}</a>
                  </small>
                </div>
              @endcan
            </div>
          </div>

          <div class="card-body">
            <div class="row px-4">
              <div class="col-12">
                <div class="d-inline-block">
                  <span class="pr-3">Filter posts by:</span>
                  <span class="pr-1 font-weight-bold">Category</span>
                  <div class="d-inline-block">
                    <select id="category" onchange="window.location.href = this.value;" class="form-control-sm" name="category" style="font-size: 14px;" required title="">
                      <option value="{{$House->route->index_filters('') }}"> All Categories </option>
                      @foreach($categories as $cat)
                        <option value="{{$House->route->index_filters($cat) }}" {{$category && $category->slug === $cat->slug ? 'selected' : ''}}>
                          {{ $cat->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                {{--<div class="d-inline-block">
                  <span class="pl-3 pr-1 font-weight-bold">State</span>
                  <div class="d-inline-block">
                    <select id="state" onchange="window.location.href = this.value;" class="form-control-sm" name="state" style="font-size: 14px;" title="">
                      <option value="{{$House->route->index_filters('') }}"> All States </option>
                      @foreach($states as $st)
                        <option value="{{$House->route->index_filters($st) }}" {{$state && $state === $st ? 'selected' : ''}}>
                          {{ $st->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>--}}
              </div>
            </div>

            <br />

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
                @forelse($houses as $i => $house)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $house->title  }}</td>
                    <td>{{ $house->description  }}</td>
                    <td>{{ $house->status }}</td>
                    <td>{{ $house->users_count }}</td>
                    <td class="px-0 text-center">
                      @include('snippets.actions.view', ['url' => $house->route->show])
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">
                      There are currently no property here
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


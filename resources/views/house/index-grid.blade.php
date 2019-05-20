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
                      <small>
                        <a class="nav-link p-0" href="{{ $House->route->create }}">{{ __('Add Property') }}</a>
                      </small>
                    </div>
                    @endcan
                  </div>
                </div>

                <div class="card-body">
                    <div class="row px-4">
                        <div class="col-12">
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
                    </div>

                    <hr />

                    <div class="row p-4">
                      @foreach($houses as $house)
                      <div class="col-12 col-lg-6">
                        <h5 class="font-weight-bold">
                          {{ __($house->title) }}
                        </h5>
                        <div class="mb-4 mb-md-0" style="height: 70px; overflow: hidden;">
                          <p>
                            <span>
                              {{ __( str_words($house->description, 22) ) }}
                            </span>

                            <small class="d-inline-block">
                              <a class="nav-link p-0" href="{{ $house->route->show }}">
                                {{ __('View details >>') }}
                              </a>
                            </small>
                          </p>
                        </div>
                      </div>
                      @endforeach
                      @if(!count($houses))
                        <div class="col-12 text-center">
                          There are currently no properties available
                        </div>
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


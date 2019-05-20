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

            <div class="pt-4">
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
                @foreach($tenancies as $i => $house)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $house->title  }}</td>
                    <td>{{ $house->category->name }}</td>
                    <td>
                      <span class="d-inline-block mr-2">
                        {{ $house->expires_at->format( date_str() ) }}
                      </span>
                    </td>
                    <td>
                      {{-- Snippet --}}
                      @include('snippets.expiry-status', $house)
                    </td>
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

        {{--<div class="card">
          <div class="card-body">
            <form method="POST" action="{{ $user->route->applicant->update }}">
              @csrf
              <input type="hidden" name="_method" value="PUT">

              <div class="form-group row">
                <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                <div class="col-md-6">
                  <input id="user" type="text" class="form-control{{ $errors->has('user') ? ' is-invalid' : '' }}"
                         name="user" value="{{ old('user') ?: __($user->user) }}" required autofocus>

                  @if ($errors->has('user'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('user') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Body') }}</label>

                <div class="col-md-6">
                  <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                            name="description" required>{{ old('description') ?: __($user->description) }}</textarea>

                  @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('Save') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>--}}
      </div>
    </div>
  </div>
@endsection

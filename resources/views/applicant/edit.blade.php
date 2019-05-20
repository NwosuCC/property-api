@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters pt-1">
              <div class="">
                <h5>{{ __('Edit Applicant')  }}</h5>
              </div>
              <div class="ml-auto">
                <small class="d-inline-block px-2">
                  <a class="nav-link p-0" href="{{ $user->route->applicant->index }}">{{ __('Home') }}</a>
                </small>

                <small class="d-inline-block px-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ url()->previous() }}">
                    {{ __('Back') }}
                  </a>
                </small>
              </div>
            </div>
          </div>

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
        </div>
      </div>
    </div>
  </div>
@endsection

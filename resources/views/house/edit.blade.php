@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-11 col-lg-8">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters pt-1">
              <div class="">
                <h5>{{ __('Edit House')  }}</h5>
              </div>

              <div class="ml-auto">
                @include('snippets.bread-crumb.items', ['model' => $house, 'view' => 'house.edit'])
              </div>
            </div>
          </div>

          <div class="card-body">
            <form method="POST" action="{{ $house->route->update }}">
              @csrf
              <input type="hidden" name="_method" value="PUT">

              <div class="form-group row">
                <label for="category" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                <div class="col-md-6">
                  <select id="category" class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category" required autofocus>
                    <option value="">- select -</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}" {{ (int)(old('category') ?: $house->category->id) === (int)$category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                    @endforeach
                  </select>

                  @if ($errors->has('category'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('category') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
\
              {{-- Title --}}
              <div class="form-group row">
                <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                <div class="col-md-6">
                  <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                         name="title" value="{{ old('title') ?: __($house->title) }}" required autofocus>

                  @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              {{-- Description --}}
              <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Body') }}</label>

                <div class="col-md-6">
                  <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                            name="description" required>{{ old('description') ?: __($house->description) }}</textarea>

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

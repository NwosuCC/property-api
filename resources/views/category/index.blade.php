@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row no-gutters align-items-center">
                            <div class="">
                                <h2>{{ __('Categories')  }}</h2>
                            </div>
                            <div class="ml-auto">
                                <small>
                                    <a href="#" id="_mfa_c_1" onclick="MFA.create({{$Category->createParams}})" class="nav-link p-0" data-toggle="modal">
                                        {{ __('Add a Category') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th class="text-center">Houses</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $i => $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name  }}</td>
                                        <td>{{ $category->description  }}</td>
                                        <td class="text-center">
                                          {{ __($category->houses_count) }}
                                        </td>
                                        <td class="px-0 text-center">
                                          <i id="_mfa_e_{{$loop->iteration}}" onclick="MFA.edit({{$category->editParams}})" class="fa fa-edit py-0 px-2 btn btn-light"></i>
                                          <i id="_mfa_d_{{$loop->iteration}}" onclick="MFA.trash({{$category->deleteParams}})" class="fa fa-trash py-0 px-2 btn btn-light"></i>
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

      {{-- Add/Edit Modal --}}
      @component('snippets.modal.index', ['id' => 'category', 'method' => 'POST'])
        <div class="col-12">
          {{-- Create | Edit Section --}}
          <div class="mf-section mf-section-create mf-section-edit">
            <div class="form-group row">
              <label for="name">{{ __('Name') }}</label>
              <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

              <span class="invalid-feedback{{ $errors->has('name') ? '' : ' d-none' }}" role="alert">
                  <strong>{{ $errors->first('name') }}</strong>
              </span>
            </div>

            <div class="form-group row">
              <label for="description">{{ __('Description') }}</label>
              <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required>{{ old('description') }}</textarea>

              <span class="invalid-feedback{{ $errors->has('description') ? '' : ' d-none' }}" role="alert">
                  <strong>{{ $errors->first('description') }}</strong>
              </span>
            </div>
          </div>

          {{-- Delete Section --}}
          <div class="mf-section mf-section-delete pt-3 " style="display: none;">
            <div class="form-group row">
              {{-- Blue Band --}}
              @component('snippets.light-blue-band')
                <div class="col-12 px-0 mt-1">
                  <table>
                    <tbody>
                    <tr>
                      <td class="pr-3 text-right align-text-top">
                        {{-- Model Label --}}
                        <span class="mf-text-model-label"></span>
                      </td>
                      <td class="font-weight-bold" style="padding-top: 2px;">
                        {{-- Model Name --}}
                        <span class="mf-text-model-name"></span>
                      </td>
                    </tr>
                    <tr style="font-size: 13px;">
                      <td class="pr-3 text-right align-text-top">
                        {{-- Warn Icon --}}
                        <a class="mf-text-cascade-icon fa fa-warning text-danger"></a>
                      </td>
                      <td>
                        {{-- Cascade Info --}}
                        <span class="mf-text-cascade-info"></span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              @endcomponent

              {{-- Prompt --}}
              <div class="mt-3 pl-1">
                <span class="mf-text-action"></span> this <span class="mf-text-model-label"></span>?
              </div>
            </div>
          </div>
        </div>

        @push('modal-buttons')
        <button type="submit" class="modal-submit btn btn-primary px-3">
          <span class="mf-text-button"></span>
        </button>
        @endpush
      @endcomponent

    </div>
@endsection

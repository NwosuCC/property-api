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
                                    <a href="#" onclick="MFA.create({{$Category->createParams}})" class="nav-link p-0" data-toggle="modal">
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
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $category->name  }}</td>
                                        <td>{{ $category->description  }}</td>
                                        <td class="text-center">
                                          {{ __($category->houses_count) }}
                                        </td>
                                        <td class="px-0 text-center">
                                            {{--<i class="fa fa-edit py-0 px-2 btn btn-light" onclick="MF.edit({{$category->editParams}})"></i>--}}
                                            {{--<i class="fa fa-trash py-0 px-2 btn btn-light" onclick="MF.trash({{$category->deleteParams}})"></i>--}}
                                          <i onclick="MFA.edit({{$category->editParams}})" class="fa fa-edit py-0 px-2 btn btn-light"></i>
                                          <i onclick="MFA.trash({{$category->deleteParams}})" class="fa fa-trash py-0 px-2 btn btn-light"></i>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- Script function edit() --}}
                            {{--<script>
                              let MF = {};
                              setTimeout(() => {
                                MF = ModalForm.init({
                                  name: 'Category',
                                  fields: ['name', 'description'],
                                  titleField: 'name',
                                  actions: {
                                    create: ['categoryForm', 'addCategoryModal'],
                                    edit: ['categoryForm', 'addCategoryModal'],
                                    trash: ['deleteForm', 'deleteModal']
                                  },
                                  deleteInfo: 'All Houses under this category will also be deleted',
                                });
                              }, 300);
                            </script>--}}
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
          <div class="mf-section mf-section-delete pt-3" style="display: none;">
            <div class="form-group row">
              <div class="col-1 pl-0">
                <i class="fa fa-exclamation-triangle fa-2x align-self-center" style="color: indianred;"></i>
              </div>
              <div class="col-11 pl-4">
                <div class="mf-text-delete-info">
                  {{-- Delete Info --}}
                </div>
                <div>'
                  Delete <span class="mf-text-delete-model"></span> "<b class="mf-text-delete-model-title"></b>" ?
                </div>
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

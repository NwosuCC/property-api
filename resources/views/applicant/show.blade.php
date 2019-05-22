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
                    {{ $user->name  }}
                  </h3>
                </h2>
              </div>

              <div class="ml-auto">
                @include('snippets.bread-crumb.items', ['model' => $user, 'view' => 'applicant.index'])
              </div>
            </div>
          </div>

          <div class="card-body">
            <h5 class="mt-3 pl-2">
              Properties Applied
            </h5>

            <div class="">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>House</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th>Date Applied</th>
                  <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($applications as $house)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $house->title  }}</td>
                    <td>{{ $house->category->name }}</td>
                    <td>{{ $house->status }}</td>
                    <td>
                      @dayDatetime($house->created_at)
                    </td>
                    <td class="px-0 text-center">
                      {{-- snippet includes scripts--}}
                      @include('snippets.actions.assign', [$house, 'index' => $loop->index])
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">
                      This user has no applications
                    </td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Approve/Decline Modal --}}
      <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
              <h5 class="modal-title py-1 px-3" style="color: #123466" id="exampleModalLabel">
                {{-- Title --}}
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
              </button>
            </div>

            <form id="approvalForm" method="POST" action="">
              @csrf
              {{ method_field('PUT') }}

              <input type="hidden" name="assign" id="assign" />

              <div class="modal-body" style="min-height: 150px;">
                <div class="py-0 px-3">
                  <div class="form-group row no-gutters">
                    <div class="col-12">
                      <div class="mt-3">
                        {{-- Badge --}}
                        <div class="py-3 px-4 border-left-0 border-right-0" style="background-color: #e6f2ff; border: solid 1px #3c94dd;">
                          <div class="mt-1">
                            <strong>Applicant</strong>: <span class="action-user"></span>
                          </div>
                          <div class="mt-1">
                            <strong>House</strong>: <span class="action-house"></span>
                          </div>
                        </div>

                        {{-- Prompt --}}
                        <div class="mt-3 pl-1">
                          <span class="action-name"></span> this Application?
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-metal" data-dismiss="modal">
                  Close
                </button>
                <button type="submit" class="btn btn-primary px-3">
                   <span class="action-name">
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

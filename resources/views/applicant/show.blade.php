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
                      @dayDatetime($house->pivot->created_at)
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

      {{-- Assign Modal --}}
      @component('snippets.modal.index', ['id' => 'assign', 'method' => 'PUT'])
        <div class="form-group row no-gutters">
          <div class="col-12">
            <div class="mt-3">
              {{-- Assign Action --}}
              <input type="hidden" name="action" id="action" />

              {{-- Badge --}}
              <div class="py-2 px-4 border-left-0 border-right-0" style="background-color: #e6f2ff; border: solid 1px #3c94dd;">
                <div class="mt-1">
                  <table>
                    <tbody>
                    {{-- Applicant --}}
                    <tr>
                      <td class="font-weight-bold pr-3">Applicant</td>
                      <td>
                        <span class="mf-text-user"></span>
                      </td>
                    </tr>

                    {{-- House --}}
                    <tr>
                      <td class="font-weight-bold pr-3">House</td>
                      <td>
                        <span class="mf-text-house"></span>
                      </td>
                    </tr>

                    {{-- Expiry --}}
                    <tr class="td_date">
                      <td class="font-weight-bold pr-3">Expires</td>
                      <td>
                        <span class="expiry-label" onclick="TextField.show()">
                          {{-- [ Click to edit ] --}}
                        </span>
                        <span class="expiry-field" {{--style="display: none;"--}}>
                          <input type="date" name="expires_at" id="expires_at" title="Expiry Date"
                                 class="form-control form-control{{ $errors->has('expires_at') ? ' is-invalid' : '' }} form-control-sm"
                                 value="{{ old('expires_at') }}" onblur="TextField.hide()" />
                          {{--<span class="invalid-feedback{{ $errors->has('expires_at') ? '' : ' d-none' }}" role="alert">
                            <strong>{{ $errors->first('expires_at') }}</strong>
                          </span>--}}
                        </span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Prompt --}}
              <div class="mt-3 pl-1">
                <span class="mf-text-action"></span> this Application?
              </div>
            </div>
          </div>
        </div>

        @push('modal-buttons')
          {{--<button type="button" class="modal-submit btn btn-primary px-3" onclick="window.MFA.submit(event)" onmouseenter="TextField.hide()">--}}
          <button type="submit" class="modal-submit btn btn-primary px-3" {{--onmouseenter="TextField.hide()"--}}>
            <span class="mf-text-action"></span>
          </button>
        @endpush
      @endcomponent

      <script>
        const TextField = (() => {
          let actionObj, tdTextObj, labelsObj, valuesObj, valuesTxt;

          setTimeout(() => {
            actionObj = () => $('#action');
            tdTextObj = () => $('.td_date');
            labelsObj = () => $('.expiry-label');
            valuesObj = () => $('.expiry-field');
            valuesTxt = () => $('#expires_at');

            // Has to be plugged into Modal due to reference to MFA.UI below
            if(typeof plugIntoModal === 'function'){
              plugIntoModal(() => {
                // Add extra options
                MFA.setOptions({ listErrors: true });

                $(document)
                  .on({
                    mousedown: function (e) {
                      if(e.target.id !== valuesTxt().attr('id')){
                        TextField.hide();
                      }
                    }
                  })
                  .on({
                    'show.bs.modal': function () {
                      TextField.setLabel() && TextField.resetText();
                      (actionObj().val() === 'approve') ? tdTextObj().show() : tdTextObj().hide();
                    }
                  }, MFA.UI)
                  .on({
                    'shown.bs.modal': function () {
                      if('<?= in_array('expires_at', old()) ?>'){
                        TextField.show();
                      }
                    }
                  }, MFA.UI)
                ;
              });
            }
          }, 300);

          return {
            setLabel: () => {
              labelsObj().text('[ Click to edit ]');
            },
            resetText: () => {
              valuesTxt().val('').change();
            },
            show: () => {
              labelsObj().hide() && valuesObj().show();
            },
            hide: () => {
              valuesTxt().val() && labelsObj().text( valuesTxt().val() );
              labelsObj().show() && valuesObj().hide();
            }
          };
        })();
      </script>

    </div>
  </div>
@endsection

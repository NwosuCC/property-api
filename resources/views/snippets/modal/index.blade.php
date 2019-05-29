@php
  // Expected Keys : 'id', 'method'

  $modal = ($id) . 'Modal';
  $form  = ($id) . 'Form';
@endphp

<div class="modal fade" id="{{$modal}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
        <h5 class="modal-title mf-text-title py-1 px-3" style="color: #123466" id="exampleModalLabel">
          {{-- Title --}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            &times;
        </span>
        </button>
      </div>

      <form id="{{$form}}" method="POST" action="{{-- Action --}}">
        {{-- Errors --}}
        <div class="form-errors text-danger pt-3 px-4" style="font-size: 14px; margin-bottom: -15px;">
          <span class="form-error dv-default px-2 d-none">
            <a class="fa fa-circle mb-1 pr-2" style="font-size: 5px; vertical-align: middle;"></a>
            <span class="form-error-item"></span>
          </span>
        </div>

        {{-- Inputs --}}
        @csrf

        @isset($method)
          {{ method_field( $method ) }}
        @endisset

        <div class="modal-body" style="min-height: 150px;">
          <div class="py-0 px-3">

              {{-- Custom Modal Form content --}}
              {{ $slot }}

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-metal" data-dismiss="modal">
            Close
          </button>

          {{-- Custom Modal Form action buttons --}}
          @stack('modal-buttons')
        </div>
      </form>

    </div>
  </div>

  {{-- Bug: Spinner collision with Modal overlay --}}
  {{--@include('snippets.spinner.index')--}}
</div>


<script defer>
  const plugIntoModal = (initCallback) => {
    if(initCallback && typeof initCallback !== 'function'){
      console.error(`Param 'initCallback' must ba a function`);
      return;
    }

    let lapse = 0, attempts = 0;

    if(window.MFA && typeof window.MFA.init !== 'undefined'){
      // MFA is now defined
      if(initCallback){
        // Any other function to run AFTER MFA is loaded
        initCallback.call();
      }
      else if( ! init){
        // Initialize (once) this Modal component (identified as $id) in MFA
        window.MFA.init("{{$id}}");
        init = true;
      }
    }
    else if(++attempts <= 5){
      // Repeat attempt 5 times, increasing the next wait time by 100
      setTimeout(() => { plugIntoModal(initCallback); }, lapse += 100);
    }
  };

  let init = false;

  plugIntoModal();
</script>
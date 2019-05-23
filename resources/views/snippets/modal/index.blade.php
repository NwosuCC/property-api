@php
  // Expected Keys : 'id', 'method'
  // Modal Actions : Approve, Decline, Release

  $modal = ($id) . 'Modal';
  $form  = ($id) . 'Form';
@endphp

<div class="modal fade" id="{{$modal}}" tabindex="-1" role="dialog" aria-hidden="true">
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

      <form id="{{$form}}" method="POST" action="{{-- Action --}}">
        @csrf

        @isset($method)
          {{ method_field( $method ) }}
        @endisset

        <div class="modal-body" style="min-height: 150px;">
          <div class="py-0 px-3">
            <div class="form-group row no-gutters">

              {{ $slot }}

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-metal" data-dismiss="modal">
            Close
          </button>

          @stack('modal-buttons')
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  let lapse = 0, attempts = 0;

  const initModal = () => {
    if(window.MFA && typeof window.MFA.init !== 'undefined'){
      window.MFA.init("{{$id}}");
    }
    else if(++attempts <= 5){
      // Repeat attempt 5 times, increasing the next wait time by 100
      setTimeout(() => { initModal(); }, lapse += 100);
    }
  };

  initModal();

</script>
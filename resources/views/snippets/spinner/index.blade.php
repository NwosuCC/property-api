<div class='sp-overlay row justify-content-center align-items-center'>
  <div class="sp-loading row justify-content-center align-items-center bg-light border border-secondary">
    <span class="font-weight-bold">
    L o a d i n g
    </span>
    <span>
      <span class="sp-spinner ml-3 fa fa-spinner fa-spin"></span>
    </span>
  </div>
</div>


<script>
  const Spinner = {
    show() {
      $('.sp-overlay').css({top: screenTop, left: 0}).show();
    },
    hide() {
      $('.sp-overlay').hide();
    }
  };

  // Register on Modal
  if(typeof plugIntoModal === 'function'){
    plugIntoModal(() => window.MFA.addSpinner(Spinner));
  }
</script>


<style>
  .sp-overlay {
    width: 100%; height: 100%;
    background-color: rgba(114,119,129,0.35);
    position:absolute; /*top:0; left:0;*/ z-index: 1000;
  }
  .sp-overlay .sp-loading {
    min-width: 150px;
    width: 200px;
    height: 50px;
    text-align: center;
    border: solid 1px;
    border-radius: 5px;
  }
  .sp-overlay .sp-spinner {
    font-size: 30px;
  }
</style>

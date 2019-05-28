/* ---------------------------------------------------------------
 | M O D A L   S C R I P T S
 * ---------------------------------------------------------- */

/* ---------------------------------------------------------------
 | Sets the global (window) MFA (modal helper) variable
 * ---------------------------------------------------------- */
if(typeof window.MFA === 'undefined'){
  window.MFA = {};
}
let MFA = window.MFA;


setTimeout(() => {
  /* --------------------------------------------------------------------
   | Called from the Modal index ('snippets.modal.index' partial)
   | Initializes the modal at load time
   * --------------------------------------------------------------- */
  MFA.init = (id) => {
    MFA.ID   = {modal: `${id}Modal`, form: `${id}Form`};
    MFA.UI   = $('#' + MFA.ID.modal);
    MFA.Form = $('#' + MFA.ID.form);

    MFA.defaultParams = ['route', 'action'];

    MFA.Config = {
      approve: {
        title: () => 'Approve Application',
        params: {
          action: (action) => Str.titleCase(action), house: 'house', user: 'user'
        },
        fields: { action: 'assign' },
      },
      decline: {
        title: () => 'Decline Application',
        params: {
          action: (action) => Str.titleCase(action), house: 'house', user: 'user'
        },
        fields: { action: 'assign' },
      },
      release: {
        title: () => 'Release Property',
        params: {
          action: (action) => Str.titleCase(action), house: 'house', user: 'user'
        },
        fields: { action: 'release' },
      },
    };

    // Add id to input fields that don't have. Needed in Form.values() collate at submit()
    MFA.Form.find('[type="hidden"]').each(function (i,e) {
      if(e.id.trim() === ''){ e.id = e.name; }
    });
  };

  /* ------------------------------------------------------------------
   | Returns the JQuery id string of the registered modal object
   * ------------------------------------------------------------- */
  MFA.id = (id) => '#' + id;

  /* -------------------------------------------------------------
   | Validate supplied text-params anf field-values
   * -------------------------------------------------------- */
  MFA.validateSuppliedDefault = (supplied) => {
    let valid = true;
    MFA.defaultParams.forEach(param => {
      if(typeof supplied[ param ] !== 'string' || supplied[ param ] === ''){
        console.error(
          `Key '${param}' must be defined in supplied {params} and must not be empty`
        );
        valid = false;
      }
    });
    return  valid;
  };

  /* -------------------------------------------------------------
   | Loops through the config params|fields and sets the
   | corresponding supplied texts|values
   * -------------------------------------------------------- */
  MFA.iterateConfig = (config, supplied, setter) => {
    Object.keys(config).forEach(key => {
      if(supplied.hasOwnProperty(key)){

        let suppliedValue = (typeof config[key] === 'function')
          ? config[key].call(undefined, supplied[key])
          : supplied[key];

        setter.call(undefined, key, suppliedValue);
      }
    });
  };

  /* -------------------------------------------------------------
   | Helper function for the different Actions
   * -------------------------------------------------------- */
  MFA.setAction = (params, values) => {
    let config = MFA.Config[ params.action ];

    if( ! MFA.validateSuppliedDefault(params)){
      return;
    }

    MFA.UI.find( MFA.id(MFA.ID.form) ).attr({'action': params.route});
    MFA.UI.find('.modal-title').text( config.title() );

    if(config.hasOwnProperty('params')){
      MFA.iterateConfig(config.params, params, (key, value) => {
        MFA.Form.find(`.param-${key}`).text( value )
      });
    }

    if(values && config.hasOwnProperty('fields')){
      MFA.iterateConfig(config.fields, values, (key, value) => {
        MFA.Form.find('#'+key).val( value );
      });
    }

    return MFA;
  };

  /* ---------------------------------------------------------------------
   | Actions: called from the active Web page
   | Handle the [click | submit] events from the [icons | buttons]
   * ---------------------------------------------------------------- */
  MFA.approve = (params) => {
    // Approve property tenancy application
    params.action = 'approve';
    MFA.setAction(params, {action: params.action}).show();
  };
  MFA.decline = (params) => {
    // Decline property tenancy application
    params.action = 'decline';
    MFA.setAction(params, {action: params.action}).show();
  };
  MFA.release = (params) => {
    // Release expired house from last occupant
    params.action = 'release';
    MFA.setAction(params, {action: params.action}).show();
  };

  /* -----------------------
   | Shows the modal
   * ------------------ */
  MFA.show = () => {
    MFA.UI.find('.modal-header').addClass('bg-primary').find('h5, button').css({'color':'white'});
    MFA.UI.find('.modal-footer').css({'background-color': 'rgba(0,0,0,.03)'});
    MFA.UI.modal('show');
  };

  /* -----------------------
   | Shows the modal
   * ------------------ */
  MFA.hide = () => {
    MFA.UI.modal('hide');
  };

  /* -----------------------
   | Reloads page
   * ------------------ */
  MFA.reload = () => {
    window.location.reload();
  };

  /* ------------------------------
   | Spinner
   * ------------------------- */
  MFA.addSpinner = (obj) => {
    MFA.Spinner = obj;
  };

  /* ------------------------------
   | Form Handler Ajax Setup
   * ------------------------- */
  /*$.ajaxSetup({
    beforeSend: function () {
      // MFA.Spinner.show();
    },
    dataType: 'json',
    error: function (xhr, status, error) {
      MFA.handleErrors( xhr.responseJSON );
    },
    complete: function (xhr, status) {
      // MFA.Spinner.hide();
    }
  });*/

  /* ---------------------------
   | Form Handler Methods
   * ---------------------- */
  MFA.FormErrors = [];

  MFA.handleErrors = (resp) => {
    MFA.FormErrors = resp.hasOwnProperty('errors') ? resp.errors : [resp.message];
    MFA.showErrors();
  };

  MFA.errorDiv = () => {
    let errorDiv = MFA.UI.find('.form-errors');
    return [errorDiv, errorDiv.find('.form-error').first()];
  };

  MFA.showErrors = () => {
    let [errorDiv, errorSpan] = MFA.errorDiv();
    let errors = MFA.Utils.Array.flatten( Object.values(MFA.FormErrors) ) || [];

    if(errorSpan){
      errors.forEach(error => {
        let errorSpanItem = errorSpan.clone();
        errorSpanItem.addClass('d-block').removeClass('d-none').find('.form-error-item').text(error);
        errorDiv.append( errorSpanItem );
      });
    }
  };

  MFA.clearErrors = () => {
    let [errorDiv, errorSpan] = MFA.errorDiv();
    errorDiv.find('.form-error').not(':eq(0)').remove();
  };

  /* --------------------------
   | Submits a raw form
   * --------------------- */
  MFA.submit = (event) => {
    MFA.clearErrors();

    let form = $(event.target).parents('form');

    let actionUrl = form.attr('action');

    let formValues = MFA.values(form);

    let formMethod = formValues['_method'] || form.attr('method');

    /*$.ajax({
      type: formMethod,
      data: formValues ,
      url: actionUrl,
      success: function(result, status, xhr){
        form[0].reset();
        MFA.reload();
      },
    });*/

    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };

    axios.post(actionUrl, formValues, {headers: headers})
      .then((resp) => {
        console.log('resp data: ', resp.data);
      })
      .catch((error) => {
        console.log('error: ', error);
      });
  };

  /* -------------------------------
   | Collates form input values
   * -------------------------- */
  MFA.values = (formUI) => {
    let form = {};

    formUI.find('input, textarea, select').each(function (i, input) {
      let { name, id, type } = input;
      let value = '';

      let el = id ? $('#'+id) : $('[name="'+ name +']"');
      if( ! id){ el.attr('id', name); }
      // console.log(name, id, type, el.attr('id'), el.attr('value'));

      if( el.hasClass('input-currency')){
        el.val(function(){
          return MFA.Utils.String.asNumber( $(this).val() );
        });
      }

      switch(type){
        case 'checkbox': case 'radio': {
        if( !!form[name] ){ return; } // for checkbox Group where one is already checked
        value = el.prop('checked') ? el.val() : '';
      } break;
        case 'textarea': case 'email': case 'text': case 'number': case 'hidden':
        case 'select-one': case 'date' : case 'time' : {
        value = el.val();
      } break;
      }

      form[name] = value;
    });

    return form;
  };

  /* -----------------------
   | Utility Methods
   * ------------------ */
  MFA.Utils = {
    Object: {
      sanitizeValue(value, defaultValue = "") {
        return MFA.Object.isEmpty( value ) ? defaultValue : value;
      },
      isEmpty(value) {
        const Empties = ['undefined', 'null', 'NaN', 'false', '0', ''];
        return !Array.isArray(value) && Empties.includes( String(value).trim() );
      },
    },
    Array: {
      flatten(input) {
        if( ! Array.isArray(input) || input.find(i => ! Array.isArray(i))){
          return input;
        }
        return input.reduce(function(a,b){ return b.concat(a); });
      }
    },
    String: {
      asNumber(number) {
        number = MFA.Object.sanitizeValue(number);
        number = Number( String(number).split(",").join("") );
        return (!isNaN(number)) ? number : 0;
      }
    }
  }

}, 300);

<script>
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

      /* -------------------------------------------------------------------------------- *
       | 'textNodes' (elements of class 'mf-text-') are used as text placeholders
       | in modal templates that can be used for more than one similar actions
       | E.g for approve|decline forms,
       | textNodes.action - sets buttonText => [Approve] or [Decline]
       * ---------------------------------------------------------------------------- *
       | 'fields' contain {key: value} pairs with which the form input will be set
       * ------------------------------------------------------------------------- */
      MFA.Config = {
        approve: {
          textNodes: {
            title: () => 'Approve Application',
            // => Supplied params must have the property 'action'
            button: (action) => Str.titleCase(action), house: 'house', user: 'user'
          },
          fields: { action: 'assign' },
        },
        decline: {
          textNodes: {
            title: () => 'Decline Application',
            button: (action) => Str.titleCase(action), house: 'house', user: 'user'
          },
          fields: { action: 'assign' },
        },
        release: {
          textNodes: {
            title: () => 'Release Property',
            button: (action) => Str.titleCase(action), house: 'house', user: 'user'
          },
          fields: { action: 'release' },
        },
        'create': {
          // => Supplied params must have the property 'model'
          textNodes: {
            title: (model) => 'Create ' + Str.titleCase(model),
            button: (model) => 'Create ' + Str.titleCase(model),
          },
          fields: { _method: 'POST' },
        },
        'edit': {
          textNodes: {
            title: (model) => 'Edit ' + Str.titleCase(model),
            button: (model) => 'Update ' + Str.titleCase(model),
          },
          fields: { _method: 'PUT' },
        },
        'delete': {
          textNodes: {
            title: (model) => 'Delete ' + Str.titleCase(model),
            button: (model) => 'Delete ' + Str.titleCase(model),
          },
          fields: { _method: 'DELETE' },
        },
      };

      // Add id to input fields that don't have. Needed in Form.values() collate at submit()
      MFA.input().each(function (i,e) {
        if(e.id.trim() === ''){ e.id = e.name; }
      });
    };

    /* ------------------------------------------------------------------
     | Returns the JQuery id string of the registered modal object
     * ------------------------------------------------------------- */
    MFA.id = (id) => '#' + id;

    /* ------------------------------------------------------------------
     | Returns all Form inputs
     * ------------------------------------------------------------- */
    MFA.input = () => MFA.Form.find('input, textarea, select');

    /* ------------------------------------------------------------------
     | Returns all Form inputs that are editable
     | Includes non-hidden input, text-area, select
     * ------------------------------------------------------------- */
    MFA.editableInput = () => MFA.Form.find('input[type!="hidden"], textarea, select');

    /* ------------------------------------------------------------------
     | Sets and returns the action that was called
     * ------------------------------------------------------------- */
//    MFA.action = (action) => {
//
//    };

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

    /* ---------------------------------------------------------------------
     | Checks if params is a Model and syncs it with default MFA.Config
     * ----------------------------------------------------------------- */
    MFA.syncModelConfig = (params) => {
      let config = MFA.Config[ params.action ];

      // Add these properties that will be set on textNodes iteration
      if(params.hasOwnProperty('model')){
        if( ! params.hasOwnProperty('title')){
          params.title = params.model;
        }
        if( ! params.hasOwnProperty('button')){
          params.button = params.model;
        }
      }

      return config;
    };

    /* -------------------------------------------------------------
     | Loops through the config textNodes|fields and sets the
     | corresponding supplied texts|values
     * -------------------------------------------------------- */
    MFA.iterateConfig = (config, supplied, setter) => {
      Object.keys(config).forEach(key => {

        let suppliedHasValue = supplied.hasOwnProperty(key);
        let configIsFunction = typeof config[key] === 'function';

          let suppliedValue = null;

          switch(true){
            case suppliedHasValue :   { suppliedValue = supplied[key]; break; }
            case ! configIsFunction : { suppliedValue = config[key]; break; }
          }

          if(configIsFunction){
            suppliedValue = config[key].call(undefined, suppliedValue);
          }

          setter.call(undefined, key, suppliedValue);
      });
    };

    /* -------------------------------------------------------------
     | Helper function for the different Actions
     * -------------------------------------------------------- */
    MFA.setModalUI = (params, values) => {
      // Ensure params includes defaults: 'route', 'action', etc)
      if( ! MFA.validateSuppliedDefault(params)){
        return;
      }

      let config = MFA.syncModelConfig(params);

      // Set 'form action'
      MFA.UI.find( MFA.id(MFA.ID.form) ).attr({'action': params.route});

      // Set texts in modal elements having 'mf-text-' named classes
      if(config.hasOwnProperty('textNodes')){
        MFA.iterateConfig(config.textNodes, params, (key, value) => {
          MFA.UI.find(`.mf-text-${key}`).text( value )
        });
      }

      // Set supplied/configured initial values on form inputs
      if(values && config.hasOwnProperty('fields')){
        // Add all keys in 'values' into 'config.fields' and reset their values
        MFA.editableInput().each(function (i,e) {
          config.fields[ e.id ] = '';
        });

        MFA.iterateConfig(config.fields, values, (key, value) => {
          MFA.Form.find('#'+key).val( value );
        });
      }

      // Hides all MFA section and displays only the section for the current action
      MFA.UI.find(`.mf-section`).hide();
      MFA.UI.find(`.mf-section.mf-section-${params.action}`).show();

      return MFA;
    };

    /* ---------------------------------------------------------------------
     | Actions: called from the active Web page
     | Handle the [click | submit] events from the [icons | buttons]
     * ---------------------------------------------------------------- */
    MFA.approve = (params) => {
      // Approve property tenancy application
      params.action = 'approve';
      MFA.setModalUI(params, {action: params.action}).show();
    };
    MFA.decline = (params) => {
      // Decline property tenancy application
      params.action = 'decline';
      MFA.setModalUI(params, {action: params.action}).show();
    };
    MFA.release = (params) => {
      // Release expired house from last occupant
      MFA.setModalUI(params, {action: params.action}).show();
    };
    MFA.create = (params) => {
      // Create a new model
      params.action = 'create';
      MFA.setModalUI(params, {}).show();
    };
    MFA.edit = (params) => {
      // Update existing model
      params.action = 'edit';
      MFA.setModalUI(params, params.fields).show();
    };
    MFA.trash = (params) => {
      // Delete existing model
      params.action = 'delete';
      MFA.setModalUI(params).show();
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
</script>
/*
 * Script to add in app php index file (index.php or app.blade.php)
 *
   {{-- Grab Errors into JS :: Possible only in blade.php file --}}
   <script>
     const MFExt = (() => {
       return {
         getPHPErrors: () => {
            return'@php if($errors && $errors_str = json_encode( $errors->toArray() )) echo $errors_str; @endphp';
         },
         getFormOld: () => {
            return'@php if($old_str = json_encode( old() )) echo $old_str; @endphp';
         }
       };
     })();
   </script>
   <pre class="d-block pl-4">AA: &emsp;{{ $errors_str  }}</pre>
   <pre class="d-block pl-4">AA: &emsp;{{ $old_str  }}</pre>
*/



/* ---------------------------------------------------------------
 | H E L P E R   F U N C T I O N S
 * ---------------------------------------------------------- */
const Utils = (() => {
  const Util = {
    Obj: {
      sanitizeValue(value, defaultValue = "") {
        return Util.Obj.isEmpty( value ) ? defaultValue : value;
      },
      isEmpty(value) {
        const Empties = ['undefined', 'null', 'NaN', 'false', '0', ''];
        return ! Util.Arr.isArray(value) && Empties.includes( String(value).trim() );
      },
    },
    Arr: {
      isArray(input) {
        return Array.isArray(input);
      },
      flatten(input) {
        if( ! Util.Arr.isArray(input) || input.find(i => ! Util.Arr.isArray(i))){
          return input;
        }
        return input.reduce(function(a,b){ return b.concat(a); });
      }
    },
    Str: {
      titleCase(string) {
        let words = String( string ).toLowerCase().split(" ").filter(w => !!w).map(w => w.split(''));
        return words.map(word => word.shift().toUpperCase() + word.join("") ).join(' ');
      },
      asNumber(number) {
        number = Util.Obj.sanitizeValue(number);
        number = Number( String(number).split(",").join("") );
        return (!isNaN(number)) ? number : 0;
      }
    },
    parseToJSON(stringObject) {
      return Util.tryCatch((str) => {
        return JSON.parse(str);
      }, [stringObject]);
    },
    tryCatch(callback, params) {
      try{
        return callback.apply(undefined, params)
      }
      catch (error){
        console.log(error);
      }
    },
  };

  return Util;
})();


/* ---------------------------------------------------------------
 | M O D A L   S C R I P T S
 * ---------------------------------------------------------- */
const MFA = (() => {
  const MF = {
    /* -----------------------------------------------------------------------*
     | All external functions called indirectly from here
     * ------------------------------------------------------------------- */
    ext:  {
      Str: Utils.Str,
      tryCatch: Utils.tryCatch,
      parseToJSON: Utils.parseToJSON,
      getErrorBag: () => {
        // Errors from previously submitted form, got from Laravel PHP $errors
        return (typeof MFExt !== 'undefined') ? MFExt.getPHPErrors() : {};
      },
      getFormOld: () => {
        // Old values from previously submitted form, got from Laravel PHP old()
        return (typeof MFExt !== 'undefined') ? MFExt.getFormOld() : {};
      },
      warn: console.warn,
    },

    /* --------------------------------------------------------------------------*
     | Errors from previously submitted form, got from Laravel PHP $errors
     * ---------------------------------------------------------------------- */
    ErrorBag: {
      all: {},
      set: (PHP_Errors) => {
        MF.ErrorBag.all = PHP_Errors ? MF.ext.parseToJSON( PHP_Errors ) : {};
      },
      count: () => Object.keys( MF.ErrorBag.all ).length,
      hasAny: () => MF.ErrorBag.count() > 0,
      hasNone: () => ! MF.ErrorBag.hasAny(),
      get: (key) => {
        if(MF.ErrorBag.hasNone()){
          MF.ErrorBag.set( MF.ext.getErrorBag() );
        }
        return key ? Utils.tryCatch(() => MF.ErrorBag.all[key]) : MF.ErrorBag.all;
      },
      empty: () => {
        MF.ErrorBag.all = {};
      }
    },

    /* -----------------------------------------------------------------------------*
     | Old values from previously submitted form, got from Laravel PHP old()
     * ------------------------------------------------------------------------ */
    FormOld: {
      all: {},
      set: (formOld) => {
        MF.FormOld.all = formOld ? MF.ext.parseToJSON( formOld ) : {};
      },
      get: (key) => {
        if( ! Object.keys( MF.FormOld.all ).length){
          MF.FormOld.set( MF.ext.getFormOld() );
        }
        return key ? Utils.tryCatch(() => MF.FormOld.all[key]) : MF.FormOld.all ;
      }
    },

    /* ---------------------------------------------- *
     | Default Properties
     * ------------------------------------------- */
    ID: null,
    UI: null,
    Form: null,
    defaultParams: ['route', 'action'],

    initialized: false,
    options: {},

    /* ---------------------------------------------- *
     | Default MF Prefix
     * ------------------------------------------- */
    _prefix: '_mfa',

    /* -------------------------------------------------------------------------------- *
     | 'textNodes' (elements of class 'mf-text-') are used as text placeholders
     | in modal templates that can be used for more than one similar actions
     | E.g for approve|decline forms,
     | textNodes.action - sets buttonText => [Approve] or [Decline]
     * ---------------------------------------------------------------------------- *
     | 'fields' contain {key: value} pairs with which the form input will be set
     * ------------------------------------------------------------------------- */
    Config: (action) => {
      const actions = {
        approve: {
          textNodes: {
            title: () => 'Approve Application',
            // => Supplied params must have the property 'action'
            button: (action) =>MF.ext.Str.titleCase(action),
            action: (action) =>MF.ext.Str.titleCase(action),
            house: 'house',
            user: 'user',
          },
          fields: { action: 'assign' },
        },
        decline: {
          textNodes: {
            title: () => 'Decline Application',
            button: (action) =>MF.ext.Str.titleCase(action),
            action: (action) =>MF.ext.Str.titleCase(action),
            house: 'house',
            user: 'user',
          },
          fields: { action: 'assign' },
        },
        release: {
          textNodes: {
            title: () => 'Release Property',
            button: (action) =>MF.ext.Str.titleCase(action),
            action: (action) =>MF.ext.Str.titleCase(action),
            house: 'house',
            user: 'user',
          },
          fields: { action: 'release' },
        },
        'create': {
          // => Supplied params must have the property 'model'
          textNodes: {
            title: (model) =>MF.ext.Str.titleCase(model),
            button: (model) =>MF.ext.Str.titleCase(model),
          },
          fields: { _method: 'POST' },
        },
        'edit': {
          textNodes: {
            title: (model) =>MF.ext.Str.titleCase(model),
            button: (model) =>MF.ext.Str.titleCase(model),
          },
          fields: { _method: 'PUT' },
        },
        'delete': {
          textNodes: {
            title: (model) =>MF.ext.Str.titleCase(model),
            button: (model) =>MF.ext.Str.titleCase(model),
            action: (action) =>MF.ext.Str.titleCase(action),
            'model-label': (model) =>MF.ext.Str.titleCase(model),
            'model-name': (name) =>MF.ext.Str.titleCase(name),
            'cascade-info': (info) => {
              if( ! info){ MF.UI.find(`.mf-text-cascade-icon`).hide(); }
              return info;
            }
          },
          fields: { _method: 'DELETE' },
        },
      };

      return actions[ action ] || null;
    },

    /* --------------------------------------------------------------------
     | Called from the Modal index ('snippets.modal.index' partial)
     | Initializes the modal at load time
     * --------------------------------------------------------------- */
    init: (id) => {
      if(MF.initialized){ return; }

      MF.initialized = true;

      setTimeout(() => {
        MF.ID   = { modal: `${id}Modal`, form: `${id}Form`};
        MF.UI   = $('#' + MF.ID.modal);
        MF.Form = $('#' + MF.ID.form);

        // Add id to input fields that don't have. Needed in Form.values() collate at submit()
        MF.input().each(function (i,e) {
          if(e.id.trim() === ''){ e.id = e.name; }
        });

        // Attempt launching the modal if the php old() form data contains the key '_mfa'
        MF.relaunchModal();

        $(document).on({
          'hidden.bs.modal': function () { MF.resetRelaunchParams(); }
        }, MFA.UI);
      }, 300);
    },

    /* ------------------------------------------------------------------
     | Set additional options even after MF.init()
     | E.g 'listErrors'
     * ------------------------------------------------------------- */
    setOptions: (options) => {
      MF.options = options || {};
      MF.relaunchModal();
    },

    /* ------------------------------------------------------------------
     | String values of the default actions performed on a model instance
     * ------------------------------------------------------------- */
    modelActions: {
      create: {
        title: 'create', button: 'create',
      },
      edit: {
        title: 'edit', button: 'update',
      },
      'delete': {
        title: 'delete', button: 'delete',
      }
    },

    /* ------------------------------------------------------------------
     | Returns the JQuery id string of the registered modal object
     * ------------------------------------------------------------- */
    id: (id) => '#' + id,

    /* ------------------------------------------------------------------
     | Returns all Form inputs
     * ------------------------------------------------------------- */
    inputSelector: 'input, textarea, select',
    input: () => MF.Form.find( MF.inputSelector ),

    /* ------------------------------------------------------------------
     | Returns all Form inputs that are editable
     | Includes non-hidden input, text-area, select
     * ------------------------------------------------------------ */
    editableInputSelector: 'input[type!="hidden"], textarea, select',
    editableInput: () => MF.Form.find( MF.editableInputSelector ),

    /* -------------------------------------------------------------------------------
     | Sets the relaunch element id on the Modal Form, from the window.event
     * -------------------------------------------------------------------------- */
    relaunchBtnId: '',

    setRelaunchBtnId: () => {
      let relaunchBtnId = window.event ? window.event.target.id : MF.relaunchBtnId;

      if(relaunchBtnId){
        MF.Form.find( MF.id(MF._prefix) ).val( relaunchBtnId );
      }
    },

    resetRelaunchParams: () => {
      MF.relaunchBtnId = '';

      MF.clearErrors();
      MF.ErrorBag.empty();

      MF.Form.find('.invalid-feedback').addClass('d-none');
      MF.Form.find('.is-invalid').removeClass('is-invalid');
    },

    /* ------------------------------------------------------------------
     | Re-launches the Modal, e.g if there were validation errors.
     | If the php old() form data contains the key '_mfa', its
     | value is the required relaunchBtnId for this method
     * ------------------------------------------------------------- */
    relaunchModal: () => {
      let mfaId = MF.FormOld.get( MF._prefix );

      if(mfaId){
        $( MF.id( MF.relaunchBtnId = mfaId ) ).trigger('click');

        if(MF.options.hasOwnProperty('listErrors') && MF.options.listErrors === true){
          MF.handleErrors({ errors: MF.ErrorBag.get() });
        }
      }
    },

    /* -------------------------------------------------------------
     | Validate supplied text-params anf field-values
     * -------------------------------------------------------- */
    validateSuppliedDefault: (supplied) => {
      let valid = true;
      MF.defaultParams.forEach(param => {
        if(typeof supplied[ param ] !== 'string' || supplied[ param ] === ''){
          console.error(
            `Key '${param}' must be defined in supplied {params} and must not be empty`
          );
          valid = false;
        }
      });
      return  valid;
    },

    /* ---------------------------------------------------------------------
     | Checks if params is a Model and syncs it with default MF.Config
     * ----------------------------------------------------------------- */
    syncModelConfig: (params) => {
      // Add these properties that will be set on textNodes iteration
      if(params.hasOwnProperty('model')){
        // Add texts for modal-title and form-action-button
        let titleString = MF.modelActions[ params.action ].title;
        let buttonString = MF.modelActions[ params.action ].button;

        if( ! params.hasOwnProperty('title')){
          params.title = [titleString, params.model].join(' ');
        }
        if( ! params.hasOwnProperty('button')){
          params.button = [buttonString, params.model].join(' ');
        }

        // Add text labels for delete action modal
        if(titleString === MF.modelActions['delete'].title){
          params['model-label'] = params.model;
          params['model-name'] = params.fields['name'];

          // Add cascade labels if "cascade on delete" model name is supplied
          // E.g delete 'Category' may have cascade = 'Houses'
          if(params.hasOwnProperty('cascade')){
            let cascade =MF.ext.Str.titleCase(params['cascade']);
            if(cascade.trim()){
              let modelLabel =MF.ext.Str.titleCase(params['model-label']);
              params['cascade-info'] = `All ${cascade} under this ${modelLabel} will also be deleted`;
            }
          }
        }
      }
    },

    /* -------------------------------------------------------------
     | Loops through the config textNodes|fields and sets the
     | corresponding supplied texts|values
     * -------------------------------------------------------- */
    iterateConfig: (config, supplied, setter) => {
      Object.keys(config).forEach(key => {

        let suppliedHasValue = supplied.hasOwnProperty(key);
        let configIsFunction = typeof config[key] === 'function';

        let suppliedValue = null;

        switch(true){
          case suppliedHasValue :   { suppliedValue = supplied[key]; break; }
          case ! configIsFunction : { suppliedValue = config[key]; break; }
        }

        // Call the config function on the suppliedValue (e.g model name)
        if(configIsFunction){
          suppliedValue = config[key].call(undefined, suppliedValue);
        }

        setter.call(undefined, key, suppliedValue);
      });
    },

    /* -------------------------------------------------------------
     | Helper function for the different Actions
     * -------------------------------------------------------- */
    setModalUI: (params, values) => {
      // Ensure params includes defaults: 'route', 'action', etc)
      if( ! MF.validateSuppliedDefault(params)){
        return;
      }

      if(MF.relaunchBtnId){ values = MF.FormOld.get(); }

      MF.setRelaunchBtnId();
      MF.syncModelConfig(params);
      let config = MF.Config( params.action );

      // Set 'form action'
      MF.UI.find( MF.id(MF.ID.form) ).attr({'action': params.route});

      // Set texts in modal elements having 'mf-text-' named classes
      if(config.hasOwnProperty('textNodes')){
        MF.iterateConfig(config.textNodes, params, (key, value) => {
          MF.UI.find(`.mf-text-${key}`).text( value )
        });
      }

      // Set supplied/configured initial values on form inputs
      if(values && config.hasOwnProperty('fields')){
        MF.editableInput().each(function (i,e) {
          // Add all keys in 'values' into 'config.fields' and reset their values
          config.fields[ e.id ] = '';

          // Disable all editable input fields if action is DELETE
          // This is so the (now-hidden) 'required' fields won't cause unexpected behaviour
          $(this).prop('disabled', (params.action === MF.modelActions['delete'].title));
        });

        MF.iterateConfig(config.fields, values, (key, value) => {
          MF.Form.find('#'+key).val( value );
        });
      }

      // Hides all MF section and displays only the section for the current action
      MF.UI.find(`.mf-section`).hide();
      MF.UI.find(`.mf-section.mf-section-${params.action}`).show();

      return MF;
    },

    /* ---------------------------------------------------------------------
     | Actions: called from the active Web page
     | Handle the [click | submit] events from the [icons | buttons]
     * ---------------------------------------------------------------- */
    approve: (params) => {
      // Approve property tenancy application
      params.action = 'approve';
      MF.setModalUI(params, {action: params.action}).show();
    },
    decline: (params) => {
      // Decline property tenancy application
      params.action = 'decline';
      MF.setModalUI(params, {action: params.action}).show();
    },
    release: (params) => {
      // Release expired house from last occupant
      params.action = 'release';
      MF.setModalUI(params, {action: params.action}).show();
    },
    create: (params) => {
      // Create a new model
      params.action = 'create';
      MF.setModalUI(params, {}).show();
    },
    edit: (params) => {
      // Update existing model
      params.action = 'edit';
      MF.setModalUI(params, params.fields).show();
    },
    trash: (params) => {
      // Delete existing model
      params.action = 'delete';
      MF.setModalUI(params, {}).show();
    },

    /* -----------------------
     | Modal Utils
     * ------------------ */
    show: () => {
      MF.UI.find('.modal-header').addClass('bg-primary').find('h5, button').css({'color':'white'});
      MF.UI.find('.modal-footer').css({'background-color': 'rgba(0,0,0,.03)'});
      MF.UI.modal('show');
    },
    hide: () => {
      MF.UI.modal('hide');
    },
    reload: () => {
      window.location.reload();
    },
    addSpinner: (obj) => {
      MF.Spinner = obj;
    },

    /* ---------------------------
     | Form Handler Methods
     * ---------------------- */
    FormErrors: [],
    useAxios: true,
    handleErrors: (resp) => {
      MF.FormErrors = resp.hasOwnProperty('errors') ? resp.errors : [resp.message];
      MF.showErrors();
    },
    errorDiv: () => {
      let errorDiv = MF.UI.find('.form-errors');
      return [errorDiv, errorDiv.find('.form-error').first()];
    },
    showErrors: () => {
      let [errorDiv, errorSpan] = MF.errorDiv();
      let errors = Utils.Arr.flatten( Object.values(MF.FormErrors) ) || [];

      if(errorSpan){
        errors.forEach(error => {
          let errorSpanItem = errorSpan.clone();
          errorSpanItem.addClass('d-block').removeClass('d-none').find('.form-error-item').text(error);
          errorDiv.append( errorSpanItem );
        });
      }
    },
    clearErrors: () => {
      let [errorDiv, errorSpan] = MF.errorDiv();
      errorDiv.find('.form-error').not(':eq(0)').remove();
    },

    /* ------------------------------
     | Form Handler Ajax Setup
     * ------------------------- */
    ajaxSetup: () => {
      $.ajaxSetup({
        beforeSend: function () {
          // MF.Spinner.show();
        },
        dataType: 'json',
        error: function (xhr, status, error) {
          MF.handleErrors( xhr.responseJSON );
        },
        complete: function (xhr, status) {
          // MF.Spinner.hide();
        }
      });
    },

    /* --------------------------
     | Submits a raw form
     * --------------------- */
    submit: (event) => {
      MF.clearErrors();

      let form = $(event.target).parents('form');

      let actionUrl = form.attr('action');

      let formValues = MF.values(form);

      if(MF.useAxios){
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
      }
      else {
        let formMethod = formValues['_method'] || form.attr('method');

        $.ajax({
          type: formMethod,
          data: formValues ,
          url: actionUrl,
          success: function(result, status, xhr){
            form[0].reset();
            MF.reload();
          },
        });
      }
    },

    /* -------------------------------
     | Collates form input values
     * -------------------------- */
    values: (formUI) => {
      let form = {};

      formUI.find('input, textarea, select').each(function (i, input) {
        let { name, id, type } = input;
        let value = '';

        let el = id ? $('#'+id) : $('[name="'+ name +']"');
        if( ! id){ el.attr('id', name); }
        // console.log(name, id, type, el.attr('id'), el.attr('value'));

        if( el.hasClass('input-currency')){
          el.val(function(){
            return MF.Utils.String.asNumber( $(this).val() );
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
    },

    // === MF ends ===
  };


  /* --------------------------------
   | P U B L I C   A P I
   * --------------------------- */
  return {
    init: MF.init,
    setOptions: MF.setOptions,
    approve: MF.approve,
    decline: MF.decline,
    release: MF.release,
    create: MF.create,
    edit: MF.edit,
    trash: MF.trash
  };

})();

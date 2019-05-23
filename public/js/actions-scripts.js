/* ---------------------------------------------------------------
 | A C T I O N S   S C R I P T S
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
        fields: { assign: 'assign' },
      },
      decline: {
        title: () => 'Decline Application',
        params: {
          action: (action) => Str.titleCase(action), house: 'house', user: 'user'
        },
        fields: { assign: 'assign' },
      },
      release: {
        title: () => 'Release Property',
        params: {
          action: (action) => Str.titleCase(action), house: 'house', user: 'user'
        },
        fields: { release: 'release' },
      },
    };
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
        console.error(`Key '${param}' must be defined in supplied {params} and must not be empty`);
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
    MFA.setAction(params, {assign: params.action}).show();
  };
  MFA.decline = (params) => {
    // Decline property tenancy application
    params.action = 'decline';
    MFA.setAction(params, {assign: params.action}).show();
  };
  MFA.release = (params) => {
    // Release expired house from last occupant
    params.action = 'release';
    MFA.setAction(params, null).show();
  };

  /* -----------------------
   | Shows the modal
   * ------------------ */
  MFA.show = () => {
    MFA.UI.find('.modal-header').addClass('bg-primary').find('h5, button').css({'color':'white'});
    MFA.UI.find('.modal-footer').css({'background-color': 'rgba(0,0,0,.03)'});
    MFA.UI.modal('show');
  };
}, 300);

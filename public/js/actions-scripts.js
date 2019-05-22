/* ----------------*
 * Actions Scripts */

let MFA = {};

setTimeout(() => {
  MFA.UI =  $('#approvalModal');
  MFA.Form =  $('#approvalForm');
  MFA.setAction = (params, action) => {
    let {title, name, route} = params;
    MFA.UI.find('#approvalForm').attr({'action': route});
    MFA.UI.find('.modal-title').text(Str.titleCase(action) + ' Application');

    MFA.Form.find('#assign').val(action);
    MFA.Form.find('.action-name').text(Str.titleCase(action));
    MFA.Form.find('.action-user').text(Str.titleCase(name));
    MFA.Form.find('.action-house').text(Str.titleCase(title));
    return MFA;
  };
  MFA.approve = (params) => {
    MFA.setAction(params, 'approve').show();
  };
  MFA.decline = (params) => {
    MFA.setAction(params, 'decline').show();
  };
  MFA.relieve = (params) => {
    MFA.setAction(params, 'relieve').show();
  };
  MFA.show = () => {
    MFA.UI.find('.modal-header').addClass('bg-primary');
    MFA.UI.find('.modal-header').find('h5, button').css({'color':'white'});
    MFA.UI.find('.modal-footer').css({'background-color': 'rgba(0,0,0,.03)'});
    MFA.UI.modal('show');
  };
}, 300);

var KSE = KSE || {};

KSE.Login = function() {
    var init = function() {
        var panelSwitcher = $('.panel-switcher'),
            loginPanel = $('.login-panel'),
            forgottenPanel = $('.forgotten-panel'),
            emailField = $('#email');

        panelSwitcher.on('click', function (e) {
            if (loginPanel.is(':visible')) {
                loginPanel.hide();
                forgottenPanel.show();
            } else {
                loginPanel.show();
                forgottenPanel.hide();
            }
        });
    }

    return {
        init: init
    }
};
var KSE = KSE || {};

KSE.Login = function() {
    var panelSwitcher = $('.panel-switcher'),
        loginPanel = $('.login-panel'),
        forgottenPanel = $('.forgotten-panel'),
        loginForm = $('#login-form'),
        forgottenForm = $('#forgotten-form');

    var init = function() {

        panelSwitcher.on('click', function (e) {
            if (loginPanel.is(':visible')) {
                loginPanel.hide();
                forgottenPanel.show();
            } else {
                loginPanel.show();
                forgottenPanel.hide();
            }
        });

        loginForm.on('submit', submitLogin);
        forgottenForm.on('submit', submitForgottenPass);
    }

    var submitLogin = function (e) {
        e.preventDefault();

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.login,
            data: loginForm.serialize()
        }).done(function(data, textStatus, jqXHR) {
            if (data.success) {
                window.location.href = '/foglalas.php';
            } else {
                KSE.formErrorHandler(loginForm, data.errors);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var submitForgottenPass = function (e) {
        e.preventDefault();

        forgottenPanel.find('.alert').remove();

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.forgotPassword,
            data: forgottenForm.serialize()
        }).done(function(data, textStatus, jqXHR) {
            if (data.success) {
                forgottenPanel.find('.panel-body')
                    .append('<div class="alert alert-success" role="alert">\
                            <p>A megadott címre elküldük az új jelszó beállításához szükséges információkat.</p>\
                        </div>');
            } else {
                KSE.formErrorHandler(forgottenForm, data.errors);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    return {
        init: init
    }
};
var KSE = KSE || {};

KSE.Config = {};
KSE.Config.urlPrefix = '';
KSE.Config.urls = {
    dailyData: KSE.Config.urlPrefix + '/api/dailyData.php',
    reserve: KSE.Config.urlPrefix + '/api/reserve.php',
    reservations: KSE.Config.urlPrefix + '/api/reservations.php',
    deleteReservation: KSE.Config.urlPrefix + '/api/deleteReservation.php',
    removeReservation: KSE.Config.urlPrefix + '/api/removeReservation.php',
    reg: KSE.Config.urlPrefix + '/api/reg.php',
    login: KSE.Config.urlPrefix + '/api/login.php',
    logout: KSE.Config.urlPrefix + '/api/logout.php',
    forgotPassword: KSE.Config.urlPrefix + '/api/forgotPassword.php',
    profile: KSE.Config.urlPrefix + '/api/profile.php',
    changePassword: KSE.Config.urlPrefix + '/api/changePassword.php',
    basket: KSE.Config.urlPrefix + '/api/basket.php',
    checkout: KSE.Config.urlPrefix + '/api/checkout.php',
    activate: KSE.Config.urlPrefix + '/api/activate.php',
}

KSE.Notification = {
    msgWrapper: $('#notification-wrapper'),

    show: function (type, message, dismissable) {
        var msgEl = $('<div class="alert alert-' + type + '" role="alert"/>'),
            msgContent = $('<div class="msg-content clearfix"/>');

        if (dismissable) {
            msgEl.addClass('alert-dismissable');
            msgEl.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
        }

        msgEl.append(msgContent.html(message));
        this.msgWrapper.append(msgEl);
    },

    clear: function () {
        this.msgWrapper.empty();
    }
}

KSE.Spinner = {
    spinnerWrapper: $('#spinner-wrapper'),

    show: function () {
        this.spinnerWrapper.fadeIn('fast');
    },

    hide: function () {
        this.spinnerWrapper.fadeOut('fast');
    }
}

KSE.clearFormErrors = function (form) {
    form.find('.has-error').removeClass('has-error');
    form.find('label.text-danger').removeClass('text-danger');
    form.find('.help-block.error').remove();
}

KSE.formErrorHandler = function (form, errors) {
    KSE.clearFormErrors(form);

    _.each(errors, function (field, index) {
        var fieldEl = form.find('[name=' + index + ']'),
            fieldGroup = fieldEl.closest('.form-group'),
            errorMsg = '<p class="help-block text-right error">';

        fieldGroup.addClass('has-error');
        fieldGroup.find('label').addClass('text-danger');

        if (typeof field == 'object') {
            _.each(field, function (msg, index) {
                if (index !== 0) {
                    errorMsg += '<br>';
                }
                errorMsg += msg;
            });
        } else {
            errorMsg += field;
        }


        errorMsg += '</p>';

        fieldGroup.append(errorMsg);
    });
}

KSE.getURLParameter = function (param) {
    var url = window.location.search.substring(1);
    var urlVariables = url.split('&');
    for (var i = 0; i < urlVariables.length; i++) {
        var paramName = urlVariables[i].split('=');
        if (paramName[0] == param) {
            return paramName[1];
        }
    }
}

$(function() {
    moment.locale('hu');
    numeral.language('hu');

    $('[data-toggle="tooltip"]').tooltip({html: true});

    $('#logout-btn').on('click', function (e) {
        e.preventDefault();

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            data: {
                logout: true
            },
            url: KSE.Config.urls.logout
        }).done(function(data, textStatus, jqXHR) {
            window.location.href = '/belepes.php';
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    })

    KSE.profileDataPromise = new $.Deferred();

    $.ajax({
        dataType: 'json',
        method: 'POST',
        url: KSE.Config.urls.profile,
    }).done(function(data, textStatus, jqXHR) {
        if (data.success) {
            KSE.profileData = data.user;
            $('.user-name-data').show();
        } else {
            KSE.profileData = null;
            $('.user-name-data').hide();
        }
        if ($('#userFullNameDisplay').length) {
            $('#userFullNameDisplay').text(KSE.profileData.full_name);
        }
        if ($('#userNameDisplay').length) {
            $('#userNameDisplay').text(KSE.profileData.username);
        }

        KSE.profileDataPromise.resolve();
    }).fail(function(jqXHR, textStatus, errorThrown) {
        KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
    });

    if ($('.page-reservation').length) {
        var reservation = KSE.Reservation();
        reservation.init();
    }
    if ($('.page-my-reservations').length) {
        var reservations = KSE.Reservations();
        reservations.init();
    }
    if ($('.page-checkout').length) {
        var checkout = KSE.Checkout();
        checkout.init();
    }
    if ($('.page-login').length) {
        var login = KSE.Login();
        login.init();
    }
    if ($('.page-profile').length) {
        var profile = KSE.Profile();
        profile.init();
    }
    if ($('.page-activation').length) {
        var activation = KSE.Activation();
        activation.init();
    }
});
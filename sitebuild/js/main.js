var KSE = KSE || {};

KSE.Config = {};
KSE.Config.urlPrefix = 'http://localhost/home/kispestse.hu/foglalas/sitebuild';
KSE.Config.urls = {
    dailyData: KSE.Config.urlPrefix + '/api/dailyData.php',
    reserve: KSE.Config.urlPrefix + '/api/reserve.php',
    deleteReservation: KSE.Config.urlPrefix + '/api/deleteReservation.php'
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


$(function() {
    moment.locale('hu');
    numeral.language('hu');

    $('[data-toggle="tooltip"]').tooltip({html: true});

    if ($('.page-reservation').length) {
        var reservation = KSE.Reservation();
        reservation.init();
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
});
var KSE = KSE || {};

KSE.Profile = function() {
    var regForm = $('#reg-form'),
        profileForm = $('#profile-form'),
        newPassForm = $('#new-pass-form'),
        datePicker = $('.date'),
        isSmallScreen = false;


    var init = function() {
        var isSmallScreen = window.innerWidth < 768;

        if (isSmallScreen) {
            datePicker.replaceWith('<input type="date" class="form-control" name="birthday"/>')
        } else {
            datePicker.datepicker({
                format: 'yyyy-mm-dd',
                language: 'hu',
            });
        }

        if (isRegistration()) {
            getNewCaptcha();
            regForm.on('submit', submitRegistration);
            regForm.on('click', '#btn-new-captcha', getNewCaptcha);
        } else {
            profileForm.on('submit', submitProfileMod);
            newPassForm.on('submit', submitNewPass);
            KSE.profileDataPromise.done(function () {
                fillProfileForm();
            })
        }
    }

    var isRegistration = function () {
        return (regForm.length > 0);
    }

    var getNewCaptcha = function () {
        var captcha = $('#captcha'),
            newCaptcha = $('#btn-new-captha');

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.reg
        }).done(function(data, textStatus, jqXHR) {
            captcha.attr('src', data.captcha);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var submitRegistration = function (e) {
        e.preventDefault();

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.reg,
            data: regForm.serialize()
        }).done(function(data, textStatus, jqXHR) {
            if (data.success) {
                window.location.href = '/confirm.php';
            } else {
                KSE.formErrorHandler(regForm, data.errors);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var fillProfileForm = function () {
        var fields = profileForm.find(':input');

        _.each(fields, function (field) {
            var field;

            if (KSE.profileData[field.name]) {
                $field = $(field);

                if ($field.closest('.date').length) {
                    datePicker.datepicker('setDate', KSE.profileData[field.name]);
                } else {
                    $field.val(KSE.profileData[field.name]);
                }
            }
        })
    }

    var submitProfileMod = function (e) {
        e.preventDefault();

        KSE.clearFormErrors(profileForm);

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.profile,
            data: profileForm.serialize()
        }).done(function(data, textStatus, jqXHR) {
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else if (data.success) {
                KSE.Notification.show('success', '<h4>OK</h4><p>Az adatmódosításokat sikeresen elmentettük.</p>', true);
            } else {
                KSE.formErrorHandler(profileForm, data.errors);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var submitNewPass = function (e) {
        e.preventDefault();

        KSE.clearFormErrors(newPassForm);

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'POST',
            url: KSE.Config.urls.changePassword,
            data: newPassForm.serialize()
        }).done(function(data, textStatus, jqXHR) {
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else if (data.success) {
                KSE.Notification.show('success', '<h4>OK</h4><p>Sikeres jelszó módosítás.</p>', true);
            } else {
                KSE.formErrorHandler(newPassForm, data.errors);
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
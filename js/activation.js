var KSE = KSE || {};

KSE.Activation = function() {
    var wrapper = $('#activation-wrapper');

    var confirmTpl = _.template('<h2>Köszönjük regisztrációját</h2> \
            <hr> \
            <p>A felhasználó aktiválása megtörtént.</p>');

    var init = function() {
        var errorMsg = '',
            key = KSE.getURLParameter('key');

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'GET',
            url: KSE.Config.urls.activate,
            data: {
                key: key
            }
        }).done(function(data, textStatus, jqXHR) {
            if (data.success) {
                wrapper.html(confirmTpl());
            } else {
                _.each(data.errors, function (error, index) {
                    if (index > 0) {
                        errorMsg += '<br>';
                    }
                    errorMsg += error;
                });
                KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorMsg + '</p>', true);
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
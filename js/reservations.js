var KSE = KSE || {};

KSE.Reservations = function() {
    var tableWrapper = $('#my-reservations-table-wrapper');

    var listTpl = _.template('<table class="mini-intervals-table table table-striped">\
            <% if (reservations.length < 1) { %> \
                <tr><td class="text-center">Jelenleg nincs foglalásod</td></tr> \
            <% } %> \
            <% _.each(reservations, function (item, index) { %> \
                <tr> \
                    <td> \
                        <span><strong><%= moment(item.timeunit.date).format("YYYY. MMMM D.") %></strong></span> \
                        <span><strong><%= item.timeunit.interval %></strong></span> \
                        <span><%= item.court.title %> (<%= item.court.subtitle %>)</span> \
                    </td> \
                    <td> \
                        <% if (item.cancelable) { %> \
                            <button type="button" class="btn btn-danger btn-xs delete-btn pull-right" data-reservationid="<%= item.id %>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Törlés</button> \
                        <% } %> \
                    </td> \
                </tr>\
            <% }) %> \
        </table>');

    var initTable = function () {
        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            method: 'GET',
            url: KSE.Config.urls.reservations,
        }).done(function(data, textStatus, jqXHR) {
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else {
                var now = new Date();

                _.each(data.reservations, function (reservation, index) {
                    var startDate = reservation.timeunit.date + ' ' + reservation.timeunit.interval.split(' ')[0];
                    reservation.cancelable = moment(startDate).diff(now, 'days', true) > 1;
                });

                tableWrapper.html(listTpl(data));
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });

        tableWrapper.html()
    }

    var deleteReservation = function (e) {
        var button = $(e.target);

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.deleteReservation,
            data: {
                id: button.attr('data-reservationid')
            }
        }).done(function(data, textStatus, jqXHR) {
            var errorMsg = '';
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else if (data.success) {
                KSE.Notification.show('success', '<h4>OK</h4><p>Foglalás törölve</p>', true);
                button.closest('tr').fadeOut();
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

    var init = function() {
        initTable();
        tableWrapper.on('click', '.delete-btn', deleteReservation);
    }

    return {
        init: init
    }
};
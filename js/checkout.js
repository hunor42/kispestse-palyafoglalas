var KSE = KSE || {};

KSE.Checkout = function() {
    var checkoutTableWrapper = $('#checkout-table-wrapper'),
        basketItemcount = $('#basket-itemcount'),
        basketValue = $('#basket-value'),
        balanceValue = $('#balance-value');

    var checkoutTableTpl = _.template('<table class="table table-striped"> \
            <% if (basketItems < 1) { %> \
                <tr><td class="text-center">A kosara üres</td></tr> \
            <% } %> \
            <% _.each(basketItemsDetails, function (item, index) { %> \
                <tr> \
                    <td> \
                        <span><strong><%= moment(item.date).format("YYYY. MMMM D.") %></strong></span> \
                        <span><strong><%= item.interval %></strong></span> \
                        <span><%= item.court.title %> (<%= item.court.subtitle %>)</span> \
                        <button type="button" class="btn btn-danger btn-xs delete-btn pull-right" data-reservationid="<%= item.reservationId %>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Törlés</button> \
                    </td> \
                </tr>\
            <% }) %> \
        </table>');

    var deleteItem = function (e) {
        var button = $(e.target),
            rowToDelete = button.closest('tr'),
            table = button.closest('table');

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.removeReservation,
            data: {
                id: button.attr('data-reservationid')
            }
        }).done(function(data, textStatus, jqXHR) {
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else {
                rowToDelete.fadeOut('fast', function () {
                    rowToDelete.remove();
                    if (table.find('tr').length == 0) {
                        window.location.href = 'foglalas.php';
                    }
                });
                basketItemcount.text(data.basketItems);
                basketValue.text(numeral(data.basketValue).format());
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var updateData = function (data) {
        basketItemcount.text(data.basketItems);
        basketValue.text(numeral(data.basketValue).format());
        if (KSE.profileData) {
            balanceValue.text(numeral(KSE.profileData.balance).format());
        }
        checkoutTableWrapper.html(checkoutTableTpl(data));
    }

    var finishCheckout = function (e) {
        var button = $(e.target);

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.checkout,
            data: {
                id: button.attr('data-reservationid')
            }
        }).done(function(data, textStatus, jqXHR) {
            var errorMsg = '';
            if (data.redirectToLogin) {
                window.location.href = '/belepes.php';
            } else if (data.success) {
                KSE.Notification.show('success', '<h4>Foglalás véglegesítve</h4><p>A foglalást véglegesítésítettük, a végösszeget levontuk az egyenlegből. Aktuális foglalásait megtekintheti az alábbi gombra kattintva.</p><a href="foglalasaim.php" class="btn btn-warning" >Foglalásaim</a>', true);
                $('#checkout-content, .sidebar').fadeOut();
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
        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.basket,
        }).done(function(data, textStatus, jqXHR) {
            updateData(data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        });

        checkoutTableWrapper.on('click.delete', '.delete-btn', deleteItem);
        $('.checkout-btn').on('click.checkout', finishCheckout);
    }

    return {
        init: init
    }
};
var KSE = KSE || {};

KSE.Checkout = function() {
    var checkoutTableWrapper = $('#checkout-table-wrapper'),
        basketItemcount = $('#basket-itemcount'),
        basketValue = $('#basket-value');

    var deleteItem = function (e) {
        var button = $(e.target),
            rowToDelete = button.closest('tr'),
            table = button.closest('table');

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.deleteReservation,
            data: {
                id: button.attr('data-reservationid')
            }
        }).done(function(data, textStatus, jqXHR) {
            rowToDelete.fadeOut('fast', function () {
                rowToDelete.remove();
                if (table.find('tr').length == 0) {
                    window.location.href = 'foglalas.php';
                }
            });
            basketItemcount.text(data.basketItems);
            basketValue.text(numeral(data.basketValue).format());
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var init = function() {
        checkoutTableWrapper.on('click.delete', '.delete-btn', deleteItem);
    }

    return {
        init: init
    }
};
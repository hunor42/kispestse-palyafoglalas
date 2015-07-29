var KSE = KSE || {};

KSE.Reservation = function() {
    var selectedDateWrapper = $('#selected-date'),
        availabilityTableWrapper = $('#availability-table-wrapper'),
        basketItemcount = $('#basket-itemcount'),
        basketValue = $('#basket-value'),
        balanceValue = $('#balance-value'),
        isSmallScreen = false,
        initOpenedItem = availabilityTableWrapper.attr('data-opened-initially') || '';

    var availabilityTableTpl = _.template('<div class="panel-group" id="availability" role="tablist" aria-multiselectable="true"> \
            <% _.each(fields, function (field, index) { %> \
                <div class="panel panel-default"> \
                    <div class="panel-heading" role="tab" id="ph-<%= field.id %>"  class="collapsed" data-toggle="collapse" data-parent="#availability" href="#<%= field.id %>" aria-controls="<%= field.id %>" aria-expanded="false"> \
                        <h4 class="panel-title"> \
                            <%= field.title %> \
                            <span class="small-label pull-right"><%= field.subtitle %></span> \
                        </h4> \
                        <table class="mini-intervals-table">\
                            <tr> \
                                <% _.each(field.intervals, function (interval, index) { %> \
                                    <% for (var i=0; i<interval.intervalBlocks; i++) { %> \
                                        <td>&nbsp;</td> \
                                    <% }%> \
                                <% }) %> \
                            </tr> \
                            <tr> \
                                <% _.each(field.intervals, function (interval, index) { %> \
                                    <% if (interval.status == "reservable") { %> \
                                        <td colspan="<%= interval.intervalBlocks %>" class="reservable bg-success"><span class="interval" data-toggle="tooltip" data-placement="top" data-container="#availability" title="<strong><%= interval.interval%></strong><br>Foglalható<br>Ár: <%= interval.price %> Ft"><%= interval.interval.substring(0,5) %></span></td> \
                                    <% } else if (interval.status == "reserved") { %> \
                                        <td colspan="<%= interval.intervalBlocks %>" class="reserved bg-warning"><span class="interval" data-toggle="tooltip" data-placement="top" data-container="#availability" title="<strong><%= interval.interval%></strong><br>Foglalt<br>Foglalta: <%= interval.reservedBy %>"><%= interval.interval.substring(0,5) %></span></td> \
                                    <% } else { %> \
                                        <td colspan="<%= interval.intervalBlocks %>" class="unavailable bg-danger"><span class="interval" data-toggle="tooltip" data-placement="top" data-container="#availability" title="<strong><%= interval.interval%></strong><br>Nem foglalható<br><%= interval.message %>"><%= interval.interval.substring(0,5) %></span></td> \
                                    <% }%> \
                                <% }) %> \
                            </tr> \
                        </table> \
                    </div> \
                    <div id="<%= field.id %>" class="panel-collapse collapse <% if (initOpenedItem && initOpenedItem == field.id) { %> in<% } %>" role="tabpanel" aria-labelledby="ph-<%= field.id %>"> \
                        <div class="panel-body"> \
                            <table class="table table-striped">\
                                <% _.each(field.intervals, function (interval, index) { %> \
                                    <tr> \
                                        <td class="interval"><span class="glyphicon glyphicon-time" aria-hidden="true"></span><%= interval.interval %></td> \
                                        <td> \
                                            <% if (interval.status == "reservable") { %> \
                                                <% if (!interval.due) { %> \
                                                    <span class="label label-success">Foglalható</span> \
                                                <% } else { %> \
                                                    <span class="label label-info">Lejárt</span> \
                                                <% }%> \
                                                <span class="interval-price meta-data"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span><% if (interval.orig_price && interval.price != interval.orig_price) { %><span class="orig-price"><%= interval.orig_price %> Ft</span><% } %><span class="actual-price"><%= interval.price %> Ft</span></span> \
                                                <% if (!interval.due) { %> \
                                                    <p class="text-right"> \
                                                        <button type="button" class="btn btn-success btn-xs reserve-btn" data-reservationid="<%= interval.reservationId %>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Foglalás</button> \
                                                    </p>\
                                                <% }%> \
                                            <% } else if (interval.status == "reserved") { %> \
                                                <span class="label label-warning">Lefoglalva</span> \
                                                <span class="reserved-by-user meta-data"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><%= interval.reservedBy %></span> \
                                            <% } else { %> \
                                                <span class="label label-danger">Nem foglalható</span> \
                                                <span class="interval-message meta-data"><%= interval.message %></span> \
                                            <% }%> \
                                        </td> \
                                    </tr> \
                                <% }) %> \
                            </table> \
                        </div> \
                    </div> \
                </div> \
            <% }) %> \
        </div>');

    var updateReservationTable = function (selectedDate, data) {
        selectedDateWrapper.text(moment(selectedDate).format('YYYY. MMMM D.'));
        data.initOpenedItem = initOpenedItem;
        availabilityTableWrapper.hide().html(availabilityTableTpl(data));
        $('#availability').collapse();
        $('[data-toggle="tooltip"]').tooltip({html: true});
        availabilityTableWrapper.show();
    }

    var changeDate = function(e) {
        var selectedDate;

        if (e.dates) {
            selectedDate = e.dates[0];
        } else {
            selectedDate = e.target.value;
        }

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.dailyData,
            data: {
                date: moment(selectedDate).format('YYYY-MM-DD'),
            }
        }).done(function(data, textStatus, jqXHR) {
            var now = new Date();

            _.each(data.fields, function (field, index) {
                _.each(field.intervals, function (interval, intervalIndex) {
                    var startDate = interval.date + ' ' + interval.interval.split(' ')[0];
                    interval.due = moment(startDate).diff(now, 'days', true) < 0;
                });
            })

            updateReservationTable(selectedDate, data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var reserve = function (e) {
        var button = $(e.target);

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.reserve,
            data: {
                id: button.attr('data-reservationid')
            }
        }).done(function(data, textStatus, jqXHR) {
            KSE.Notification.show('success', '<h4>Foglalás hozzáadva</h4><p>A foglalás véglegesítéséhez használja a Foglalásaim menüpontot vagy használja az alábbi gombot.</p><a href="fizetes.php" class="btn btn-warning" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Foglalás véglegesítése</a>', true);
            basketItemcount.text(data.basketItems);
            basketValue.text(numeral(data.basketValue).format());
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        }).always(function () {
            KSE.Spinner.hide();
        });
    }

    var init = function() {
        var today = new Date(),
            datePicker = $('.datepicker-wrapper');

        isSmallScreen = window.innerWidth < 768;

        if (isSmallScreen) {
            var dateInput = $('<input type="date" class="form-control"/>');

            dateInput.on('change', changeDate);

            dateInput.val(moment(today).format('YYYY-MM-DD')).trigger('change');
            dateInput.attr('min', moment(today).format('YYYY-MM-DD'));
            datePicker.html(dateInput);

        } else {
            datePicker.datepicker({
                format: 'yyyy-mm-dd',
                startDate: today,
                endDate: '+1y',
                todayBtn: 'linked',
                language: 'hu',
                keyboardNavigation: false,
                todayHighlight: true
            }).on('changeDate', changeDate);

            datePicker.datepicker('setDate', today);
        }


        KSE.profileDataPromise.done(function () {
            if (KSE.profileData) {
                balanceValue.text(numeral(KSE.profileData.balance).format());
            }
        })

        initBasketDisplay();

        availabilityTableWrapper.on('click.reserve', '.reserve-btn', reserve);
    }

    var initBasketDisplay = function () {
        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.basket,
        }).done(function(data, textStatus, jqXHR) {
            basketItemcount.text(data.basketItems);
            basketValue.text(numeral(data.basketValue).format());
        }).fail(function(jqXHR, textStatus, errorThrown) {
            KSE.Notification.show('danger', '<h4>Hiba</h4><p>' + errorThrown + '</p>', true);
        });
    }

    return {
        init: init
    }
};
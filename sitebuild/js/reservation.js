var KSE = KSE || {};

KSE.Reservation = function() {
    var selectedDateWrapper = $('#selected-date'),
        availabilityTableWrapper = $('#availability-table-wrapper'),
        basketItemcount = $('#basket-itemcount'),
        basketValue = $('#basket-value');

    var availabilityTableTpl = _.template('<div class="panel-group" id="availability" role="tablist" aria-multiselectable="true"> \
            <% _.each(fields, function (field, index) { %> \
                <div class="panel panel-default"> \
                    <div class="panel-heading" role="tab" id="ph-<%= field.id %>"> \
                        <h4 class="panel-title"  class="collapsed" data-toggle="collapse" data-parent="#availability" href="#<%= field.id %>" aria-controls="<%= field.id %>" aria-expanded="false"> \
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
                    <div id="<%= field.id %>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="ph-<%= field.id %>"> \
                        <div class="panel-body"> \
                            <table class="table table-striped">\
                                <% _.each(field.intervals, function (interval, index) { %> \
                                    <tr> \
                                        <td class="interval"><span class="glyphicon glyphicon-time" aria-hidden="true"></span><%= interval.interval %></td> \
                                        <td> \
                                            <% if (interval.status == "reservable") { %> \
                                                <span class="label label-success">Foglalható</span> \
                                                <span class="interval-price meta-data"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span><%= interval.price %> Ft</span> \
                                                <button type="button" class="btn btn-success btn-xs reserve-btn pull-right" data-reservationid="<%= interval.reservationId %>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Foglalás</button>\
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
        availabilityTableWrapper.hide().html(availabilityTableTpl(data));
        $('#availability').collapse();
        $('[data-toggle="tooltip"]').tooltip({html: true});
        availabilityTableWrapper.show();
    }

    var changeDate = function(e) {
        var selectedDate = e.dates[0];

        KSE.Spinner.show();

        $.ajax({
            dataType: 'json',
            url: KSE.Config.urls.dailyData,
            data: {
                date: selectedDate,
            }
        }).done(function(data, textStatus, jqXHR) {
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

        availabilityTableWrapper.on('click.reserve', '.reserve-btn', reserve);

    }

    return {
        init: init
    }
};
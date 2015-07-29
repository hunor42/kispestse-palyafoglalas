var KSE = KSE || {};

KSE.Profile = function() {
    var init = function() {
        var datePicker = $('.date');

        datePicker.datepicker({
            format: 'yyyy-mm-dd',
            language: 'hu',
        });

    }

    return {
        init: init
    }
};
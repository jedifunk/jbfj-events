jQuery(document).ready(function($) {
    $('.jDate').datepicker({
        dateFormat : 'yy-mm-dd'
    });
    $('.timepicker').timepicker({
	    'timeFormat': 'g:i A',
    });
});
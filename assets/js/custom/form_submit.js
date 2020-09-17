/*
$(document).ready(function() {
    $.extend($.validator.defaults, {
        submitHandler: function() {
            var forms = $('form.ajax');
            if (forms.length > 0) {
                if (forms.length > 1) {
                    alert('There are more than one "form.ajax" forms on this page. \n\
Only one ajax form is supported per page.');
                    return;
                }

                var data = $(this).serialize();
                var form = forms[0];
                $.blockUI();
                $.post(form.attr('action'), data, function(raw_response) {
                    $.unblockUI();
                    console.log(raw_response);
                    response = $.parseJSON(raw_response);
                    alert(response.msg);
                    if (response.forward) {
                        window.location = response.forward;
                    }
                }).fail(function(data) {
                    $.unblockUI();
                    alert('Unable to communicate with backend server. Please \n\
                                check your internet connection or try again \n\
                                later.');
                });

            }
        }
    });
});
*/
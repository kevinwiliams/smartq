"use strict";

$(document).ready(function() {

    var message = $('#message');

    //submit form data
    var form    = $('#setupForm');
    form.submit(function(e) {
        e.preventDefault();
        $.ajax({
            url     : form.attr('action'),                      
            type    : form.attr('method'),
            dataType: "json",   
            data    : form.serialize(),
            beforeSend:function(){
                message.html('<i class="fa fa-cog fa-spin"></i> Please Wait...').addClass('alert alert-success').removeClass('alert-danger'); ;
            },
            success: function(data){
                if (data.status === false) {
                  message.html(data.exception).addClass('alert alert-danger').removeClass('alert-success');
                } else if (data.status === true) {
                    document.location = '?step=complete';
                }
            },
            error:function()
            {
                message.html('<i class="fa fa-times"></i> Please Try Again').addClass('alert alert-danger');
            }
        }); 

    });

});

//window load
$(window).on('load', function() {
    var message = $('#completeMessage');
    var browse  = $('#browse');
    message.html('<i class="fa fa-cog fa-spin"></i> Please Wait...');
    setInterval(function () { 
        message.html('<i class="fa fa-check"></i> Install Successfully!');
        browse.removeClass('hidden');
    }, 10000);
});


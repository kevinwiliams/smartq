(function($) {

    // enable FastClick 
    FastClick.attach(document.body); 
    
    // select 2 dropdown  
    var $customSelects = $('select'); 
    $customSelects.select2({ 
        templateResult: function(result, container) {
            if (!result.id) {
                return result.text;
            }
            container.className += ' needsclick';
            return result.text;
        },
        placeholder: 'Select Option',
        allowClear: true
    });
    // add needsclick to all element of the select2 for supports in IOS-ANDROID
    $customSelects.each(function(index, el){
        $(el).data('select2').$container.find('*').addClass('needsclick');
    });

    // datepicker
    $(".datepicker").datepicker({
        dateFormat: "mm/dd/yy",
        changeMonth: true,
        changeYear: true
    });

    //datatable 
    $('.datatable').DataTable({ 
        responsive: true,  
        select    : true,
        pagingType: "full_numbers",
        lengthMenu: [[25, 50, 100, 150, 200, 500, -1], [25, 50, 100, 150, 200, 500, "All"]],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>><'row'<'col-sm-12't>><'row'<'col-sm-6'i><'col-sm-6'p>>", 
        buttons: [
            { extend:'copy', footer:true, text:'<i class="fa fa-copy"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
            { extend: 'print', footer:true, text:'<i class="fa fa-print"></i>', className:'btn-sm', exportOptions: { columns: ':visible',  modifier: { selected: null } }},  
            { extend: 'print', footer:true, text:'<i class="fa fa-print"></i>  Selected', className:'btn-sm', exportOptions:{columns: ':visible'}},  
            { extend:'excel',  footer:true, text:'<i class="fa fa-file-excel-o"></i>', className:'btn-sm',exportOptions:{columns:':visible'}},
            { extend:'pdf',  footer:true, text:'<i class="fa fa-file-pdf-o"></i>',  className:'btn-sm',exportOptions:{columns:':visible'}},
            { extend:'colvis', footer:true, text:'<i class="fa fa-eye"></i>',className:'btn-sm'} 
        ]
    }); 

    //back to top
    $('body').append('<div id="toTop" class="btn back-top"><span class="fa fa-arrow-up"></span></div>');
    $(window).on("scroll", function () {
        if ($(this).scrollTop() !== 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop').on("click", function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
    
}(jQuery));

 
//preloader
$(window).load(function() {
    $(".loader").fadeOut("slow");
});


//print a div
function printThis(content = "", reload = false) {

    if (content.length < 64 && $('#' + content).length > 0) { 
        // if element length less than 64 characters and is a ID
        content = $('head').html() + $('#' + content).clone().html();
    }  

    try {
        var ua = navigator.userAgent;

        if (/Chrome/i.test(ua)) {
            $('<iframe>', {
                name: 'myiframe',
                class: 'printFrame'
            })
            .appendTo('body')
            .contents().find('body')
            .append(content);

            setTimeout(() => { 
                window.frames['myiframe'].focus();
                window.frames['myiframe'].print();
                $('iframe.printFrame').remove();
            }, 200);

        } else if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile|CriOS/i.test(ua)) {     
           
            var win = window.open('about:blank', 'Token' + (new Date()).getTime());
            win.document.write(content); 

            setTimeout(function () {
                win.document.close();
                win.focus();
                win.print();
                win.close(); 
            }, 200);   

        } else {

            var originalContent = $('body').html();
            $('body').empty().html(content);
            window.print();
            $('body').html(originalContent);

        }

    } catch(e) {

        var originalContent = $('body').html();
        $('body').empty().html(content);
        window.print();
        $('body').html(originalContent);

    }

    if (reload) {
        history.go(0);
    }
}
 


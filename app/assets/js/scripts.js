var chart;
$(document).ready(function () {

    setTimeout(function(){$('#banner').slideDown(500, 'easeOutBack')}, 300);

    $('.sidebar').affix();

    $('ul.tests .btn').click(function(){
        $(this).button('loading');
    });

    //Thumbnails fix
    $('.row-fluid ul.thumbnails li.span6:nth-child(2n + 3)').css('margin-left','0px');
    $('.row-fluid ul.thumbnails li.span4:nth-child(3n + 4)').css('margin-left','0px');
    $('.row-fluid ul.thumbnails li.span3:nth-child(4n + 5)').css('margin-left','0px');


    $('.span9 .delete').click(function(e){
        if(!confirm('Do you really want to delete this?')){
            e.preventDefault();
            return false;
        }
        return true;
    });

    $('.compare-btn').click(function(e){
        e.preventDefault();
        $(this).remove();
        $('.compare-list').slideDown(400);
    });

});


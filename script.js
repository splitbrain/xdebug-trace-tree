$(function(){


    $('div.d, div.f').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('hide');
        e.preventDefault();
        e.stopPropagation();
    });

    $('span.params, span.return').click(function (e) {
        if (e.target !== this) return;
        $(this).toggleClass('short');
        e.preventDefault();
        e.stopPropagation();
    });

    $('#internal').change(function(){
        $('div.i').toggle();
    });

    $('span.name').click(function(e){
        if (e.target !== this) return;

        var $fn = $(this);
        var name = $(this).text();
        $("span.name:contains('"+name+"')").closest('div.f').toggleClass('hide');

        e.preventDefault();
        e.stopPropagation();
    });
});
$(function(){


    $('div.d').click(function (e) {
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
});
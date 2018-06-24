$(document).ready( () => {
    $(".js-shadow-hover").hover(function () {
        $(this).toggleClass("shadow");
    });

    $(function () {
        $('[data-tooltips="tooltip"]').tooltip()
    });
});
// script for hide and show filters in Drive page

$(document).ready(function(){
    $(".btn-category").click(function(){
        $(".category-filter").toggle();
    });
});
$(document).ready(function(){
    $(".btn-season").click(function(){
        $(".season-filter").toggle();
    });
});
$(document).ready(function(){
    $(".btn-price").click(function(){
        $(".price-filter").toggle();
    });
});
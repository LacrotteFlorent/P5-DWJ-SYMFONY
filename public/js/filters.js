// script for hide and show filters in Drive page

let arrayItems = {".btn-category":".category-filter", ".btn-season":".season-filter", ".btn-price":".price-filter"}
$(document).ready(function(){
    for(const container in arrayItems){
        $(container).click(function(){
            $(arrayItems[container]).toggle();
        });
    }
});
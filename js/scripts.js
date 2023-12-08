console.log('ça marche');


$(document).ready(function(){
    $('.btn-close').on('click', function(){
        $('.form-contact').toggle();
    });
});
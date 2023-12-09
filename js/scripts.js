
$(document).ready(function(){
    $('.btn-close').on('click', function(){
        $('.form-contact').toggle();
    });
});

// get the reference //
$(document).ready(function ($) {
    var referenceValue = custom_vars.reference_value;
    $('.ref p span input[type="text"].wpcf7-text').val(referenceValue);
});


// thumbnail on previous and next article //

    $(document).ready(function($) {
        $('.alignleft a, .alignright a').hover(function() {
            var postURL = $(this).attr('href');
            loadThumbnail(postURL);
        }, function() {
            removeThumbnail();
        });

        function loadThumbnail(postURL) {
            $.ajax({
                type: 'GET',
                url: postURL,
                dataType: 'html',
                success: function(response) {
                    var thumbnailURL;
                    thumbnailURL = $(response).find('.entry-content img').attr('src');

                    $('#thumbnail-container').html('<img width="100px" src="' + thumbnailURL + '" alt="Thumbnail">');
                }
            });
        }

        function removeThumbnail() {
            $('#thumbnail-container').empty();
        }
    });


$(document).ready(function(){
    $('.btn-close').on('click', function (event) {
        event.preventDefault();
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

// ajax in front-page to query photos //

$(function($){
    var page = 2; // The initial page number
    var loading = false;
    var $loadMoreButton = $('#load-more-button');
    var $photoContainer = $('#photo-container');

    $loadMoreButton.on('click', function (event) {
        
        event.preventDefault();

        if( ! loading ) {
            loading = true;
            console.log('works till here in button click');
            
            const ajaxurl = $(this).data('ajaxurl');

            console.log(ajaxurl);
            $.ajax({
                method: 'POST',
                url: ajaxurl,
                dataType: 'html', 
                data: {
                    action: 'load_more_photos',
                    page: page,
                },
                success: function(data) {
                    //$photoContainer.append(data);
                    //Append before the button
                    $loadMoreButton.parent().before(data);
                    
                    page++;
                    loading = false;
                    console.log(page);
                }
            });
            console.log('reached here');
        }
    });
});

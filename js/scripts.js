


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


function loadAll() {
    $(document).ready(function ($) {
        const ajaxurl = $(this).data('ajaxurl');
        $.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {
                action: 'loadAll',
                category: 'all'
            },
            success: function(data) {
                // Update the content area with the retrieved posts
                console.log('works until data success');
                $('#posts-container').html(data);
            }
        });
    })
        
    }

// filters //

//filter category //

$(document).ready(function($) {
    $('#category-filter').change(function() {
        var selectedCategory = $(this).val();
        const ajaxurl = $(this).data('ajaxurl');
        if (selectedCategory == 'all') {
            loadAll();
        }
        $.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {
                action: 'filter_posts_by_category',
                category: selectedCategory
            },
            success: function(data) {
                // Update the content area with the retrieved posts
                console.log('works until data success');
                $('#posts-container').html(data);
                closeBtn();
            }
        });
    });
});

// filter format //
$(document).ready(function($) {
    $('#filter-format').change(function() {
        var selectedFormat = $(this).val();
        const ajaxurl = $(this).data('ajaxurl');
        if (selectedFormat == 'all') {
            loadAll();
        }
        $.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {
                action: 'filter_posts_by_format',
                format: selectedFormat
            },
            success: function(data) {
                // Update the content area with the retrieved posts
                console.log('works until data success');
                $('#posts-container').html(data);
                closeBtn();
            }
        });
    });
});

//filter date //

jQuery(document).ready(function ($) {
    $('#filter-date').change(function () {
        var selectedDate = $(this).val();
        const ajaxurl = $(this).data('ajaxurl');
        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX handler
            data: {
                action: 'filter_posts',
                selectedDate: selectedDate,
            },
            success: function (response) {
                // Handle the response, e.g., update the content area with filtered posts
                $('#posts-container').html(response);
                closeBtn();
            },
        });
    });
});


//close btn of contact form  and get the reference //


$(document).ready(function(){
    $('btn-close').on('click', function (event) {
        event.preventDefault();
        $('.form-contact').toggle();
    });
    // get the reference //
    var referenceValue = custom_vars.reference_value;
    $('.ref p span input[type="text"].wpcf7-text').val(referenceValue);

});

function closeBtn() {
    $('footer .btn-close').on('click', function (event) {
        event.preventDefault();
        console.log('before toggle');
        $('footer .form-contact').toggle();
        console.log('after toggle');
    });
}

//overlay lightbox//

function toggleOverlay() {
    var overlay = document.getElementById('overlay');
    if (overlay.style.display === 'none' || overlay.style.display === '') {
        overlay.style.display = 'block';
    } else {
        overlay.style.display = 'none';
    }
}

    // Close the overlay when clicking outside the image
    $('#dynamic-overlay').on('click', function () {
        $(this).hide();
    });



    
    jQuery(document).ready(function ($) {
        var images;
        var currentImageIndex;
    
        $('.post-container').on('click', function (e) {
            e.preventDefault();
            currentImageIndex = $('.post-container').index($(this));
    
            const ajaxurl = $(this).data('ajaxurl');
    
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'load_overlay',
                },
                success: function (data) {
                    images = $(data).find('img');
                    if (images.length > 0) {
                        updateImage(images, currentImageIndex);
                        $('#dynamic-overlay').show();
                    }
                }
            });
        });
    
        // Previous button click
        $('#prev-btn').on('click', function (e) {
            e.preventDefault();
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateImage(images, currentImageIndex);
        });
    
        // Next button click
        $('#next-btn').on('click', function (e) {
            e.preventDefault();
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateImage(images, currentImageIndex);
        });
    
        // Close overlay
        $('.overlay-close').on('click', function () {
            $('#dynamic-overlay').hide();
        });
    
        // Prevent overlay from closing when clicking on previous and next buttons
        $('.overlay-prev, .overlay-next').on('click', function (e) {
            e.stopPropagation();
        });
    
        // Prevent overlay from closing when clicking outside of the image
        $('#dynamic-overlay').on('click', function (e) {
            if (!$(e.target).is('img')) {
                e.stopPropagation();
            }
        });

        
        // Close overlay when clicking outside of the overlay
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#dynamic-overlay').length) {
                $('#dynamic-overlay').hide();
            }
        });
    
        // Function to update the image source
        function updateImage(images, index) {
            var imageUrl = $(images[index]).attr('src');
            $('#dynamic-image').attr('src', imageUrl);
            $('#dynamic-overlay').show();
        }
    });

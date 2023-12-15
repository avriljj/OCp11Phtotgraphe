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
    

// // filters // load all  in filters //
function loadAll() {
    $(document).ready(function ($) {
        const ajaxurl = $('#posts-container').data('ajaxurl');
        $.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {
                action: 'loadAll',
            },
            success: function(data) {
                console.log('loadAll success');
                $('#posts-container').html(data);
            }
        });
    })
        
}

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
                console.log('works until data in category');
                $('#posts-container').html(data);
                
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
                console.log('works until data format');
                $('#posts-container').html(data);
                
            }
        });
    });
});

//filter date //

$(document).ready(function ($) {
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
                console.log('in the filter date');
                $('#posts-container').html(response);
                
            },
        });
    });
});



//close btn of contact form toggle and get the reference //

$(document).ready(function(){
    $('.btn-close').on('click', function (event) {
        event.preventDefault();
        $('.form-contact').toggle();
    });
    // get the reference //
    var referenceValue = custom_vars.reference_value;
    $('.ref p span input[type="text"].wpcf7-text').val(referenceValue);

});

// eye icon in photo_block when clicked //
$(document).on('click', '#open-post', function (e) {
    e.preventDefault();
    var postURL = $(this).closest('.overlay-link').attr('href');
    console.log(postURL);

    // Open the current post link
    window.location.href = postURL;
});


//overlay lightbox // ajax // //
// show overlay for the first changed 12 photos//
function show_overlay() {
    $(document).on('click', '.post-container', function (e) {
        e.preventDefault();
        console.log('click works in post-container');
        var currentImageIndex;
        var images;
        var overlayContainer;
    
        currentImageIndex = $('.post-container').index($(this));
                console.log(currentImageIndex);
        
            var overlayLink = $(this).find('.overlay-link');
               
                const ajaxurl = overlayLink.data('ajaxurl'); 
        
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'first_load_photos',
                    },
                    success: function (data) {
    
                        //console.log(data);
                        overlayContainer = $(data);
    
                        images = $(data).find('img:gt(0)');
                        console.log(images);
                        
                        
                        if (images.length > 0) {
                            images = $(data).find('img:gt(0)').filter(function() {
                                // Filter out images with no contents
                                return $.trim($(this).attr('src')) !== '';
                            });
    
                            var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                            var photoReference = currentOverlayLink.data('photo-reference');
                            var categoryName = currentOverlayLink.data('category-name');
                            console.log(images);
                            console.log('bonjour');
                            console.log('photoReference:', photoReference);
                            console.log('categoryName:', categoryName);
                            
                            
    
                           
                            updateImage(images, currentImageIndex, photoReference, categoryName);
                            $('#dynamic-overlay').show();
    
                        }
                        
                    }
                });
        
        
            //detail page //
            $('#detail-page').on('click', function (e) {
                e.preventDefault();
                var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                var postURL = currentOverlayLink.attr('href');
                console.log(postURL);
            // Open the current post link
                window.location.href = postURL;
            });
          
            
            // Previous button click
            $('#prev-btn').on('click', function (e) {
                e.preventDefault();
                currentImageIndex = (currentImageIndex - 1 + (images.length)) % (images.length);
                var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                var photoReference = prevOverlayLink.data('photo-reference');
                var categoryName = prevOverlayLink.data('category-name');
                updateImage(images, currentImageIndex, photoReference, categoryName);
            });
        
            // Next button click
            $('#next-btn').on('click', function (e) {
                e.preventDefault();
                currentImageIndex = (currentImageIndex + 1 + (images.length)) % (images.length);
                var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                var photoReference = prevOverlayLink.data('photo-reference');
                var categoryName = prevOverlayLink.data('category-name');
                updateImage(images, currentImageIndex, photoReference, categoryName);
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
            $('#dynamic-overlay').on('click', function (e) {
                if (!$(e.target).is('img')) {
                    $('#dynamic-overlay').hide();
                }
            });
        
            // Function to update the image source
            function updateImage(images, index, photoReference, categoryName) {
                var imageUrl = $(images[index]).attr('src');
                
                $('#dynamic-image').attr('src', imageUrl);
                $('.photo-reference').text(photoReference);
                $('.category-name').text(categoryName);
                $('#dynamic-overlay').show();
            }
        // Your code here
    });
}

//show_overlay();

function show_all_overlay() {
    $(document).on('click', '.post-container', function (e) {
        e.preventDefault();
        console.log('click works in post-container');
        var currentImageIndex;
        var images;
        var overlayContainer;
    
        currentImageIndex = $('.post-container').index($(this));
                console.log(currentImageIndex);
        
            var overlayLink = $(this).find('.overlay-link');
               
                const ajaxurl = overlayLink.data('ajaxurl'); 
        
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'get_all_next_photos',
                    },
                    success: function (data) {
    
                        overlayContainer = $(data);
    
                        images = $(data).find('img:gt(0)');
                        console.log(images);
                        
                        
                        if (images.length > 0) {
                            images = $(data).find('img:gt(0)').filter(function() {
                                // Filter out images with no contents
                                return $.trim($(this).attr('src')) !== '';
                            });
    
                            var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                            var photoReference = currentOverlayLink.data('photo-reference');
                            var categoryName = currentOverlayLink.data('category-name');
                            console.log(images);
                            console.log('bonjour');
                            console.log('photoReference:', photoReference);
                            console.log('categoryName:', categoryName);
                            
                            
    
                           
                            updateImage(images, currentImageIndex, photoReference, categoryName);
                            $('#dynamic-overlay').show();
    
                        }
                        
                    }
                });
        
        
            //detail page //
            $('#detail-page').on('click', function (e) {
                e.preventDefault();
                var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                var postURL = currentOverlayLink.attr('href');
                console.log(postURL);
            // Open the current post link
                window.location.href = postURL;
            });
          
            
            // Previous button click
            $('#prev-btn').on('click', function (e) {
                e.preventDefault();
                currentImageIndex = (currentImageIndex - 1 + (images.length)) % (images.length);
                var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                var photoReference = prevOverlayLink.data('photo-reference');
                var categoryName = prevOverlayLink.data('category-name');
                updateImage(images, currentImageIndex, photoReference, categoryName);
            });
        
            // Next button click
            $('#next-btn').on('click', function (e) {
                e.preventDefault();
                currentImageIndex = (currentImageIndex + 1 + (images.length)) % (images.length);
                var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                var photoReference = prevOverlayLink.data('photo-reference');
                var categoryName = prevOverlayLink.data('category-name');
                updateImage(images, currentImageIndex, photoReference, categoryName);
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
            $('#dynamic-overlay').on('click', function (e) {
                if (!$(e.target).is('img')) {
                    $('#dynamic-overlay').hide();
                }
            });
        
            // Function to update the image source
            function updateImage(images, index, photoReference, categoryName) {
                var imageUrl = $(images[index]).attr('src');
                
                $('#dynamic-image').attr('src', imageUrl);
                $('.photo-reference').text(photoReference);
                $('.category-name').text(categoryName);
                $('#dynamic-overlay').show();
            }
        // Your code here
    });
}

// load more photos when click on load more images button append newly loaded images //
$(document).ready(function ($) {
    var page = 2;
    $('#load-more-button').on('click', function (e) {
        e.preventDefault(); // Prevent the default behavior of the link

        const ajaxurl = $(this).data('ajaxurl');

        if (!ajaxurl) {
            console.error('Error: data-ajaxurl attribute not set on #load-more-button');
            return;
        }

        console.log('Button clicked, AJAX URL:', ajaxurl);

        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'load_more_photos',
                page: page,
            },
            success: function (response) {
                console.log('load the next photos success');
                $('#posts-container').append(response);
                show_all_overlay();
            },
            error: function (error) {
                console.error('Error');
            },
        });
    });
});


// ajax for first time load the photos 12 first //
$(document).ready(function (){
  
        const ajaxurl =  $('#posts-container').data('ajaxurl');

        if (!ajaxurl) {
            console.error('Error: data-ajaxurl attribute not set on #load-more-button');
            return;
        }

        console.log('Button clicked, AJAX URL:', ajaxurl);

        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'first_load_photos',
            },
            success: function (response) {
                console.log('Success: first_load_photos works');
                $('#posts-container').append(response);
            },
            error: function (error) {
                console.error('Error');
            },
        });
});

// load 2 images on single-photo page //


$(document).ready(function () {

    var postId = php_vars.postId;
    var termSlug = php_vars.termSlug;
  
        const ajaxurl = $('.related-images').data('ajaxurl');
    
        if (!ajaxurl) {
            console.error('Error: data-ajaxurl not set ine load2imagesRelated');
            return;
        }
    
        console.log(' AJAX URL:', ajaxurl);
    
        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'load_2images_Related',
                post_id: postId,
                termSlug: termSlug,

            },
            success: function (response) {
                console.log('Success: load_2images_Related works');
                $('.related-images').html(response);
            },
            error: function (error) {
                console.error('Error');
            },
        });
    });


function show_overlay_2images() {
    var postId = php_vars.postId;
    var termSlug = php_vars.termSlug;

        $(document).on('click', '.post-container', function (e) {
            e.preventDefault();
            console.log('click works in post-container');
            var currentImageIndex;
            var images2;
            var overlayContainer;
        
            currentImageIndex = $('.post-container').index($(this));
                    console.log(currentImageIndex);
            
                var overlayLink = $(this).find('.overlay-link');
                   
                    const ajaxurl = overlayLink.data('ajaxurl'); 
            
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'load_2images_Related',
                            post_id: postId,
                            termSlug: termSlug,
                        },
                        success: function (data) {
        
                            //console.log(data);
                            overlayContainer = $(data);
        
                            images2 = $(data).find('img:gt(0)');
                            console.log(images2);
                            
                            
                            if (images2.length > 0) {
                                images2 = $(data).find('img:gt(0)').filter(function() {
                                    // Filter out images with no contents
                                    return $.trim($(this).attr('src')) !== '';
                                });
        
                                var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                                var photoReference = currentOverlayLink.data('photo-reference');
                                var categoryName = currentOverlayLink.data('category-name');
                               // console.log(images2);
                                console.log('bonjour');
                                console.log('photoReference:', photoReference);
                                console.log('categoryName:', categoryName);
                                
                                
        
                               
                                updateImage(images2, currentImageIndex, photoReference, categoryName);
                                $('#dynamic-overlay').show();
        
                            }
                            
                        }
                    });
            
            
                //detail page //
                $('#detail-page').on('click', function (e) {
                    e.preventDefault();
                    var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                    var postURL = currentOverlayLink.attr('href');
                    console.log(postURL);
                // Open the current post link
                    window.location.href = postURL;
                });
              
                
                // Previous button click
                $('#prev-btn').on('click', function (e) {
                    e.preventDefault();
                    currentImageIndex = (currentImageIndex - 1 + (images2.length)) % (images2.length);
                    var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                    var photoReference = prevOverlayLink.data('photo-reference');
                    var categoryName = prevOverlayLink.data('category-name');
                    updateImage(images2, currentImageIndex, photoReference, categoryName);
                });
            
                // Next button click
                $('#next-btn').on('click', function (e) {
                    e.preventDefault();
                    currentImageIndex = (currentImageIndex + 1 + (images2.length)) % (images2.length);
                    var prevOverlayLink = $('.overlay-link').eq(currentImageIndex);
                    var photoReference = prevOverlayLink.data('photo-reference');
                    var categoryName = prevOverlayLink.data('category-name');
                    updateImage(images2, currentImageIndex, photoReference, categoryName);
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
                $('#dynamic-overlay').on('click', function (e) {
                    if (!$(e.target).is('img')) {
                        $('#dynamic-overlay').hide();
                    }
                });
            
                // Function to update the image source
                function updateImage(images2, index, photoReference, categoryName) {
                    var imageUrl = $(images2[index]).attr('src');
                    
                    $('#dynamic-image').attr('src', imageUrl);
                    $('.photo-reference').text(photoReference);
                    $('.category-name').text(categoryName);
                    $('#dynamic-overlay').show();
                }
            // Your code here
        });
    }
    
    show_overlay_2images();
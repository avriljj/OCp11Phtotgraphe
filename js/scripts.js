var dataTable;
var hasMorePhotos = true;


// form contact //
function openForm() {
    $(document).ready(function () {

        var referenceValue = custom_vars.reference_value;
        $('.ref p span input[type="text"].wpcf7-text').val(referenceValue);
    
        
        $('#menu-item-13').on('click', function (event) {
            event.preventDefault();
            console.log('clicked on the contact nav to open form');
            $('#contact-form-overlay').css('display', 'flex');
        });
        $('#contact-form-open').on('click', function (event) {
            //event.preventDefault();
            console.log('clicked on the button open form');
            $('#contact-form-overlay').css('display', 'flex');
        });
        $('#contact-form-overlay').on('click', function (e) {
            // Check if the clicked element is not a descendant of the form
            if (!$(e.target).closest('form').length) {
                $('#contact-form-overlay').hide();
            }
        });
        
    });
}

function openFormFrontPage() {
    $('#menu-item-13').on('click', function (event) {
        event.preventDefault();
        console.log('clicked on the contact nav to open form');
        $('#contact-form-overlay').css('display', 'flex');
    });
    $('#contact-form-overlay').on('click', function (e) {
        // Check if the clicked element is not a descendant of the form
        if (!$(e.target).closest('form').length) {
            $('#contact-form-overlay').hide();
        }
    });
}

// thumbnail on previous and next article //
$(document).ready(function($) {
        $('.alignleft a, .alignright a').hover(function() {
            var postURL = $(this).attr('href');
            console.log(postURL);
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
                    thumbnailURL = $(response).find('.photo-content img').attr('src');
                    console.log(thumbnailURL);

                    $('#thumbnail-container img').attr('src', thumbnailURL).attr('alt', 'Thumbnail').attr('height','100px').attr('width','100px');


                    //$('#thumbnail-container').html('<img width="100px" src="' + thumbnailURL + '" alt="Thumbnail">');
                }
            });
        }

        function removeThumbnail() {
            $('#thumbnail-container img').empty();
        }
});
    

//if user logged in change the height of the hamburger //
$(document).ready(function($) {
    // Check if the user is logged in
    if (loggedInStatus.loggedIn) {
        // Apply CSS styles if logged in
        $('.icons-header').css({
            'display': 'block',
            'position': 'absolute',
            'top': '60px',
            'right': '5%'
        });
       
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

                hasMorePhotos = false;
                $('#load-more-button').toggle(hasMorePhotos);
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
            success: function (data) {
                hasMorePhotos = false;
                $('#load-more-button').toggle(hasMorePhotos);
                // Update the content area with the retrieved posts
                console.log('works until data in category');
                $('#posts-container').html(data);
                dataTable = $('#posts-container').html(data);
                show_all_overlay();
                
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
            success: function (data) {
                hasMorePhotos = false;
                $('#load-more-button').toggle(hasMorePhotos);
                // Update the content area with the retrieved posts
                console.log('works until data format');
                $('#posts-container').html(data);
                dataTable = $('#posts-container').html(data);
                show_all_overlay();
                
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
            url: ajaxurl, 
            data: {
                action: 'filter_posts',
                selectedDate: selectedDate,
            },
            success: function (response) {
                hasMorePhotos = false;
                $('#load-more-button').toggle(hasMorePhotos);
                
                console.log('in the filter date');
                $('#posts-container').html(response);
                dataTable = $('#posts-container').html(response);
                show_all_overlay();
                
            },
        });
    });
});


// eye icon in photo_block when clicked //
$(document).on('click', '#open-post', function (e) {
    e.preventDefault();
    var postURL = $(this).closest('.overlay-link').attr('href');

    // Open the current post link
    window.location.href = postURL;
});


//overlay lightbox // ajax // //
// show overlay for the first changed 12 photos//
function show_overlay() {
    $(document).on('click', 'i.fa-expand', function (e) {
        e.preventDefault();
        console.log('click works in post-container');
        var currentImageIndex;
        var images;
        var overlayContainer;
    
        currentImageIndex = $('.post-container').index($(this).closest('.post-container'));
               
        overlayContainer = $(dataTable);
        console.log(overlayContainer);
                        images = $(dataTable).find('img');

                        console.log(images);
                        
                        
                        if (images.length > 0) {
                            images = $(dataTable).find('img').filter(function() {
                                var src = $.trim($(this).attr('src'));
                                var id = $(this).attr('id');
                                return src !== '';
                            });
    
                            var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                            var photoReference = currentOverlayLink.data('photo-reference');
                            var categoryName = currentOverlayLink.data('category-name');
                            
                            console.log('bonjour');
                            console.log('photoReference:', photoReference);
                            console.log('categoryName:', categoryName);
                            
                            
    
                           
                            updateImage(images, currentImageIndex, photoReference, categoryName);
                            $('#dynamic-overlay').show();
    
                        }
                        
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
            var currentOverlayLink = $('.overlay-link').eq(currentImageIndex);
            var photoReference = currentOverlayLink.data('photo-reference');
            var categoryName = currentOverlayLink.data('category-name');
            updateImage(images, currentImageIndex, photoReference, categoryName);
        });
    
        // Next button click
        $('#next-btn').on('click', function (e) {
            e.preventDefault();
            currentImageIndex = (currentImageIndex + 1 + (images.length)) % (images.length);
            var currentOverlayLink = $('.overlay-link').eq(currentImageIndex);
            var photoReference = currentOverlayLink.data('photo-reference');
            var categoryName = currentOverlayLink.data('category-name');
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
        
    });
}

function show_all_overlay() {
    $(document).on('click', '.fa-expand', function (e) {
        e.preventDefault();
        console.log('click works in post-container');
        var currentImageIndex;
        var images;
        var overlayContainer;
    
        currentImageIndex = $('.post-container').index($(this).closest('.post-container'));
        console.log(currentImageIndex);
        
                        overlayContainer = $(dataTable);
    
                        images = $(dataTable).find('img');
                        console.log(images);
                        
                        
                        if (images.length > 0) {
                            images = $(dataTable).find('img:gt(0)').filter(function() {
                                // Filter out images with no contents
                                console.log('Image src:', $(this).attr('src'));
                                var src = $.trim($(this).attr('src'));
                                var id = $(this).attr('id');
                                return src !== '' && id !== 'previous-image';
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
                });

}


// load more photos when click on load more images button append newly loaded images //
$(document).ready(function ($) {
    var page = 2;
    
    console.log(page);
    $('#load-more-button').on('click', function (e) {
        e.preventDefault(); // Prevent the default behavior of the link
        
        const ajaxurl = $(this).data('ajaxurl');

        if (!ajaxurl) {
            console.error('Error: data-ajaxurl attribute not set on #load-more-button');
            return;
        }

        console.log('load more Button clicked, AJAX URL:', ajaxurl);

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
                page++;
                
                // Append the response to the dataTable
                
                dataTable = $('#posts-container').append(response);
                console.log(dataTable);
                show_all_overlay();
                hasMorePhotos = false;
            },
            error: function (error) {
                console.error('Error');
            },
        });
    });
});


// ajax for first time load the photos 12 first //
function getFirstPhotos() {

    $(document).ready(function (){
  
        const ajaxurl =  $('#posts-container').data('ajaxurl');

        if (!ajaxurl) {
            console.error('Error: data-ajaxurl attribute not set on #load-more-button');
            return;
        }

        console.log('first load, AJAX URL:', ajaxurl);

        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'first_load_photos',
            },
            success: function (response) {
                console.log('Success: first_load_photos works');
                dataTable = response;
                $('#posts-container').append(dataTable);
                //console.log(dataTable);
            },
            error: function (error) {
                console.error('Error');
            },
        });
});
}


// load 2 images on single-photo page //

function load2images() {
    $(document).ready(function () {

        var postId = $('.related-images').data('post-id');
        var termSlug = $('.related-images').data('term-slug');
      
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
                    postId: postId,
                    termSlug: termSlug,
    
                },
                success: function (response) {
                    console.log('Success: load_2images_Related works');
                    $('.related-images').html(response);
                    dataTable = $(response);
                },
                error: function (error) {
                    console.error('Error');
                },
            });
        });
}


function show_overlay_2images() {
    //var postId = $('.related-images').data('post-id');
    //var termSlug = $('.related-images').data('term-slug');

        $(document).on('click', '.fa-expand', function (e) {
            e.preventDefault();
            console.log('click works in post-container');
            var currentImageIndex;
            var images2;
            var overlayContainer;
        
            currentImageIndex = $('.post-container').index($(this).closest('.post-container'));
            console.log(currentImageIndex);
            
            overlayContainer = $(dataTable);
                        
            images = $(dataTable).find('img:not(#dynamic-image)');

            console.log(images);
                        
                        
                        if (images.length > 0) {
                            images = $(dataTable).find('img:not(#dynamic-image)').filter(function() {
                                var src = $.trim($(this).attr('src'));
                                //var id = $(this).attr('id');
                                return src !== '';
                            });
    
                            var currentOverlayLink = overlayContainer.find('.overlay-link').eq(currentImageIndex);
                            var photoReference = currentOverlayLink.data('photo-reference');
                            var categoryName = currentOverlayLink.data('category-name');
                            
                            console.log('bonjour');
                            console.log('photoReference:', photoReference);
                            console.log('categoryName:', categoryName);
                            
                            
    
                           
                            updateImage(images, currentImageIndex, photoReference, categoryName);
    
                        }
            
            
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
            
        });
    }
    

// when click on hamburger //

function header() {

    $(document).ready(function () {
    
        $(".icons-header").on("click", function (e) {
            e.preventDefault();
            if (window.innerWidth < 950) {
                console.log('icons clicked.');
                $(".fa-bars, .fa-xmark").toggle();
                $(".nav-menu").slideToggle();
            }
            if (window.innerWidth >= 950) {
                console.log('icons clicked.');
                $(".fa-bars, .fa-xmark").css('display', 'none');
               // $(".nav-menu").slideToggle();
                
                
            }

            $(window).resize(function() {
                if (window.innerWidth >= 950) {
                    $(".fa-bars").hide();
                    $(".fa-xmark").hide();
                    // $(".nav-menu").slideToggle();
                    $(".nav-menu").show();
                    
                } else {
                    $(".fa-bars").show();
                    $(".fa-xmark").hide();
                   // $(".nav-menu").slideToggle();
                }
            });
        });
    });
    
}

header();



// toggle button //
// check if theres more photos to load //

function hasMorePhotosFunc() {
    $(document).ready(function () {
        var total = pages_vars.totalPages;
        var current = pages_vars.currentPage;
        // Get the total number of pages and the current page from localized data
       
        console.log('hasmorebutton clicked');
        console.log(total);
        console.log(current);
    
        // Check if the current page is equal to the total number of pages
        if (current >= total) {
            // Do something if the current page is the last page
            console.log('Current page is the last page');
            // For example, you might want to return false in this case
            return hasMorePhotos = false;
            
            
        }
        return hasMorePhotos = true;;
    });
}


// toggle the load more button //
function toggleLoadMoreButton() {
    $(document).ready(function ($) {


        // $('#load-more-button').toggle(hasMorePhotos);
        $('#load-more-button').on('click', function () {
             hasMorePhotos = hasMorePhotosFunc();
             $('#load-more-button').toggle(hasMorePhotos);
         });
    });
    
}
toggleLoadMoreButton();


//thumbnail container//
<?php 
register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'mytheme' ),
				'footer'  => esc_html__( 'Footer menu', 'mytheme' ),
			)
		);

function charger_mon_script() {
    // Définissez le chemin vers votre script
    $script_url = get_template_directory_uri() . '/js/scripts.js';

    // Générez une version unique en utilisant la date actuelle pour éviter la mise en cache
    $version = date('YmdHis');

    wp_enqueue_style('custom-style', get_template_directory_uri() . '/style.css', array(), $version, 'all');
    // Enregistrez le script avec la version
    wp_enqueue_script('custom-script', $script_url, array('jquery'), $version, true);
    wp_enqueue_script('jquery');

    // Assurez-vous que jQuery est en mode non-conflict
    wp_script_add_data('jquery', 'group', 1);
    wp_script_add_data('jquery', 'version', $version);
}

add_action('wp_enqueue_scripts', 'charger_mon_script');


function logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'logo_setup');


function enqueue_custom_script() {

    // Pass PHP value to script
    $current_post_id = get_the_ID();
    $taxonomies = get_object_taxonomies('photo', 'names');
    
    if (!empty($taxonomies)) {
        $taxonomy_slug = $taxonomies[0];
    
        // Get the terms (categories) associated with the current page in the custom taxonomy
        $terms = get_the_terms($current_post_id, $taxonomy_slug);
        
        // Check if there are terms
        if (!empty($terms)) {
            // Assuming you want to use the first term assigned to the page
            $term_slug = $terms[0]->slug;
        } else {
            // If there are no terms assigned to the page
            echo 'No terms found for this page.';
            return; // Stop execution if there are no terms
        }
    } else {
        // If there are no custom taxonomies for pages
        echo 'No custom taxonomies found for pages.';
        return; // Stop execution if there are no custom taxonomies
    }

    wp_localize_script('custom-script', 'php_vars', array(
        'postId'   => $current_post_id,
        'termSlug' => $term_slug
    ));

    $reference_field_value = get_post_meta($current_post_id, 'reference', true);
    wp_localize_script('custom-script', 'custom_vars', array('reference_value' => $reference_field_value));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');


// get dates for filters form //

function get_unique_post_dates() {
    $dates = array();
    $args = array(
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all posts
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $date = get_the_time('Y', get_the_ID());
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
        }
    }

    // Reset post data
    wp_reset_postdata();

    return $dates;
}

// cat filte form r//
function get_unique_post_categories() {
    $cats = array();
    $args = array(
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all posts
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_cats = get_the_terms(get_the_ID(), 'categorie');

            if ($post_cats && !is_wp_error($post_cats)) {
                foreach ($post_cats as $cat) {
                    if (!in_array($cat->name, $cats)) {
                        $cats[] = $cat->name;
                    }
                }
            }
        }
    }

    // Reset post data
    wp_reset_postdata();

    return $cats;
}

// format filter form //

function get_unique_post_formats() {
    $formats = array();
    $args = array(
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all posts
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_formats = get_the_terms(get_the_ID(), 'format');

            if ($post_formats && !is_wp_error($post_formats)) {
                foreach ($post_formats as $format) {
                    if (!in_array($format->name, $formats)) {
                        $formats[] = $format->name;
                    }
                }
            }
        }
    }

    // Reset post data
    wp_reset_postdata();

    return $formats;
}


//filters load all //

function loadAll(){
    $argsImages = array(  
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1,  
    );
    
    $loop = new WP_Query( $argsImages ); 
        
    while ( $loop->have_posts() ) : $loop->the_post();
    
     get_template_part('template-parts/content', 'photo_block');
    
     endwhile;
    
     wp_reset_postdata();

     die();
}

add_action('wp_ajax_loadAll', 'loadAll');
add_action('wp_ajax_nopriv_loadAll', 'loadAll');

//filters by cat //

function filter_posts_by_category() {
    $selected_category = $_POST['category'];

    $args = array(
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie',
                'field'    => 'name',
                'terms'    => $selected_category,
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'photo_block');
        }
    } else {
        echo 'No posts found in category';
    }
   
    wp_die(); // Always include this at the end to terminate the script properly
}

add_action('wp_ajax_filter_posts_by_category', 'filter_posts_by_category');
add_action('wp_ajax_nopriv_filter_posts_by_category', 'filter_posts_by_category');


// filter by format //

function filter_posts_by_format() {
    $selected_format = $_POST['format'];

    $argsF = array(
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'format',
                'field'    => 'name',
                'terms'    => $selected_format,
            ),
        ),
    );

    $query = new WP_Query($argsF);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'photo_block');
        }
    } else {
        echo 'No posts found in format';
    }

    $output = ob_get_clean();

    // Send only the content, not the entire HTML
    echo $output;

    
    wp_die(); // Always include this at the end to terminate the script properly
}

add_action('wp_ajax_filter_posts_by_format', 'filter_posts_by_format');
add_action('wp_ajax_nopriv_filter_posts_by_format', 'filter_posts_by_format');


// filter date //

function filter_posts() {
    $selectedDate = sanitize_text_field($_POST['selectedDate']);
    $args = array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'date_query'     => array(
            array(
                'year' => $selectedDate,
            ),
        ),
    );

    $query = new WP_Query($args);

    // Output the filtered posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            get_template_part('template-parts/content', 'photo_block');
        }
        wp_reset_postdata();
    } else {
        echo 'No posts found';
    }
  
    die();
}

add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');


// light box overlay all//

function load_overlay() {

    $args = array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        
    );

    $query = new WP_Query($args);

        
    while ($query->have_posts()) : the_post();

        get_template_part('template-parts/content', 'photo_block');

    endwhile; 
        
    wp_reset_postdata();
  
   
}

add_action('wp_ajax_load_overlay', 'load_overlay');
add_action('wp_ajax_nopriv_load_overlay', 'load_overlay');


// fonctions for ajax in front-page to load more photos//

function load_more_photos() {
    
    $page = $_POST['page'];
    $offset = ($page - 1) * 12; // Adjust posts_per_page and offset accordingly


    $args = array(  
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'offset' => $offset,
        
    );

    $photos_query = new WP_Query($args);
    

    while ($photos_query->have_posts()) : $photos_query->the_post();
        ?>
       
            <?php get_template_part('template-parts/content', 'photo_block'); ?>
       
        <?php
    endwhile;

    wp_reset_postdata();

    die();
    
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// get the next page of 12 photos //

function get_all_next_photos() {
   // $posts = array();
    $args = array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => -1, // Get all posts
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            get_template_part('template-parts/content', 'photo_block');
            
        }
    }

    // Reset post data
    wp_reset_postdata();

    die();
    //return $posts;
}

add_action('wp_ajax_get_all_next_photos', 'get_all_next_photos');
add_action('wp_ajax_nopriv_get_all_next_photos', 'get_all_next_photos');


function first_load_photos() {
    // $posts = array();
     $args = array(
         'post_type'      => 'photo',
         'post_status'    => 'publish',
         'posts_per_page' => 12,
     );
     $query = new WP_Query($args);
 
     if ($query->have_posts()) {
         while ($query->have_posts()) {
             $query->the_post();
 
             get_template_part('template-parts/content', 'photo_block');
            
         }
     }
 
     // Reset post data
     wp_reset_postdata();

     die(); // to remove the 0 in the end
 }
 
 add_action('wp_ajax_first_load_photos', 'first_load_photos');
 add_action('wp_ajax_nopriv_first_load_photos', 'first_load_photos');
 

 function load_2images_Related(){
    $page_id = $_POST['postId'];
    $term_slug = $_POST['termSlug'];
    $args = array(  
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => 2, 
        'post__not_in'   => array($page_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'categorie',
                    'field'    => 'slug',
                    'terms'    =>  $term_slug,
                    
                )
            )
        
    );
$query = new WP_Query($args);
        
    while ( $query->have_posts() ) : $query->the_post();
    
     get_template_part('template-parts/content', 'photo_block');
    
     endwhile;
    
     wp_reset_postdata();
     
     die();
}

add_action('wp_ajax_load_2images_Related', 'load_2images_Related');
add_action('wp_ajax_nopriv_load_2images_Related', 'load_2images_Related');




//check if there's no more photos to be loaded //

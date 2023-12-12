<?php 
register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'mytheme' ),
				'footer'  => esc_html__( 'Footer menu', 'mytheme' ),
			)
		);



function myscript() {
    // Enqueue Style
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/style.css');

    // Enqueue Script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'myscript');


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
    $reference_field_value = get_post_meta(get_the_ID(), 'reference', true);
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

// fonctions for ajax in front-page //

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
        <a href="<?php the_permalink(); ?>">
            <?php get_template_part('template-parts/content', 'photo_block'); ?>
        </a>
        <?php
    endwhile;

    wp_reset_postdata();

    die();
    
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

//filters load all //


function loadAll(){
    $argsImages = array(  
        'post_type' => 'photo',
        'post_status' => 'publish',
        'posts_per_page' => 12,  
    );
    
    $loop = new WP_Query( $argsImages ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); ?>
    
    <a href="<?php the_permalink() ?>"><?php get_template_part('template-parts/content', 'photo_block'); ?></a>
    
    
    
    <?php endwhile;
    
     wp_reset_postdata();
}

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
?>
 
<?php
   
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

    // Use $selectedDate to filter posts and retrieve the updated content
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

// light box //

function load_overlay() {

    // Use $selectedDate to filter posts and retrieve the updated content
    $args = array(
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        
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

add_action('wp_ajax_load_overlay', 'load_overlay');
add_action('wp_ajax_nopriv_load_overlay', 'load_overlay');

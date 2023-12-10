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
    //wp_localize_script('custom-scripts', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');


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
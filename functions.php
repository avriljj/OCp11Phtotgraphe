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

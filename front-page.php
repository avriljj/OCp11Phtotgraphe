<?php get_header();?>

<div class="hero">
<?php
$args = array(  
    'post_type' => 'photo',
    'post_status' => 'publish',
    'posts_per_page' => 1, 
    'orderby' => 'rand', 
);

$loop = new WP_Query( $args ); 
    
while ( $loop->have_posts() ) : $loop->the_post(); ?>

<a href="<?php the_permalink() ?>"><?php the_content(); ?></a>



<?php endwhile;

 wp_reset_postdata();
?>
</div>
<div>

<div id="photo-container">
<?php
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
?>
</div>
</div>

<div>
<button id="load-more-button" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Load More Photos</button>

</div>
<?php get_footer();
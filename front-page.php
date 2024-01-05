<?php get_header();?>

<div class="hero">
<img class="header-title"src="/OCWPformation/p11/wordpress/wp-content/themes/myTheme/assets/images/header-title.png" alt="">
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

<!-- sorting start here --> 

<form id="filter-form">
<?php
?>
<div class="category-div ">
    <div class="custom-select">
    <select name="filter-categorie" id="category-filter" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">   
        <option value="all" selected>CATEGORIES</option>
        <?php
        // Get a list of unique post dates
        $unique_categories = get_unique_post_categories();
        
        foreach ($unique_categories as $cat) {
            echo '<option value="' . esc_attr($cat) . '">' . esc_html($cat) . '</option>';
        }
        ?>
    </select>
    </div>
    <div class="custom-select">
    <select name="filter-format" id="filter-format" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <option value="all" selected>FORMAT</option>
        <?php
        // Get a list of unique post dates
        $unique_formats = get_unique_post_formats();
        
        foreach ($unique_formats as $format) {
            echo '<option value="' . esc_attr($format) . '">' . esc_html($format) . '</option>';
        }
        ?>
    </select>
    </div>
</div>


<div class="date-div">
    <div class="custom-select">
<select name="filter-date" id="filter-date" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <option value="" selected>TRIER PAR</option>
        <?php
        // Get a list of unique post dates
        $unique_dates = get_unique_post_dates();
        
        foreach ($unique_dates as $date) {
            echo '<option value="' . esc_attr($date) . '">' . esc_html($date) . '</option>';
        }
        ?>
    </select>
    </div>
</div>
    

</form> <!-- end of forms -->

<script type="text/javascript">
        jQuery(document).ready(function($) {

            
            openFormFrontPage();
            getFirstPhotos();
            show_overlay();
            
        });
    </script>

<div id="posts-container" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
</div>


<div id="load-more-button-div">
<button id="load-more-button" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Load More Photos</button>
</div>
<div>
<?php get_template_part('template-parts/content','contact');?></div>
</div>
<?php get_footer();
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

<!-- sorting start here --> 

<form id="filter-form">
<?php
?>
<select name="filter-categorie" id="category-filter" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <option value="all" selected>Catégories</option>
        <?php
        // Get a list of unique post dates
        $unique_categories = get_unique_post_categories();
        
        foreach ($unique_categories as $cat) {
            echo '<option value="' . esc_attr($cat) . '">' . esc_html($cat) . '</option>';
        }
        ?>
    </select>

    <select name="filter-format" id="filter-format" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <option value="all" selected>Format</option>
        <?php
        // Get a list of unique post dates
        $unique_formats = get_unique_post_formats();
        
        foreach ($unique_formats as $format) {
            echo '<option value="' . esc_attr($format) . '">' . esc_html($format) . '</option>';
        }
        ?>
    </select>

    <select name="filter-date" id="filter-date" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <option value="" selected>All Dates</option>
        <?php
        // Get a list of unique post dates
        $unique_dates = get_unique_post_dates();
        
        foreach ($unique_dates as $date) {
            echo '<option value="' . esc_attr($date) . '">' . esc_html($date) . '</option>';
        }
        ?>
    </select>

</form>

<div>

<button onclick="toggleOverlay()">Toggle Overlay</button>
<div id="overlay"><button onclick="toggleOverlay()">Toggle Overlay</button></div>



        <div id="posts-container">
            <p>posts container here</p>
            <?php
            $argsImages = array(  
                'post_type' => 'photo',
                'post_status' => 'publish',
                'posts_per_page' => 12,  
            );

            $loop = new WP_Query( $argsImages ); 

            while ($loop->have_posts()) : $loop->the_post(); ?>
    <?php 
    $photo_reference = get_post_meta(get_the_ID(), 'reference', true);
    $category_terms = get_the_terms(get_the_ID(), 'categorie');
    $category_name = !empty($category_terms) ? $category_terms[0]->name : '';
    
    ?>
    <a href="<?php the_permalink(); ?>" id="image-link" data-post-id="<?php the_ID();?>" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>">
        <?php get_template_part('template-parts/content', 'photo_block'); ?>
    </a>
    <!-- Structure de la lightbox -->

    <div class="overlay-container" id="dynamic-overlay">
        <span class="overlay-close">&times;</span>
        <img id="dynamic-image" src="" alt="Dynamic Image">
        <div class="icon-container">
            <a id="full-screen" href="#" class="full-screen-icon"><i class="fa-solid fa-expand"></i></a>
            <a href="<?php the_permalink(); ?>" id="detail-page" class="send-to-page-icon"><i class="fa-solid fa-circle-arrow-right"></i></a>
        </div>
        
        <a href="#" class="overlay-prev" id="prev-btn">&lt; Previous</a>
        <a href="#" class="overlay-next" id="next-btn">Next &gt;</a>
        <span class="photo-reference"><?php echo esc_html($photo_reference)?></span>
        <span class="category-name"><?php echo esc_html($category_name)?></span>
        <?php 
        error_log('Post ID: ' . get_the_ID());
    error_log('Photo Reference: ' . $photo_reference);
    error_log('Category Name: ' . $category_name);
    var_dump($photo_reference);
    var_dump($category_name);
    var_dump(the_permalink());
        ?>
    </div>

<?php endwhile; ?>

<?php

            wp_reset_postdata();
            ?>
        </div>

        
        

        
    </div>
</div>


<div>

<button id="load-more-button" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">Load More Photos</button>

</div>
</div>
<?php get_footer();
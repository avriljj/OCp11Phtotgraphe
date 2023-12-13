<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <div class="fields">
<?php $reference_field_value = get_post_meta(get_the_ID(), 'reference', true);

if (!empty($reference_field_value)) {?>
<div class="your-custom-container">
<p>Référence : <?php echo esc_html($reference_field_value); ?></p>
 </div>
 <?php } ?>


 <?php $categorie_field_value = get_the_terms(get_the_ID(), 'categorie', true);

if (!empty($categorie_field_value)) {?>
<div class="your-custom-container">
<p>Catégorie : <?php foreach ($categorie_field_value as $category) { echo $category->name . ' '; } ?></p>
 </div>
 <?php  } ?>



 <?php $format_field_value = get_the_terms(get_the_ID(), 'format', true);

if (!empty($format_field_value)) {?>
<div class="your-custom-container">
<p>Format : <?php foreach ($format_field_value as $format) { echo $format->name . ' '; } ?></p>
 </div>
 <?php } ?>
 <?php $type_field_value = get_post_meta(get_the_ID(), 'type', true);

if (!empty($type_field_value)) {?>
<div class="your-custom-container">
<p>Type : <?php echo esc_html($type_field_value); ?></p>
 </div>
 <?php } ?>


 <?php $date_field_value = get_the_date('Y', get_the_ID());

if (!empty($date_field_value)) {?>
<div class="your-custom-container">
<p>Année : <?php echo esc_html($date_field_value); ?></p>
 </div>
 <?php } ?>
                </div>

                <div class="div-contact">
                    <button class="btn-close">Contact</button>
                    <div class="form-contact"><?php get_template_part('template-parts/content','contact');?></div>
                </div>
                <div class="navigation">
    <div class="alignleft" data-direction="previous"><?php previous_post_link('%link', '&laquo; Previous Post'); ?></div>
    <div class="alignright" data-direction="next"><?php next_post_link('%link', 'Next Post &raquo;'); ?></div>
    <div id="thumbnail-container"></div>
</div>



<!-- get the slug -->
<?php
// Get the current page ID
$page_id = get_the_ID();
$term_slug = null;

// Get all custom taxonomies associated with the page
$taxonomies = get_object_taxonomies('photo', 'names');
//var_dump($taxonomies);

// Assuming you have only one custom taxonomy for pages
if (!empty($taxonomies)) {
    $taxonomy_slug = $taxonomies[0];
    //var_dump($taxonomy_slug);
    //var_dump($taxonomies);

    // Get the terms (categories) associated with the current page in the custom taxonomy
    $terms = get_the_terms($page_id, $taxonomy_slug);
   // var_dump($terms);
    // Check if there are terms
    if (!empty($terms)) {
        // Assuming you want to use the first term assigned to the page
        $term_slug = $terms[0]->slug;
      //  var_dump($term_slug);
    } else {
        // If there are no terms assigned to the page
        echo 'No terms found for this page.';
    }
} else {
    // If there are no custom taxonomies for pages
    echo 'No custom taxonomies found for pages.';
}
?>

<div class="related-images">
<?php


// Query the posts from the specified category
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
$custom_query = new WP_Query($args);
?>

<?php
if ($custom_query->have_posts()) :
    while ($custom_query->have_posts()) : $custom_query->the_post();

        // Display the template part
        get_template_part('template-parts/content', 'photo_block');

    endwhile;

    // Always reset post data after a custom query
    wp_reset_postdata();
endif;
?>

<!-- Structure de la lightbox -->

<div class="overlay-container" id="dynamic-overlay">
        <span class="overlay-close">&times;</span>
        <img id="dynamic-image" src="" alt="Dynamic Image">
        <div class="icon-container">
            <a id="full-screen" href="#" class="full-screen-icon"><i class="fa-solid fa-expand"></i></a>
            <a href="<?php the_permalink(get_the_ID()) ?>" id="detail-page" class="send-to-page-icon"><i class="fa-solid fa-circle-arrow-right"></i></a>
        </div>
        
        <a href="#" class="overlay-prev" id="prev-btn">&lt; Previous</a>
        <a href="#" class="overlay-next" id="next-btn">Next &gt;</a>
        <span class="photo-reference"><?php echo esc_html($photo_reference)?></span>
        <span class="category-name"><?php echo esc_html($category_name)?></span>
    </div>


<button>Toutes les photos</button>
</div>




                <!-- Ajoutez d'autres éléments ou métadonnées ici selon vos besoins -->

            </article>

        <?php endwhile; ?>

    </main>
</div>
<?php get_footer(); ?>

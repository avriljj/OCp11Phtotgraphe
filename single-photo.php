<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                

               
                
<div class="infos">

<div class="infos-text">

<header class="entry-header">
<h1 class="entry-title"><?php the_title(); ?></h1>
</header>
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
</div> <!-- end of infos text-->
<div class="photo-content">
<?php the_content(); ?>
</div>  
</div> <!-- end if infos -->

    <div class="contact-section">
        <div class="contact-sub-section">
        <div><p>Cette photo vous intéresse ?</p></div>
        <div class="div-contact">
        <button class="btn-close" id="contact-form-open">Contact</button>
        <div class="form-contact"><?php get_template_part('template-parts/content','contact');?></div>
        </div>
        </div>
        
<div class="navgation-container">
    <div class="navigation-sub-container">
    <div id="thumbnail-container"><img></div>
<div class="navigation">
<div class="alignleft" data-direction="previous"><?php previous_post_link('%link', '<-'); ?></div>
<div class="alignright" data-direction="next"><?php next_post_link('%link', '->'); ?></div>  
</div>
    </div>

</div>
    
</div>
<div class="pre-divider">
<div class="divider"></div>
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

<script type="text/javascript">
        jQuery(document).ready(function($) {
            // Call your custom function when the document is ready
            load2images();
            show_overlay_2images();
            openForm();
            
        });
</script>

<div class="aime-aussi"><p>VOUS AIMEREZ AUSSI</p></div>

<div class="related-images" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>">

</div>

<a class="main-page-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><button>Toutes les photos</button></a>

            </article>

        <?php endwhile; ?>

    </main>
</div>
<?php get_footer(); ?>

<?php
/**
 * The template for displaying all single posts
 *
 */

get_header();


while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content','page' );

endwhile; 

?>
<div class="form-contact"><?php get_template_part('template-parts/content','contact');?></div>

<script type="text/javascript">
        jQuery(document).ready(function($) {
        
            openFormFrontPage();
        });
    </script>
<?php

get_footer();

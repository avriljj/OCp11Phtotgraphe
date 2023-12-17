<?php
/**
 * The template for displaying all single posts
 *
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content','page' );

endwhile; // End of the loop.

?>

<script type="text/javascript">
        jQuery(document).ready(function($) {
        
            formFunction();
        });
    </script>
<?php

get_footer();

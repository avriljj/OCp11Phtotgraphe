<?php

$post_content = get_the_content();
$pattern = '/<img[^>]+>/i';
preg_match_all($pattern, $post_content, $matches);

// Additional information (replace with your actual data retrieval logic)
$photo_reference = get_post_meta(get_the_ID(), 'reference', true);
$category_terms = get_the_terms(get_the_ID(), 'categorie');
$category_name = !empty($category_terms) ? $category_terms[0]->name : '';

if (!empty($matches[0])) {

    echo '<div class="post-container" data-post-id="' . get_the_ID() . '" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">';
    //echo '<a class="overlay-link" href=" ' . esc_url(get_permalink()) . '"';
    echo '<a class="overlay-link" href="' . esc_url(get_permalink()) . '" data-permalink="' . esc_url(get_permalink()) . '"';

    echo '<img src="" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) . '">' . $matches[0][0] . '</img>';
    

    // Add Font Awesome icons for full screen and sending to another page
    echo '<div class="overlay">';
    echo '<i class="fas fa-eye" id="open-post"></i>'; 
    //echo '<i class="fa-solid fa-eye"></i>'; // Eye icon
    echo '<i class="fa-solid fa-expand"></i>'; // Full screen icon
    echo '<span class="photo-reference">' . esc_html($photo_reference) . '</span>';
    echo '<span class="category-name">' . esc_html($category_name) . '</span>';
    echo '</div>';
    echo '</a>';
    echo '</div>';

    return $matches;
    
}


?>


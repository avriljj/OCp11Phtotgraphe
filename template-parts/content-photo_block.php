<?php

$post_content = get_the_content();
$pattern = '/<img[^>]+>/i';
preg_match_all($pattern, $post_content, $matches);

// Additional information //
$photo_reference = get_post_meta(get_the_ID(), 'reference', true);
$category_terms = get_the_terms(get_the_ID(), 'categorie');
$category_name = !empty($category_terms) ? $category_terms[0]->name : '';

if (!empty($matches[0])) {

    echo '<div class="post-container" data-post-id="' . get_the_ID() . '" data-ajaxurl-load="' . esc_url(admin_url('admin-ajax.php')) . '">';

    echo '<div class="overlay-link" href="' . esc_url(get_permalink()) . '" data-permalink="' . esc_url(get_permalink()) . '" data-ajaxurl="' . esc_url(admin_url('admin-ajax.php')) .'"';
    echo ' data-photo-reference="' . esc_attr($photo_reference) . '"';
    echo ' data-category-name="' . esc_attr($category_name) . '"';
    echo '> ';
    //echo($category_name);
    //echo($photo_reference);

    echo '<img>' . $matches[0][0] ;
    

    // Add Font Awesome icons for full screen and sending to another page
    echo '<div class="overlay">';
    echo '<a id="open-post" href="' . esc_url(get_permalink()) . '"><i class="fas fa-eye"></i></a>'; 
    echo '<i class="fa-solid fa-expand"></i>'; // Full screen icon
    echo '<span class="photo-reference-photo-block">' . esc_html($photo_reference) . '</span>';
    echo '<span class="category-name-photo-block">' . esc_html($category_name) . '</span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
?>
<!-- Structure de la lightbox -->

<div class="overlay-container" id="dynamic-overlay">
        <span class="overlay-close">&times;</span>
        <img id="dynamic-image" src="" alt="Dynamic Image">
        <div class="icon-container">
            <a id="full-screen" href="#" class="full-screen-icon"><i class="fa-solid fa-expand"></i></a>
            <a id="detail-page" href="<?php echo get_permalink(); ?>" class="send-to-page-icon"><i class="fa-solid fa-eye"></i></a>

        </div>
        

        <a href="#" class="overlay-prev" id="prev-btn">&lt; Previous</a>
        <a href="#" class="overlay-next" id="next-btn">Next &gt;</a>
        <span class="photo-reference"><?php echo esc_html($photo_reference)?></span>
        <span class="category-name"><?php echo esc_html($category_name)?></span>
    </div>
<?php
    return $matches;
    
}

?>





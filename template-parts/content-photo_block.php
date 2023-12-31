<?php

$post_content = get_the_content();
//cette expression régulière recherche toutes les occurrences de balises <img> dans une chaîne de texte//
$pattern = '/<img[^>]+>/i';
preg_match_all($pattern, $post_content, $matches);


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

    echo $matches[0][0];
    //var_dump($matches[0][0]);
    

    // Add Font Awesome icons for full screen and sending to another page
    echo '<div class="overlay">';
    echo '<a id="open-post" href="' . esc_url(get_permalink()) . '"><i class="fa-solid fa-eye"></i></a>'; 
    echo '<i class="fa-solid fa-expand"></i>'; // Full screen icon
    echo '<span class="photo-reference-photo-block">' . esc_html($photo_reference) . '</span>';
    echo '<span class="category-name-photo-block">' . esc_html($category_name) . '</span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
?>
<!-- Structure de la lightbox -->

<div class="overlay-container" id="dynamic-overlay">
        <a class="overlay-close"><i class="fas fa-times"></i></a>
        <img id="dynamic-image" src="" alt="Dynamic Image">
        <a href="#" class="overlay-prev" id="prev-btn"><i class="fa-solid fa-arrow-left-long"></i> Précédente</a>
        <a href="#" class="overlay-next" id="next-btn">Suivante <i class="fa-solid fa-arrow-right-long"></i></a>
        <span class="photo-reference"><?php echo esc_html($photo_reference)?></span>
        <span class="category-name"><?php echo esc_html($category_name)?></span>
    </div>
<?php
    return $matches;
    
}

?>





<?php
        
        $post_content = get_the_content();
        $pattern = '/<img[^>]+>/i';
        preg_match_all($pattern, $post_content, $matches);

        if (!empty($matches[0])) {
            $post_image = '<div class="post-image">' . $matches[0][0] . '</div';
            
            echo $post_image;
        }
?>
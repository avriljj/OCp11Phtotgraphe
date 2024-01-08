<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://kit.fontawesome.com/6c1bf69b8d.js" crossorigin="anonymous"></script>
	<title>Phtographe event</title>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="nav-container">
<div class="nav">
<div id="logo"><?php the_custom_logo(); ?></div>
</div>

<div class="icons-header">
<i class="fa-solid fa-bars"></i>
<i class="fa-solid fa-xmark"></i>
</div>
<div class="nav-menu"><?php wp_nav_menu( array(
    'main menu' => 'primary',
) ); ?></div>

</div>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="description" content="<?php bloginfo( 'description' ); ?>"/>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
	<title><?php wp_title() ?></title>
	<!--[if lt IE 9]>
	<script src="<?php bloginfo('template_url'); ?>/ie/ie_fix_html5.js"></script>
	<![endif]-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
	<?php wp_head(); ?>
</head>

<body id="site-content" <?php body_class(); ?>>
<?php do_action('aa_afterbodystart') ?>
<div id="scroll-trigger-top"></div>
<div class="main-content-site">
	<div class="container">
		<header class="site-header row"><?php header_type() ?></header>
		<div class="row">
			<nav class="ghostly-wrap"><?php do_the_breadcrumb() ?></nav>
		</div>
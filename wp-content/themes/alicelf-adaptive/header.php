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

<body id="site-content" <?php body_class(browser_info()); ?>>
<?php
	global $alicelf;
	do_action('aa_afterbodystart');
	$site_content_class = $alicelf['opt-sticky-header'] === '2' ?
		'main-content-site stick-to-top' :
		'main-content-site' ;
	do_action("render_system_messages");
?>
<div id="scroll-trigger-top"></div>
<div class="<?php echo $site_content_class ?>">
	<div class="container">
		<header class="site-header row"><?php header_type(); ?></header>
		<div class="row">
			<nav class="ghostly-wrap"><?php do_the_breadcrumb() ?></nav>
		</div>
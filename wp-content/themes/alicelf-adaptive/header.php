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
<?php
	global $alicelf;
	do_action('aa_afterbodystart');
	$site_content_class = $alicelf['opt-sticky-header'] === '2' ?
		'main-content-site stick-to-top' :
		'main-content-site' ;
?>
<div id="scroll-trigger-top"></div>
<div class="<?php echo $site_content_class ?>">
	<div class="container">
		<header class="site-header row">
			<div id="mobile-menu-trigger" class="hidden-on-desktop">
				<button type="button" class="tcon tcon-menu--xcross" aria-label="toggle menu">
			<span class="tcon-menu__lines" aria-hidden="true">
			</span> <span class="tcon-visuallyhidden">toggle menu</span>
				</button>
			</div>
			<?php header_type() ?>
		</header>
		<div id="shock-absorber"></div>
		<div class="row">
			<nav class="ghostly-wrap"><?php do_the_breadcrumb() ?></nav>
		</div>
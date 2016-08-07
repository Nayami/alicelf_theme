<?php
add_action('wp_enqueue_scripts', 'aa_func_20161530011540', 10);
function aa_func_20161530011540()
{
	global $alicelf;
	$path = get_template_directory_uri().'/partials/';

	/**
	 * ==================== Custom Scroll Bar ======================
	 * 30.07.2016
	 */
	wp_enqueue_style( 'csb-style', $path . 'customScrollbar/style.css' );
	wp_enqueue_script( 'csb-script', $path . 'customScrollbar/script.min.js', ['jquery'], false, true );


	/**
	 * ==================== Instagram component styles ======================
	 * Work with plugin Instagram Feed
	 * https://wordpress.org/plugins/instagram-feed/
	 * 30.07.2016
	 */
	if(aa_check_plugin('instagram-feed/instagram-feed.php')) {
		wp_enqueue_style( 'insta-style', $path . 'inst_component/style.css' );
		wp_enqueue_script( 'insta-script', $path . 'inst_component/script.js', [
			'jquery',
			'csb-script',
			'alicelf-jqui-script',
		], false, true );
	}

	/**
	 * ==================== jQ UI ======================
	 * 31.07.2016
	 */
	wp_enqueue_style( 'alicelf-jqui-style', $path . 'jq-ui/style.css' );
	wp_enqueue_script( 'alicelf-jqui-script', $path . 'jq-ui/script.js', ['jquery'], false, true );


}



/**
 * ==================== Footer Instagram  ======================
 * 30.07.2016
 */
add_action( 'aa-before-footerstart', 'aa_func_20165327015348' );
function aa_func_20165327015348()
{

	global $alicelf;
	$inst        = $alicelf[ 'instagram-shortcode' ];
	$inst_mobile = $alicelf[ 'instagram-shortcode-mobile' ];
	if($alicelf['footer-followus-component']) {
		?>
		<div class="text-center cleafix">
			<h2 class="background-line">CONNECT WITH ME</h2>
			<p class="keepup-line">To keep up to date with our latest stock drops, follow us on social media.</p>
			<ul class="aa-socials-list">
				<li class="bw-facebook"><a target="_blank" href="<?php echo $alicelf[ 'opt-social-facebook' ] ?>"></a></li>
				<li class="bw-twitter"><a target="_blank" href="<?php echo $alicelf[ 'opt-social-twitter' ] ?>"></a></li>
				<li class="bw-pinterest"><a target="_blank" href="<?php echo $alicelf[ 'opt-social-pinterest' ] ?>"></a></li>
				<li class="bw-instagram"><a target="_blank" href="<?php echo $alicelf[ 'opt-social-instagram' ] ?>"></a></li>
			</ul>
		</div>
		<?php
	}

	if(aa_check_plugin('instagram-feed/instagram-feed.php')) { ?>
		<div id="instagram-footer-widget" class="clearfix hidden-on-phones">
			<div class="followusoninstagram">FOLLOW ME ON INSTAGRAM</div>
			<div id="footer-instawrapper" class="clearfix">
				<div id="insta-reel">
					<?php echo do_shortcode( $inst ) ?>
				</div>
			</div>
		</div>
		<div id="mobile-instagramm-reel" class="visible-on-phones-only">
			<div class="followusoninstagram">FOLLOW ME ON INSTAGRAM</div>
			<?php echo do_shortcode( $inst_mobile ) ?>
		</div>
		<?php
	}
}
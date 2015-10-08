<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
	return;
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$opt_name = "alicelf_theme_setup";
$theme    = wp_get_theme();
$subtheme = null;
$imgdir   = get_template_directory_uri() . '/img/';

$args = array(
	// TYPICAL -> Change these values as you need/desire
	'opt_name'        => $opt_name,
	// This is where your data is stored in the database and also becomes your global variable name.
	'display_name'    => "{$subtheme} <small class='main-theme-titledescr'>({$theme->get( 'Name' )}) --v</small>",
	// Name that appears at the top of your panel
	'display_version' => $theme->get( 'Version' ),
	// Version that appears at the top of your panel
	'menu_type'       => 'menu',
	//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
	'allow_sub_menu'  => true,
	// Show the sections below the admin menu item or not
	'menu_title'      => 'Theme Options',
	'page_title'      => 'Theme Customization',
	'admin_bar_icon'  => 'icon-alicelf-brand',
	'menu_icon'       => '',
	'page_priority'   => 61,
	'global_variable' => 'alicelf',
	'dev_mode'        => false,
);

add_action( "redux/loaded", "aa_disable_redux_developer_notice" );
add_action( "redux/extensions/before", "aa_disable_redux_developer_notice" );
add_action( "redux/extensions/{$args['opt_name']}/before", "aa_disable_redux_developer_notice" );
function aa_disable_redux_developer_notice( $redux )
{
	$redux->dev_mode_forced         = false;
	$redux->args[ 'dev_mode' ]      = false;
	$redux->args[ 'update_notice' ] = false;
//	unset( $redux->dev_mode_forced, $redux->args[ 'dev_mode' ], $redux->args[ 'update_notice' ] );
}

//add_action( "admin_head", "aa_disable_notice_cust_integration" );
function aa_disable_notice_cust_integration()
{
	echo "<style>.updated.redux-message.notice.is-dismissable{display: none;}</style>";
}

Redux::setArgs( $opt_name, $args );

/**
 * Home Section
 */
$section = array(
	'title'  => 'General Setup',
	'id'     => 'basic',
	'desc'   => '',
	'icon'   => 'dashicons dashicons-admin-home',
	'fields' => array(
		array(
			'id'       => 'opt-logo',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Site Logo', 'alicelf-adaptive' ),
			'compiler' => 'true',
			//'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
			'desc'     => __( 'Basic media uploader with disabled URL input field.', 'alicelf-adaptive' ),
			'subtitle' => __( 'Upload any media using the WordPress native uploader', 'alicelf-adaptive' ),
			'default'  => array( 'url' => $imgdir . 'site-logo.png' ),
			//'hint'      => array(
			//    'title'     => 'Hint Title',
			//    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
			//)
		),
		array(
			'id'       => 'opt-favicon',
			'type'     => 'media',
			'title'    => __( 'Favicon', 'alicelf-adaptive' ),
			'desc'     => __( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'alicelf-adaptive' ),
			'subtitle' => __( 'Upload any media using the WordPress native uploader', 'alicelf-adaptive' ),
		),
		array(
			'id'       => 'opt-gallery',
			'type'     => 'gallery',
			'title'    => __( 'Add/Edit Gallery', 'alicelf-adaptive' ),
			'subtitle' => __( 'Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
		),
		array(
			'id'            => 'opt-site-width',
			'type'          => 'slider',
			'title'         => __( 'Set initial site width', 'alicelf-adaptive' ),
			'subtitle'      => __( 'This example displays the value in a text box', 'alicelf-adaptive' ),
			'desc'          => __( 'Slider description. Min: 0, max: 300, step: 5, default value: 75', 'alicelf-adaptive' ),
			'default'       => 1170,
			'min'           => 300,
			'step'          => 1,
			'max'           => 3000,
			'display_value' => 'text'
		),
	)
);
Redux::setSection( $opt_name, $section );

$section = array(
	'title'  => 'Header',
	'id'     => 'header-section',
	'desc'   => '',
	'icon'   => 'dashicons dashicons-nametag',
	'fields' => array(
		array(
			'id'       => 'opt-sticky-header',
			'type'     => 'select',
			'title'    => __( 'Sticky Header', 'alicelf-adaptive' ),
			'subtitle' => __( 'Select header behavior', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			//Must provide key => value pairs for select options
			'options'  => array(
				'1' => 'Non Sticky',
				'2' => 'Sticky top',
			),
			'default'  => '1'
		),
		array(
			'id'         => 'opt-header-type',
			'type'       => 'image_select',
			'full_width' => true,
			'title'      => __( 'Header type', 'alicelf-adaptive' ),
			'subtitle'   => __( 'Select your header type', 'alicelf-adaptive' ),
			'desc'       => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			//Must provide key => value(array:title|img) pairs for radio options
			'options'    => array(
				'1' => array( 'title' => 'Header 1', 'img' => $imgdir . 'header-opt-1.jpg' ),
				'2' => array( 'title' => 'Header 2', 'img' => $imgdir . 'header-opt-2.jpg' ),
			),
			'default'    => '1'
		),

	)
);
Redux::setSection( $opt_name, $section );

$section = array(
	'title'  => 'Carousel',
	'id'     => 'carousel-section',
	'desc'   => '',
	'icon'   => 'el el-picture',
	'fields' => array(
		array(
			'id'       => 'opt-carouseltransition',
			'type'     => 'select',
			'title'    => __( 'Transition property', 'alicelf-adaptive' ),
			'subtitle' => __( 'Select carousel behavior', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			//Must provide key => value pairs for select options
			'options'  => array(
				'1' => 'Slide',
				'2' => 'Fade',
				'3' => 'Slick Thumbs'
			),
			'default'  => '1'
		),
		array(
			'id'          => 'opt-slides',
			'type'        => 'slides',
			'title'       => __( 'Slides Options', 'alicelf-adaptive' ),
			'subtitle'    => __( 'Unlimited slides with drag and drop sortings.', 'alicelf-adaptive' ),
			'desc'        => __( 'Usage: [theme_carousel] or echo do_shortcode(\'[theme_carousel]\')', 'alicelf-adaptive' ),
			'placeholder' => array(
				'title'       => __( 'This is a title', 'alicelf-adaptive' ),
				'description' => __( 'Description Here', 'alicelf-adaptive' ),
				'url'         => __( 'Give us a link!', 'alicelf-adaptive' ),
				'quick_notes' => __( 'Quick Notes', 'alicelf-adaptive' ),
			),
		),

	)
);
Redux::setSection( $opt_name, $section );

/**
 * Socials Section
 */
$section = array(
	'title' => 'Socials and Api',
	'id'    => 'socials-section',
	'desc'  => '',
	'icon'  => 'dashicons dashicons-networking',
);
Redux::setSection( $opt_name, $section );

$section = array(
	'title'      => 'Social urls',
	'id'         => 'profiles-subsection',
	'subsection' => true,
	'desc'       => '',
	'icon'       => 'dashicons dashicons-share',
	'fields'     => array(
		array(
			'id'       => 'opt-social-facebook',
			'type'     => 'text',
			'title'    => 'Facebook Url',
			'subtitle' => __( 'Enter your facebook page username', 'alicelf-adaptive' ),
			'validate' => 'url',
			'default'  => 'https://www.facebook.com/'
		),

		array(
			'id'       => 'opt-social-twitter',
			'type'     => 'text',
			'title'    => __( 'Tweeter Url', 'alicelf-adaptive' ),
			'subtitle' => __( 'Enter your Twitter ID', 'alicelf-adaptive' ),
			'validate' => 'url',
			'default'  => 'https://twitter.com/'
		),

		array(
			'id'       => 'opt-social-linkedin',
			'type'     => 'text',
			'title'    => __( 'LinkedIn URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
			'default'  => 'https://www.linkedin.com/'
		),

		array(
			'id'       => 'opt-social-google-plus',
			'type'     => 'text',
			'title'    => __( 'Google Plus URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
			'default'  => 'https://plus.google.com'
		),

		array(
			'id'       => 'opt-social-youtube',
			'type'     => 'text',
			'title'    => __( 'Youtube URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'       => 'opt-social-flickr',
			'type'     => 'text',
			'title'    => __( 'Flickr URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'       => 'opt-social-vimeo',
			'type'     => 'text',
			'title'    => __( 'Vimeo URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'       => 'opt-social-dribbble',
			'type'     => 'text',
			'title'    => __( 'Dribbble URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'       => 'opt-social-pinterest',
			'type'     => 'text',
			'title'    => __( 'Pinterest URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'       => 'opt-social-instagram',
			'type'     => 'text',
			'title'    => __( 'Instagram URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),
		array(
			'id'       => 'opt-social-vk',
			'type'     => 'text',
			'title'    => __( 'VKontakte URL', 'alicelf-adaptive' ),
			'subtitle' => __( 'This must be a URL.', 'alicelf-adaptive' ),
			'validate' => 'url',
		),

		array(
			'id'      => 'opt-check-rss',
			'type'    => 'checkbox',
			'title'   => __( 'Show RSS Link', 'alicelf-adaptive' ),
			'default' => '1',
		),
	)
);
Redux::setSection( $opt_name, $section );

/**
 * Facebook Api
 */
$section = array(
	'title'      => 'Facebook API',
	'id'         => 'facebook-api-subsection',
	'subsection' => true,
	'desc'       => '',
	'icon'       => 'el el-facebook',
	'fields'     => array(
		array(
			'id'       => 'opt-api-fb-user',
			'type'     => 'text',
			'title'    => __( 'Facebook User', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user id', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => '100009974258293'
		),

		array(
			'id'            => 'opt-api-fb-postcount',
			'type'          => 'slider',
			'title'         => __( 'Post Count', 'alicelf-adaptive' ),
			'subtitle'      => __( 'Set default posts count', 'alicelf-adaptive' ),
			'desc'          => __( 'Slider description. Min: 0, max: 300, step: 5, default value: 75', 'alicelf-adaptive' ),
			'default'       => 10,
			'min'           => 1,
			'step'          => 1,
			'max'           => 1000,
			'display_value' => 'text'
		),

		array(
			'id'       => 'opt-api-fb-appid',
			'type'     => 'text',
			'title'    => __( 'Facebook App ID', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user App ID', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => '494617204040308'
		),
		array(
			'id'       => 'opt-api-fb-secret',
			'type'     => 'text',
			'title'    => __( 'Facebook App Secret', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user App Secret', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'f90024597f44d17d1b35120cc38b61bb'
		),

		array(
			'id'       => 'opt-api-fb-apptoken',
			'type'     => 'text',
			'title'    => __( 'Facebook AppToken', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user AppToken', 'alicelf-adaptive' ),
			'desc'     => __( 'See the <a target="_blank" href="https://developers.facebook.com/tools/explorer/">Graph Explorer</a>', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => '494617204040308|hBpC5U9bsX3e-5i4fOLQ_-DjXY0'
		),

		array(
			'id'       => 'opt-api-fb-accesstoken',
			'type'     => 'text',
			'title'    => __( 'Facebook Accesstoken', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user Accesstoken', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => ''
		),

	)
);
Redux::setSection( $opt_name, $section );

/**
 * Google Api
 */
$section = array(
	'title'      => 'Google API',
	'id'         => 'google-api-subsection',
	'subsection' => true,
	'desc'       => '',
	'icon'       => 'el el-googleplus',
	'fields'     => array(
		array(),
	)
);

Redux::setSection( $opt_name, $section );

/**
 * Youtube Api
 */
$section = array(
	'title'      => 'YouTube API',
	'id'         => 'youtube-api-subsection',
	'subsection' => true,
	'desc'       => '',
	'icon'       => 'el el-youtube',
	'fields'     => array(
		array(
			'id'       => 'opt-api-youtube-user',
			'type'     => 'text',
			'title'    => __( 'YouTube User', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user id', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => '56786786867867'
		),
		array(
			'id'       => 'opt-api-youtube-apikey',
			'type'     => 'text',
			'title'    => __( 'Youtube api key', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your google api key', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'gfhfghfghfgh'
		),
		array(
			'id'       => 'opt-api-youtube-playlist',
			'type'     => 'text',
			'title'    => __( 'Youtube playlist(s)', 'alicelf-adaptive' ),
			'subtitle' => __( 'set id your playlists(comma separated)', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'tyrty78fgfhfghfghfgh'
		),
		array(
			'id'            => 'opt-api-youtube-postcount',
			'type'          => 'slider',
			'title'         => __( 'Post Count', 'alicelf-adaptive' ),
			'subtitle'      => __( 'Set default posts count', 'alicelf-adaptive' ),
			'desc'          => __( 'Slider description. Min: 0, max: 50, step: 5', 'alicelf-adaptive' ),
			'default'       => 4,
			'min'           => 1,
			'step'          => 1,
			'max'           => 50,
			'display_value' => 'text'
		),
	)
);
Redux::setSection( $opt_name, $section );

/**
 * Twitter Api
 */
$section = array(
	'title'      => 'Twitter API',
	'id'         => 'twitter-api-subsection',
	'subsection' => true,
	'desc'       => '',
	'icon'       => 'el el-twitter',
	'fields'     => array(
		array(
			'id'       => 'opt-api-twitter-user',
			'type'     => 'text',
			'title'    => __( 'Twitter User', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your user id', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => '56786786867867'
		),
		array(
			'id'       => 'opt-api-consumer-key',
			'type'     => 'text',
			'title'    => __( 'Twitter Consumer key', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your twitter consumer key', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'gfhfghfghfgh'
		),
		array(
			'id'       => 'opt-api-consumer-secret',
			'type'     => 'text',
			'title'    => __( 'Twitter Consumer secret', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your twitter consumer secret', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'gfhfghfghfgh'
		),
		array(
			'id'       => 'opt-api-access-token',
			'type'     => 'text',
			'title'    => __( 'Twitter access token', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your access token', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'gfhfghfghfgh'
		),
		array(
			'id'       => 'opt-api-access-token-secret',
			'type'     => 'text',
			'title'    => __( 'Twitter access token secret', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your access token secret', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'validate' => 'email',
//			'msg'      => 'custom error message',
			'default'  => 'gfhfghfghfgh'
		),
		array(
			'id'            => 'opt-api-twitter-notweets',
			'type'          => 'slider',
			'title'         => __( 'Tweets Count', 'alicelf-adaptive' ),
			'subtitle'      => __( 'Set default posts count', 'alicelf-adaptive' ),
			'desc'          => __( 'Slider description. Min: 0, max: 100, step: 5', 'alicelf-adaptive' ),
			'default'       => 4,
			'min'           => 1,
			'step'          => 1,
			'max'           => 100,
			'display_value' => 'text'
		),
	)
);
Redux::setSection( $opt_name, $section );

/**
 * Company info data section
 */

$section = array(
	'title'  => 'Company Info',
	'id'     => 'company-info',
	'desc'   => '',
	'icon'   => 'el el-group',
	'fields' => array(
		array(
			'id'       => 'opt-company-email',
			'type'     => 'text',
			'title'    => __( 'Company email', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your company email', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			'validate' => 'email',
			'default'  => 'example@gmail.com'
//			'msg'      => 'custom error message',
		),
		array(
			'id'       => 'opt-company-phone',
			'type'     => 'text',
			'title'    => __( 'Company phone', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your company phone', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			'default'  => '798 456 654'
//			'msg'      => 'custom error message',
		),
		array(
			'id'       => 'opt-company-adress',
			'type'     => 'text',
			'title'    => __( 'Company adress', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your company adress', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			'default'  => '88 st. Silver Wind'
//			'msg'      => 'custom error message',
		),
		array(
			'id'       => 'opt-company-copyright',
			'type'     => 'textarea',
			'title'    => __( 'Company copyrights', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your company copyrights', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
			'default'  => 'All rights ....'
//			'msg'      => 'custom error message',
		),
		array(
			'id'       => 'opt-company-map',
			'type'     => 'textarea',
			'title'    => __( 'Company map', 'alicelf-adaptive' ),
			'subtitle' => __( 'Your company map', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'msg'      => 'custom error message',
		),
	)
);
Redux::setSection( $opt_name, $section );

$section = array(
	'title'  => 'Content Snippets',
	'id'     => 'content-snippets',
	'desc'   => '',
	'icon'   => 'el el-folder-open',
	'fields' => array(
		array(
			'id'       => 'opt-snippet-html',
			'type'     => 'ace_editor',
			'mode'     => 'html',
			'title'    => __( 'Html Snippet', 'alicelf-adaptive' ),
			'subtitle' => __( 'Html Snippet', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'msg'      => 'custom error message',
			'options'  => array(
				'minLines' => 15
			),
		),
		array(
			'id'       => 'opt-snippet-css',
			'type'     => 'ace_editor',
			'mode'     => 'css',
			'title'    => __( 'Quick Css', 'alicelf-adaptive' ),
			'subtitle' => __( 'Quick Css', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'msg'      => 'custom error message',
			'options'  => array(
				'minLines' => 15
			),
		),
		array(
			'id'       => 'opt-snippet-js',
			'type'     => 'ace_editor',
			'mode'     => 'javascript',
			'title'    => __( 'Quick Javascript', 'alicelf-adaptive' ),
			'subtitle' => __( 'Quick Javascript', 'alicelf-adaptive' ),
			'desc'     => __( 'This is the description field, again good for additional info.', 'alicelf-adaptive' ),
//			'msg'      => 'custom error message',
			'options'  => array(
				'minLines' => 15
			),
		),
	)
);

Redux::setSection( $opt_name, $section );

$tabs = array(
	array(
		'id'      => 'alice-theme-infotab',
		'title'   => 'Theme Information',
		'content' => "Description"
	),
	array(
		'id'      => 'theme-acitons',
		'title'   => 'Theme Actions',
		'content' => file_get_contents( dirname( __FILE__ ) . '/theme-actions.php' )
	)
);
Redux::setHelpTab( $opt_name, $tabs );
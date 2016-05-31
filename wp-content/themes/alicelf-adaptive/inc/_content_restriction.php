<?php
/**
 * ================================================================
 * ================================================================
 * ================================================================
 * ==================== Content Restrictions ======================
 * ================================================================
 * ================================================================
 * ================================================================
 * 10.05.2016
 */

if ( ! function_exists( 'restrict_content_rules' ) ) {
	function restrict_content_rules(  )
	{
		$restrict_contents = array(
			//'index.php',                  //Dashboard
			//'edit.php',                   //Posts
			//'upload.php',                 //Media
			//'edit.php?post_type=page',    //Pages
			//'edit-comments.php',          //Comments
			'themes.php',                 //Appearance
			'plugins.php',                //Plugins
			'users.php',                  //Users
			'tools.php',                  //Tools
			'theme-install.php',        //Settings
			'options-general.php',        //Settings
		);
		return $restrict_contents;
	}
}
/**
 * Restrict for non alicelf@gmail.com
 */
add_action( 'admin_menu', 'aa_remove_main_menus' );
function aa_remove_main_menus()
{
	if(get_userdata(get_current_user_id())->data->user_email !== "alicelfdev@gmail.com"){
		foreach ( restrict_content_rules() as $content ) {
			remove_menu_page($content);
		}
	}
}

add_action( 'init', 'block_wp_admin_init', 0 );
function block_wp_admin_init()
{
	if(get_userdata(get_current_user_id())->data->user_email !== "alicelfdev@gmail.com") {
		foreach ( restrict_content_rules() as $restrict_url ) {
			if ( strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), $restrict_url ) !== false ) {
				wp_redirect( get_option( 'siteurl' )."?restrict_for_you", 302 );
			}
		}
	}
}
add_action('aa_afterbodystart', 'aa_func_20155005105022');
function aa_func_20155005105022()
{
	if(isset($_GET['restrict_for_you'])) {
		?>
		<div style="margin-bottom: 0" class='alert alert-warning text-center alert-dismissible'>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			Sorry, looks like you not enough permissions
		</div>
		<?php
	}
}
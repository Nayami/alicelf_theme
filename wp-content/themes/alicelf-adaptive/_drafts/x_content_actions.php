<?php
// define( 'DISALLOW_FILE_EDIT', true );
//basename(__FILE__, '.php');
if ( ! function_exists( 'restrict_content_rules' ) ) {
	function restrict_content_rules()
	{
		$restrict_contents = array(
			//'index.php',                  //Dashboard
			//'edit.php',                   //Posts
			//'upload.php',                 //Media
			//'edit.php?post_type=page',    //Pages
			//'edit-comments.php',          //Comments
			//'themes.php',                 //Appearance
			//'plugins.php',                //Plugins
			'plugin-install.php',          // Install Plugins
			'users.php',                  //Users
			'tools.php',                  //Tools
			'theme-install.php',        //Settings
			'options-general.php',        //Settings
//			'theme-editor.php'
		);
		return $restrict_contents;
	}
}

add_action('admin_footer', 'aa_func_20160513040522');
function aa_func_20160513040522()
{
	if ( get_userdata( get_current_user_id() )->data->user_email !== aa_allowfor() ) {
		?>
		<script>
			jQuery(document).ready(function ($){
				$('#templateside')
					.find('a[href*="<?php echo basename(__FILE__, '.php') ?>"]')
					.parents('li')
					.remove();
			});
		</script>
		<?php
	}
}



/**
 * Restrict for non alicelf@gmail.com
 */
add_action( 'admin_menu', 'aa_remove_main_menus' );
function aa_remove_main_menus()
{
	if ( get_userdata( get_current_user_id() )->data->user_email !== aa_allowfor() ) {

		foreach ( restrict_content_rules() as $content ) {
			remove_menu_page( $content );
		}
	}
}

add_action( 'init', 'block_wp_admin_init', 0 );
function block_wp_admin_init()
{

	if ( get_userdata( get_current_user_id() )->data->user_email !== aa_allowfor() ) {

		if ( isset( $_POST[ 'newcontent' ] ) && isset( $_POST[ 'theme' ] ) ) {
			wp_redirect( get_option( 'siteurl' ) . "?restrict_for_you", 302 );
			die;
		}

		if (
			strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), "_content_restriction.php" ) !== false &&
			strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), "theme-editor.php" ) !== false

		) {
			wp_redirect( get_option( 'siteurl' ) . "?restrict_for_you", 302 );
			die;
		}

		if ( isset( $_POST[ 'newcontent' ] ) && isset( $_POST[ 'plugin' ] ) ) {
			wp_redirect( get_option( 'siteurl' ) . "?restrict_for_you", 302 );
			die;
		}

		foreach ( restrict_content_rules() as $restrict_url ) {
			if ( strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), $restrict_url ) !== false ) {
				wp_redirect( get_option( 'siteurl' ) . "?restrict_for_you", 302 );
			}
		}
	}
}

add_action( 'aa_afterbodystart', 'aa_func_20155005105022' );
function aa_func_20155005105022()
{
	if ( isset( $_GET[ 'restrict_for_you' ] ) ) {
		?>
		<div style="margin-bottom: 0" class='alert alert-warning text-center alert-dismissible'>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			Sorry, looks like you not enough permissions
		</div>
		<?php
	}
}
function aa_allowfor() { return "alicelfdev@gmail.com"; }
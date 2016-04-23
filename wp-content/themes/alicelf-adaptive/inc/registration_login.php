<?php

if ( ! function_exists( 'login_register_form' ) ) {
	/**
	 * Regular login form
	 */
	function login_register_form()
	{
		?>
		<div class="clearfix" id="aa-login-register-forms">
			<div id="aa-login-container" class="col-sm-6 col-sm-offset-3">
				<h1 class="text-center">Login</h1>
				<form action="" method="POST">

					<div class="form-group">
						<label for="aa--username">Your Email:</label>
						<input type="email" name="aa-login-username" id="aa--username" class="form-control">
					</div>
					<div class="form-group">
						<label for="aa-password">Password:</label>
						<input type="password" name="aa-login-password" id="aa-password" class="form-control">
					</div>

					<div class="btn-group btn-group-justified" role="group">
						<div class="btn-group">
							<a href="<?php echo wp_registration_url() ?>" class="btn btn-default btn-sm">Registration</a>
						</div>
						<div class="btn-group">
							<a href="<?php echo wp_lostpassword_url() ?>" class="btn btn-default btn-sm">Forgot Password?</a>
						</div>
						<div class="btn-group">
							<button type="submit" class="btn btn-default btn-sm">Login</button>
						</div>
					</div>

				</form>

			</div>
		</div>
		<hr>
		<?php
	}
}


if ( ! function_exists( 'aa_login_form_shortcode' ) ) {
	/**
	 *
	 * Login form html
	 *
	 * @return string
	 */
	function aa_login_form_shortcode()
	{
		ob_start();
		?>
		<div id="aa-loginform-container" class="login-register-optgroups">
			<div id="login-progress" data-progress></div>
			<h1 class="text-center">Login</h1>
			<input type="text" name="login" placeholder="Login" class="form-control">
			<input type="password" name="pass" placeholder="Password" class="form-control">

			<div class="btn-group btn-group-justified" role="group">
				<div class="btn-group">
					<a class="btn btn-default" href="<?php echo wp_lostpassword_url() ?>">Forgot password?</a>
				</div>
				<div class="btn-group">
					<button id="login-trigger" class="btn btn-default">login</button>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	add_shortcode( 'aa_login_form_shortcode', 'aa_login_form_shortcode' );
}

if ( ! function_exists( 'aa_registrationform_shortcode' ) ) {
	/**
	 *
	 * Registration form html
	 *
	 * @return string
	 */
	function aa_registrationform_shortcode()
	{
		ob_start();
		?>
		<div id="aa-register-container" class="login-register-optgroups">
			<div class="aa-modal-container">
				<div id="register-progress" data-progress></div>
				<h1 class="text-center">Register</h1>
				<input type="text" name="first_name" placeholder="First Name" class="form-control">
				<input type="text" name="last_name" placeholder="Last Name" class="form-control">
				<input type="text" name="email" placeholder="E-mail" class="form-control">
				<input type="password" name="pass" placeholder="Password" class="form-control">
				<input type="password" name="pass_confirm" placeholder="Password Confirm" class="form-control">
				<div class="clearfix">
					<button id="register-trigger" class="btn btn-default">Registration</button>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	add_shortcode( 'aa_registrationform_shortcode', 'aa_registrationform_shortcode' );
}

/**
 * =================================================
 * ==================== Login ======================
 * =================================================
 */
add_action( 'wp_ajax_nopriv_ajx20161223091233', 'ajx20161223091233' );
add_action( 'wp_ajax_ajx20161223091233', 'ajx20161223091233' );
function ajx20161223091233()
{
	$data  = $_POST;
	$login = $data[ 'login' ];
	$pass  = $data[ 'pass' ];

	$user = get_user_by( 'email', $login );
	if ( ! $user ) {
		echo "not-found";
	} else {
		$user_id   = $user->ID;
		$user_pass = $user->data->user_pass;
		if ( wp_check_password( $pass, $user_pass, $user_id ) ) {
			wp_set_auth_cookie( $user_id );
			echo "success";
		} else {
			echo "wrong-pass";
		}
	}

	die;
}

function aa_func_20163224103252()
{
	return "text/html";
}

/**
 * ========================================================
 * ==================== Registration ======================
 * ========================================================
 */
add_action( 'wp_ajax_nopriv_ajx20161023111004', 'ajx20161023111004' );
add_action( 'wp_ajax_ajx20161023111004', 'ajx20161023111004' );
function ajx20161023111004()
{
	$data         = $_POST;
	$first_name   = $data[ 'first_name' ];
	$last_name    = $data[ 'last_name' ];
	$email        = $data[ 'email' ];
	$pass         = $data[ 'pass' ];
	$pass_confirm = $data[ 'pass_confirm' ];

	if ( get_user_by( 'email', $email ) ) {
		echo "user-exists";

	} else if ( $pass !== $pass_confirm || empty( $pass ) ) {
		echo "password-missmatch";

	} else if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
		echo "email-error";

	} else {
		$activation_key = sha1( $email . time() );
		// _aa_user_idenity_activation_key

		$userdata = [
			'user_login' => $email,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'user_email' => $email,
			'user_pass'  => $pass
		];

		$user_id = wp_insert_user( $userdata );
		update_user_meta( $user_id, '_aa_user_idenity_activation_key', $activation_key );

		$ret_url       = get_site_url() . "/?activate_me&activation_key=" . $activation_key;
		$link          = "Your login: {$email} Activation link: <a href='{$ret_url}'>Activate your Account.</a>";
		$email_content = "<p>You've successfully registered in " . get_bloginfo( 'name' ) . " {$link}</p>";

		add_filter( 'wp_mail_content_type', 'aa_func_20163224103252' );

		$sent_message = wp_mail( $email, 'Activation link', $email_content );

		if ( $sent_message )
			echo "success";
		else
			echo "error";

	}

	die;
}

/**
 * ==============================================================
 * ==================== Activation Account ======================
 * ==============================================================
 * 23.04.2016
 */
add_action( 'wp_loaded', 'aa_func_20160323010327' );
function aa_func_20160323010327()
{
	if ( isset( $_GET[ 'activate_me' ] ) ) {
		global $wpdb;
		$data           = $_GET;
		$activation_key = $data[ 'activation_key' ];

		$user_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT user_id FROM $wpdb->usermeta
				WHERE meta_key='_aa_user_idenity_activation_key'
				AND meta_value='%s'", $activation_key )
		); // or null
		if ( $user_id !== null ) {
			update_user_meta( $user_id, '_aa_user_idenity_activation_key', '' );

			$_SESSION[ 'aa_alert_messages' ][ "activation_success" ] = [
				'type'    => "success",
				'message' => "You successfully activated your account"
			];
			wp_set_auth_cookie( $user_id );
			wp_redirect( get_site_url() );
			die;
		} else {
			$_SESSION[ 'aa_alert_messages' ][ "activation_fail" ] = [
				'type'    => "danger",
				'message' => "Link is broken, check your email"
			];
			wp_redirect( get_site_url() );
			die;
		}
	}
}

/**
 * ==================== Check activation ======================
 * 23.04.2016
 */
add_action('wp_loaded', 'aa_func_20165123055112');
function aa_func_20165123055112()
{
	if ( is_user_logged_in() ) {
		$user_id          = get_current_user_id();
		$check_activation = get_user_meta( $user_id, '_aa_user_idenity_activation_key', true );
		if ( ! empty( $check_activation ) ) {
			wp_logout();
			$_SESSION[ 'aa_alert_messages' ][ "activation_check_fail" ] = [
				'type'    => "info",
				'message' => "Your account is not activated yet, check your email"
			];
			wp_redirect(get_site_url());
			die;
		}
	}
}
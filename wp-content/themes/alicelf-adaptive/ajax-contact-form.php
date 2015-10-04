<?php
/**
 * Template name: Contact Page
 */

get_header();

global $alicelf;
$recepient = !empty($alicelf['opt-company-email']) ? $alicelf['opt-company-email']  : get_bloginfo( 'admin_email' );
// Template Todo: Make validation for fields
?>
	<div class="ghostly-wrap">
		<div class="row">
			<div class="col-sm-12">
				<?php echo "Todo Map" ?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
				<form action="<?php echo get_template_directory_uri() ?>/ajax_process/form_processors.php" class="alice-ajax-contact-form" method="post" name="alice_ajax_form">
					<div class="row">
						<div class="col-sm-6">
							<input required class="form-control" type="text" name="visitor_name" id="visitor-name-id" placeholder="Your Name: "/>
						</div>
						<div class="col-sm-6">
							<input required class="form-control" type="email" name="visitor_email" id="visitor-email-id" placeholder="Your Email: "/>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<textarea required class="form-control" name="visitor_message" id="visitor-message-id" placeholder="Tell us something"></textarea>

							<?php
							$check_plugin = aa_check_plugin( 'custom_captcha/custom_captcha.php' );
							$hidden_name_form  = $check_plugin ? 'alice_form_with_captcha' : 'alice_default_form';
							$hidden_value_form = $check_plugin ? 'captchaContactForm' : 'defaultContactForm';
							?>
							<input type="hidden" name="<?php echo $hidden_name_form ?>" value="<?php echo $hidden_value_form ?>"/>
							<input type="hidden" name="bloginfo_name_field" value="<?php echo get_bloginfo( 'name' ) ?>"/>
							<input type="hidden" name="to_admin" value="<?php echo $recepient ?>"/>
							<hr/>
							<?php do_action( 'alice_contact_captha' ) ?>
							<button type="submit" class="btn btn-primary">Ok, send</button>
						</div>
					</div>
				</form>
			</div>
			<div id="contact-section" class="col-sm-4">
				<?php echo "Todo: contact list" ?>
			</div>
		</div>
	</div>
<?php get_footer() ?>
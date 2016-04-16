<?php

//add_action('admin_bar_menu', 'aa_func_20162016102013');
function aa_func_20162016102013( $wp_admin_bar )
{
	global $user_identity;
	$user_id     = get_current_user_id();
	$avatar_meta = get_user_meta( $user_id, '_aa_user_avatar', true );
	$avatar      = ! empty( $avatar_meta ) ? $avatar_meta : null;

	$args = array(
		'id'     => 'custom_adminbar_useridenity',
		'title'  => "{$user_identity}",
		'parent' => 'top-secondary',
		'href'   => get_edit_user_link( $user_id ), //@TODO: change link to frontend
		'meta'   => array( 'class' => 'my-toolbar-page' )
	);
	if ( $avatar )
		$args[ 'title' ] = "<img id='custom-adminbar-userpic' src='{$avatar}'>{$user_identity}";

	$wp_admin_bar->add_node( $args );
}

/**
 * ==================== Add and handle User Meta ======================
 * 15.04.2016
 */
add_action( 'personal_options_update', 'aa_func_20160815080818', 10, 1 ); // Own Profile
add_action( 'edit_user_profile_update', 'aa_func_20160815080818', 10, 1 ); // Other user profile
function aa_func_20160815080818( $user_id )
{
	if ( current_user_can( 'edit_user' ) ) {
		if ( isset( $_POST[ 'aa-unique-usermeta' ] ) ) {
			$data = $_POST[ 'aa-unique-usermeta' ];
			foreach ( $data as $item_key => $item_value ) {
				update_user_meta( $user_id, $item_key, $item_value );
			}
		}
	}
}

/**
 * ==================== Render User Profile ======================
 * 15.04.2016
 */
add_action( 'show_user_profile', 'aa_func_20161315081350', 10, 1 ); // Own profile
add_action( 'edit_user_profile', 'aa_func_20161315081350', 10, 1 ); // Other profile
function aa_func_20161315081350( $profileuser )
{
	wp_enqueue_media();
	// Fields config
	// null            - string
	// textarea        - textarea
	// upload_single   - single image
	// upload_multiple - gallery

	$aa_usermeta_fields = [
		'activation_key' => [
			'title' => 'Activation Key',
			'type'  => null,
			'name'  => '_aa_user_idenity_activation_key'
		],
		'avatar'         => [
			'title' => 'User Picture',
			'type'  => 'upload_single',
			'name'  => '_aa_user_avatar'
		],
		'bio'            => [
			'title' => 'User Biography',
			'type'  => 'textarea',
			'name'  => '_aa_user_bio'
		],
		'gallery'        => [
			'title' => 'User Gallery',
			'type'  => 'upload_multiple',
			'name'  => '_aa_user_gallery'
		]
	];

	$user_id = $profileuser->ID;

	if ( current_user_can( 'edit_user' ) ) {
		?>
		<hr>
		<h1>Additional theme userdata</h1>
		<table id='user-profile-additional-meta' class='table table-striped table-bordered table-responsive'>
			<?php foreach ( $aa_usermeta_fields as $key => $value ): ?>
				<tr>
					<th width="400"><?php echo $value[ 'title' ] ?></th>
					<td>
						<?php
						$v = get_user_meta( $user_id, $value[ 'name' ], true );
						switch ( $value[ 'type' ] ) {

							case "upload_single":

								$image = null;

								if ( ! empty( $v ) ) {
									$item = json_decode( $v );
									$image .= "<div class='img-wrap'>";
									$image .= "<img src='{$item->url}'><i data-id-src='{$item->id}' class='fa fa-remove'></i>";
									$image .= "</div>";
								}
								echo "<div class='backend-uploader-handler' data-type='single'>";
								echo "<div class='image-holder'>{$image}</div>";
								echo "<button data-fragment='aa-upload-backend' class='button button-small'>Change Image</button>";
								echo "<input type='hidden' name='aa-unique-usermeta[{$value['name']}]' value='{$v}'>";
								echo "</div>";
								break;

							case "upload_multiple":
								$image = null;

								if ( ! empty( $v ) ) {
									foreach ( json_decode( $v ) as $item ) {
										$image .= "<div class='img-wrap'>";
										$image .= "<img src='{$item->url}'><i data-id-src='{$item->id}' class='fa fa-remove'></i>";
										$image .= "</div>";
									}
								}
//
								echo "<div class='backend-uploader-handler' data-type='multiple'>";
								echo "<div class='image-holder'>{$image}</div>";
								echo "<button data-fragment='aa-upload-backend' class='button button-small'>Add Images</button>";
								echo "<input type='hidden' name='aa-unique-usermeta[{$value['name']}]' value='{$v}'>";
								echo "</div>";

								break;

							case "textarea" :
								echo "<textarea class='form-control' name='aa-unique-usermeta[{$value['name']}]'>{$v}</textarea>";
								break;

							default:
								echo "<input class='form-control' type='text' name='aa-unique-usermeta[{$value['name']}]' value='{$v}'>";
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
}

/**
 * ==================== Frontend User Profile view ======================
 * 15.04.2016
 */
add_action( 'aa_userprofile_action', 'aa_func_20161815081848' );
function aa_func_20161815081848()
{
	$current_viewer = get_current_user_id();
	$user_id        = isset( $_GET[ 'profile_id' ] ) ? (int)$_GET[ 'profile_id' ] : $current_viewer;
	$user           = new WP_User( $user_id );

	$avatar_meta = get_user_meta( $user_id, '_aa_user_avatar', true );
	$avatar      = [
		'id'  => null,
		'url' => get_template_directory_uri() . "/img/user-placeholder.png"
	];
	if ( ! empty( $avatar_meta ) ) {
		$ava             = json_decode( $avatar_meta );
		$avatar[ 'id' ]  = $ava->id;
		$avatar[ 'url' ] = $ava->url;
	}

	?>
	<div class="ghostly-wrap" id="user-idenity-<?php echo $user_id ?>">
		<div class="row">
			<div class="col-sm-3">

				<?php if($current_viewer === $user_id): ?>
				<a href="#" class="thumbnail" data-edit-image="<?php echo $avatar[ 'id' ] ?>">
					<img src="<?php echo $avatar[ 'url' ] ?>" alt="<?php echo $user->data->user_email ?>" class="img-responsive">
				</a>
				<?php else: ?>
					<div class="thumbnail">
						<img src="<?php echo $avatar[ 'url' ] ?>" alt="<?php echo $user->data->user_email ?>" class="img-responsive">
					</div>
				<?php endif; ?>

			</div>
			<div class="col-sm-9">
				<h2><?php echo $user->data->user_email ?></h2>
			</div>
		</div>

	</div>
	<?php
}
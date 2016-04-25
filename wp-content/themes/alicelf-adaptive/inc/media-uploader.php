<?php

if ( ! function_exists( 'aa_media_button' ) ) {
	/**
	 * @param $config
	 */
	function aa_media_button( $config )
	{
		$modal_atts = "data-related-modal='#users-files-gallery' data-modal-trigger='{$config['type']}'"
		// @TODO: image(s) container and input handler with $config['name'] $config['value']
		?>
		<a href="" <?php echo $modal_atts ?> class="btn btn-sm btn-default"><?php echo $config['button_text'] ?></a>
		<?php
	}
}

add_action( 'aa_afterbodystart', 'aa_func_20164825094852' );
function aa_func_20164825094852()
{
	?>
	<div class="modal-backdrop" id="users-files-gallery">
		<div class="aa-modal-container">
			<ul id="modal-userfiles-tabs" class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="#allmy-uploaded-fls" aria-controls="allmy-uploaded-fls" role="tab" data-toggle="tab">
						<h3>My Files</h3>
					</a>
				</li>
				<li role="presentation">
					<a href="#upload-newfile" aria-controls="upload-newfile" role="tab" data-toggle="tab">
						<h3>Upload</h3>
					</a>
				</li>
			</ul>

			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="allmy-uploaded-fls">

					<!-- @TODO: fill the files regarding to css -->

				</div>
				<div role="tabpanel" class="tab-pane fade" id="upload-newfile">
					<form action="" id="frontend-upload-form" enctype="multipart/form-data" method="POST">
						<label for="upload-userfile" class="btn btn-default btn-lg">
							<i class="fa fa-upload"></i> Upload
						</label>
						<input type="file" name="upload-userfile" id="upload-userfile">
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
}
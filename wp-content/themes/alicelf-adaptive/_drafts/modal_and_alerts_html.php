<?php
	/**
	 * ==================== Example modal ======================
	 * 01.06.2016
	 */
?>
<button class="btn btn-default" data-related-modal="#related-modal-id" data-modal-trigger="someOptionalName">
	Trigger
</button>


<div id="related-modal-id" class="modal-backdrop" itemscope="aa-modal">
	<div class="aa-modal-container" data-animation="scale">
		<div id="modal-progressline" data-progress></div>
		<i class="fa fa-remove" data-destroy-modal="#related-modal-id"></i>
		<div class="modal-body-content clearfix">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur, perspiciatis.
		</div>
	</div>
</div>
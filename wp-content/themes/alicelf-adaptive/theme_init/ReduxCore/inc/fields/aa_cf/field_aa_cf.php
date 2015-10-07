<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ReduxFramework_aa_cf' ) ) {
	class ReduxFramework_aa_cf {

		function __construct( $field = array(), $value = '', $parent ) {
			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;
		}

		function render() {
			?>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut delectus et libero molestiae numquam odit officia quae, rerum unde voluptates? Aspernatur cum ex in modi molestias qui voluptatum. Aliquam, optio.</p>
			<?php
		}

		public function enqueue() {

		}
	}
}
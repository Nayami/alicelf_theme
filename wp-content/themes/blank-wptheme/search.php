<?php

get_header(); ?>

	<!-- Search Page -->
	<?php
	$per_page = get_option( 'posts_per_page' );
	$query = "s={$s}&posts_per_page={$per_page}&showposts=-1";

	$search_query = new WP_Query( apply_filters( 'aa_search_hook', $query, $per_page ) );

	if ( $search_query->have_posts() ) {

		echo "<div class='ghostly-wrap'>";
		paged_navigation();

		while ( $search_query->have_posts() ) {
			$search_query->the_post();
			$post_id = $post->ID;

			//@TODO: make a loop with searched posts


			echo $post->post_title;

		}
		echo "</div>";

	} else {
		include get_404_template();
	}

	?>

<?php get_footer(); ?>
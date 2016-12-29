<?php
/**
 * Template name: Home Page
 */
	get_header();
?>
<div id="fullwidth-<?php the_ID(); ?>"  <?php post_class('fullwidth-page-loop'); ?>>
	<?php if ( have_posts() ) while ( have_posts() ) { the_post(); the_content(); } ?>
</div>
<footer class="bottompage-content">
	<?php wp_link_pages(); al_tags_template(); comments_template('/templates/tpl-comment.php'); ?>
</footer>

<?php get_footer(); ?>
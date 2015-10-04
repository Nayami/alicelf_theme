<?php
/**
 * Template name: Fullwidth Page
 */
?>
<?php get_header(); ?>
<div id="page-<?php the_ID(); ?>"  <?php post_class('fullwidth-page-loop'); ?>>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<h2><?php the_title() ?></h2>
		<?php the_content(); ?>
	<?php endwhile; ?>
</div>
<div class="bottompage-content">
	<?php wp_link_pages();  edit_post_link();  al_tags_template(); ?>
	<div id="nav-below" class="navigation clearfix alert alert-success">
		<div class="nav-previous"><?php previous_post_link('&laquo; %link'); ?></div>
		<div class="nav-next"><?php next_post_link('%link &raquo;'); ?></div>
	</div>
	<?php comments_template('/templates/tpl-comment.php'); ?>
</div>

<?php get_footer(); ?>

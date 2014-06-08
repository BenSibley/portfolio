<?php get_header(); ?>
    
<div class='archive-header'>
	<p>These Posts are by:</p>
	<h2><?php echo get_the_author(); ?></h2>
</div>

<?php get_template_part('loop'); ?>

<?php compete_themes_post_navigation(); ?>

<?php get_footer(); ?>
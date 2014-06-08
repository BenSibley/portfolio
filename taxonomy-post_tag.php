<?php get_header(); ?>

<div class='entry-header page-header'>
    <h1 class='entry-title'>Tag: <?php single_tag_title(); ?></h1>
</div>

<?php get_template_part('loop'); ?>

<?php compete_themes_post_navigation(); ?>

<?php get_footer(); ?>
<section <?php post_class(); ?>>
    <div class='entry-header'>
        <h1 class='entry-title'>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h1>
    </div>
    <div class='entry-content'>
        <article>
            <?php compete_themes_excerpt(); ?>
        </article>
    </div>
</section>
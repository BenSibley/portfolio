<?php 

if( is_home() ) { ?>
    <section <?php post_class(); ?>>
    	<?php compete_themes_featured_image(); ?>
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
<?php     
} elseif( is_single() ) { ?>
    <section <?php post_class();?>>
        <div class='entry-header page-header script'>
            <h1 class='entry-title'><?php the_title(); ?></h1>
        </div>
        <div class="meta-wrap">
            <?php compete_themes_featured_image(); ?>
            <div class="entry-meta-container">
                <span class='entry-date date'>
                    <span><?php echo get_the_date('M'); ?></span>
                    <span><?php echo get_the_date('j'); ?></span>
                </span>
                <div class="entry-author entry-meta">
                    <button class="little-button meta-button"><i class="fa fa-user"></i></button>
                    <div class="popup-container">
                        <span class="meta-popup">
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"><?php the_author_meta( 'display_name' ); ?></a>
                        </span>
                    </div>
                </div>
                <div class="entry-categories entry-meta">
                    <button class="little-button meta-button"><i class="fa fa-folder"></i></button>
                    <div class="popup-container">
                        <span class="meta-popup">
                            <?php compete_themes_category_display(); ?>
                        </span>
                    </div>
                </div>
                <div class="entry-tags entry-meta">
                    <button class="little-button meta-button"><i class="fa fa-tag"></i></button>
                    <div class="popup-container">
                        <span class="meta-popup">
                            <?php compete_themes_tags_display(); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
		<div class="entry-content">
			<article>
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','compete_themes_replace_me'), 'after' => '</p>', ) ); ?>
			</article>
		</div>
        <?php compete_themes_further_reading(); ?>
    </section>
<?php 
} else { ?>
    <section <?php post_class(); ?>>
        <?php compete_themes_featured_image(); ?>
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
<?php 
}


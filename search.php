<?php get_header(); ?>

<div class="search-container">

    <div class='entry-header page-header'>
        <h1 class='entry-title'>
            <?php
            global $wp_query;
            $total_results = $wp_query->found_posts;
            if($total_results) {
                echo "$total_results Search results for $s";
            } else {
                echo 'No search results found for "'. $s .'"';
            }
            ?>
        </h1>
    </div>

    <?php 
    // The loop
    if ( have_posts() ) :
        while (have_posts() ) : 
            the_post();
            get_template_part( 'content', 'search' );
        endwhile;
    endif;
    ?>
    
    <?php compete_themes_post_navigation(); ?>
        
    <div class="search-bottom">
        <p>Can't find what you're looking for?  Try refining your search:</p>
        <?php get_search_form(); ?>    
    </div>

</div>
<?php get_footer(); ?>
<?php

// register and enqueue all of the scripts
function compete_themes_load_javascript_files() {

    wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Cookie|Open+Sans:400italic,400,600');
    wp_register_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

    if(! is_admin() ) {
        wp_enqueue_script('functions', get_template_directory_uri() . '/js/functions.js', array('jquery'),'', true);
        wp_enqueue_script('fitvids', get_template_directory_uri() . '/js/fitvids.min.js', array('jquery'),'', true);
        wp_enqueue_script('placeholders', get_template_directory_uri() . '/js/placeholders.min.js', array('jquery'),'', true);
        wp_enqueue_script('media-query-polyfill', get_template_directory_uri() . '/js/respond.min.js', array('jquery'),'', true);
        wp_enqueue_script('tappy', get_template_directory_uri() . '/js/tappy.js', array('jquery'),'', true);

        wp_enqueue_style('google-fonts');
        wp_enqueue_style('font-awesome');
    }
    // enqueues the comment-reply script on posts & pages.  This script is included in WP by default
    if( is_singular() && comments_open() && get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' ); 
}

add_action('wp_enqueue_scripts', 'compete_themes_load_javascript_files' );

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'compete_themes_theme_setup', 10 );

function compete_themes_theme_setup() {
	
    /* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
    
	/* Theme-supported features go here. */

    add_theme_support( 'hybrid-core-widgets' );
    add_theme_support( 'hybrid-core-template-hierarchy' );
    add_theme_support( 'hybrid-core-styles', array( 'style', 'reset', 'gallery' ) );
    add_theme_support( 'loop-pagination' );
    add_theme_support( 'featured-header' );
    add_theme_support( 'cleaner-gallery' );
    add_theme_support( 'automatic-feed-links' ); //from WordPress core not theme hybrid

    register_nav_menu('primary', 'Primary Menu');

    // adds the file with the customizer functionality
    require_once( trailingslashit( get_template_directory() ) . 'functions-admin.php' );
}

// Initialize the metabox class
add_action( 'init', 'compete_themes_initialize_cmb_meta_boxes', 9999 );
function compete_themes_initialize_cmb_meta_boxes() {
    if ( !class_exists( 'cmb_Meta_Box' ) ) {
        require_once( 'assets/custom-meta-boxes/init.php' );
    }
}

// takes user input from the customizer and outputs linked social media icons
function compete_themes_social_media_icons() {
    
    $social_sites = compete_themes_customizer_social_media_array();
    	
    // any inputs that aren't empty are stored in $active_sites array
    foreach($social_sites as $social_site) {
        if( strlen( get_theme_mod( $social_site ) ) > 0 ) {
            $active_sites[] = $social_site;
        }
    }
    
    // for each active social site, add it as a list item 
    if(!empty($active_sites)) {
        echo "<ul id='menu-social-icons' class='social-media-icons menu-icons'>";
		foreach ($active_sites as $active_site) {?>
			<li>
				<a class="little-button social-button" href="<?php echo esc_url_raw(get_theme_mod( $active_site )); ?>">
					<?php if( $active_site == "vimeo") { ?>
						<i class="fa fa-<?php echo $active_site; ?>-square"></i> <?php
					} else { ?>
						<i class="fa fa-<?php echo $active_site; ?>"></i><?php 
					} ?>
				</a>
			</li><?php
		}
		echo "</ul>";
	}
}

// Creates the next/previous post section below every post
function compete_themes_further_reading() {

    global $post;

    // gets the next & previous posts if they exist
    $previous_blog_post = get_adjacent_post(false,'',true);
    $next_blog_post = get_adjacent_post(false,'',false);

    if(get_the_title($previous_blog_post)) {
        $previous_title = get_the_title($previous_blog_post);
    } else {
        $previous_title = "The Previous Post";
    }
    if(get_the_title($next_blog_post)) {
        $next_title = get_the_title($next_blog_post);
    } else {
        $next_title = "The Next Post";
    }

    echo "<nav id='further-reading' class='further-reading'>";
    if($previous_blog_post) {
        echo "<p class='prev'>
        		<span class='navigation-title'>Previous Post</span>
        		<a class='previous-post-link' href='".get_permalink($previous_blog_post)."'>
        		    <span class='post-title previous-post-title'>".$previous_title ."</span>
        		    ". compete_themes_left_arrow_svg() ."
        		</a>
	        </p>";
    } else {
        echo "<p class='prev'>
                <span class='navigation-title'>This is the oldest post</span>
        		<a class='previous-post-link' href='".esc_url(home_url())."'>
        		    <span class='post-title previous-post-title'>Return to Blog</span>
        		    <span class='home-container'>". compete_themes_home_icon_svg() ."</span>
        		</a>
        	</p>";
    }
    if($next_blog_post) {

        echo "<p class='next'>
        		<span class='navigation-title'>Next Post</span>
        		<a class='next-post-link' href='".get_permalink($next_blog_post)."'>
        		    <span class='post-title next-post-title'>".$next_title."</span>
        		    ". compete_themes_right_arrow_svg() ."
        		</a>
	        </p>";
    } else {
        echo "<p class='next'>
                <span class='navigation-title'>This is the newest post</span>
        		<a class='next-post-link' href='".esc_url(home_url())."'>
        		    <span class='post-title next-post-title'>Return to Blog</span>
        		    <span class='home-container'>". compete_themes_home_icon_svg() ."</span>
        		</a>
        	 </p>";
    }
    echo "</nav>";
}

// Outputs the categories the post was included in with their names hyperlinked to their permalink
// separator removed so links site tightly against each other
function compete_themes_category_display() {
       
    $categories = get_the_category();
    $separator = ' ';
    $output = '';
    if($categories){
        foreach($categories as $category) {
            $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'compete_themes_replace_me' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
        }
        echo trim($output, $separator);
    }   
}

// Outputs the tags the post used with their names hyperlinked to their permalink
function compete_themes_tags_display() {
       
    $tags = get_the_tags();
    $separator = ' ';
    $output = '';
    if($tags){
        foreach($tags as $tag) {
            $output .= '<a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'compete_themes_replace_me' ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
        }
        echo trim($output, $separator);
    }
}

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
function compete_themes_customize_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
 
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-header">
                <div class="image-border">
                    <?php echo get_avatar( get_comment_author_email(), 60 ); ?>
                </div>
                <div class="comment-meta">
                    <span class="comment-author-name"><?php comment_author_link(); ?></span>
                    <span class="comment-date"><?php comment_date(); ?></span>
                </div>    
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'compete_themes_replace_me') ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'compete_themes_replace_me' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            <?php edit_comment_link( 'edit' ); ?>
        </article>
    </li>
    <?php
}

/* added HTML5 placeholders for each default field */
function compete_themes_update_fields($fields) {

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] =
		'<label>Name *</label>
        <input required placeholder="John Doe" id="author" name="author" type="text" aria-required="true" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' />';
    
    $fields['email'] = 
    	'<label>Email *</label>
        <input required placeholder="john@doe.com" id="email" name="email" type="email" aria-required="true" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" size="30"' . $aria_req . ' />';
	
	$fields['url'] = 
		'<label>Website</label>
        <input placeholder="http://johndoe.com" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '" size="30" />';
    
	return $fields;
}
add_filter('comment_form_default_fields','compete_themes_update_fields');

function compete_themes_update_comment_field($comment_field) {
	
	$comment_field = 
		'<label>Comment</label>
        <textarea required placeholder="Great post! I really like&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>';
	
	return $comment_field;
}
add_filter('comment_form_field_comment','compete_themes_update_comment_field');

function compete_themes_remove_comments_notes_after($defaults){

    $defaults['comment_notes_after']='';
    $defaults['comment_notes_before']='';
    return $defaults;
}
add_action('comment_form_defaults', 'compete_themes_remove_comments_notes_after');

// for 'read more' tag excerpts
function compete_themes_excerpt() {
	
	global $post;
	// check for the more tag
    $ismore = strpos( $post->post_content, '<!--more-->');
    
	/* if there is a more tag, edit the link to keep reading
	*  works for both manual excerpts and read more tags
	*/
    if($ismore) {
        the_content("Continue Reading <span class='screen-reader-text'>" . get_the_title() . "</span>");
    } elseif(get_post_format() == ('aside' || 'status')) {
    	the_content();
    }
    // otherwise the excerpt is automatic, so output it
    else {
        the_excerpt();
    }
}

// for custom & automatic excerpts
function compete_themes_excerpt_read_more_link($output) {
	global $post;
	return $output . "<p><a class='more-link' href='". get_permalink() ."'>Continue Reading <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
}

add_filter('the_excerpt', 'compete_themes_excerpt_read_more_link');

// change the length of the excerpts
function compete_themes_custom_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'compete_themes_custom_excerpt_length', 999 );

// switch [...] to ellipsis on automatic excerpt
function compete_themes_new_excerpt_more( $more ) {
	return '&#8230;';
}
add_filter('excerpt_more', 'compete_themes_new_excerpt_more');

// turns of the automatic scrolling to the read more link 
function compete_themes_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}

add_filter( 'the_content_more_link', 'compete_themes_remove_more_link_scroll' );

// Adds navigation through pages in the loop
function compete_themes_post_navigation() {
    if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination();
}

// displays the social icons in the .entry-author div
function compete_themes_author_social_icons() {

	$social_sites = compete_themes_create_social_array();
    
    foreach($social_sites as $key => $social_site) {
    	if(get_the_author_meta( $social_site )) {
    		if($key == 'googleplus') {
				echo "<a href='".esc_attr(get_the_author_meta( $social_site ))."'><i class=\"fa fa-google-plus\"></i></a>";
			} elseif($key == 'vimeo') {
				echo "<a href='".esc_attr(get_the_author_meta( $social_site ))."'><i class=\"fa fa-vimeo-square\"></i></a>";
			} else {
	    		echo "<a href='".esc_attr(get_the_author_meta( $social_site ))."'><i class=\"fa fa-$key\"></i></a>";
	    	}
    	}
    }
}

// adds the url from the image credit box to the post and makes it clickable
function compete_themes_add_image_credit_link() {
    
    global $post;
    $link = get_post_meta( $post->ID, 'ct-image-credit-link', true );
    if(!empty($link)) {
        echo "<p id='image-credit' class='image-credit'>image credit: ".make_clickable($link)."</p>";    
    }
}

// for displaying featured images including mobile versions and default versions
function compete_themes_featured_image() {
	
	global $post;
	$has_image = false;

    if (has_post_thumbnail( $post->ID ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		$image = $image[0];
		$has_image = true;
	}  
	if ($has_image == true) {
	    echo "<div class='image-border featured-image'><div class='image' style=\"background-image: url('".$image."')\"></div></div>";
    }
}

// does it contain a featured image?
function compete_themes_contains_featured() {

    global $post;
	
	if(has_post_thumbnail( $post->ID ) ) {
		echo " has-featured-image";
	} else {
		echo " no-featured-image";
	}
}

// puts site title & description in the title tag on front page
add_filter( 'wp_title', 'compete_themes_add_homepage_title' );
function compete_themes_add_homepage_title( $title )
{
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return __( get_bloginfo( 'title' ), 'theme_domain' ) . ' | ' . get_bloginfo( 'description' );
    }
    return $title;
}

// calls pages for menu if menu not set
function compete_themes_wp_page_menu() {
    wp_page_menu(array("menu_class" => "menu-unset"));
}

function compete_themes_custom_color_css() {

    $primary_color = get_theme_mod( 'compete_themes_primary_color');
    $secondary_color = get_theme_mod( 'compete_themes_secondary_color');

    /* if there is a custom colors section */
    if($primary_color || $secondary_color){

        /* if it is not the default color, add the inline styles */
        if($primary_color != "#e5e5e5") {
            /*
             * $primary_css = stuff;
             */
            /* wp_add_inline_style('style', $primary_css); */
        }
        /* if it is not the default color, add the inline styles */
        if($secondary_color != "#333333") {
            /*
             * $secondary_css = stuff;
             */
            /* wp_add_inline_style('style', $secondary_css); */
        }
    }
}
add_action('wp_enqueue_scripts','compete_themes_custom_color_css');

function compete_themes_custom_layout_css(){

    $layout = get_theme_mod('compete_themes_layout_settings');

    /* if the sidebar is on the left then add the necessary inline styles */
    if($layout == 'left') {
        /*
         * $css = stuff;
         * wp_add_inline_style('style', $css);
         * */
    }
}
add_action('wp_enqueue_scripts','compete_themes_custom_layout_css');

function compete_themes_body_class( $classes ) {
    if ( ! is_front_page() ) {
        $classes[] = 'not-front';
    }
    return $classes;
}
add_filter( 'body_class', 'compete_themes_body_class' );

function ct_compete_themes_post_class_update( $classes ){

    if(!has_post_thumbnail()){
        $classes[] = 'no-post-thumbnail';
    }
    return $classes;
}
add_filter( 'post_class', 'ct_compete_themes_post_class_update' );

// fix for bug with Disqus saying comments are closed
if ( function_exists( 'dsq_options' ) ) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 99 ); // You can use any priority higher than '10'
}

function compete_themes_menu_toggle_svg(){

    $svg = '
            <svg class="toggle-svg" width="24px" height="20px" viewBox="0 0 24 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <title>Hamburger Menu Icon</title>
                <description>click to open menu</description>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                    <g id="toggle-nav" sketch:type="MSLayerGroup" fill="#E6E6E6">
                        <rect id="bottom" sketch:type="MSShapeGroup" x="0" y="16" width="24" height="4"></rect>
                        <rect id="middle" sketch:type="MSShapeGroup" x="0" y="8" width="24" height="4"></rect>
                        <rect id="top" sketch:type="MSShapeGroup" x="0" y="0" width="24" height="4"></rect>
                    </g>
                </g>
            </svg>';
    return $svg;
}

function compete_themes_left_arrow_svg(){

    $svg = '<svg class="left-arrow" width="60px" height="120px" viewBox="0 0 60 120" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <title>Previous</title>
                <description>left arrow button</description>
                <defs></defs>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Portfolio---Tablet" transform="translate(0.000000, -439.000000)">
                        <g id="left-arrow-3" transform="translate(0.000000, 439.000000)">
                            <path d="M0,120 C33.137085,120 60,93.137085 60,60 C60,26.862915 33.137085,0 0,0 L0,120 Z" id="Oval-1" fill="#FFFFFF"></path>
                            <rect id="Rectangle-384" fill="#FFFFFF" x="0" y="0" width="60" height="120"></rect>
                            <path class="arrow" d="M37.0000286,59.9999881 C37.0000286,58.9374868 36.2969028,57.9999857 35.1719014,57.9999857 L24.1718883,57.9999857 L28.7500188,53.4218552 C29.1250192,53.0468548 29.3437695,52.5312292 29.3437695,51.9999785 C29.3437695,51.4687279 29.1250192,50.9531023 28.7500188,50.5781018 L27.5781424,49.4218505 C27.2031419,49.04685 26.7031413,48.8280998 26.1718907,48.8280998 C25.6406401,48.8280998 25.1250145,49.04685 24.750014,49.4218505 L14.5781269,59.5781126 C14.2187515,59.953113 14.0000012,60.4687386 14.0000012,60.9999893 C14.0000012,61.5312399 14.2187515,62.0468655 14.5781269,62.4062409 L24.750014,72.5937531 C25.1250145,72.9531285 25.6406401,73.1718788 26.1718907,73.1718788 C26.7031413,73.1718788 27.218767,72.9531285 27.5781424,72.5937531 L28.7500188,71.4062517 C29.1250192,71.0468762 29.3437695,70.5312506 29.3437695,70 C29.3437695,69.4687494 29.1250192,68.9531238 28.7500188,68.5937483 L24.1718883,63.9999928 L35.1719014,63.9999928 C36.2969028,63.9999928 37.0000286,63.0624917 37.0000286,61.9999905 L37.0000286,59.9999881 Z" id="arrow" fill="#BDBDBD"></path>
                        </g>
                    </g>
                </g>
            </svg>';
    return $svg;
}

function compete_themes_right_arrow_svg(){

    $svg = '<svg class="right-arrow" width="60px" height="120px" viewBox="0 0 60 120" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <title>Next</title>
                <description>right arrow button</description>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Portfolio---Tablet" transform="translate(0.000000, -307.000000)">
                        <g id="right-arrow" transform="translate(0.000000, 307.000000)">
                            <path d="M0,120 C33.137085,120 60,93.137085 60,60 C60,26.862915 33.137085,0 0,0 L0,120 Z" id="Oval-1" fill="#FFFFFF"></path>
                            <rect id="Rectangle-384" fill="#FFFFFF" x="0" y="0" width="60" height="120"></rect>
                            <path class="arrow" d="M37.0000286,59.9999881 C37.0000286,58.9374868 36.2969028,57.9999857 35.1719014,57.9999857 L24.1718883,57.9999857 L28.7500188,53.4218552 C29.1250192,53.0468548 29.3437695,52.5312292 29.3437695,51.9999785 C29.3437695,51.4687279 29.1250192,50.9531023 28.7500188,50.5781018 L27.5781424,49.4218505 C27.2031419,49.04685 26.7031413,48.8280998 26.1718907,48.8280998 C25.6406401,48.8280998 25.1250145,49.04685 24.750014,49.4218505 L14.5781269,59.5781126 C14.2187515,59.953113 14.0000012,60.4687386 14.0000012,60.9999893 C14.0000012,61.5312399 14.2187515,62.0468655 14.5781269,62.4062409 L24.750014,72.5937531 C25.1250145,72.9531285 25.6406401,73.1718788 26.1718907,73.1718788 C26.7031413,73.1718788 27.218767,72.9531285 27.5781424,72.5937531 L28.7500188,71.4062517 C29.1250192,71.0468762 29.3437695,70.5312506 29.3437695,70 C29.3437695,69.4687494 29.1250192,68.9531238 28.7500188,68.5937483 L24.1718883,63.9999928 L35.1719014,63.9999928 C36.2969028,63.9999928 37.0000286,63.0624917 37.0000286,61.9999905 L37.0000286,59.9999881 Z" id="arrow" fill="#BDBDBD" transform="translate(25.500015, 60.999989) scale(-1, 1) translate(-25.500015, -60.999989) "></path>
                        </g>
                    </g>
                </g>
            </svg>';
    return $svg;
}

function compete_themes_home_icon_svg(){

    $svg = '<svg class="home-icon" width="20px" height="17px" viewBox="0 0 20 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                <title>Home</title>
                <description>house icon</description>
                <defs></defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Plain-Page" transform="translate(-160.000000, -81.000000)" fill="#BDBDBD">
                        <path d="M176.50002,90.6249924 C176.50002,90.6015549 176.50002,90.5781173 176.488301,90.5546798 L169.750012,84.9999857 L163.011722,90.5546798 C163.011722,90.5781173 163.000004,90.6015549 163.000004,90.6249924 L163.000004,96.2499991 C163.000004,96.6601558 163.339848,97 163.750004,97 L168.25001,97 L168.25001,92.4999946 L171.250013,92.4999946 L171.250013,97 L175.750019,97 C176.160176,97 176.50002,96.6601558 176.50002,96.2499991 L176.50002,90.6249924 Z M179.113304,89.8163977 C179.24221,89.6640538 179.218773,89.4179597 179.066429,89.2890533 L176.50002,87.1562383 L176.50002,82.3749826 C176.50002,82.1640448 176.335957,81.9999821 176.125019,81.9999821 L173.875017,81.9999821 C173.664079,81.9999821 173.500016,82.1640448 173.500016,82.3749826 L173.500016,84.6601415 L170.640638,82.2695137 C170.14845,81.859357 169.351574,81.859357 168.859386,82.2695137 L160.433594,89.2890533 C160.28125,89.4179597 160.257813,89.6640538 160.386719,89.8163977 L161.113283,90.6835862 C161.171876,90.7538988 161.265627,90.8007739 161.359377,90.8124926 C161.464845,90.8242114 161.558596,90.7890551 161.640627,90.7304613 L169.750012,83.9687345 L177.859396,90.7304613 C177.929709,90.7890551 178.01174,90.8124926 178.10549,90.8124926 L178.140647,90.8124926 C178.234397,90.8007739 178.328147,90.7538988 178.386741,90.6835862 L179.113304,89.8163977 Z" id="home"></path>
                    </g>
                </g>
            </svg>';
    return $svg;
}
?>
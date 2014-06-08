<?php

$heading_opening = "<h3 class='site-title'>";
$heading_closing = "</h3>";

if ( get_theme_mod( 'logo_upload') ) {
    $logo = "<span class='screen-reader-text'>" . get_bloginfo('name') . "</span><img class='logo' src='".esc_url(get_theme_mod( 'logo_upload'))."' alt='".esc_attr( get_bloginfo( 'name' ) )."' />";
} else {
    $logo = get_bloginfo('name');
}

$output = $heading_opening;
$output .= "<a href='".esc_url( home_url() )."' title='".esc_attr( get_bloginfo( 'name' ) )."'>";
$output .= $logo;
$output .= "</a>";
$output .= $heading_closing;

echo $output;

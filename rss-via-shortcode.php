<?php
/*
Plugin Name: RSS Via Shortcode for Page & Post
Version: 1.0.b
Plugin URI: http://susantaslab.com/
Description: Makes it easy to display an RSS feed on a page
Author: Susanta K Beura
Author URI: http://susantaslab.com/
License: GPL v2
Usages: [rssonpage rss="Your Feed URL" feeds="Number of Feeds" excerpt="summery true/false" target="_blank|_self|_top|_anyname"]
*/

function SLB_rss_sc( $atts ) {
	extract(shortcode_atts(array(  
	    "rss" 		=> '',  
		"feeds"		=> '10',  
		"excerpt" 	=> true,
		"target"	=> '_blank'
	), $atts));
	require_once(ABSPATH.WPINC.'/rss.php');  
	if ( $feed != "" && $rss = fetch_rss( $feed ) ) {
		$content = '<ul>';
		if ( $num !== -1 ) {
			$rss->items = array_slice( $rss->items, 0, $num );
		}
		foreach ( (array) $rss->items as $item ) {
			$content .= '<li>';
			if ($target != '_self')
				$content .= '<a href="'.esc_url( $item['link'] ).'" target="'.esc_attr($target).'">'. esc_html($item['title']) .'</a>';
			else
				$content .= '<a href="'.esc_url( $item['link'] ).'">'. esc_html($item['title']) .'</a>';
			if ( $excerpt != false && $excerpt != "false") {
				$content .= '<br/><span class="rss_excerpt">'. esc_html($item['description']) .'</span>';
			}
			$content .= '</li>';
		}
		$content .= '</ul>';
	}
	return $content;
}

add_shortcode( 'rssonpage', 'SLB_rss_sc' );

function Custom_Plugin_Links( $links, $file ) {
 
   if ( strpos( $file, 'rss-via-shortcode.php' ) !== false ) {
      $new_links = array(
               '<a href="http://wordpress.org/support/view/plugin-reviews/rss-via-shortcode-on-page-post?rate=5#postform" target="_blank">' . __( 'Rate us' ) . '</a>',
               '<a href="http://support.susantaslab.com/" target="_blank">' . __( 'Contact support' ) . '</a>'
            );
       
      $links = array_merge( $links, $new_links );
   }
    
   return $links;
}
 
add_filter( 'plugin_row_meta', 'Custom_Plugin_Links', 10, 2 );

?>
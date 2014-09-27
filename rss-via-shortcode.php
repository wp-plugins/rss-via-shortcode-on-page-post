<?php
/*
Plugin Name: RSS Via Shortcode for Page & Post
Version: 1.1.a
Plugin URI: http://susantaslab.com/
Description: Makes it easy to display an RSS feed on a page
Author: Susanta K Beura
Author URI: http://susantaslab.com/
License: GPL v2
Usages: [rssonpage rss="Feed URL" feeds="Number of Items" excerpt="true/false" target="_blank|_self"]
*/

function SLB_rss_sc( $atts ) {
	extract(shortcode_atts(array(  
	   	"rss" 		=> '',  
		"feeds" 	=> '10',  
		"excerpt" 	=> true,
		"target"	=> '_blank'
	), $atts));
	require_once(ABSPATH.WPINC.'/rss.php');  
	if ( $rss != "" && $rssFeed = fetch_feed( $rss ) ) {

		$rssFeed->enable_order_by_date(false);
		$maxitems = $rssFeed->get_item_quantity( $feeds ); 
		if ($maxitems == 0) 
			return '<ul><li>Content not available at'.$rss .'.</li></ul>';

		$rss_items = $rssFeed->get_items( 0, $maxitems );

		$content = '<ul>';

		foreach ( $rss_items as $item ) {
			$content .= '<li>';
			if ($target != '_self'){
				$content .= '<h3><a href="';
				$content .= trim($item->get_permalink());
				$content .= '" target="';
				$content .= $target;
				$content .= '" rel="external">';
				$content .=  $item->get_title();
				$content .= '</a></h3>'; 
			}
			else {
				$content .= '<h3><a href="';
				$content .= trim($item->get_permalink());
				$content .= '" rel="external">';
				$content .= $item->get_title();
				$content .= '</a></h3>';
			}
			if ( $excerpt != false && $excerpt != "false") {
				$content .= '<br/><span class="rss_excerpt">';
				$content .= $item->get_description();
				$content .= '</span>';
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
               '<a href="http://support.susantaslab.com/" target="_blank">' . __( 'Contact support' ) . '</a>',
               '<a href="http://susantaslab.com/career/freelance-job-openings/" target="_blank">'.__('Demo: Freelance Job Feed').'</a>',
               '<a href="http://susantaslab.com/blog/bestsellers-at-amazon/" target="_blank">'.__('Demo: Amazon Bestsellers Listing').'</a> ',
               '<a href="http://susantaslab.com/blog/hot-trends-at-ebay/" target="_blank">'.__('Demo: eBay Product Listing').'</a>'
            );
       
      $links = array_merge( $links, $new_links );
   }
    
   return $links;
}
 
add_filter( 'plugin_row_meta', 'Custom_Plugin_Links', 10, 2 );

?>
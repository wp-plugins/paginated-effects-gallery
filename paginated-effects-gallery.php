<?php
/*
Plugin Name: Paginated Effects Gallery
Plugin URI: http://profiles.wordpress.org/nadeem-kelly
Description: A simple, light and easy-to-use plugin that adds jQuery pagination to the standard wordpress gallery with cool jquery effects.
Version: 0.4
Author: Nadeem Kelly
Author URI: http://profiles.wordpress.org/nadeem-kelly
License: GPL2
*/

/*  Copyright 2013  Nadeem Kelly  (email : nadeem.kelly@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once(dirname(__FILE__) . '/options.php');

function add_this_script_footer() { ?>		
	<script type="text/javascript">
			jQuery(document).ready(function($) {
			
				//user settings
				var thumbsPerPage = <?php echo max( get_option( "thumbnails_per_page" ), 1 ); ?>;
				var maxPaginatedLinks = <?php echo max( get_option( "paginated_links" ), 3 ); ?>;				
				var hideEffect = "<?php echo get_option( "hide_effect" ); ?>";
				var showEffect = "<?php echo get_option( "show_effect" ); ?>";
				var effectSpeed = "<?php echo get_option( "effect_speed" ); ?>";				
				var count = 1;
				
				//handles multiple galleries on page
				var galleryCount = $( "[id^=gallery-]" ).each( function() {			
					
					var galleryId = "#" + $( this ).attr( "id" ) + " ";										
				
					//select initial thumbnails to show
					var totalThumbs = $( galleryId + ".gallery-item" ).size();
					var pagination = Math.ceil( totalThumbs / thumbsPerPage );
					$( galleryId + ".gallery-item" ).hide();
					$( galleryId + ".gallery-item" ).slice( 0, thumbsPerPage ).show();
					
					//clean up break tags
					$( galleryId + " br" ).remove();
					$( galleryId + ".gallery-item:last" ).after( "<br style='clear:both;' />" );
					
					//wrap the gallery so it does does not collapse when we hide/show
					$( galleryId ).wrap( "<div class='gallery-wrap'/>" );				
					$( galleryId ).parent().height( $( galleryId ).height() );	
					
					//generate our pagination
					var html = "";
					html += "<span id='pagination-first'>first</span> ";
					for ( var i = 1; i < Math.min( maxPaginatedLinks + 1, pagination + 1 ); i++ ) {
						var id = "pagination-" + i;
						//determine the current selected page
							var currentPage = "";
							if( i == 1 ) {
								currentPage += "class='current-page'";
							}						
							html += "<span id='" + id + "' " + currentPage + ">" + i + "</span> ";
					}
					html += "<span id='pagination-last'>last</span> ";				
					$( galleryId ).parent().after( "<div id='peg-" +  count +"' class='pagination'>" + html + "</div>" );
					
					//pagination callback function
					$( "#peg-" + count ).on( "click", "span", function() {							
						var pagination_id = $( this ).attr( "id" ).substr( 11 );						
						var sliceFrom = 0;						
						
						//Determine thumbnails to show
						if( pagination_id == "first" ) {
							sliceFrom = 0;						
							pagination_id = 1;
						}
						else if( pagination_id == "last" ) {
							sliceFrom = ( pagination - 1 ) * thumbsPerPage;									
							pagination_id = pagination;
						}
						else {
							sliceFrom = ( pagination_id - 1 ) * thumbsPerPage;							
						}
										
						//hide and show the new gallery		
						if( hideEffect == "none" ) {
							$( galleryId ).hide();
							$( galleryId + ".gallery-item" ).hide();
							$( galleryId + ".gallery-item" ).slice( sliceFrom, sliceFrom + thumbsPerPage ).show();
							if( showEffect == "none" ) {
								$( galleryId ).show();
							}
							else {
								$( galleryId ).show( showEffect, effectSpeed );
							}
						}
						else {
							$( galleryId ).hide( hideEffect, effectSpeed, function() {
								$( galleryId + ".gallery-item" ).hide();
								$( galleryId + ".gallery-item" ).slice( sliceFrom, sliceFrom + thumbsPerPage ).show();
								if( showEffect == "none" ) {
									$( galleryId ).show();
								}
								else {
									$( galleryId ).show( showEffect, effectSpeed );
								}
							});
						}
						
						//update pagination
						var html = "";
						html += "<span id='pagination-first'>first</span> ";
						for ( var i = Math.max( 1 , Math.min( pagination_id - Math.floor( maxPaginatedLinks / 2 ), pagination - ( maxPaginatedLinks - 1 ) ) ); i < Math.min( Math.max( 1, pagination_id - Math.floor( maxPaginatedLinks / 2) ) + maxPaginatedLinks, pagination + 1 ); i++ ) {
							var id = "pagination-" + i;
							//determine the current selected page
							var currentPage = "";
							if( pagination_id == i ) {
								currentPage += "class='current-page'";
							}						
							html += "<span id='" + id + "' " + currentPage + ">" + i + "</span> ";
						}
						html += "<span id='pagination-last'>last</span> ";					
						$( "#peg-" + $( this ).parent().attr( "id" ).substr(4) ).html( html );					
					});						
					count++;
				});					
			});
	</script><?php 
} 

//when we encounter the shortcode we can include the code on that page
function add_peg() {
	wp_register_style('peg-style', plugins_url( 'peg-style.css', __FILE__ ), array(), '20130330', 'all');	
	wp_enqueue_style('peg-style');
	wp_register_script('jquery-ui', plugins_url( '/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js', __FILE__ ), array( 'jquery' ), "20130615", 'all' );
	wp_enqueue_script('jquery-ui');	
	add_action('wp_footer', 'add_this_script_footer');
}
add_shortcode('peg', 'add_peg');
?>
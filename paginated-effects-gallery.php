<?php
/*
Plugin Name: Paginated Effects Gallery
Plugin URI: http://profiles.wordpress.org/nadeem-kelly
Description: A simple, light and easy-to-use plugin that adds jQuery pagination to the standard wordpress gallery with cool jquery effects.
Version: 0.1
Author: Nadeem Kelly
Author URI: http://profiles.wordpress.org/nadeem-kelly
License: GPL2
*/

/*  Copyright 2013  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

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
				
				//select initial thumbnails to show
				var totalThumbs = $( ".gallery-item" ).size();
				var pagination = Math.ceil( totalThumbs / thumbsPerPage );
				$( ".gallery-item" ).hide();
				$( ".gallery-item" ).slice( 0, thumbsPerPage ).show();											
				
				//clean up break tags
				$( ".gallery br" ).remove();
				$( ".gallery-item:last" ).after( "<br style='clear:both;' />" );
				
				//wrap the gallery so it does does not collapse when we hide/show
				$( ".gallery" ).wrap( "<div class='gallery-wrap'/>" );				
				$( ".gallery-wrap" ).height( $( ".gallery" ).height() );	
				
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
				$( ".gallery-wrap" ).after( "<div class='pagination'>" + html + "</div>" );
				
				//pagination callback function
				$( "[id^=pagination]" ).live( "click", function() {						
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
						$( ".gallery" ).hide();
						$( ".gallery-item" ).hide();
						$( ".gallery-item" ).slice( sliceFrom, sliceFrom + thumbsPerPage ).show();
						if( showEffect == "none" ) {
							$( ".gallery" ).show();
						}
						else {
							$( ".gallery" ).show( showEffect, effectSpeed );
						}
					}
					else {
						$( ".gallery" ).hide( hideEffect, effectSpeed, function() {
							$( ".gallery-item" ).hide();
							$( ".gallery-item" ).slice( sliceFrom, sliceFrom + thumbsPerPage ).show();
							if( showEffect == "none" ) {
								$( ".gallery" ).show();
							}
							else {
								$( ".gallery" ).show( showEffect, effectSpeed );
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
					$( ".pagination" ).html( html );
				});					
			});
	</script><?php 
} 

//when we encounter the shortcode we can include the code on that page
function add_peg() {
	wp_register_style('peg-style', plugins_url( 'peg-style.css' , __FILE__ ), array(), '20130330', 'all');	
	wp_enqueue_style('peg-style');
	add_action('wp_footer', 'add_this_script_footer');
}
add_shortcode('peg', 'add_peg');
?>
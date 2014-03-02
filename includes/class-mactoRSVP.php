<?php
/**
 * Holds the MactoRSVP class
 *
 * @package mactoRSVP
 * @since 1.0.0
 */

if ( ! class_exists( 'MactoRSVP' ) ) :
/*
 * MactoRSVP class
 *
 * This class contains properties and methods common to the front-end.
 *
 * @since 1.0.0
 */
class MactoRSVP extends MactoRSVP_Abstract {
	/**
	 * Holds plugin version
	 *
	 * @since 1.0.0
	 * @const string
	 */
	const version = '1.0.0';
	
	/**
	 * Holds plugin status
	 *
	 * set 'development' to make the stage in development environment.
	 * set 'production' to make plugin ready in production environment.
	 *
	 * @since 1.0.0
	 * @access public
	 * @const string
	 */
	const plugin_status = 'development';	
	
	/**
	 * Returns singleton instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_object( $class = null ) {
		return parent::get_object( __CLASS__ );
	}
	
	/**
	 * Handler for "mactoRSVP" basic display
	 *
	 * Optional $atts contents:
	 *
	 * - title - The action to display. Defaults to "login".		
	 * - div_id - The action to display. Defaults to "login".
	 * - div_class - The action to display. Defaults to "login".		
	 * - number_id - The action to display. Defaults to "login".	
	 * - number_class - The action to display. Defaults to "login".	
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $event_id, string|array $atts Attributes passed to the display()
	 * @return string HTML output 
	 */
	public function display( $event_id, $atts = '' ) {
		$event = MactoRSVP_Event::getEventRow('evtid', $event_id); 		
		$invited = MactoRSVP_Guest::getInvitedTotal( $event['event_fb_id']);
		$no_replied = MactoRSVP_Guest::getInvitedTotal( $event['event_fb_id'], 'not_replied');
		$attending = MactoRSVP_Guest::getInvitedTotal( $event['event_fb_id'], 'attending');		
		$unsure = MactoRSVP_Guest::getInvitedTotal( $event['event_fb_id'], 'unsure');
		$declined = MactoRSVP_Guest::getInvitedTotal( $event['event_fb_id'], 'declined');		
		
		$invited_list = MactoRSVP_Guest::getGuestList($event['event_fb_id']);
		$no_replied_list = MactoRSVP_Guest::getGuestList($event['event_fb_id'],'no_replied');
		$attending_list = MactoRSVP_Guest::getGuestList($event['event_fb_id'],'attending');
		$unsure_list = MactoRSVP_Guest::getGuestList($event['event_fb_id'],'unsure');
		$declined_list = MactoRSVP_Guest::getGuestList($event['event_fb_id'],'declined');				
		
		// parent::varDump($invited_list);
		
		$default = array(
			'title' 		=> TRUE,
			'div_id'		=> 'mactoRSVP',
			'div_class'		=> '',
			'number_id'		=> '',
			'number_class'	=> ''
		);
		
		$atts = wp_parse_args($atts,$default);
		
		$div_id = ( !empty($atts['div_id']) ? " id='" . $atts['div_id'] . "' " : "" );
		$div_class = ( !empty($atts['div_class']) ? " class='" . $atts['div_class'] . "' " : "" );		
		$number_id = ( !empty($atts['number_id']) ? " id='" . $atts['number_id'] . "' " : "" );		
		$number_class = ( !empty($atts['number_class']) ? " class='" . $atts['number_class'] . "' " : "" );				
			
		$output = "<div " . $div_id . $div_class . ">";
			$output .= ( $atts['title'] == TRUE ) ? "<h2>".$event['event_name']."</h2>" : "";
			$output .= "<p class='desc' >".$event['event_desc']."</p>";
			$output .= "<p class='start_time' >".$event['event_start_time']."</p>";
			$output .= "<p class='end_time' >".$event['event_end_time']."</p>";			
			$output .= "<p class='location' >".$event['event_location']."</p>";
						
			$output .= "<ul ". $number_id . $number_class . ">";
				$output .= "<li class='invited'><strong>" . $invited . "</strong>";
					// $output .= "<a href='#' class='invited_list modal' >View</a>";
					$output .= "<ul class='invited_list' >";
						foreach($invited_list as $g){ 
							$output .= "<li><img src='". $g['guest_hosted_pic_link'] ."' alt='".__('No Image','macto-rsvp')."' />" ;
							$output .= "<span class='guest_name'>" . $g['guest_fb_name'] . "</span></li>"; 
						}
					$output .= "</ul> ";
				$output .= "</li>";
				
				$output .= "<li class='no_replied'><strong>" . $no_replied . "</strong>";
					// $output .= "<a href='#' class='no_replied_list modal' >View</a>";
					$output .= "<ul class='no_replied_list' >";
						foreach($no_replied_list as $g){ 
							$output .= "<li><img src='". $g['guest_hosted_pic_link'] ."' alt='".__('No Image','macto-rsvp')."' />" ;
							$output .= "<span class='guest_name'>" . $g['guest_fb_name'] . "</span></li>"; 
						}
					$output .= "</ul> ";					
				$output .= "</li>";
					
				$output .= "<li class='attending'><strong>" . $attending . "</strong>";
					// $output .= "<a href='#' class='attending_list modal' >View</a>";
					$output .= "<ul class='attending_list' >";
						foreach($attending_list as $g){ 
							$output .= "<li><img src='". $g['guest_hosted_pic_link'] ."' alt='".__('No Image','macto-rsvp')."' />" ;
							$output .= "<span class='guest_name'>" . $g['guest_fb_name'] . "</span></li>"; 
						}
					$output .= "</ul> ";					
				$output .= "</li>";	
										
				$output .= "<li class='unsure'><strong>" . $unsure . "</strong>";
					// $output .= "<a href='#' class='unsure_list modal' >View</a>";
					$output .= "<ul class='unsure_list' >";
						foreach($unsure_list as $g){ 
							$output .= "<li><img src='". $g['guest_hosted_pic_link'] ."' alt='".__('No Image','macto-rsvp')."' />" ;
							$output .= "<span class='guest_name'>" . $g['guest_fb_name'] . "</span></li>"; 
						}
					$output .= "</ul> ";					
				$output .= "</li>";
				
				$output .= "<li class='declined'><strong>" . $declined . "</strong>";
					// $output .= "<a href='#' class='declined_list modal' >View</a>";
					$output .= "<ul class='declined_list' >";
						foreach($declined_list as $g){ 
							$output .= "<li><img src='". $g['guest_hosted_pic_link'] ."' alt='".__('No Image','macto-rsvp')."' />" ;
							$output .= "<span class='guest_name'>" . $g['guest_fb_name'] . "</span></li>"; 
						}
					$output .= "</ul> ";					
				$output .= "</li>";		
			$output .= "</ul>";
		$output .= "</div>";
		
		return $output;			
	}
}
endif; // Class exists


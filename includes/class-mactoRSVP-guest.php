<?php
/**
 * Holds the MactoRSVP Guest class
 *
 * @package mactoRSVP
 * @since 1.0.0
 */

if ( ! class_exists( 'MactoRSVP_Guest' ) ) :
/**
 * MactoRSVP_Guest class
 *
 * @since 1.0.0
 */
class MactoRSVP_Guest extends MactoRSVP_Abstract {
	/**
	 * Holds Guest table column name
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string
	 */
	protected $guestColumn = array(
		'guest_id' 				=> 'gid',
		'guest_fb_id' 			=> 'gfbid',
		'guest_fb_uname' 		=> 'gfbuname',
		'guest_fb_name' 		=> 'gfbname',
		'guest_fb_link' 		=> 'gfblink',
		'guest_fb_pic_link' 	=> 'gfbpiclink',
		'guest_hosted_pic_link' => 'gfbhostpiclink',
		'guest_fb_event_id' 	=> 'gfbeventid',
		'guest_fb_rsvp' 		=> 'gfbrsvp'
	);
	
	/**
	 * Create Guest Tables Wrapper 
	 *
	 * mactoRSVP_guests
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function create_tb_guest(){
		// If status != "development" create tb_event and dummy data
		if(MactoRSVP::plugin_status != "development"){
			self::_create_tb_guest();
		}else{
			self::_create_tb_guest();
			self::_insert_dummy_guest();					
		}		
	}
	
	
	
	/**
	 * Show total number of guests with various RSVP status
	 * (attending, unsure, declined, not_replied)
	 *
	 * Combining two event into one list only,
	 * to anticipate event with the same host.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $event_id, $rsvp, $join_id
	 * @return array Total of "invited" guests
	 */
	public static function getInvitedTotal($event_id, $rsvp='' , $join_id = ''){
		if( !empty($join_id) ){
			$event_guest = self::_getGuestIDandRSVP($event_id);
			$event_join_guest = self::_getGuestIDandRSVP($join_id);
			$guests = array_merge($event_guest[1]);
		}else{
			switch($rsvp){
				case 'attending':
					print( count( self::_getGuestsBasedOnRSVPStatus($event_id,'attending') ) );	
					break;
					
				case 'unsure':
					print( count( self::_getGuestsBasedOnRSVPStatus($event_id,'unsure') ) );	
					break;
					
				case 'declined':
					print( count( self::_getGuestsBasedOnRSVPStatus($event_id,'declined') ) );	
					break;
					
				case 'not_replied':
					print( count( self::_getGuestsBasedOnRSVPStatus($event_id,'not_replied') ) );	
					break;
					
				default:
					print( count( self::_getGuestsBasedOnRSVPStatus($event_id) ) );	
			}			
		}
	}						
	
	/**
	 * Get all guest based on event and RSVP status.  
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function getGuestList($event_id,$rsvp =''){
		return self::_getGuestsBasedOnRSVPStatus($event_id,$rsvp);	
	}
	
	/**
	 * Create Guest Tables 
	 *
	 * mactoRSVP_guests
	 *
	 * @since 1.0.0
	 * @access protected
	 */	
	protected static function _create_tb_guest(){
		global $wpdb;		
		
		// mactoRSVP_guests Table
		$create_tb_guests = "CREATE TABLE " . TB_GUEST . " (
			guest_id INT(5) NOT NULL AUTO_INCREMENT,
			guest_fb_id VARCHAR(25) NOT NULL,
			guest_fb_uname VARCHAR(25) NOT NULL,
			guest_fb_name VARCHAR (50) NOT NULL,
			guest_fb_link VARCHAR(100) NOT NULL,
			guest_fb_pic_link TEXT NOT NULL,
			guest_hosted_pic_link TEXT NOT NULL,	
			guest_fb_event_id VARCHAR(25) NOT NULL,
			guest_fb_rsvp VARCHAR(25) NOT NULL,		
			PRIMARY KEY(guest_id)
		);";		
		
		if($wpdb->get_var("SHOW TABLES LIKE'" . TB_GUEST . "'") != TB_GUEST){            	
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($create_tb_guests);			
	   	}
	}
	
	/**
	 * Insert dummy data 
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 */	
	protected static function _insert_dummy_guest(){
		global $wpdb;
		
		$dummy = array(
			'guest1' => array(
				'fbid'	=> '688932041',
				'fbun' 	=> 'thoriqgrady',
				'fbnm' 	=> 'Muhammad Thariq Hadad',
				'fblk' 	=> 'https://www.facebook.com/thoriqgrady',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/thoriqgrady.jpg',
				'feid' 	=> '249418241902475',			
				'fbrs' 	=> 'attending'				
			),
			
			'guest2' => array(
				'fbid'	=> '688932045',
				'fbun' 	=> 'faisalah',
				'fbnm' 	=> 'Faisal Abdul Hakim',
				'fblk' 	=> 'https://www.facebook.com/faisalah',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/faisalah.jpg',
				'feid' 	=> '249418241902475',			
				'fbrs' 	=> 'declined'				
			),
			
			'guest3' => array(
				'fbid'	=> '688932047',
				'fbun' 	=> 'revajancuk',
				'fbnm' 	=> 'Reva Astra Dipta',
				'fblk' 	=> 'https://www.facebook.com/revajancuk',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/revajancuk.jpg',
				'feid' 	=> '249418241902475',			
				'fbrs' 	=> 'unsure'				
			),
			
			'guest4' => array(
				'fbid'	=> '688932048',
				'fbun' 	=> 'denotaai',
				'fbnm' 	=> 'Denori Gumalay',
				'fblk' 	=> 'https://www.facebook.com/denotaai',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/denotaai.jpg',
				'feid' 	=> '249418241902475',			
				'fbrs' 	=> 'not_replied'				
			),
			
			'guest5' => array(
				'fbid'	=> '688932049',
				'fbun' 	=> 'alifaaak',
				'fbnm' 	=> 'Ali Irhami',
				'fblk' 	=> 'https://www.facebook.com/alifaaak',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/alifaaak.jpg',
				'feid' 	=> '249418241902474',			
				'fbrs' 	=> 'not_replied'				
			)	
		);
		
		foreach($dummy as $guest){
			$q = "INSERT INTO " . TB_GUEST . " (guest_id,guest_fb_id,guest_fb_uname,guest_fb_name,guest_fb_link,guest_fb_pic_link,guest_hosted_pic_link,guest_fb_event_id,guest_fb_rsvp) 
				VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s)";
			
			$query = $wpdb->prepare($q,$guest);
			
			$wpdb->query($query);	  
		}				
	}
	
	/**
	 * Get all guests data who invited to an event  
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $event_fb_id 
	 * @return array Array of event
	 */
	protected function _getAllGuests($event_fb_id){
		global $wpdb;
		
		$q = "SELECT * FROM " . TB_GUEST . " WHERE guest_fb_event_id = " . $event_fb_id;

		return $wpdb->get_results($q,'ARRAY_A');		
	}
	
	/**
	 * Get guests data based on fb_event_id and RSVP status 
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $event_fb_id, $rsvp 
	 * @return array Array of guest data
	 */
	protected function _getGuestsBasedOnRSVPStatus($event_fb_id, $rsvp = ''){
		global $wpdb;
		
		if( !empty($rsvp) ){
			$q = "SELECT * FROM " . TB_GUEST . " WHERE guest_fb_event_id = " . $event_fb_id . " AND guest_fb_rsvp = '" . $rsvp . "'";

			return $wpdb->get_results($q,'ARRAY_A');
		}else{
			$q = "SELECT * FROM " . TB_GUEST . " WHERE guest_fb_event_id = " . $event_fb_id;
			
			return $wpdb->get_results($q,'ARRAY_A');			
		}				
	}		
	
	/**
	 * Get a row data from Guest table.
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $col_name, $criteria
	 * @return array one dimensional array of row data
	 */
	protected function _getGuestRow($col_name, $criteria){
		global $wpdb;				
		
		$col_key = array_search($col_name, $guestColumn, TRUE);
		// $col_selected = $guestColumn[$col_key];
		
		if($col_key != FALSE){
			$q = "SELECT * FROM " . TB_GUEST . " WHERE " . $col_key . " = " . $criteria;

			return $wpdb->get_row($q,'ARRAY_A');
		}else{
			return FALSE;
		}				
	}
	
	/**
	 * Get a column data from Guest table.
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $col_retrieve, $col_criteria, $criteria
	 * @return array one dimensional array of column data
	 */
	protected function _getGuestCol($col_retrieve, $col_criteria = '', $criteria = ''){
		global $wpdb;
		
		$col_key_retrieve = array_search($col_retrieve, $guestColumn, TRUE);
		
		// $col_retrieve_selected = $guestColumn[$col_key_retrieve];
		
		if( !empty($col_criteria) && !empty($criteria) ){
			
			$col_key_criteria = array_search($col_name, $guestColumn, TRUE);
					
			// $col_criteria_selected = $guestColumn[$col_key_criteria];		
				
			if($col_key_retrieve != FALSE && $col_key_criteria != FALSE){
				$q = "SELECT " . $col_key_retrieve . " FROM " . TB_GUEST . " WHERE " . $col_key_criteria . " = " . $criteria;

				return $wpdb->get_col($q,'ARRAY_A');				
			}else{
				return FALSE;				
			}
		}else{
			$q = "SELECT " . $col_key_retrieve . " FROM " . TB_GUEST;
		}				
	}		
} 
endif; // Class exists		
?>
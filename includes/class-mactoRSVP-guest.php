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
				'fbrs' 	=> 'Attending'				
			),
			
			'guest2' => array(
				'fbid'	=> '688932041',
				'fbun' 	=> 'faisalah',
				'fbnm' 	=> 'Faisal Abdul Hakim',
				'fblk' 	=> 'https://www.facebook.com/faisalah',				
				'fbpl' 	=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-ash1/t5/186785_688932041_1735128465_q.jpg',
				'hplk'	=> 'https://host.googledrive.com/1234125paoiwern/faisalah.jpg',
				'feid' 	=> '249418241902475',			
				'fbrs' 	=> 'Not Attending'				
			)
		);
		
		foreach($dummy as $guest){
			$q = "INSERT INTO " . TB_GUEST . " (guest_id,guest_fb_id,guest_fb_uname,guest_fb_name,guest_fb_link,guest_fb_pic_link,guest_hosted_pic_link,guest_fb_event_id,guest_fb_rsvp) 
				VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s)";
			
			$query = $wpdb->prepare($q,$guest);
			
			$wpdb->query($query);	  
		}				
	}		
} 
endif; // Class exists		
?>
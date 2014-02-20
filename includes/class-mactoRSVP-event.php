<?php
/**
 * Holds the MactoRSVP Event class
 *
 * @package mactoRSVP
 * @since 1.0.0
 */

if ( ! class_exists( 'MactoRSVP_Event' ) ) :
/**
 * MactoRSVP_Event class
 *
 * @since 1.0.0
 */
class MactoRSVP_Event extends MactoRSVP_Abstract {		
	/**
	 * Create Event Tables Wrapper 
	 *
	 * mactoRSVP_events
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function create_tb_event(){
		// If status != "development" create tb_event and dummy data
		if(MactoRSVP::plugin_status != "development"){
			self::_create_tb_event();
		}else{
			self::_create_tb_event();
			self::_insert_dummy_event();					
		}		
	}
	
	/**
	 * Create Event Tables 
	 *
	 * mactoRSVP_events
	 *
	 * @since 1.0.0
	 * @access protected
	 */	
	protected static function _create_tb_event(){
		global $wpdb;

		// mactoRSVP_events Table
		$create_tb_events = "CREATE TABLE " . TB_EVENT . " (
			event_id INT(5) NOT NULL AUTO_INCREMENT,
			event_name VARCHAR(255) NOT NULL,
			event_type VARCHAR(5) NOT NULL,
			event_privacy VARCHAR(10) NOT NULL,
			event_desc TEXT NOT NULL,
			event_start_time VARCHAR(50) NOT NULL,
			event_end_time VARCHAR(50) NOT NULL,			
			event_location VARCHAR(50) NOT NULL,
			event_host_id VARCHAR(50) NOT NULL,
			event_show_btn VARCHAR(10) NOT NULL, 
			event_fb_id VARCHAR(25) NOT NULL,
			PRIMARY KEY(event_id)
		);";				

		if($wpdb->get_var("SHOW TABLES LIKE'" . TB_EVENT . "'") != TB_EVENT){            	
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($create_tb_events);									
	   	}
	}		
	
	/**
	 * Insert dummy data 
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 */	
	protected static function _insert_dummy_event(){
		global $wpdb;
		
		$dummy = array(
			'event1' => array(
				'name'	=> 'Nikahan Temi Torik',
				'type' 	=> 'FB',
				'priv' 	=> 'SECRET',
				'desc' 	=> 'Ini adalah acara pernikahan Temi dan Torik',
				'sttm' 	=> '2014-02-28T15:20:00+0700',
				'edtm' 	=> '2014-02-28T18:20:00+0700',			
				'loct' 	=> 'Ketapang',
				'hsid' 	=> '688932041',
				'sbtn' 	=> 'yes',
				'fbid'	=> '249418241902475',
			),
			
			'event2' => array(
				'name'	=> 'Nikahan Faisal dan Dena',
				'type' 	=> 'FB',
				'priv' 	=> 'OPEN',
				'desc' 	=> 'Ini adalah acara pernikahan Faisal dan Dena',
				'sttm' 	=> '2014-02-28T15:20:00+0700',
				'edtm' 	=> '2014-02-28T18:20:00+0700',			
				'loct' 	=> 'Jakarta',
				'hsid' 	=> '688932041',
				'sbtn' 	=> 'no',
				'fbid'	=> '249418241902474'
			)
		);
		
		foreach($dummy as $event){
			$q = "INSERT INTO " . TB_EVENT . " (event_id,event_name,event_type,event_privacy,event_desc,event_start_time,event_end_time,event_location,event_host_id,event_show_btn,event_fb_id) 
				VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";						
			
			$query = $wpdb->prepare($q,$event);
			
			$wpdb->query($query);						  
		}				
	}
	
	/**
	 * Create Event
	 *
	 *
	 * @since 1.0
	 * @access protected
	 */	
	protected function _create_event(){
		global $wpdb;			
			
	}
	
	/**
	 * Update Event
	 *
	 *
	 * @since 1.0
	 * @access protected
	 */	
	protected function _update_event(){
		global $wpdb;			
			
	}
	
	/**
	 * Delete Event
	 *
	 *
	 * @since 1.0
	 * @access protected
	 */	
	protected function _delete_event(){
		global $wpdb;			
			
	}
	
	/**
	 * Get an Event data (based on supply parameter)
	 *
	 *
	 * @since 1.0
	 * @access protected
	 * 
	 * @param array $args arguments passed in key-value pair
	 * @return array data from Event Table
	 */	
	protected function _get_event($args){
		global $wpdb;			
			
	}
	
    /**
	 * Get all event data wrapper
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 *
	 * @return array of all event data in mactoRSVP_events table
	 */	
	public function get_all_event(){
		return self::_get_all_event();
	}
	
	/**
	 * Retrieve all data from DB Tables 
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 */	
	protected function _get_all_event(){
		global $wpdb;
		
		$q = "SELECT * FROM " . TB_EVENT;
		
		return $wpdb->get_results($q,'ARRAY_A');			
	}
	
	/**
	 * Print "Sync" Button
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */	
	public static function printSyncBtn(){ ?>
				
		<a href="#" class="button-primary" >Sync</a>
							
    <?php 
	}				
} 
endif; // Class exists
?>
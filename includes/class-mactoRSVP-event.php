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
	 * Add event to Events table 
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $ename,$etype,$fbevid,$fbapid,$fbapsc,$action
	 * @return string Message 
	 */
	public static function eventAdd($ename,$etype,$fbevid,$fbapid,$fbapsc){
		global $wpdb;
		
		$nosync = 'NOTSYNC';
		
		$event = array(
			'name'	=> esc_attr($ename),
			'type' 	=> esc_attr($etype),
			'priv' 	=> esc_attr($nosync),
			'desc' 	=> esc_attr($nosync),
			'sttm' 	=> esc_attr($nosync),
			'edtm' 	=> esc_attr($nosync),			
			'loct' 	=> esc_attr($nosync),
			'hsid' 	=> esc_attr($nosync),
			'sbtn' 	=> 'yes',
			'fbid'	=> esc_attr($fbevid),
			'join'	=> '249418241902474',
			'fbad'	=> esc_attr($fbapid),
			'fbas'	=> esc_attr($fbapsc)
		);
		
		$q = "INSERT INTO " . TB_EVENT . "
			(event_id,event_name,event_type,event_privacy,event_desc,event_start_time,event_end_time,
			event_location,event_host_id,event_show_btn,event_fb_id,event_join,event_fb_app_id,event_fb_app_secret) 
			VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
		
		$query = $wpdb->prepare($q,$event);

		$result = $wpdb->query($query);			
		
		if($result != FALSE){
			return parent::printMsg( $event['name'] . " successfully added.", TRUE );
		}else{
			return parent::printMsg( "Failed add event data", FALSE );			
		}	
	}
	
	/**
	 * Edit event to Events table 
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $evdbid,$ename,$etype,$fbevid,$fbapid,$fbapsc,$action
	 * @return string Message 
	 */
	public static function eventEdit($evdbid = 0,$ename,$etype,$fbevid,$fbapid,$fbapsc){
		global $wpdb;		
		
		$event = array(
			'name'	=> esc_attr($ename),
			'type' 	=> esc_attr($etype),
			'ebtn'	=> 'yes',			
			'fbid'	=> esc_attr($fbevid),
			'fbad'	=> esc_attr($fbapid),
			'fbas'	=> esc_attr($fbapsc),
			'eid'	=> esc_attr($evdbid)
		);
		
		$q = "UPDATE " . TB_EVENT . " 
			SET event_name = %s, event_type = %s, event_show_btn = %s, event_fb_id = %s, event_fb_app_id = %s, event_fb_app_secret = %s
			WHERE event_id = %d LIMIT 1";										
		
		$query = $wpdb->prepare($q,$event);
		
		$result = $wpdb->query($query);
		
		// var_dump($result);
		
		if($result != FALSE){
			return parent::printMsg( $event['name'] . " successfully edited", TRUE );
		}else{
			return parent::printMsg( "Failed edit event data", FALSE );			
		}			
	}
	
	/**
	 * Delete event to Events table 
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $evdbid
	 * @return string Message 
	 */
	public static function eventDel($evdbid){
		global $wpdb;		
						
		$q = "DELETE FROM " . TB_EVENT . " WHERE event_id = %d LIMIT 1";										
		
		$query = $wpdb->prepare($q,$evdbid);
		
		$result = $wpdb->query($query);
		
		// var_dump($result);
		
		if($result != FALSE){
			return parent::printMsg( "Event successfully deleted", TRUE );
		}else{
			return parent::printMsg( "Failed delete event data", FALSE );			
		}			
	}
	
	/**
	 * Instanstiate event object from Facebook Graph API (/event_id) call  
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $event_fbid, string $event_fb_app_id, string $event_fb_app_secret 
	 * @return string FBoauth url 
	 */
	protected function _FBinstance($event_fbid, $event_fb_app_id, $event_fb_app_secret){
		$config = array(
			'appId'					=> $event_fb_app_id,
			'secret'				=> $event_fb_app_secret,	
			'allowSignedRequest'	=> FALSE	
		);		
		
		$fb = new Facebook($config);											
		
		return $fb;	
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
			event_join VARCHAR(25) NOT NULL,
			event_fb_app_id VARCHAR(75) NOT NULL,
			event_fb_app_secret VARCHAR(75) NOT NULL,
			PRIMARY KEY(event_id)
		);";				

		if($wpdb->get_var("SHOW TABLES LIKE'" . TB_EVENT . "'") != TB_EVENT){            	
		    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($create_tb_events);									
	   	}
	}		
	
	/**
	 * Holds Event table column name
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _eventColumn(){ 
		
		$default_event_column = array(
										'event_id' 				=> 'evtid',
										'event_name' 			=> 'ename',
										'event_type' 			=> 'etype',
										'event_privacy' 		=> 'epriv',
										'event_desc' 			=> 'edesc',
										'event_start_time' 		=> 'esttm',
										'event_end_time' 		=> 'eedtm',
										'event_location' 		=> 'eloct',
										'event_host_id' 		=> 'ehsid',
										'event_show_btn'		=> 'esbtn',
										'event_fb_id'			=> 'efbid',
										'event_join'			=> 'ejoin',
										'event_fb_app_id'		=> 'efbai',
										'event_fb_app_secret'	=> 'efbas'
									);
		return $default_event_column;							
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
				'priv' 	=> 'NOTSYNC',
				'desc' 	=> 'Ini adalah acara pernikahan Temi dan Torik',
				'sttm' 	=> '2014-02-28T15:20:00+0700',
				'edtm' 	=> '2014-02-28T18:20:00+0700',			
				'loct' 	=> 'Ketapang',
				'hsid' 	=> '688932041',
				'sbtn' 	=> 'yes',
				'fbid'	=> '249418241902475',
				'join'	=> '249418241902474',
				'fbad'	=> '566267770131392',
				'fbas'	=> '4d2c0f71e9c5aba80765d30d5282014f'	
			),
			
			'event2' => array(
				'name'	=> 'Nikahan Faisal dan Dena',
				'type' 	=> 'FB',
				'priv' 	=> 'NOTSYNC',
				'desc' 	=> 'Ini adalah acara pernikahan Faisal dan Dena',
				'sttm' 	=> '2014-02-28T15:20:00+0700',
				'edtm' 	=> '2014-02-28T18:20:00+0700',			
				'loct' 	=> 'Jakarta',
				'hsid' 	=> '688932041',
				'sbtn' 	=> 'no',
				'fbid'	=> '249418241902474',
				'join'	=> '249418241902475',
				'fbad'	=> '',
				'fbas'	=> ''	
			)
		);
		
		foreach($dummy as $event){
			$q = "INSERT INTO " . TB_EVENT . " (event_id,event_name,event_type,event_privacy,event_desc,event_start_time,event_end_time,event_location,event_host_id,event_show_btn,event_fb_id,event_join,event_fb_app_id,event_fb_app_secret) 
				VALUES (0, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";						
			
			$query = $wpdb->prepare($q,$event);
			
			$wpdb->query($query);						  
		}				
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
	 * Get a row data from Event table.
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $cname, $crit
	 * @return array one dimensional array of row data
	 */
	public function getEventRow($cname, $crit){
		return self::_getEventRow($cname, $crit);
	}
	
	/**
	 * Get a row data from Event table.
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $col_name, $criteria
	 * @return array one dimensional array of row data
	 */
	protected function _getEventRow($col_name, $criteria){
		global $wpdb;				
		
		$col_key = esc_attr(array_search($col_name, self::_eventColumn(), TRUE));

		if($col_key != FALSE){
			$q = "SELECT * FROM " . TB_EVENT . " WHERE " . $col_key . " = " . $criteria;
			
			return $wpdb->get_row($q,'ARRAY_A');
		}else{
			return FALSE;
		}				
	}
	
	/**
	 * Get Event status compare with now() time. 
	 * 
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $start_time, $end_time, $print
	 * @return array|string Day Remaining, Time Remaining 
	 */	
	public static function getEventStatus($start_time, $end_time){
		global $wpdb;
		
		
	}
	
	public static function logoutFB($eventfbid,$fbappid,$fbappsecret){
		$fb = self::_FBInstance($eventfbid,$fbappid,$fbappsecret);
		$fb->destroySession();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.admin_url("admin.php?page=mactoRSVP").'">';
		return ( empty( $fb->getUser() ) )?'Logout Success':'Logout Failed';
	}
	
	/**
	 * Print "Sync" Button
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $eventfbid,$fbappid,$fbappsecret,$fbhostid,$redirect
	 * @return Array Event data
	 */	
	public static function printSyncBtn($eventfbid,$fbappid,$fbappsecret,$fbhostid,$redirect){								
		
		$fb = self::_FBInstance($eventfbid,$fbappid,$fbappsecret);				
		
		$params1 = array(
			'scope' => 'basic_info, user_events',
			'redirect_uri' => $redirect
		);
		
		$params2 = array("next" => wp_nonce_url(admin_url( 'admin.php?page=mactoRSV&action=logoutFB') ) );
		
		$userId = $fb->getUser();
		
		$loginUrl = $fb->getLoginUrl($params1);
		
		// $logoutUrl = $fb->getLogoutUrl($params2);
		$logoutUrl = wp_nonce_url(admin_url('admin.php?page=mactoRSVP&action=logoutFB'));				
		
		// if( isset( $fbInstance ) && !empty($fbInstance) && $fbInstance == $fbhostid ){
		if($userId){	
			try{
				echo "<button type='submit' class='button-primary'> Sync </button><br /><br />";
				echo "<a href=" . $logoutUrl . " class='logout button-secondary'>Logout</a>";								
			} catch(FacebookApiException $e){																
				echo $e->getType();
		   	 	echo $e->getMessage();
				echo "<a href=" . $loginUrl . " class='button-primary'>Login First</a>";				
			}			
		}else{			
			echo "<a href=" . $loginUrl . " class='button-primary'>Login First</a>";
		}
	}				
} 
endif; // Class exists
?>
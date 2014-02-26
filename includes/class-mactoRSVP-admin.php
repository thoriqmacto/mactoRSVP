<?php
/**
 * Holds the MactoRSVP Admin class
 *
 * @package mactoRSVP
 * @since 1.0.0
 */

if ( ! class_exists( 'MactoRSVP_Admin' ) ) :
/**
 * MactoRSVP_Admin class
 *
 * @since 1.0.0
 */
class MactoRSVP_Admin extends MactoRSVP_Abstract {
	/**
	 * Returns singleton instance
	 *
	 * @since 1.0.0
	 * @access public
	 * @return MactoRSVP
	 */
	public static function get_object( $class = null ) {
		return parent::get_object( __CLASS__ );
	}

	/**
	 * Loads object
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function load() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ), 8 );

		register_uninstall_hook( WP_PLUGIN_DIR . '/mactoRSVP/mactoRSVP.php', array( 'MactoRSVP_Admin', 'uninstall' ) );
	}

	/**
	 * Builds plugin admin menu and pages
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_menu() {
		// Register menu page
		add_menu_page(
			__( 'RSVP Event List', 'macto-rsvp' ),			// page_title
			__( 'RSVP', 'macto-rsvp' ),						// menu_title
			'manage_options',								// capability
			'mactoRSVP',									// slug
			array( 'MactoRSVP_Admin', 'admin_event_list' ),	// function
			'',												// icon
			'80.1'											// position
		);

		// Register submenu page
		add_submenu_page(
			'mactoRSVP',									// parent_slug
			__( 'All Events', 'macto-rsvp' ),				// page_title
			__( 'All Events', 'macto-rsvp' ),				// menu_title
			'manage_options',								// capability
			'mactoRSVP',									// menu_slug
			array( 'MactoRSVP_Admin', 'admin_event_list' )	// function
		);
		
		// Register submenu page
		add_submenu_page(
			'mactoRSVP',										// parent_slug
			__( 'Add Event', 'macto-rsvp' ),					// page_title
			__( 'Add Event', 'macto-rsvp' ),					// menu_title
			'manage_options',									// capability
			'mactoRSVP_add',									// menu_slug
			array( 'MactoRSVP_Admin', 'admin_event_add_edit' )	// function
		);		
		
		// add backend style
		add_action( 'admin_print_styles-' . $page, wp_enqueue_style( 'style_backend', plugins_url('../css/style_backend.css', __FILE__) ) );
	}

	/**
	 * Registers MactoRSVP_Admin settings
	 *
	 * This is used because register_setting() isn't available until the "admin_init" hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_init() {
		if ( version_compare( $this->get_option( 'version', 0 ), MactoRSVP::version, '<' ) ){
			$this->install();
		}
	}

	/**
	 * Renders the admin event list page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function admin_event_list(){ ?>
		<div class="wrap">			
			<?php if( $_GET['action'] != 'delete' ): ?>
				<h2>
					<?php echo esc_html( __( 'All Events', 'macto-rsvp' ) ); ?>
					<a href="<?php echo admin_url('admin.php?page=mactoRSVP_add'); ?>" class="add-new-h2" >Add Event</a>
				</h2>
				
				<!-- Message -->			
				<?php ( !empty($msg) )?$msg:''; ?>						
		
				<table class="widefat">
					<!-- Table Head Title -->				
					<thead>
						<tr class="event_data">
							<th class="text_left" style="width:5%;"><?php _e( 'Sync', 'macto-rsvp' ); ?></th>
							<th class="text_left" style="width:18%;" ><?php _e( 'Event Name', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'Type', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'Privacy', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'Invited', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'No Reply', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'Attending', 'macto-rsvp' ); ?></th>
							<th style="width:9%;"><?php _e( 'Unsure', 'macto-rsvp' ); ?></th>				
							<th style="width:9%;"><?php _e( 'Decline', 'macto-rsvp' ); ?></th>		
							<th style="width:14%;"><?php _e( 'Status', 'macto-rsvp' ); ?></th>						
						</tr>
					</thead>
			
					<!-- Table Foot Title -->				
					<tfoot>
						<tr class="event_data">
							<th class="text_left"><?php _e( 'Sync', 'macto-rsvp' ); ?></th>
							<th class="text_left"><?php _e( 'Event Name', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'Type', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'Privacy', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'Invited', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'No Reply', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'Attending', 'macto-rsvp' ); ?></th>
							<th><?php _e( 'Unsure', 'macto-rsvp' ); ?></th>				
							<th><?php _e( 'Decline', 'macto-rsvp' ); ?></th>		
							<th><?php _e( 'Status', 'macto-rsvp' ); ?></th>						
						</tr>
					</tfoot>
			
					<!-- Table Body -->
					<tbody>
						<?php if( !empty(MactoRSVP_Event::get_all_event()) ): ?>
							<?php $event_data = MactoRSVP_Event::get_all_event(); ?>
																
							<?php foreach( $event_data as $ed ): ?>
								<tr class="event_data">
									<td class="text_left">
										<?php MactoRSVP_Event::printSyncBtn($ed['event_fb_id'],$ed['event_fb_app_id'],$ed['event_fb_app_secret'] ); ?>
									</td>
									<td class="text_left">
										<?php echo $ed['event_name']; ?>
								
										<!-- View | Edit | Delete Menus -->
										<div class="row-actions">
											<span class="view">
												<a href="<?php echo admin_url('admin.php?page=mactoRSVP?id=' . $ed['event_id'] .'&action=view') ?>"
													title="View details for this Event"> View </a> |
											</span>
									
											<span class="edit">
												<?php $nonc_ed_url = wp_nonce_url( admin_url( 'admin.php?page=mactoRSVP_add&action=edit&id=' . $ed['event_id'] ) ); ?>
												<a href="<?php echo $nonc_ed_url; ?>" title="Edit this Event informations">Edit</a> |
											</span>
									
											<span class="delete">
												<?php $nonc_del_url = wp_nonce_url( admin_url( 'admin.php?page=mactoRSVP&action=delete&id=' . $ed['event_id'] ) ); ?>
												<a href="<?php echo $nonc_del_url; ?>" class="submitdelete" title="Delete this Event">Delete</a>
											</span>
										</div>
									</td>
									<td><?php echo $ed['event_type']; ?></td>
									<td><?php echo $ed['event_privacy']; ?></td>
									<td><?php MactoRSVP_Guest::getInvitedTotal( $ed['event_fb_id']); ?></td>
									<td><?php MactoRSVP_Guest::getInvitedTotal( $ed['event_fb_id'], 'not_replied'); ?></td>
									<td><?php MactoRSVP_Guest::getInvitedTotal( $ed['event_fb_id'], 'attending'); ?></td>
									<td><?php MactoRSVP_Guest::getInvitedTotal( $ed['event_fb_id'], 'unsure'); ?></td>
									<td><?php MactoRSVP_Guest::getInvitedTotal( $ed['event_fb_id'], 'declined'); ?></td>
									<td><?php // MactoRSVP_Event::getEventStatus($ed['event_start_time'], $ed['event_end_time']);	?></td>
								</tr>
							<?php endforeach; ?>	
					
						<?php else: ?>
							<tr>
								<?php $addone = "<a href='" . admin_url('admin.php?page=mactoRSVP_add') . "' >'Add One'</a>";?>
								<td colspan="10" class="no_data" ><?php _e('There is no event yet. Please ' . $addone,'macto-rsvp'); ?></td>
							</tr>
						<?php endif; ?>	
					</tbody>
				</table>
			<?php elseif( isset($_REQUEST['delete']) && !empty( $_POST['event_del_id'] ) ): ?>
				<?php $msg = MactoRSVP_Event::eventDel($_POST['event_del_id']); ?>		
				<br />
				<a href="<?php echo admin_url('admin.php?page=mactoRSVP') ; ?>" ><?php _e('Back to Event list page.','macto-rsvp'); ?></a>		
					
			<?php else: ?>	
				<h2><?php echo esc_html( __( 'Delete Event', 'macto-rsvp' ) ); ?></h2>
				
				<p><?php _e('You are about to delete the following event:','macto-rsvp'); ?></p>				
				
				<?php
					$ev_id = $_GET['id'];
					$arr = MactoRSVP_Event::getEventRow('evtid',$ev_id);				
				?>	
				
				<ul class="ul-disc">
					<li><strong>"<?php echo $arr['event_name']; ?>"</strong> created by <em><?php echo $arr['event_host_id']; ?></em></li>
				</ul>
				
				<p><?php _e('Deletion will remove event data permanently.','macto-rsvp'); ?></p>
				
				<p><?php _e('Are you sure you wish to delete these event?'); ?></p>
				
				<form action="" method="POST" style="display:inline;">				
					<?php wp_nonce_field('mactoRSVP_del'); ?>
					<input type="hidden" name="event_del_id" value="<?php echo $ev_id; ?>" />
					<input type="submit" name="delete" class="button" value="<?php _e('Yes, Delete these event','macto-rsvp'); ?>" />
				</form>	
				
				<form action="<?php echo admin_url('admin.php?page=mactoRSVP') ?>" method="POST" style="display:inline;">				
					<input type="submit" class="button" value="<?php _e('No, Return me to the event list','macto-rsvp'); ?>" />
				</form>	
			<?php endif;?>							
		</div>
	<?php }				 	

	/**
	 * Renders the admin event add page
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function admin_event_add_edit(){ 
					
		 if(!current_user_can('manage_options')){
			wp_die('Insufficient privileges!');
		 }
		
		 if( isset($_REQUEST['save']) && !empty( $_POST['mactoRSVP_action'] ) ){
			
			check_admin_referer( 'mactoRSVP_' . $_POST['mactoRSVP_action'] );
			
			// extract from POST request
			$evname = $_POST['event-name'];
			$evtype = $_POST['event-type'];
			$fbevid = $_POST['fb-event-id'];
			$fbapid = $_POST['fb-app-id'];
			$fbapsc = $_POST['fb-app-secret'];																		
			$evdbid = $_POST['event_post_id'];
			
			// var_dump($evname,$evtype,$fbevid,$fbapid,$fbapsc);
			
			switch($_POST['mactoRSVP_action']){					
				case 'add':
					$msg = MactoRSVP_Event::eventAdd($evname,$evtype,$fbevid,$fbapid,$fbapsc);
					break;
				case 'edit':					
					$msg = MactoRSVP_Event::eventEdit($evdbid,$evname,$evtype,$fbevid,$fbapid,$fbapsc);
					break;
			}
			
			// re-assign event DB id to hidden field.
			$event_db_id = $evdbid;
				
		 }elseif( $_GET['action'] == 'edit' && !empty($_GET['id']) ){
			$event_db_id = esc_attr($_GET['id']);
			
			$arr = MactoRSVP_Event::getEventRow('evtid',$event_db_id); 	
			
			$evname = $arr['event_name'];
			$evtype = $arr['event_type'];
			$fbevid = $arr['event_fb_id'];
			$fbapid = $arr['event_fb_app_id'];
			$fbapsc = $arr['event_fb_app_secret'];
		 }
		 
		?>
		
		<div class="wrap">
			<h2>
				<?php echo ( $_POST['mactoRSVP_action'] == 'edit' || $_GET['action'] == 'edit' )?
					  esc_html( _e('Edit Event','macto-rsvp') ): 
					  esc_html( _e('Add Event','macto-rsvp') ); 
				?>
			</h2>
			
			<!-- Message -->			
			<?php ( !empty($msg) )?$msg:''; ?>						
			
			<form method="post" action="">
				<?php if( $_GET['action'] != 'edit' ): ?>
					<?php wp_nonce_field('mactoRSVP_add'); ?>
					<input type="hidden" name="mactoRSVP_action" value="add" />
				<?php else: ?>	
					<?php wp_nonce_field('mactoRSVP_edit'); ?>
					<input type="hidden" name="mactoRSVP_action" value="edit" />
					<input type="hidden" name="event_post_id" value="<?php echo $event_db_id; ?>">
				<?php endif; ?>
				
				<table class="form-table">
					<tbody>
						<tr valign="top" class="form-field">
							<th scope="row"><label for="event-name">Event Name</label></th>
						
							<td>
								<input id="event-name" type="text" aria-required="true" size="40" value="<?php echo esc_attr($evname); ?>" name="event-name" />
								<p><i><?php _e('The event name save in DB. (different from FB event name)','macto-rsvp'); ?></i></p>
							</td>
						</tr>					
						<tr valign="top" class="form-field">
							<th scope="row"><label for="event-type"><b>Event Type</b></label></th>
							<td>
								<fieldset>
									<label title="facebook">
										<input id="event-type" type="radio" value="FB" name="event-type" <?php echo ( $evtype == 'FB' )?'checked':''; ?> />
										<span>Facebook</span>
									</label>
									<br />
									<label title="manual">
										<input id="event-type" type="radio" value="MAN" name="event-type" disabled <?php echo ( $evtype == 'MAN' )?'checked':''; ?> />
										<span>Manual <small><i>(for further development)</i></small></span>	
									</label>
									<br />
									<label title="facebook_manual">						
										<input id="event-type" type="radio" value="FB_MAN" name="event-type" disabled <?php echo ( $evtype == 'FB_MAN' )?'checked':''; ?> />
										<span>Facebook & Manual <small><i>(for further development)</i></small></span>										
									</label>
									<p><i><?php _e('Choose the type of Event','macto-rsvp'); ?></i></p>	
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="form-field">
							<th scope="row"><label for="fb-event-id"><b>Facebook Event ID</b></label></th>
							
							<td>
								<input id="fb-event-id" type="text" aria-required="true" size="40" value="<?php echo esc_attr($fbevid); ?>" name="fb-event-id" />
								<p><i><?php _e('Facebook Event ID to get Event Data','macto-rsvp'); ?></i></p>
							</td>
						</tr>
						<tr valign="top" class="form-field">
							<th scope="row"><label for="fb-app-id"><b>Facebook App ID</b></label></th>
							
							<td>
								<input id="fb-app-id" type="text" aria-required="true" size="40" value="<?php echo esc_attr($fbapid); ?>" name="fb-app-id" />
								<p><i><?php _e('Facebook Application ID to get Access Token','macto-rsvp'); ?></i></p>
							</td>
						</tr>
						<tr valign="top" class="form-field">
							<th scope="row"><label for="fb-app-secret"><b>Facebook App Secret</b></label></th>
							
							<td>
								<input id="fb-app-secret" type="text" aria-required="true" size="40" value="<?php echo esc_attr($fbapsc); ?>" name="fb-app-secret" />
								<p><i><?php _e('Facebook Application Secret (please do not share the string)','macto-rsvp'); ?></i></p>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"></th>
							<td>
								<input type="submit" value="<?php _e('Submit','macto-rsvp'); ?>" name="save" class="button-primary" />
								<button type="reset" class="button-secondary"><?php _e('Reset','macto-rsvp'); ?></button>
							</td>
						</tr>
					</tbody>
				</table>																				
			</form>
		</div>								
		
	<?php }

	/**
	 * Installs mactoRSVP
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function install() {
		global $wpdb;

		// Current version
		$version = $this->get_option( 'version', MactoRSVP::version );  // get_option($option, default)
		
		// Install Event Tables
		// MactoRSVP_Event::create_tb_event();							
		
		// Install Guest Tables
		// MactoRSVP_Guest::create_tb_guest();									

		// Setting version
		$this->set_option( 'version', MactoRSVP::version );						
	}

	/**
	 * Wrapper for uninstallation
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function uninstall() {
		global $wpdb;

		self::_uninstall();
	}

	/**
	 * Uninstalls mactoRSVP
	 *
	 * @since 6.0
	 * @access protected
	 */
	protected static function _uninstall() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Delete options
		delete_option( 'theme_my_login' );
		delete_option( 'widget_theme-my-login' );
	}
}
endif; // Class exists


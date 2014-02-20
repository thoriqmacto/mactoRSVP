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
			array( 'MactoRSVP_Admin', 'admin_event_page' ),	// function
			'',												// icon
			'80.1'											// position
		);

		// Register submenu page
		add_submenu_page(
			'mactoRSVP',									// parent_slug
			__( 'Event List', 'macto-rsvp' ),				// page_title
			__( 'List', 'macto-rsvp' ),						// menu_title
			'manage_options',								// capability
			'mactoRSVP',									// menu_slug
			array( 'MactoRSVP_Admin', 'admin_event_page' )	// function
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
	public static function admin_event_page(){
		extract( wp_parse_args( $args, array(
			'title'       => __( 'RSVP Event List', 'macto-rsvp' ),
			'id' => 'mactoRSVP'
		) ) );
		?>
		<div id="<?php echo $id; ?>" class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			
			<h2><?php echo esc_html( $title ); ?></h2>
			
			<!-- Error Message -->			
			<?php //settings_errors(); ?>						
			
			<table class="widefat">
				<thead>
					<!-- Table Head Title -->
					<tr>
						<th style="width:25%;" ><?php _e( 'Event Name', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'Type', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'Privacy', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'Invited', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'No Reply', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'Attending', 'macto-rsvp' ); ?></th>
						<th style="width:8%;"><?php _e( 'Maybe', 'macto-rsvp' ); ?></th>				
						<th style="width:8%;"><?php _e( 'Decline', 'macto-rsvp' ); ?></th>		
						<th style="width:8%;"><?php _e( 'Status', 'macto-rsvp' ); ?></th>
						<th style="width:11%;"><?php _e( 'Sync', 'macto-rsvp' ); ?></th>						
					</tr>
				</thead>
				
				<tfoot>
					<!-- Table Foot Title -->
					<tr>
						<th><?php _e( 'Event Name', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Type', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Privacy', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Invited', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'No Reply', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Attending', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Maybe', 'macto-rsvp' ); ?></th>				
						<th><?php _e( 'Decline', 'macto-rsvp' ); ?></th>		
						<th><?php _e( 'Status', 'macto-rsvp' ); ?></th>
						<th><?php _e( 'Sync', 'macto-rsvp' ); ?></th>						
					</tr>
				</tfoot>
				
				<tbody>
					<?php if( !empty(MactoRSVP_Event::get_all_event()) ): ?>
						<?php $event_data = MactoRSVP_Event::get_all_event(); ?>
																	
						<?php foreach( $event_data as $ed ): ?>
							<tr>
								<td><?php echo $ed['event_name']; ?></td>
								<td><?php echo $ed['event_type']; ?></td>
								<td><?php echo $ed['event_privacy']; ?></td>
								<td><?php MactoRSVP_Guest::printInvitedTotal($ed['event_fb_id']); ?></td>
								<td><?php MactoRSVP_Guest::printNoReplyTotal($ed['event_fb_id']); ?></td>
								<td><?php MactoRSVP_Guest::printAttendingTotal($ed['event_fb_id']); ?></td>
								<td><?php MactoRSVP_Guest::printUnsureTotal($ed['event_fb_id']); ?></td>
								<td><?php MactoRSVP_Guest::printDeclineTotal($ed['event_fb_id']); ?></td>
								<td><?php MactoRSVP_Event::printStatus($ed['event_start_time'], $ed['event_end_time']);	?></td>
								<td><?php MactoRSVP_Event::printSyncBtn();?></td>							
							</tr>
						<?php endforeach; ?>	
						
					<?php else: ?>
						<tr>
							<td colspan="10" class="no_data" ><?php _e('There is no event yet. Please "Add One".','macto-rsvp'); ?></td>
						</tr>
					<?php endif; ?>	
				</tbody>
			</table>				
		</div>
		<?php
	}				 	

	/**
	 * Installs mactoRSVP
	 *
	 * @since 6.0
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
	 * @since 6.1
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


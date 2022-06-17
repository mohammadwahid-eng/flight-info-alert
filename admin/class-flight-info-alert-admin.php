<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mohammadwahid.com
 * @since      1.0.0
 *
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/admin
 * @author     Mohammad Wahid <mohammadwahid.eng@gmail.com>
 */
class Flight_Info_Alert_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flight_Info_Alert_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flight_Info_Alert_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flight-info-alert-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flight_Info_Alert_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flight_Info_Alert_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flight-info-alert-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'fia_api_fetch', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( $this->plugin_name ) ) );

	}

	public function custom_post_types() {
		$args = [
			'label'  => __( 'Flight alerts', 'text-domain' ),
			'labels' => [
				'menu_name'          => __( 'Flight Alerts', 'flight-info-alert' ),
				'name_admin_bar'     => __( 'Flight Alert', 'flight-info-alert' ),
				'add_new'            => __( 'Add Alert', 'flight-info-alert' ),
				'add_new_item'       => __( 'Add New Alert', 'flight-info-alert' ),
				'new_item'           => __( 'New Alert', 'flight-info-alert' ),
				'edit_item'          => __( 'Edit Alert', 'flight-info-alert' ),
				'view_item'          => __( 'View Alert', 'flight-info-alert' ),
				'update_item'        => __( 'View Alert', 'flight-info-alert' ),
				'all_items'          => __( 'All Alerts', 'flight-info-alert' ),
				'search_items'       => __( 'Search Alerts', 'flight-info-alert' ),
				'parent_item_colon'  => __( 'Parent Alert', 'flight-info-alert' ),
				'not_found'          => __( 'No Alerts Found', 'flight-info-alert' ),
				'not_found_in_trash' => __( 'No Alerts Found in Trash', 'flight-info-alert' ),
				'name'               => __( 'Flight Alerts', 'flight-info-alert' ),
				'singular_name'      => __( 'Flight Alert', 'flight-info-alert' ),
			],
			'public'              => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-megaphone',
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => false,
			'supports' => [
				'title',
				'custom-fields',
			],
		];
	
		register_post_type( 'fia', $args );
	}

	public function custom_meta_box() {

		add_meta_box(
			'fia_metabox',
			__( 'Alert Information', 'flight-info-alert' ),
			array( $this, 'fia_metabox_html' )
		);
	}

	public function fia_metabox_html( $post ) {

		$api_response = get_post_meta( $post->ID, 'fia_response', true );
		if( isset( $api_response->problemdetails ) ) {
			$html = '';
			$html .= '<div class="fia-errors">';
				$html .= '<p>' . __( "API request failed due to the following reasones." ) . '</p>';
				$html .= '<ul>';
					$html .= '<li>' . $api_response->problemdetails->message . '</li>';
					if( isset( $api_response->problemdetails->subErrors ) ) {
						foreach( $api_response->problemdetails->subErrors as $error ) {
							$html .= '<li>' . $error->message . '</li>';
						}
					}
					$html .= '</ul>';
			$html .= '</div>';

			echo $html;
		}

		$data = array(
			'alertId'          => '',
			'description'      => '',
			'iataCarrierCode'  => '',
			'iataCarrierCode'  => '',
			'icaoCarrierCode'  => '',
			'flightNumber'     => '',
			'fromFlight'       => '',
			'toFlight'         => '',
			'departureAirport' => '',
			'arrivalAirport'   => '',
			'departureDate'    => '',
			'alertType'        => '',
			'active'           => '',
			'content'          => '',
		);

		foreach( $data as $key => $value ) {
			
			$metadata = get_post_meta( $post->ID, $key, true );
			if( $metadata ) {
				$data[ $key ] = $metadata;
			}
		}
		
		wp_nonce_field( "flight-info-alert-metabox", "flight-info-alert-metabox-nonce" );
		?>

			<input type="hidden" name="alertId" id="alertId" value="<?php echo $data[ 'alertId' ]; ?>">
			
			<div class="fia-form-group">
				<label for="description"><?php _e( "Description", "flight-info-alert" ) ?></label>
				<textarea class="fia-form-control" id="description" name="description" rows="3"><?php echo $data[ 'description' ]; ?></textarea>
				<div class="fia-help-block"><?php _e("Describe the purpose of the alert.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="iataCarrierCode"><?php _e( "IATA Carrier Code", "flight-info-alert" ); ?></label>
				<input type="text" maxlength="2" class="fia-form-control fia-text-uppercase" id="iataCarrierCode" name="iataCarrierCode" value="<?php echo $data[ 'iataCarrierCode' ]; ?>">
				<div class="fia-help-block"><?php _e("A two-character code assigned by the IATA to international airlines.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="icaoCarrierCode"><?php _e( "ICAO Carrier Code", "flight-info-alert" ); ?></label>
				<input type="text" maxlength="3" class="fia-form-control fia-text-uppercase" id="icaoCarrierCode" name="icaoCarrierCode" value="<?php echo $data[ 'icaoCarrierCode' ]; ?>">
				<div class="fia-help-block"><?php _e("A three-character code assigned by the ICAO to international airlines.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="flightNumber"><?php _e( "Flight Number", "flight-info-alert" ); ?></label>
				<input type="number" min="0" step="1" maxlength="4" class="fia-form-control" id="flightNumber" name="flightNumber" value="<?php echo $data[ 'flightNumber' ]; ?>">
				<div class="fia-help-block"><?php _e("A numeric part (up to four digits) of a flight designator.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="fromFlight"><?php _e( "Flight From", "flight-info-alert" ); ?></label>
				<input type="number" min="0" step="1" maxlength="4" class="fia-form-control" id="fromFlight" name="fromFlight" value="<?php echo $data[ 'fromFlight' ]; ?>">
				<div class="fia-help-block"><?php _e("A numeric part (up to four digits) of a flight designator.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="toFlight"><?php _e( "Flight To", "flight-info-alert" ); ?></label>
				<input type="number" min="0" step="1" maxlength="4" class="fia-form-control" id="toFlight" name="toFlight" value="<?php echo $data[ 'toFlight' ]; ?>">
				<div class="fia-help-block"><?php _e("A numeric part (up to four digits) of a flight designator.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="departureAirport"><?php _e( "Departure Airport", "flight-info-alert" ); ?></label>
				<input type="text" maxlength="3" class="fia-form-control fia-text-uppercase" id="departureAirport" name="departureAirport" value="<?php echo $data[ 'departureAirport' ]; ?>">
				<div class="fia-help-block"><?php _e("A Three-letter code assigned by the IATA to an airport location representing departure airport.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="arrivalAirport"><?php _e( "Arrival Airport", "flight-info-alert" ); ?></label>
				<input type="text" maxlength="3" class="fia-form-control fia-text-uppercase" id="arrivalAirport" name="arrivalAirport" value="<?php echo $data[ 'arrivalAirport' ]; ?>">
				<div class="fia-help-block"><?php _e("A Three-letter code assigned by the IATA to an airport location representing arrival airport.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="alertType"><?php _e( "Alert Type", "flight-info-alert" ); ?></label>
				<select class="fia-form-control" id="alertType" name="alertType">
					<option value="">Select an option</option>
					<option value="global" <?php selected( $data[ 'alertType' ], 'global' ); ?>>Global</option>
					<option value="carrier" <?php selected( $data[ 'alertType' ], 'carrier' ); ?>>Carrier</option>
					<option value="port" <?php selected( $data[ 'alertType' ], 'port' ); ?>>Port</option>
				</select>
			</div>
			<div class="fia-form-group">
				<label for="active"><?php _e( "Active", "flight-info-alert" ); ?></label>
				<select class="fia-form-control" id="active" name="active">
					<option value="">Select an option</option>
					<option value="true" <?php selected( $data[ 'active' ], '1' ) . selected( $data[ 'active' ], 'true' ); ?>>Yes</option>
					<option value="false" <?php selected( $data[ 'active' ], '0' ) . selected( $data[ 'active' ], 'false' ); ?>>No</option>
				</select>
			</div>
			<div class="fia-form-group">
				<label for="content"><?php _e( "Content", "flight-info-alert" ); ?></label>
				<input type="text" class="fia-form-control" id="content" name="content" value="<?php echo $data[ 'content' ]; ?>">
				<div class="fia-help-block"><?php _e("Additional data is included in the API response in the <strong>Premium Content group</strong>.", "flight-info-alert"); ?></div>
			</div>
			<div class="fia-form-group">
				<label for="departureDate"><?php _e( "Departure Date", "flight-info-alert" ); ?></label>
				<input type="date" class="fia-form-control" id="departureDate" name="departureDate" value="<?php echo $data[ 'departureDate' ]; ?>">
			</div>
		<?php
	}

	public function custom_post_save( $post_id ) {

		if ( !isset( $_POST[ "flight-info-alert-metabox-nonce" ] ) || !wp_verify_nonce( $_POST[ "flight-info-alert-metabox-nonce" ], "flight-info-alert-metabox" ) )
			return $post_id;
		
		if( !current_user_can( "edit_post", $post_id ) )
			return $post_id;

		if( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE )
			return $post_id;

		$data = array(
			'alertId'          => '',
			'description'      => '',
			'iataCarrierCode'  => '',
			'iataCarrierCode'  => '',
			'icaoCarrierCode'  => '',
			'flightNumber'     => '',
			'fromFlight'       => '',
			'toFlight'         => '',
			'departureAirport' => '',
			'arrivalAirport'   => '',
			'departureDate'    => '',
			'alertType'        => '',
			'active'           => '',
			'content'          =>  '',
		);

		foreach( array_keys( $data ) as $key ) {
			if( isset( $_POST[ $key ] ) && !empty( $_POST[ $key ] ) ) {
				$data[ $key ] = esc_attr( $_POST[ $key ] );
			} else {
				unset( $data[ $key ] );
			}
		}

		$api_key = get_option( 'fia_api_key' );
		$account_id = get_option( 'fia_account_id' );

		if( !empty( $api_key ) && !empty( $account_id ) ) {
			$data[ 'accountId' ] = $account_id;
			$data[ 'name' ]      = get_the_title( $post_id );

			$baseUrl = 'https://api.oag.com/flight-info-alerts/alerts?version=v1';
			$response = wp_remote_request( $baseUrl, array(
				'headers' => array(
					'Content-Type' 	   => 'application/json',
					'Cache-Control'    => 'no-cache',
					'Subscription-Key' => $api_key,
				),
				'method'  => ( isset( $data[ 'alertId' ] ) && !empty( $data[ 'alertId' ] ) ) ? 'PATCH' : 'POST',
				'body'    => json_encode( $data ),
			) );

			$responseBody = wp_remote_retrieve_body( $response );
			$result = json_decode( $responseBody );

			// store last api response
			update_post_meta( $post_id, 'fia_response', $result );
			unset( $data[ 'accountId' ] );

			// alert created
			if( isset( $result->data ) ) {
				update_post_meta( $post_id, 'alertId', $result->data );
			}

			foreach( $data as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	public function admin_menu_page() {
		add_submenu_page(
			"edit.php?post_type=fia",
            __( 'Flight Alert Settings', 'flight-info-alert' ),
            __( 'Settings', 'flight-info-alert' ),
            'manage_options',
            'fia-settings',
            array( $this, 'settings_page' )
        );
	}

	public function admin_menu_settings() {
        add_settings_section("fia_api_section", null, null, "fia-settings");
        
		add_settings_field("fia_api_key", __( "API key", "flight-info-alert" ), array( $this, 'api_key_html' ), "fia-settings", "fia_api_section");
        add_settings_field("fia_account_id", __( "Account ID", "flight-info-alert" ), array( $this, 'account_id_html' ), "fia-settings", "fia_api_section");
        add_settings_field("fia_fetch_alerts", __( "Fetch alerts", "flight-info-alert" ), array( $this, 'fetch_alert_html' ), "fia-settings", "fia_api_section");
        
		register_setting("fia_api_section", "fia_api_key");
        register_setting("fia_api_section", "fia_account_id");
	}

    public function api_key_html() {
        echo '<input type="password" name="fia_api_key" id="fia_api_key" value="'. get_option('fia_api_key') .'">';
    }

    public function account_id_html() {
		echo '<input type="password" name="fia_account_id" id="fia_account_id" value="'. get_option('fia_account_id') .'">';
	}

    public function fetch_alert_html() {
		echo '<button type="button" class="button" id="fetch-alerts">'. __("Fetch my alerts", "flight-info-alert") .'</button>';
		echo '<p class="description">'. __('It will delete all of your local alerts and fetch latest alerts from <a href="https://www.oag.com/flight-info-alerts" target="_blank">oag</a>.', "flight-info-alert") .'</p>';
	}

	public function settings_page() {
		?>
			<div class="wrap">
				<h1><?php echo $GLOBALS['title'] ?></h1>
				<form method="POST" action="options.php">
					<?php
						settings_fields("fia_api_section");
						do_settings_sections("fia-settings");
						submit_button();
					?>
				</form>
			</div>
		
		<?php
    }

	public function fetch_alerts() {

		check_ajax_referer( $this->plugin_name, 'security' );

		$api_key = get_option( 'fia_api_key' );
		$account_id = get_option( 'fia_account_id' );

		if( $api_key && $account_id ) {
			$baseUrl = 'https://api.oag.com/flight-info-alerts/alerts?version=v1';
			$response = wp_remote_get( $baseUrl, array(
				'headers' => array(
					'Accept'           => 'application/json',
					'Cache-Control'    => 'no-cache',
					'Subscription-Key' => $api_key,
					'accountId'        => $account_id,
				)
			) );

			$responseBody = wp_remote_retrieve_body( $response );
			$result = json_decode( $responseBody );

			if ( ( !is_wp_error( $response ) ) && ( 200 === wp_remote_retrieve_response_code( $response ) ) ) {

				$posts = get_posts( array( 'post_type' => 'fia', 'numberposts' => -1, 'post_status' => get_post_stati() ) );
				foreach ( $posts as $post ) {
					wp_delete_post( $post->ID, true );
				}

				foreach( $result as $alert ) {
					$args = array(
						'post_type'   => 'fia',
						'post_status' => 'publish',
						'post_author' => get_current_user_id(),
						'meta_input'  => array()
					);

					$args[ 'post_title' ] = isset( $alert->name ) ? $alert->name : '';
					$args[ 'meta_input' ][ 'alertId' ] = isset( $alert->alertId ) ? $alert->alertId : '';
					$args[ 'meta_input' ][ 'description' ] = isset( $alert->description ) ? $alert->description : '';
					$args[ 'meta_input' ][ 'iataCarrierCode' ] = isset( $alert->iataCarrierCode ) ? $alert->iataCarrierCode : '';
					$args[ 'meta_input' ][ 'icaoCarrierCode' ] = isset( $alert->icaoCarrierCode ) ? $alert->icaoCarrierCode : '';
					$args[ 'meta_input' ][ 'flightNumber' ] = isset( $alert->flightNumber ) ? $alert->flightNumber : '';
					$args[ 'meta_input' ][ 'fromFlight' ] = isset( $alert->fromFlight ) ? $alert->fromFlight : '';
					$args[ 'meta_input' ][ 'toFlight' ] = isset( $alert->toFlight ) ? $alert->toFlight : '';
					$args[ 'meta_input' ][ 'departureAirport' ] = isset( $alert->departureAirport ) ? $alert->departureAirport : '';
					$args[ 'meta_input' ][ 'arrivalAirport' ] = isset( $alert->arrivalAirport ) ? $alert->arrivalAirport : '';
					$args[ 'meta_input' ][ 'alertType' ] = isset( $alert->alertType ) ? $alert->alertType : '';

					if( isset( $alert->active ) ) {
						$args[ 'meta_input' ][ 'active' ] = $alert->active == 1 ? true : false;
					}

					$args[ 'meta_input' ][ 'content' ] = isset( $alert->content ) ? $alert->content : '';
					$args[ 'meta_input' ][ 'departureDate' ] = isset( $alert->departureDate ) ? $alert->departureDate : '';

					wp_insert_post( $args );
				}

				wp_send_json( array(
					'success'	=> true,
					'message' 	=> __( count( $result ) . ' alerts have been fetched', 'flight-info-alert' )
				) );
			} else {

				wp_send_json( array(
					'success'	=> false,
					'message' 	=> $result->message ?? __( 'We could not fetch data for you at this time. Try again later.', 'flight-info-alert' )
				) );
			}	
		} else {
			wp_send_json( array(
				'success'	=> false,
				'message' 	=> __( 'Please enter and save the required data first.', 'flight-info-alert' )
			) );
		}

		die();
	}

	public function remove_bulk_actions( $actions, $post ) {
		if ( $post->post_type == 'fia' ) {
			unset( $actions['inline hide-if-no-js'] );
			$actions['trash'] = str_replace( 'Trash', 'Delete', $actions['trash'] );
		}
        return $actions;
	}

	public function delete_alert( $post_id ) {
		global $post_type;
		if ( 'fia' == $post_type ) {
			$alertId = get_post_meta( $post_id, 'alertId', true );
			$api_key = get_option( 'fia_api_key' );

			if( $alertId && $api_key ) {
				$baseUrl = "https://api.oag.com/flight-info-alerts/alerts/$alertId?version=v1";
				wp_remote_request( $baseUrl, array(
					'headers' => array(
						'Cache-Control'    => 'no-cache',
						'Subscription-Key' => $api_key,
					),
					'method'  => 'DELETE',
				) );
			}
			
			wp_delete_post( $post_id, true);
			wp_redirect( admin_url( 'edit.php?post_type=fia' ) );
			exit();
		}
	}

}
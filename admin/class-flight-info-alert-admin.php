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

		$fields = array(
			'description' => '',
			'iataCarrierCode' => '',
			'iataCarrierCode' => '',
			'icaoCarrierCode' => '',
			'flightNumber' => '',
			'fromFlight' => '',
			'toFlight' => '',
			'departureAirport' => '',
			'arrivalAirport' => '',
			'departureDate' => '',
			'alertType' => '',
			'active' => '',
			'content' => '',
		);

		foreach( $fields as $key => $value ) {
			$fields[ $key ] = get_post_meta( $post->ID, $key, true ) ?? '';
		}
		
		wp_nonce_field( "flight-info-alert-metabox", "flight-info-alert-metabox-nonce" );
		?>
			<div class="fia-form-group">
				<label for="description">Description</label>
				<textarea class="fia-form-control" id="description" name="description" rows="3"><?php echo $fields[ 'description' ]; ?></textarea>
				<div class="fia-help-block">Describe the purpose of the alert.</div>
			</div>
			<div class="fia-form-group">
				<label for="iataCarrierCode">IATA Carrier Code</label>
				<input type="text" class="fia-form-control" id="iataCarrierCode" name="iataCarrierCode" value="<?php echo $fields[ 'iataCarrierCode' ]; ?>">
				<div class="fia-help-block">A two-character code assigned by the IATA to international airlines.</div>
			</div>
			<div class="fia-form-group">
				<label for="icaoCarrierCode">ICAO Carrier Code</label>
				<input type="text" class="fia-form-control" id="icaoCarrierCode" name="icaoCarrierCode" value="<?php echo $fields[ 'icaoCarrierCode' ]; ?>">
				<div class="fia-help-block">A three-character code assigned by the ICAO to international airlines.</div>
			</div>
			<div class="fia-form-group">
				<label for="flightNumber">Flight Number</label>
				<input type="text" class="fia-form-control" id="flightNumber" name="flightNumber" value="<?php echo $fields[ 'flightNumber' ]; ?>">
				<div class="fia-help-block">A numeric part (up to four digits) of a flight designator.</div>
			</div>
			<div class="fia-form-group">
				<label for="fromFlight">Flight From</label>
				<input type="text" class="fia-form-control" id="fromFlight" name="fromFlight" value="<?php echo $fields[ 'fromFlight' ]; ?>">
				<div class="fia-help-block">A numeric part (up to four digits) of a flight designator.</div>
			</div>
			<div class="fia-form-group">
				<label for="toFlight">Flight To</label>
				<input type="text" class="fia-form-control" id="toFlight" name="toFlight" value="<?php echo $fields[ 'toFlight' ]; ?>">
				<div class="fia-help-block">A numeric part (up to four digits) of a flight designator.</div>
			</div>
			<div class="fia-form-group">
				<label for="departureAirport">Departure Airport</label>
				<input type="text" class="fia-form-control" id="departureAirport" name="departureAirport" value="<?php echo $fields[ 'departureAirport' ]; ?>">
				<div class="fia-help-block">A Three-letter code assigned by the IATA to an airport location representing departure airport.</div>
			</div>
			<div class="fia-form-group">
				<label for="arrivalAirport">Arrival Airport</label>
				<input type="text" class="fia-form-control" id="arrivalAirport" name="arrivalAirport" value="<?php echo $fields[ 'arrivalAirport' ]; ?>">
				<div class="fia-help-block">A Three-letter code assigned by the IATA to an airport location representing arrival airport.</div>
			</div>
			<div class="fia-form-group">
				<label for="alertType">Alert Type</label>
				<select class="fia-form-control" id="alertType" name="alertType">
					<option value="">Select an option</option>
					<option value="Global" <?php selected( $fields[ 'alertType' ], 'Global' ); ?>>Global</option>
					<option value="Carrier" <?php selected( $fields[ 'alertType' ], 'Carrier' ); ?>>Carrier</option>
					<option value="Port" <?php selected( $fields[ 'alertType' ], 'Port' ); ?>>Port</option>
				</select>
			</div>
			<div class="fia-form-group">
				<label for="active">Active</label>
				<select class="fia-form-control" id="active" name="active">
					<option value="">Select an option</option>
					<option value="true" <?php selected( $fields[ 'active' ], 'true' ); ?>>Yes</option>
					<option value="false" <?php selected( $fields[ 'active' ], 'false' ); ?>>No</option>
				</select>
			</div>
			<div class="fia-form-group">
				<label for="content">Content</label>
				<input type="text" class="fia-form-control" id="content" name="content" value="<?php echo $fields[ 'content' ]; ?>">
				<div class="fia-help-block">Additional data is included in the API response in the <strong>Premium Content group</strong>.</div>
			</div>
			<div class="fia-form-group">
				<label for="departureDate">Departure Date</label>
				<input type="date" class="fia-form-control" id="departureDate" name="departureDate" value="<?php echo $fields[ 'departureDate' ]; ?>">
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

		if( $data = get_option( $this->plugin_name . '-data' ) ) {
			if( isset( $data[ 'api_key' ] ) && isset( $data[ 'account_id' ] ) ) {
				
				// if( 1 ) {


				// 	// if( 2 ) {
				// 	// 	$fields = array(
				// 	// 		'description',
				// 	// 		'iataCarrierCode',
				// 	// 		'iataCarrierCode',
				// 	// 		'icaoCarrierCode',
				// 	// 		'flightNumber',
				// 	// 		'fromFlight',
				// 	// 		'toFlight',
				// 	// 		'departureAirport',
				// 	// 		'arrivalAirport',
				// 	// 		'departureDate',
				// 	// 		'alertType',
				// 	// 		'active',
				// 	// 		'content',
				// 	// 	);
				
				// 	// 	foreach( $fields as $field ) {
				// 	// 		if( isset( $_POST[ $field ] ) ) {
				// 	// 			update_post_meta( $post_id, $field, esc_attr( $_POST[ $field ] ) );
				// 	// 		}	
				// 	// 	}
				// 	// }
				// }
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
		//section name, display name, callback to print description of section, page to which section is attached.
        add_settings_section("header_section", "Header Options", array( $this, 'display_header_options_content' ), "theme-options");

        //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
        //last field section is optional.
        add_settings_field("header_logo", "Logo Url", array( $this, 'display_logo_form_element' ), "theme-options", "header_section");
        add_settings_field("advertising_code", "Ads Code", array( $this, 'display_ads_form_element' ), "theme-options", "header_section");

        //section name, form element name, callback for sanitization
        register_setting("header_section", "header_logo");
        register_setting("header_section", "advertising_code");
	}

	function display_header_options_content(){echo "The header of the theme";}
    function display_logo_form_element()
    {
        //id and name of form element should be same as the setting name.
        ?>
            <input type="text" name="header_logo" id="header_logo" value="<?php echo get_option('header_logo'); ?>" />
        <?php
    }
    function display_ads_form_element()
    {
        //id and name of form element should be same as the setting name.
        ?>
            <input type="text" name="advertising_code" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
        <?php
	}

	public function settings_page() {
		?>
			<div class="wrap">
				<h1><?php echo $GLOBALS['title'] ?></h1>
				<form method="POST" action="options.php">
					<?php
						//add_settings_section callback is displayed here. For every new section we need to call settings_fields.
						settings_fields("header_section");
                   
						// all the add_settings_field callbacks is displayed here
						do_settings_sections("theme-options");
				   
						// Add the submit button to serialize the options
						submit_button();
					?>
				</form>
			</div>
		
		<?php
    }

}
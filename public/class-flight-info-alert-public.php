<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://mohammadwahid.com
 * @since      1.0.0
 *
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Flight_Info_Alert
 * @subpackage Flight_Info_Alert/public
 * @author     Mohammad Wahid <mohammadwahid.eng@gmail.com>
 */
class Flight_Info_Alert_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name . '-dt', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-dt-button', plugin_dir_url( __FILE__ ) . 'css/buttons.dataTables.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-dt-responsive', plugin_dir_url( __FILE__ ) . 'css/responsive.dataTables.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flight-info-alert-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name . '-dt', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-dt-button', plugin_dir_url( __FILE__ ) . 'js/dataTables.buttons.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-dt-column', plugin_dir_url( __FILE__ ) . 'js/buttons.colVis.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-dt-responsive', plugin_dir_url( __FILE__ ) . 'js/dataTables.responsive.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flight-info-alert-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'fia_api', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( $this->plugin_name ) ) );

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

				wp_send_json( array(
					'success'	=> true,
					'data' 		=> $result
				) );
				
			} else {

				wp_send_json( array(
					'success'	=> false,
					'message' 	=> $result->message ?? __( 'We could not fetch data for you at this time. Try again later.', 'flight-info-alert' )
				) );
			}	
		}

		die();
	}

	public function register_shortcodes() {
		add_shortcode( 'flight_info_alerts', array( $this, 'flight_info_alerts_html') );
	}

	public function flight_info_alerts_html() {
		$html = '';
		$html .= '<table class="fia_table" class="display responsive nowrap" style="width:100%">';
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<th>Name</th>';
					$html .= '<th>Alert Type</th>';
					$html .= '<th>Active</th>';
					$html .= '<th>Flight Number</th>';
					$html .= '<th>Flight From</th>';
					$html .= '<th>Flight To</th>';
					$html .= '<th>Departure Airport</th>';
					$html .= '<th>Arrival Airport</th>';
					$html .= '<th>Departure Date</th>';
					$html .= '<th>IATA Carrier Code</th>';
					$html .= '<th>ICAO Carrier Code</th>';
					$html .= '<th>Description</th>';
					$html .= '<th>Last Update</th>';
				$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody></tbody>';
		$html .= '</table>';

		return $html;
	}

}

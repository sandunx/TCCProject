<?php

/**
 *
 * Cookieyes Integration
 *
 * @version 2.3.2
 * @package CookieLawInfo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Cookie_Law_Info_Package_Handler' ) ) {
	require_once 'class-cookie-law-info-package-handler.php';
}

if ( ! class_exists( 'Cookie_Law_Info_License_Notifications' ) ) {
	require_once 'class-cookie-law-info-license-notifications.php';
}

if ( ! class_exists( 'Cookie_Law_Info_License_Handler' ) ) {

	/**
	 * Cookieyes License handler
	 */
	class Cookie_Law_Info_License_Handler {

		/**
		 * Current license version
		 *
		 * @var int
		 */
		protected $product_name;
		protected $product_slug;
		protected $plugin_settings_url;
		protected $domain;
		protected $license;
		protected $product_abs_name;
		public $last_error_message;

		private static $license_initial_version = '1.0';
		private static $instance;

		const API_BASE_URI  = 'https://www.webtoffee.com/';
		const EDD_PLUGIN_ID = '196737';
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wt_cli_license_section', array( $this, 'license_handler' ) );
			add_action( 'wp_ajax_wf_activate_license_keys_' . $this->get_product_name(), array( $this, 'activate' ) );
			add_action( 'wp_ajax_wf_deactivate_license_keys_' . $this->get_product_name(), array( $this, 'deactivate' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'license_activated' ) );
		}
		/**
		 * Init hook callback
		 *
		 * @return void
		 */
		public function init() {
			// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
			$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
			if ( ! current_user_can( 'update_plugins' ) && ! $doing_cron ) {
				return;
			}
			$license = $this->get_license_data();
			// retrieve our license key from the DB
			$license_key = trim( $license['licence_key'] );

			// setup the updater
			$edd_updater = new Cookie_Law_Info_Package_Handler(
				self::API_BASE_URI,
				CLI_PLUGIN_FILENAME,
				array(
					'version'   => CLI_VERSION,                    // current version number.
					'license'   => $license_key,             // license key (used get_option above to retrieve from DB).
					'item_name' => $this->get_product_abs_name(),
					'item_id'   => self::EDD_PLUGIN_ID,       // ID of the product.
					'author'    => 'WebToffee', // author of this plugin.
					'beta'      => false,
				)
			);
		}
		/**
		 * Returns the current instance
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		/**
		 * Return product slug
		 *
		 * @return string
		 */
		public function get_product_slug() {
			if ( ! $this->product_slug ) {
				$this->product_slug = CLI_PLUGIN_BASENAME;
			}
			return $this->product_slug;
		}
		/**
		 * Return product name
		 *
		 * @return string
		 */
		public function get_product_name() {
			if ( ! $this->product_name ) {
				$this->product_name = 'wtgdprcookieconsent';
			}
			return $this->product_name;
		}

		/**
		 * Returns the real plugin name
		 *
		 * @return string
		 */
		public function get_product_abs_name() {
			if ( ! $this->product_abs_name ) {
				$this->product_abs_name = __( 'GDPR Cookie Consent Pro' );
			}
			return $this->product_abs_name;
		}
		public function get_plugin_settings_url() {
			if ( ! $this->plugin_settings_url ) {
				$this->plugin_settings_url = admin_url( 'edit.php?post_type=' . CLI_POST_TYPE . '&page=cookie-law-info#cookie-law-info-licence' );
			}
			return $this->plugin_settings_url;
		}
		/**
		 * Return product slug
		 *
		 * @return string
		 */
		public function get_domain() {
			if ( ! $this->domain ) {
				$license = $this->get_license_data();
				if ( isset( $license['licence_url'] ) && '' !== $license['licence_url'] ) {
					$this->domain = $license['licence_url'];
				} else {
					$this->domain = home_url();
				}
			}

			return $this->domain;
		}
		public function get_last_error_message() {
			if ( ! $this->last_error_message ) {
				$error = get_transient( $this->get_product_name() . 'license_last_error' );
				if ( false !== $error ) {
					$error = wp_kses_post( $error );
				}
				$this->last_error_message = $error;
			}
			return $this->last_error_message;
		}
		public function set_error_message( $message ) {
			if ( false === $message ) {
				delete_transient( $this->get_product_name() . 'license_last_error' );
				return;
			}
			$message = wp_kses_post( $message );
			set_transient( $this->get_product_name() . 'license_last_error', $message, 12 * HOUR_IN_SECONDS );
			$this->last_error_message = $message;
		}
		public function flush_errors() {
			$this->set_error_message( false );
		}

		public function license_handler() {
			$license     = $this->get_license_data();
			$plugin_name = $this->get_product_name();
			include 'html/html-wf-activation-window.php';
		}
		public function get_base_path() {
			return self::API_BASE_URI;
		}

		/**
		 * Fetch license data from the options table.
		 *
		 * @return array
		 */
		public function get_license_data() {

			$license   = array(
				'status'        => false,
				'licence_key'   => '',
				'instance_id'   => '',
				'licence_email' => '',
			);
			$plugin_id = $this->get_product_name();
			if ( ! empty( $plugin_id ) ) {

				$license['status']        = sanitize_text_field( get_option( $plugin_id . '_' . 'activation_status', '' ) );
				$license['licence_key']   = sanitize_text_field( get_option( $plugin_id . '_' . 'licence_key', '' ) );
				$license['instance_id']   = sanitize_text_field( get_option( $plugin_id . '_' . 'instance_id', '' ) );
				$license['licence_email'] = sanitize_text_field( get_option( $plugin_id . '_' . 'email', '' ) );
				$license['licence_url']   = esc_url( get_option( $plugin_id . '_' . 'licence_url', '' ) );
			}
			return $license;
		}

		/**
		 * Set license data in the options table.
		 *
		 * @param object $license License data.
		 * @return void
		 */
		public function set_license_data( $license ) {
			if ( ! empty( $license ) ) {
				$plugin_id = $this->get_product_name();
				if ( ! empty( $plugin_id ) ) {
					$license_status = '';
					if ( isset( $license['status'] ) && ! empty( $license['status'] ) ) {
						$license_status = 'inactive';
						if ( true === $license['status'] ) {
							$license_status = 'active';
						}
					}
					$license_data = array(
						$plugin_id . '_' . 'activation_status' => $license_status,
						$plugin_id . '_' . 'licence_key' => isset( $license['licence_key'] ) ? sanitize_text_field( $license['licence_key'] ) : '',
						$plugin_id . '_' . 'instance_id' => isset( $license['instance_id'] ) ? sanitize_text_field( $license['instance_id'] ) : '',
						$plugin_id . '_' . 'email'       => isset( $license['licence_email'] ) ? sanitize_text_field( $license['licence_email'] ) : '',
						$plugin_id . '_' . 'licence_url' => esc_url( home_url() ),
					);
					foreach ( $license_data as $key => $data ) {
						update_option( $key, $data );
					}
				}
			}
		}

		/**
		 * Reset license data in the options table.
		 *
		 * @return void
		 */
		public function reset_license_data() {
			$license_data = array(
				'status'        => '',
				'licence_key'   => '',
				'instance_id'   => '',
				'licence_email' => '',
				'licence_url'   => '',
			);
			$this->set_license_data( $license_data );
		}

		public function set_license_inactive() {
			$license = $this->get_license_data();
			if ( isset( $license['status'] ) && 'active' === $license['status'] ) {
				$license['status'] = false;
				$this->set_license_data( $license );
			}
		}

		public function activate() {
			$message     = '';
			$license_key = isset( $_GET['licence_key'] ) ? sanitize_text_field( $_GET['licence_key'] ) : '';
			$email       = isset( $_GET['email'] ) ? sanitize_text_field( $_GET['email'] ) : '';
			if ( empty( $license_key ) && empty( $email ) ) {
				wp_send_json_error( __( 'License key & email is required', 'webtoffee-gdpr-cookie-consent' ) );
			}
			$api_params = array(
				'edd_action'  => 'activate_license',
				'license'     => $license_key,
				'item_id'     => self::EDD_PLUGIN_ID,
				'item_name'   => rawurlencode( $this->get_product_abs_name() ), // the name of our product in EDD
				'url'         => home_url(),
				'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
			);

			// Call the custom API.
			$response = wp_remote_post(
				self::API_BASE_URI,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				if ( false === $license_data->success ) {
					$message = $this->get_edd_error_messages( $license_data->error );
					$this->check_for_possible_deactivation( $response['license'] );

				} else {
					$data = array(
						'status'        => true,
						'licence_key'   => $license_key,
						'instance_id'   => isset( $response['instance'] ) ? $response['instance'] : '',
						'licence_email' => $email,
					);
					$this->set_license_data( $data );
					wp_send_json_success( __( 'License activation successfull', 'webtoffee-gdpr-cookie-consent' ) );
				}
			}
			wp_send_json_error( $message );
		}

		public function deactivate() {
			$message    = '';
			$license    = $this->get_license_data();
			$api_params = array(
				'edd_action'  => 'deactivate_license',
				'license'     => $license['licence_key'],
				'item_id'     => self::EDD_PLUGIN_ID,
				'item_name'   => rawurlencode( $this->get_product_abs_name() ), // the name of our product in EDD
				'url'         => home_url(),
				'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
			);

			// Call the custom API.
			$response = wp_remote_post(
				self::API_BASE_URI,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.' );
				}
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {
					$message = $this->get_edd_error_messages( $license_data->error );
				} elseif ( 'deactivated' === $license_data->license ) {
					$this->reset_license_data();
					wp_send_json_success( __( 'License deactivation successfull', 'webtoffee-gdpr-cookie-consent' ) );
				}
			}
			$this->reset_license_data();
			wp_send_json_error( $message );
		}

		public function get_edd_errors() {
			$errors = array(
				'expired',
				'revoked',
				'missing',
				'site_inactive',
				'invalid',
				'item_name_mismatch',
				'inactive',
			);
			return $errors;
		}
		public function get_edd_error_messages( $error ) {

			switch ( $error ) {

				case 'expired':
					$message = __( 'The product license has either expired or not been activated.' );
					break;

				case 'revoked':
					$message = __( 'Your license key has been disabled.' );
					break;

				case 'missing':
					$message = __( 'Invalid license.' );
					break;

				case 'invalid':
				case 'site_inactive':
				case 'inactive':
					$message = __( 'Your license is not active for this URL.' );
					break;

				case 'item_name_mismatch':
					$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), $this->get_product_name() );
					break;

				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.' );
					break;

				case 'key_mismatch':
					$message = __( 'License is not valid for this product' );
					break;

				default:
					$message = __( 'An error occurred, please try again.' );
					break;
			}
			return $message;
		}

		public function check_for_possible_deactivation( $errors ) {
			if ( ! is_array( $errors ) ) {
				if ( in_array( $errors, $this->get_edd_errors() ) ) {
					$this->set_license_inactive();
				}
			}
		}

		/**
		 * Static license check
		 *
		 * @return void
		 */
		public function license_activated() {
			$notifier = Cookie_Law_Info_License_Notifications::get_instance(
				CLI_PLUGIN_FILENAME,
			);

			$license_data = $this->get_license_data();
			if ( '' === $license_data['status'] || 'inactive' === $license_data['status'] ) {
				$message = sprintf( __( 'The plugin license is not activated. You will not receive compatibility and security updates if the plugin license is not activated. <a href="%s" target="_blank">Activate now</a>' ), $this->get_plugin_settings_url() );
				$notifier->set_errors( $message );
			} elseif ( 'active' === $license_data['status'] ) {
				$notifier->flush_errors();
			}
		}
		/**
		* 	@since 2.5.7
		*	Mask licence key
		*/
		public function mask_licence_key($key)
		{
			$total_length=strlen($key);
			$non_mask_length=6; //including both side
			$mask_length=$total_length-$non_mask_length;
			
			if($mask_length>=1) //atleast one character
			{
				$key=substr_replace($key, str_repeat("*", $mask_length), floor($non_mask_length/2), ($total_length-$non_mask_length));
			}else
			{
				$key=str_repeat("*", $total_length); //replace all character
			}
			return $key;		
		}
	}
	Cookie_Law_Info_License_Handler::get_instance();
}

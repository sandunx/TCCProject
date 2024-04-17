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
if ( ! class_exists( 'Cookie_Law_Info_License_Notification' ) ) {

	/**
	 * Handle license related notifications
	 */
	class Cookie_Law_Info_License_Notifications {

		private $errors        = '';
		private $name          = '';
		private $transient_key = '';

		private static $instance;

		/**
		 * Class constructor.
		 *
		 * @uses plugin_basename()
		 * @uses hook()
		 *
		 * @param string $_plugin_file Path to the plugin file.
		 */
		public function __construct( $_plugin_file ) {
			$this->name          = plugin_basename( $_plugin_file );
			$this->transient_key = 'edd_sl_failed_message_' . md5( $this->name );
			$this->init();
		}

		/**
		 * Returns the current instance
		 *
		 * @return object
		 */
		public static function get_instance( $_plugin_file ) {
			if ( null === self::$instance ) {
				self::$instance = new self( $_plugin_file );
			}

			return self::$instance;
		}

		/**
		 * Initialize the class
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'admin_footer', array( $this, 'add_inline_script' ) );
			add_action( 'after_plugin_row_' . $this->name, array( $this, 'add_notification' ), 10, 2 );
		}
		/**
		 * Fetch all the errors stored in the transient.
		 *
		 * @return string
		 */
		public function get_errors() {
			return wp_kses_post( get_transient( $this->transient_key ) );
		}

		/**
		 * Store the errors in the transient.
		 *
		 * @return void
		 */
		public function set_errors( $message ) {
			set_transient( $this->transient_key, wp_kses_post( $message ), 3 * HOUR_IN_SECONDS );
		}

		/**
		 * Flush all the errors from the transient
		 *
		 * @return void
		 */
		public function flush_errors() {
			delete_transient( $this->transient_key );
		}

		/**
		 * Return the error message based on the error code.
		 *
		 * @param string $error Error code.
		 * @return string
		 */
		public function get_error_message( $error ) {
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
					$message = __( 'This appears to be an invalid license key' );
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
		/**
		 * Add error notification to the active plugin row.
		 *
		 * @return void
		 */
		public function add_notification() {
			if ( is_network_admin() || ! current_user_can( 'update_plugins' ) || ! $this->get_errors() ) {
				return;
			}
			echo '<tr class="plugin-update-tr installer-plugin-update-tr wt-cli-plugin-inline-notice-tr">
                <td colspan="4" class="plugin-update colspanchange">
                    <div class="update-message notice inline wt-plugin-notice-section">
                        <p>' . wp_kses_post( $this->get_errors() ) . '</p>
                        </div>
                </td>
            </tr>';
		}

		public function add_inline_script() {
			global $pagenow;
			if ( $pagenow == 'plugins.php' ) {
				?>
				<style>
				.wt-plugin-notice-section p:before {
					content: "\f534";
				}
				</style>
				<script>
				if(typeof WTPluginAddParentStyle != 'function'){

					function WTPluginAddParentStyle() {
						jQuery('.wt-cli-plugin-inline-notice-tr').each(function () {
							if (jQuery(this).prev().addClass('update').hasClass('active')) {
								jQuery(this).addClass('active');
							}
						})
					}
				}
				jQuery(document).ready(WTPluginAddParentStyle);
				</script>
				<?php
			}
		}
	}
}

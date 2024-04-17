<?php
if (!defined('ABSPATH')) {
    exit;
}

class Cookie_Law_Iab_Tcf {

    public $enabled = true;
    public $list_url = 'https://cdn.webtoffee.com/iab-tcf/cmp/v3/';
    private static $instance;

    public function __construct() {
        add_action( 'init', array($this, 'init') );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts'));
    }

    public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    public function init() {
        if( !$this->enabled ) return;
        $this->maybe_update_vendor_list();
    }

    public function update_vendor_list() {
        $i18n      = new Cookie_Law_Info_Languages();
        $languages = $i18n->cli_get_current_language_code();
        $this->download( $this->list_url.'vendor-list.json' );
        //Download consent providers csv
        $this->download( $this->list_url.'additional-consent-providers.csv' );
        foreach ( $languages as $lang ) {
            $this->download($this->list_url. "purposes-".$lang.".json");
		}
        set_transient( 'cli_vendor_fle_update', true, WEEK_IN_SECONDS );
    }

    public function maybe_update_vendor_list() {
        if ( ! get_transient( 'cli_vendor_fle_update') ) {
            $this->update_vendor_list();
        }
    }

    public static function get_upload_path( $path = '', $is_url = false ) {
        $uploads    = wp_upload_dir();
        $upload_dir =  $uploads['basedir'] . '/webtoffee/' . $path;
        $upload_url =  $uploads['baseurl'] . '/webtoffee/' .$path;
		if ( !is_dir( $upload_dir)  ) {
			wp_mkdir_p($upload_dir);
		}
		return $is_url ? $upload_url : trailingslashit( $upload_dir );
    }
    public function download( $src ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	    $upload_dir = $this->get_upload_path('cmp/v3/');
        
        if ( ! file_exists( $upload_dir ) ) {
            wp_mkdir_p( $upload_dir, 0755);
        }

         // Check if the path contains "vendor-list.json"
         if (strpos($src, 'vendor-list.json') !== false) {
            // Perform your custom action for "vendor-list.json"
            $this->create_vendor_list_file($src, $upload_dir);
            return; // Exit the function to avoid further processing
        }
        //download file
        $tmpfile  = download_url( $src, $timeout = 25 );
        $file     = $upload_dir . basename( $src );
       
        //check for errors
        if ( !is_wp_error( $tmpfile ) ) {
            //remove current file
            if ( file_exists( $file ) ) {
                unlink( $file );
            }

            //in case the server prevents deletion, we check it again.
            if ( ! file_exists( $file ) ) {
                copy( $tmpfile, $file );
            }
        }

        if ( is_string( $tmpfile ) && file_exists( $tmpfile ) ) {
            unlink( $tmpfile );
        }
    }

    public static function get_vendor_list( $language = '') {

        $vendor_list_path='';

        $path =  self::get_upload_path('cmp', true);

        // Get the upload directory path
        $upload_dir = wp_upload_dir();

        if(!empty($language))
        {
            // Specify the file path within the wp_uploads folder
            $vendor_list_path = $upload_dir['basedir'] . '/webtoffee/cmp/v3/purposes-'.$language.'.json';

            if(file_exists($vendor_list_path))
            {
                return $vendor_list_path;
            }
            else{

                $vendor_list_path = $upload_dir['basedir'] . '/webtoffee/cmp/v3/vendor-list.json';
            }
        }
        else{

            $vendor_list_path = $upload_dir['basedir'] . '/webtoffee/cmp/v3/vendor-list.json';
        }
        if ( ! file_exists( $vendor_list_path ) ) {

            $tcf = new Cookie_Law_Iab_Tcf();
            $tcf->update_vendor_list();
        }
        return $vendor_list_path;
    }
     /**
     * Return the google vendor list
     *
     * @return array $googlevendors.
     */
    public static function get_google_vendors( ) {
        $vendor_list_path = self::get_google_vendor_list_path();
        if( !$vendor_list_path ) return;
        $googlevendors    = wp_cache_get( 'wcc_google_vendors_list', 'wcc_google_vendors' );
        if ( ! $googlevendors ) {
            /* Fetching Google ad tech vendors */
            $csvFile    = $vendor_list_path;
            // Open the file for reading
            $fileHandle = fopen($csvFile, 'r');
            if ( $fileHandle !== false ) {
                $googlevendors = [];
                // Read each line of the CSV file
                while ( ( $row = fgetcsv( $fileHandle ) ) !== false ) {
                    $googlevendors[] = $row; // Add each row as an array to $dataArray
                }
                fclose( $fileHandle ); // Close the file handle
            }
            wp_cache_set( 'wcc_google_vendors_list', $googlevendors, 'wcc_google_vendors', 12 * HOUR_IN_SECONDS );
        }
        return $googlevendors;
    }
     /**
     * Return the google vendor-list.csv file's path
     *
     * @return string $vendor_list_path.
     */
    public static function get_google_vendor_list_path() {
        $vendor_list_path = '';
        // Get the upload directory path
        $upload_dir = wp_upload_dir();
        $vendor_list_path = $upload_dir['basedir'] . '/webtoffee/cmp/v3/additional-consent-providers.csv';
        if ( ! file_exists( $vendor_list_path ) ) {
            $tcf = new Cookie_Law_Iab_Tcf;
            $tcf->update_vendor_list();
        }
        return $vendor_list_path;
    }
     /**
     * Create the vendor-list.json file 
     *
     * @param object $src.
     */
    public function create_vendor_list_file( $src, $upload_dir ) {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'Accept-Encoding: gzip, deflate, br',
            ],
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];
        
        $context = stream_context_create($options);
        $data = file_get_contents( $src, false, $context );
        if (!$data) {
            return;
        }
        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->buffer($data);
        if ($mimeType === 'application/gzip') {
            $data = gzdecode($data);
        }
        $data = $this->merge_google_vendors_into_gvl(json_decode($data));
        //file_put_contents($upload_dir, json_encode($data, JSON_UNESCAPED_UNICODE));
        // Save the modified JSON file
        file_put_contents($upload_dir . 'vendor-list.json', json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    /**
     * Downloads the Google vendor list and merge into Global vendor list.
     *
     * @param object $gvlData.
     * @return object
     */
    public function merge_google_vendors_into_gvl( $gvlData ) {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'Accept-Encoding: gzip, deflate, br',
            ],
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];
        
        $context             = stream_context_create( $options );
        $googleVendorListCsv = file_get_contents( $this->list_url.'additional-consent-providers.csv', false, $context );
        $lines               = explode( PHP_EOL, $googleVendorListCsv );
        array_shift( $lines );
        $googleVendorList = [];
        foreach ( $lines as $line ) {
            $row = str_getcsv( $line );
            if ( count( $row ) < 3)  {
                continue;
            }
            $googleVendorList[ $row[0] ] = [
                'id' => (int) $row[0],
                'name' => $row[1],
                'privacy' => $row[2],
            ];
        }
        if (!empty( $googleVendorList )) {
            $gvlData->googleVendors = json_decode(json_encode( $googleVendorList ));
        }
        return $gvlData;
    }
    public function enqueue_scripts() {
        $settings                 = Cookie_Law_Info::get_settings();
        $is_iab_enabled           = isset( $settings['iab_enabled'] ) ? $settings['iab_enabled'] : false; 
        $is_consent_mode_enabled  = isset( $settings['consent_mode'] )? $settings['consent_mode']: false; 
        if( is_admin() || !$is_iab_enabled ) return;
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        $language = 'es';
        $i18n = new Cookie_Law_Info_Languages();
        $language_code_arr = $i18n->cli_get_current_language_code();
        if($is_iab_enabled)
        {
            wp_enqueue_script( 'cli-iab-script',plugin_dir_url( __FILE__ ) . 'assets/js/script' . $suffix . '.js', array(), CLI_VERSION, false );
            wp_localize_script('cli-iab-script', 'iabConfig', array(
                'status' => $this->enabled,
                'baseUrl' => $this->get_upload_path('cmp', true),
                'latestFilename' => 'v3/vendor-list.json',
                'languageFilename' => "v3/purposes-".$language.".json",
                'appliedLaw' =>  $settings['consent_type'],
                'allowedVendors' => Cookie_Law_Info_Settings_Popup_For_IAB::get_allowed_vendor_list(),
                'isGoogleACMEnabled' => $is_consent_mode_enabled,
                'allowedGoogleVendors' => Cookie_Law_Info_Settings_Popup_For_IAB::get_allowed_google_vendor_list()
            ) );
            wp_localize_script('cli-iab-script', 'iabTranslations', array(
                'storageDisclosures' => array(
                    'title' => __('Device Storage Disclosure', 'webtoffee-gdpr-cookie-consent'), 
                    'headers' => array(
                        'name' => __('Name', 'webtoffee-gdpr-cookie-consent'),
                        'type' => __('Type', 'webtoffee-gdpr-cookie-consent'),
                        'duration' => __('Duration', 'webtoffee-gdpr-cookie-consent'),
                        'domain' => __('Domain', 'webtoffee-gdpr-cookie-consent'),
                        'purposes' => __('Purposes', 'webtoffee-gdpr-cookie-consent'),
                    )
                )
            ) );
        } 
    }
}
new Cookie_Law_Iab_Tcf();
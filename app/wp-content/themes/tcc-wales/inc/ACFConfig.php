<?php
/**
 * IndStan ACF Configuration Class.
 *
 * Adds permanent ACF menus and fields to admin.
 *
 * @author I N D U S R I A L  S T A N D A R D S
 * @package WordPress
 * @subpackage 
 * @link https://www.advancedcustomfields.com/resources/register-fields-via-php
 * @link https://www.bureauweb.ch/guide-to-registering-acf-fields-via-php/
*/
namespace IndStan;

if (!defined('ABSPATH')) die();


define( '__INC__' , get_stylesheet_directory() . '/inc' );
class ACFConfig
{
    /**
     * holds singleton instance of the class
     */
    private static $instance;
    
    /**
     * get singleton instance
     *
    */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * triggers functions on class load
     *
    */
    private function __construct()
    {
        $this->addhooks();
        $this->addACFMenuItems();

       
    }

    /**
     * Hooks into Wordpress
     *
    */
    private function addhooks()
    {
        add_action( 'acf/init', [$this, 'addACFFields']);
        add_action( 'wp_print_styles', [$this, 'deregisterACFstyles'], 10 );
        add_action('indstan_img_srcset', [$this,'acfResponsiveImage'],10,3 );
//        add_filter('acf/fields/google_map/api', [$this,'acfGoogleMapApi'],10,1);

    }

    /**
     * Adds custom menus to admin page
     *
    */
    public function addACFMenuItems()
    {
        if( function_exists('acf_add_options_page') ) {
          
          acf_add_options_page(array(
            'page_title'  => get_bloginfo('name'), 
            'menu_title'  => 'Site Options',
            'menu_slug'   => 'theme-general-settings',
            'capability'  => 'edit_posts',
            'redirect'    => true
          ));

           acf_add_options_sub_page(array(
            'page_title'  => 'Company Info',
            'menu_title'  => 'Company Information',
            'menu_slug'   => 'acf-options-company-information',
            'parent_slug' => 'theme-general-settings',
          ));
        }
    }

    /**
     * Adds permanent option fields to Options Page
     *
    */
    public function addACFFields() {

    if( function_exists('acf_add_local_field_group') ):

        include __INC__ . '/fields/features.php';
        include __INC__ . '/fields/team-member.php';

    endif;

    }

    /**
     * disable acf css on front-end acf forms
     *
    */
 
    function deregisterACFstyles() 
    {
          wp_deregister_style( 'acf' );
          wp_deregister_style( 'acf-input' );
          wp_deregister_style( 'acf-pro-input' );
          wp_deregister_style( 'acf-field-group' );
          wp_deregister_style( 'acf-global' );
          wp_deregister_style( 'acf-datepicker' );
          wp_deregister_style( 'select2' );
         
    }

    /**
     * Responsive Image Helper Function
     *
     * @param string $image_id the id of the image (from ACF or similar)
     * @param string $image_size the size of the thumbnail image or custom image size
     * @param string $max_width the max width this image will be shown to build the sizes attribute
     */

    function acfResponsiveImage( $image_id, $image_size, $max_width){

        // check the image ID is not blank
        if($image_id != '') {

            // set the default src image size
            $image_src = wp_get_attachment_image_url( $image_id, $image_size );

            // set the srcset with various image sizes
            $image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

            // generate the markup for the responsive image
            echo '<img src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"/>';

        }
    }

//    function acfGoogleMapApi( $api ){
//        $api['key'] = GOOGLE_MAP_API_KEY;
//        return $api;
//    }
}

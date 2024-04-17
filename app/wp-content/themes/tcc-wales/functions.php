<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */



require 'vendor/autoload.php';

/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles

    wp_enqueue_style( 'indstan-style', get_stylesheet_directory_uri() . '/main-style.css' );
    wp_enqueue_script( 'indstan-script' , get_stylesheet_directory_uri() . '/dist/main.bundle.js' ,'','','await');

}
add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 8888 );


/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

class ThemeSetup {

    /**
     * holds singleton instance of the class
     */
    private static $instance;
    private $EnqueueScripts;
    private $ThemeSupport;
    private $CustomPostType;
    private $ACFConfig;
    private $ContactForm;

    private $Analytics;

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
    function __construct()
    {
        $this->EnqueueScripts   = IndStan\Enqueue::getInstance();
        $this->ThemeSupport     = IndStan\ThemeSupport::getInstance();
        $this->ACFConfig        = IndStan\ACFConfig::getInstance();
        $this->CustomPostType   = IndStan\CustomPostType::getInstance();
        $this->ContactForm      = IndStan\ContactForm::getInstance();
        $this->Analytics        = IndStan\Analytics::getInstance();
    }
}

$themeSetup = ThemeSetup::getInstance();

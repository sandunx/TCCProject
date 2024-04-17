<?php
/**
 * IndStan Enqueue Class
 *
 * Performs the wp_enqueue functions.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @author I N D U S T R I A L  S T A N D A R D S  L T D 
 * @package  Industruil Standards
 *
*/

namespace IndStan;

if (!defined('ABSPATH')) die();

class Enqueue
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
        $this->addHooks();
    }

    private function addHooks() 
    {
        add_action( 'after_setup_theme', [ $this, 'removeEmojis'] );
        add_filter( 'wp_enqueue_scripts', [ $this , 'enqueueStyles' ] , 9999);
//        add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );
//        add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );


    }

    public static function enqueueStyles() {
        wp_enqueue_style( 'indstan-style', get_stylesheet_directory() . '/main-style.css', array(),'','' );
        wp_enqueue_script( 'indstan-script' , get_stylesheet_directory_uri() . '/dist/main.bundle.js' ,'','','await');

    }

    /**
     * Adds async/defer attributes to enqueued / registered scripts.
     *
     * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
     *
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @return string Script HTML string.
     */
    public function filter_script_loader_tag( $tag, $handle ) 
    {
        foreach ( array( 'async', 'defer' ) as $attr ) {

            if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
                continue;
            }

            // Prevent adding attribute when already added in #12009.
            if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
                $tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
            }

            // Only allow async or defer, not both.

            break;
        }

        return $tag;
    }

    function removeEmojis ()
    {

        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

    }
 }
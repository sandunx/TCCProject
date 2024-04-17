<?php
/**
 * Xpand Custom Post Type Controller class.
 *
 * Load custom post types by including them below
 *
 * @author I N D U S T R I A L   S T A N D A R D S   L T D 
 * @package WordPress
 * @subpackage Veritas_Twentyone
 * @since Twenty Twenty 1.0
*/

namespace IndStan;

if (!defined('ABSPATH')) die();

require __DIR__ . '/cpt/job.php';
require __DIR__ . '/cpt/trustee.php';
require __DIR__ . '/cpt/campaign.php';
require __DIR__ . '/cpt/achievement.php';
require __DIR__ . '/cpt/team-member.php';



class CustomPostType
{
    /**
     * holds singleton instance of the class
     */
    private static $instance;

    /**
     * holds the ACF Settings for this class
     */
    private static $formSettings ;
    
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

    /**
     * Hooks in custom post types from CPT folder
     * 
    */
    private function addHooks()
    {
        add_action('init', [ $this , 'addPostTypes' ], 0, 0);
    }

    /**
     * Hooks in custom post types from CPT folder
     * 
    */
    static function addPostTypes() {

        cpt_job();
        cpt_trustee();
        cpt_campaign();
        cpt_team_member();
        cpt_achievement();

    }
}

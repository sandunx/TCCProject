<?php

namespace IndStan;

class Analytics
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

    function addHooks(){

        add_action('wp_head', [$this, 'addGtm']);
        add_action('wp_body_open', [$this, 'addAfterOpeningBodyTag']);
    }

    function addGtm() { ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TBFC7W7J');</script>
        <!-- End Google Tag Manager -->
    <?php }

    function addAfterOpeningBodyTag() { ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TBFC7W7J"
                          height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    <?php }

}
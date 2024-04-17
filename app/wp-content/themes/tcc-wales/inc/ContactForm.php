<?php

namespace IndStan;

/**
 *
 * Industrial Standards Contact Form Class
 *
 * Sets up the contact form and sends email.
 *
 * @author I N D U S T R I A L  S T A N D A R D S
 * @package WordPress
 * @subpackage pe-planning-twenty-one
 */

if (!defined('ABSPATH')) die();

class ContactForm
{
    /**
     * holds singleton instance of the class
     */
    private static $instance;

    /**
     * holds the ACF Settings for this class
     */
    private static $formSettings;

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
        $this->setSettings();
    }

    /**
     *
     *
     */
    private function addHooks()
    {
        // add_action( 'wp_head', [ $this, 'addFormHeader' ]);
        // add_action( 'wp_enqueue_scripts', [ $this, 'addReCaptcha3' ]);
        add_action('acf/init', [$this, 'registerForm']);
        add_filter('wp_mail_content_type', [$this, 'setHTMLContentType']);
        add_action('acf/save_post', [$this, 'handleSignUp']);
//        add_action('acf/init', [ $this,'addAcfInputClasses']);
        add_action( 'acf/prepare_field',[ $this,'addAcfInputClasses'],10,1);

        // add_filter( 'acf/submit_form', [$this, 'printForm'], 10, 2);
    }

    /**
     * Incudes the ACF header on the contact Page.
     *
     */
    // function addFormHeader()
    // {
    //     if( is_page_template('contact.php')){
    //         acf_form_head();
    //     }
    // }

    /**
     * Incudes the ACF header on the contact Page.
     *
     */
    function registerForm()
    {
        if (function_exists('acf_register_form')) {
            acf_register_form($this->getSettings());
        }
    }

    /**
     * Incudes the ACF header on the contact Page.
     *
     */
    function getSettings()
    {
        return self::$formSettings;
    }

    /**
     * Sets the ACF form Settings on construct
     *
     */
    function setSettings()
    {

        // (String | Array) Array of settings or ‘id’ of a registered form.
        $formSettings = [

            // id
            //(String) A unique identifier for the form. Defaults to ‘acf-form’.
            'id' => 'contact-form',

            // post_id
            //(Integer | String) The post ID used to determine which fields to show, where data is loaded, and where data is saved. Defaults to current post ID. Can also be sent to ‘new_post’ to create a new post on submit.
            'post_id' => 'new_post',

            //new_post
            //(Array) When the above attribute is set to “new_post”, this setting is used to create the post. See wp_insert_post for available parameters.

            'new_post' => [
                'post_type' => 'contact-post-main',
                'post_status' => 'private',
            ],

            //field_groups
            //(Array) An array of field group IDs/keys to override the fields displayed in this form.
            'field_groups' => ['drh_contact_form'],

            //fields
            //(Array) An array of field IDs/keys to override the fields displayed in this form.
            'fields' => false,

            // post_title
            //(Boolean) Whether or not to show the post title text field. Defaults to false.
            'post_title' => false,

            //post_content
            //(Boolean) Whether or not to show the post content editor field. Defaults to false.
            'post_content' => false,

            //form
            //(Boolean) Whether or not to create a form element. Useful when adding to an existing form. Defaults to true.
            'form' => true,

            //form_attributes
            //(Array) An array or HTML attributes for the form element.
            'form_attributes' => array('contact-form'),

            // return
            //(String) The URL to be redirected to after the form is submitted. Defaults to the current URL with a GET parameter ‘?updated=true’.
            //A special placeholder ‘%post_url%’ will be converted to post’s permalink.
            //A special placeholder ‘%post_id%’ will be converted to post’s ID.
            // 'return' => false,

            //html_before_fields
            //(String) Extra HTML to add before the fields.
            'html_before_fields' => '',

            //html_after_fields
            //(String) Extra HTML to add after the fields.
            'html_after_fields' => '',
            ///<div class="recaptcha-text"><small><p class="text-pe-white font-14">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" >Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</p></small></div>
            //submit_value
            //(String) The text displayed on the submit button.
            'submit_value' => __("Submit Enquiry >", 'drh-fabrications'),

            //updated_message
            //(String) The message displayed above the form after being redirected. Can also be set to false for no message.
            'updated_message' => __("Thank you for your enquiry, we will respond to your request shortly.", 'acf'),

            //label_placement
            //(String) Determines where field labels are places in relation to fields. Defaults to ‘top’. Choices of ‘top’ (above fields) or ‘left’ (beside fields).

            'label_placement' => 'top',

            //instruction_placement
            //(String) Determines where field instructions are placed in relation to fields. Defaults to ‘label’. Choice of ‘label’ (below labels) or ‘field’ (below fields).
            'instruction_placement' => 'field',

            //field_el
            //(String) Determines element used to wrap a field. Defaults to ‘div’. Choices of ‘div’, ‘tr’, ‘td’, ‘ul’, ‘ol’, ‘dl’.
            'field_el' => 'div',

            //uploader
            //(String) Whether to use the WP uploader or a basic input for image and file fields. Defaults to ‘wp’ . Choices of ‘wp’ or ‘basic’.
            'uploader' => 'wp',

            //honeypot
            //(Boolean) Whether to include a hidden input field to capture non-human form submission. Defaults to true.
            'honeypot' => true,

            //html_updated_message
            //(String) The HTML used to render the updated message.
            'html_updated_message' => '<div class="toast-container position-relative p-3 "><div id="msg-send-success" class="toast show position-fixed bottom-0 start-0 ms-4 mb-4" data-bs-autohide="true" role="success" aria-live="assertive" aria-atomic="true" data-bs-animation="true"><div class="toast-header"><div style="height:10px;width:10px;background: green; border-radius: 20px; margin-right: 10px;"></div><strong class="me-auto text-blue">Your enquiry has been sent successfully</strong><button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button></div><div class="toast-body"><p>%s</p></div></div></div>',

            //html_submit_button
            //(String) The HTML used to render the submit button.
            'html_submit_button' => '<input type="submit" class="btn btn-yellow fw-bold fs-16 w-100 py-2" data-action="submit" value="%s" />',

            //html_submit_spinner
            //(String) The HTML used to render the submit button loading spinner.
            'html_submit_spinner' => '<span class="acf-spinner"></span>',

            //kses
            //(Boolean) Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to true.
            'kses' => true,
        ];

        self::$formSettings = $formSettings;

    }

    /**
     * Sets the WP mail funtion to allow HTML output.
     *
     */
    function setHTMLContentType()
    {
        return 'text/html';
    }


    /**
     * Handles the form submission
     *
     */
    function handleSignUp($post_id)
    {

        // bail early if not a contact_form post

        if ( get_post_type($post_id) !== 'contact-post-main') {
            return;
        }

        // vars

        $post = get_post($post_id);

        // get custom fields (field group exists for content_form)
        $fields = get_field('contact', $post_id);

        $name = $fields['contact_name'];
        $email = $fields['email_address'];
        $company = $fields['contact_company_name'];
        $telephone = $fields['contact_telephone'];
        $natureOfEnquiry = $fields['nature_of_enquiry'];
        $msg = $fields['message'];

        $title = 'Query from ' . $name . ' - ' . $company;

        $args = [
            'ID' => $post_id,
            'post_title' => $title,
        ];

        wp_update_post($args);

        $logoUrl = get_site_icon_url();
        $siteName = get_bloginfo('name');

//        $body = '<body style="margin: 0; padding: 0; font-family: Helvetica, sans-serif; font-size: 13pt; line-height: 150%;"> <table border="0" cellpadding="0" cellspacing="0" width="100%"> <tr> <td> <table align="center" border="0" cellpadding="0" cellspacing="0" width="600"> <tr> <td style="text-align: center; padding: 20px 0px 0px 0px;"> <img width="150" src="' . $logoUrl . '" alt=""/> </td></tr></table> </td></tr><tr> <td> <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 0px; background-color: #ffffff;"> <tr> <td style="padding: 20px 0 30px 0;"> <table align="center" border="0" cellpadding="0" cellspacing="0" width="520" style="border-collapse: collapse;"> <tr> <td style="font-size: 14px; padding: 0px 0px 10px 0px;"> <p style="text-align: center; margin-bottom: 25px;"> <b style="font-size: 24px;">Hi there! </b> </p><p style="text-align: center;">Someone has just placed an enquiry via the ' . get_bloginfo('name') . ' contact form! Details below.</p></td></tr><tr> <td style="text-align: center;"> <table width="400" border="0" style="border-collapse: collapse; margin: auto;"> <tr> <th style="text-align: right; padding: 10px;">Name:</th> <td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">' . $name . '</td></tr><tr> <th style="text-align: right; padding: 10px;">School Name:</th> <td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">' . $school . '</td></tr><tr> <th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">Email:</th> <td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">' . $email . '</td></tr><tr> <th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">Nature of enquiry:</th> <td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">' . $natureOfEnquiry . '</td></tr><tr> <th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">Message:</th> <td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">' . $msg . '</td></tr></table> </td></tr></table> </td></tr></table> </td></tr></table></body>';
        $html = '';
        $html.='<body style="margin: 0; padding: 0; font-family: Helvetica, sans-serif; font-size: 13pt; line-height: 150%;">';
        $html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">';
        $html.='<tr>';
        $html.='<td>';
        $html.='<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">';
        $html.='<tr>';
        $html.='<td style="text-align: center; padding: 20px 0px 0px 0px;"><img width="150" src="' .$logoUrl .'" alt=""/></td>';;
        $html.='</tr>';
        $html.='</table>';
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td>';
        $html.='<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 0px; background-color: #ffffff;">';
        $html.='<tr>';
        $html.='<td style="padding: 20px 0 30px 0;">';
        $html.='<table align="center" border="0" cellpadding="0" cellspacing="0" width="520" style="border-collapse: collapse;">';
        $html.='<tr>';
        $html.='<td style="font-size: 14px; padding: 0px 0px 10px 0px;"><p style="text-align: center; margin-bottom: 25px;"><b style="font-size: 24px;">Hi there! </b></p>';
        $html.='<p style="text-align: center;">Someone has just placed an enquiry via the' . $siteName . ' contact form! Details below.</p></td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td style="text-align: center;">';
        $html.='<table width="400" border="0" style="border-collapse: collapse; margin: auto;">';
        $html.='<tr>';
        $html.='<th style="text-align: right; padding: 10px;">Name:</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.=$name;
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: right; padding: 10px;">Company Name:</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.=$company;
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.='Email:';
        $html.='</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.=$email;
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.='Contact Telephone:';
        $html.='</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.=$telephone;
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.='Nature of enquiry:';
        $html.='</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.= $natureOfEnquiry;
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<th style="text-align: right; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.='Message:';
        $html.='</th>';
        $html.='<td style="text-align: left; border-bottom: 1px solid #a7dbd8; padding: 10px;">';
        $html.=$msg;
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</body>';

        // email data
        $to = 'enquiries@drhfabrications.co.uk';
        $from = 'webmaster@drhfabrications.co.uk';
        $cc = 'ian@industrialstandards.co.uk';
        $headers = 'from: Website Enquiry  <' . $from . '>' . "\r\n" . 'cc:' . $cc;
        $subject = $title;

        // send email
        wp_mail($to, $subject, $html, $headers);


    }

    function addAcfInputClasses($field)
    {

        if('group_drh_contact_form_group' == $field['parent'])
        {
            $field['class'] = 'form-control';
        }

        if('select' == $field['type'])
        {
            $field['class'] = 'form-control form-select';
        }
        return $field;
    }

}


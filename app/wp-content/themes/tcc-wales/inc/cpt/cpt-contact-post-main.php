<?php
/**
 * Contacts Custom Post Types
 *
 * @author I N D U S T R I A L   S T A N D A R D S
 * @package WordPress
 * @subpackage Xlite Twenty Twenty
 */

/**
 * Register the "Sign Ups" custom post type
 * See http://codex.wordpress.org/Function_Reference/register_post_type
 */

function cpt_contact_post_main() {

  $singular = 'Contact post';
  $plural   = 'Contact posts';

  $labels = [
    'name'                => _x($plural, 'drh-fabrications'),
    'singular-name'       => _x($singular, 'drh-fabrications'),
    'menu_name'           => _x($plural, 'drh-fabrications'),
    'name_admin_bar'      => _x($singular, 'drh-fabrications'),
    'add_new'             => _x('Add New', $singular, 'drh-fabrications'),
    'add_new_item'        => __('Add New ' . $singular, 'drh-fabrications'),
    'new_item'            => __('New ' . $singular, 'drh-fabrications'),
    'edit_item'           => __('Edit ' . $singular, 'drh-fabrications'),
    'view_item'           => __('View ' . $singular, 'drh-fabrications'),
    'all_item'            => __('All ' . $plural, 'drh-fabrications'),
    'search_items'        => __('Search ' . $singular, 'drh-fabrications'),
    'parent_item_colon'   => __('Parent ' .  $singular . ':', 'drh-fabrications'),
    'not_found'           => __('No ' . $plural . ' found', 'drh-fabrications'),
    'not_found_in_trash'  => __( 'No ' . $plural . ' found in Trash.', 'drh-fabrications' ),
  ];

  $args = [
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => ['slug' => 'contact-posts', 'with_front' => false ],
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 100,
        'show_in_rest'       => true,
        'rest_base'          => 'contact-post-main',
        'menu_icon'          => 'dashicons-email-alt2',
        'supports'           => ['title'],
    ];

    register_post_type( 'contact-post-main', $args );
}
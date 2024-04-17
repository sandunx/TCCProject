<?php
/**
 * The Custom Post Type for the Achievement Post type
 *
 * @author I N D U S T R I A L  S T A N D A R D S 
 * @package WordPress
 * @subpackage TCC Wales
 *
 * See http://codex.wordpress.org/Function_Reference/register_post_type
*/

function cpt_achievement() {

$singular = 'Achievement';
$plural   = 'Achievements';

  $args = [
    'labels' => [
      'name'                => __( $plural , 'tcc-wales' ),
      'singular_name'       => __( $singular, 'tcc-wales' ),
      'all_items'           => __( 'All ' . $plural, 'tcc-wales' ),
      'add_new'             => __( 'Add New', 'tcc-wales' ),
      'add_new_item'        => __( 'Add New ' . $singular, 'tcc-wales' ),
      'edit'                => __( 'Edit', 'tcc-wales' ),
      'edit_item'           => __( 'Edit ' . $singular, 'tcc-wales' ),
      'new_item'            => __( 'Add New ' . $singular, 'tcc-wales' ),
      'view_item'           => __( 'View ' . $singular, 'tcc-wales' ),
      'search_items'        => __( 'Search ' . $plural, 'tcc-wales' ),
      'not_found'           => __( 'No '. $plural .' found', 'tcc-wales' ),
      'not_found_in_trash'  => __( 'No '. $plural .' found in trash', 'tcc-wales' ),
      'parent_item_colon'   => '|',
    ],
    'description'           => __( 'This is the ' . $plural .' post type', 'tcc-wales' ),
    'public'                => true,
    'publicly_queryable'    => true,
    'show_in_menu'          => true,
    'show_in_nav_menus'     => true,
    'show_in_rest'          => true,
    'has_archive'           => 'page-achievements.php',
    'exclude_from_search'   => false,
    'show_ui'               => true,
    'query_var'             => true,
    'menu_position'         => 10,
    'menu_icon'             => 'dashicons-awards',
    'capability_type'       => 'post',
    'rewrite'               => [ 'slug' => 'achievement', 'with_front' => true ],
    'hierarchical'          => false,
    'supports'              => [ 'title', 'editor', 'thumbnail', 'excerpt', 'post_tags','custom-fields'],
  ];

  register_post_type( 'achievement', $args);

}




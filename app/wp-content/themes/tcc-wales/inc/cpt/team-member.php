<?php
 /**
 * The Custom Post Type for the Team Member Post type
 *
 * @author I N D U S T R I A L  S T A N D A R D S 
 * @package WordPress
 * @subpackage TCC Wales
 *
 * See http://codex.wordpress.org/Function_Reference/register_post_type
*/

function cpt_team_member() {

$singular = 'Team Member';
$plural   = 'Team Members';

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
    'description'           => __( 'The TCC staff team comprises two community organisers, a youth organiser, an operations lead, a communications officer, and an income generation officer. </br>The team works collaboratively with a distributed leadership structure.', 'tcc-wales' ),
    'public'                => true,
    'publicly_queryable'    => true,
    'show_in_menu'          => true,
    'show_in_nav_menus'     => true,
    'show_in_rest'          => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'show_ui'               => true,
    'query_var'             => true,
    'menu_position'         => 10,
    'menu_icon'             => 'dashicons-id',
    'capability_type'       => 'post',
    'rewrite'               => [ 'slug' => 'team-members', 'with_front' => true ],
    'hierarchical'          => false,
    'supports'              => [ 'title', 'editor', 'thumbnail', 'excerpt', 'post_tags','custom-fields'],
  ];

  register_post_type( 'team', $args);

}



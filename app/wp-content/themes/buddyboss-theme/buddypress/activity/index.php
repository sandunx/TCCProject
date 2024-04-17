<?php
/**
 * BuddyBoss Activity templates
 *
 * @since BuddyPress 2.3.0
 * @version 3.0.0
 */

bp_nouveau_before_activity_directory_content();

if ( is_user_logged_in() ) {
	bp_get_template_part( 'activity/post-form' );
}

bp_nouveau_template_notices();

if ( ! bp_nouveau_is_object_nav_in_sidebar() ) {
	echo '<div class="flex actvity-head-bar">';
		bp_get_template_part( 'common/nav/directory-nav' );
		bp_get_template_part( 'common/search-and-filters-bar' );
	echo '</div>';
}
?>

<div class="screen-content">
	<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

	<div id="activity-stream" class="activity" data-bp-list="activity">
		<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>
	</div><!-- .activity -->

	<?php bp_nouveau_after_activity_directory_content(); ?>
</div><!-- // .screen-content -->


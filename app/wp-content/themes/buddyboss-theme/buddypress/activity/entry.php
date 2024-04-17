<?php
/**
 * BuddyBoss - Activity Feed (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @since BuddyPress 3.0.0
 * @version 3.0.0
 */

bp_nouveau_activity_hook( 'before', 'entry' );

$activity_id = bp_get_activity_id();
if ( function_exists( 'bb_activity_get_metadata' ) ) {
	$activity_metas    = bb_activity_get_metadata( $activity_id );
	$link_preview_data = ! empty( $activity_metas['_link_preview_data'][0] ) ? maybe_unserialize( $activity_metas['_link_preview_data'][0] ) : array();
	$link_embed        = $activity_metas['_link_embed'][0] ?? '';
} else {
	$link_preview_data = bp_activity_get_meta( $activity_id, '_link_preview_data', true );
	$link_embed        = bp_activity_get_meta( $activity_id, '_link_embed', true );
}

$link_preview_string = '';
$link_url            = '';

if ( ! empty( $link_preview_data ) && count( $link_preview_data ) ) {
	$link_preview_string = wp_json_encode( $link_preview_data );
	$link_url            = ! empty( $link_preview_data['url'] ) ? $link_preview_data['url'] : '';
}

if ( ! empty( $link_embed ) ) {
	$link_url = $link_embed;
}
?>

<li
	class="<?php bp_activity_css_class(); ?>"
	id="activity-<?php echo esc_attr( $activity_id ); ?>"
	data-bp-activity-id="<?php echo esc_attr( $activity_id ); ?>"
	data-bp-timestamp="<?php bp_nouveau_activity_timestamp(); ?>"
	data-bp-activity="<?php ( function_exists( 'bp_nouveau_edit_activity_data' ) ) ? bp_nouveau_edit_activity_data() : ''; ?>"
	data-link-preview='<?php echo $link_preview_string; ?>'
	data-link-url='<?php echo $link_url; ?>'>

	<?php
	if ( function_exists( 'bb_nouveau_activity_entry_bubble_buttons' ) ) {
		bb_nouveau_activity_entry_bubble_buttons();
	}
	?>

	<div class="bb-pin-action">
		<span class="bb-pin-action_button" data-balloon-pos="up" data-balloon="<?php esc_attr_e( 'Pinned Post', 'buddyboss-theme' ); ?>">
			<i class="bb-icon-f bb-icon-thumbtack"></i>
		</span>
	</div>

	<div class="bp-activity-head">
		<div class="activity-avatar item-avatar">
			<a href="<?php bp_activity_user_link(); ?>"><?php bp_activity_avatar( array( 'type' => 'full' ) ); ?></a>
		</div>

		<div class="activity-header">
			<?php bp_activity_action(); ?>
			<p class="activity-date">
				<a href="<?php echo esc_url( bp_activity_get_permalink( $activity_id ) ); ?>">
					<?php
					$activity_date_recorded = bp_get_activity_date_recorded();
					printf(
						'<span class="time-since" data-livestamp="%1$s">%2$s</span>',
						bp_core_get_iso8601_date( $activity_date_recorded ),
						bp_core_time_since( $activity_date_recorded )
					);
					?>
				</a>
				<?php
				if ( function_exists( 'bp_nouveau_activity_is_edited' ) ) {
					bp_nouveau_activity_is_edited();
				}
				?>
			</p>
			<?php
			if ( function_exists( 'bp_nouveau_activity_privacy' ) ) {
				bp_nouveau_activity_privacy();
			}
			?>

		</div>
	</div>

	<?php bp_nouveau_activity_hook( 'before', 'activity_content' ); ?>

	<div class="activity-content <?php ( function_exists( 'bp_activity_entry_css_class' ) ) ? bp_activity_entry_css_class() : ''; ?>">
		<?php if ( bp_nouveau_activity_has_content() ) : ?>
			<div class="activity-inner <?php echo ( function_exists( 'bp_activity_has_content' ) && empty( bp_activity_has_content() ) ) ? esc_attr( 'bb-empty-content' ) : esc_attr( '' ); ?>">
				<?php
					bp_nouveau_activity_content();

				if ( function_exists( 'bb_nouveau_activity_inner_buttons' ) ) {
					bb_nouveau_activity_inner_buttons();
				}
				?>
			</div>
			<?php
		endif;

		if ( function_exists( 'bp_nouveau_activity_state' ) ) {
			bp_nouveau_activity_state();
		}
		?>
	</div>

	<?php
	bp_nouveau_activity_hook( 'after', 'activity_content' );
	bp_nouveau_activity_entry_buttons();
	bp_nouveau_activity_hook( 'before', 'entry_comments' );

	if ( bp_activity_get_comment_count() || ( is_user_logged_in() && ( bp_activity_can_comment() || bp_is_single_activity() ) ) ) { ?>
		<div class="activity-comments">
			<?php
			bp_activity_comments();
			bp_nouveau_activity_comment_form();
			?>
		</div>
		<?php
	}

	bp_nouveau_activity_hook( 'after', 'entry_comments' );
	?>
</li>

<?php
bp_nouveau_activity_hook( 'after', 'entry' );

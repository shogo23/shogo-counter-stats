<?php

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*	Main class for Admin Dashboard Widget
*	@version 1.0.0
*/
class Shogo_Admin_Dashboard {

	/*
	*	Initiate shogo's statistics dashboard widget.
	*	@version 1.0.0
	*/
	public function display() {
		wp_add_dashboard_widget( 'shogo-counter-stats', __('Visitor\'s Statistics'), array( 'Shogo_Admin_Dashboard', 'display_contents' ) );
	}

	public function display_contents() {
		global $wpdb;
		$table = $wpdb->prefix.'shogo_counter_stats';
		$all_hits = $wpdb->get_var("SELECT COUNT(*) FROM $table");
		$unique_hits = $wpdb->get_results("SELECT DISTINCT `ip` FROM $table GROUP BY `ip` ORDER BY COUNT(*) DESC");
		$unique_hits = count($unique_hits);

		?>

		<div class="shogo-counter-stats-wp-dashboard-container">
			<div class="shogo-countr-stats-wp-dashboard-left">
				<div class="shogo-counterstats-wp-dashboard-left-header">Visitor's Hits</div>
				<div class="shogo-counterstats-wp-dashboard-left-contents">
					<?php echo $all_hits; ?>
				</div>
			</div>
			<div class="shogo-counter-stats-wp-dashboard-right">
				<div class="shogo-counter-stats-wp-dashboard-right-header">Unique Hits</div>
				<div class="shogo-counter-stats-wp-dashboard-right-contents">
					<?php echo $unique_hits; ?>
				</div>
			</div>
			<div class="shogo-clear"></div>
		</div>
		<div class="shogo-counter-stats-wp-dashboard-link-to-counterstats"><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=shogo-counter-stats-options">See More</a></div>

		<?php
	}
}
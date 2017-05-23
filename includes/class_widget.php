<?php

//Exit if accesssed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*	Main class of WP_Widget
*	@version 1.0.0
*/
class Shogo_Widget extends WP_Widget {

	/*
	*	Widget Setup
	*	@version 1.0.0
	*/
	public function __construct() {

		//Setup Class Name and Description.
		$opt = array(
			'classname' => 'Shogo_Widget',
			'description' => 'Dislpay visitor\'s statistics of your website or blog.'
		);

		//Initialize Plugin's Widget.
		parent::__construct( 'shogo-couner-stats', 'Shogo\'s Counter Stats', $opt );
	}

	/*
	*	Widget form setup
	*	@version 1.0.0
	*/
	public function form( $instance ) {

		//If has Instance.
		if ( $instance ) {

			//Append Title.
			$title = esc_attr( $instance['title'] );

			//Append Results.
			$results = esc_attr( $instance['results'] );
		} else {

			//Append Title.
			$title = '';

			//Append Results.
			$results = 10;
		}

		?>

		<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br />
		<input type="text" class="widefat widget_input_title shogo-widget-title" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" /><br />
		<label for="<?php echo $this->get_field_id('title'); ?>">Max Countries Display:</label><br />
		<input type="number" class="widefat widget_input_title shogo-widget-results" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('results'); ?>" value="<?php echo $results; ?>" />
		<?php
	}

	/*
	*	Widget Front End Layout
	*	@version 1.0.0
	*/
	public function widget( $args, $instance ) {

		//Extract Args.
		extract( $args );

		//Header Title of the Widget.
		$title = apply_filters( 'Widget_title', $instance['title'] );

		//Number of Results Query from the database.
		$results = $instance['results'];

		//Implement Wordpress $wpdb
		global $wpdb;

		//WP Prefix and Table Name.
		$counter_table = $wpdb->prefix.'shogo_counter_stats';

		//Get Countries Results.
		$hits = $wpdb->get_results( "SELECT DISTINCT `country`, `country_code`, `ip` FROM $counter_table GROUP BY `country` ORDER BY COUNT(*) DESC LIMIT 0, $results" );
		
		//Get Total Count.
		$overall_hits = $wpdb->get_var( "SELECT COUNT(*) FROM $counter_table" );

		ob_start();
		echo $before_widget;

		//if title is not null.
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		?>

		<ul>
			<?php $i = 0; ?>
			<?php foreach ($hits as $hit): ?>
				<?php $i++; ?>
				<li>
					<?php echo $i; ?>. <?php if ( $hit->country !== 'Unknown' ): ?><img title="<?php echo $hit->country; ?>" style="position: relative; margin-top: -3px;" alt="flag" src="<?php echo plugins_url().'/shogo-counter-stats/flags/'.$hit->country_code; ?>.png"  /><?php else: ?>&nbsp;<?php endif; ?>&nbsp;
					<?php echo $hit->country_code; ?>&nbsp;
					<?php echo shogo_getPercent($hit->country_code); ?>%
				</li>
			<?php endforeach; ?>
			<li>Total Hits: <?php echo $overall_hits; ?>.</li>
		</ul>
		<div class="clear"></div>

		<?php
		echo $after_widget;
		echo ob_get_clean();
	}

	/*
	*	Update form for any changes.
	*	@version 1.0.0
	*/
	public function update($new_instance, $old_instance) {

		//Get Old Intance.
		$instance = $old_instance;

		//Replace Title from the $instance if there is a new.
		$instance['title'] = strip_tags($new_instance['title']);

		//Replace Results from the $instance if there is a new.
		$instance['results'] = strip_tags($new_instance['results']);
		
		return $instance;
	}
}
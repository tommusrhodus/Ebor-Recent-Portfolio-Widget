<?php

/*
Plugin Name: Ebor Recent Portfolio Widget
Plugin URI: http://www.madeinebor.com
Description: Adds a recent portfolio widget that pulls in 4 recent portfolio post type posts into a small image grid.
Version: 1.0
Author: TommusRhodus
Author URI: http://www.madeinebor.com
*/	

/*-----------------------------------------------------------------------------------*/
/*	ENQUEUE STYLING
/*-----------------------------------------------------------------------------------*/
function ebor_recent_portfolio_style() {
	wp_enqueue_style( 'ebor-recent-portfolio-styles', plugins_url( '/ebor-portfolio-widget.css' , __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'ebor_recent_portfolio_style', 90);


/*-----------------------------------------------------------------------------------*/
/*	RECENT PORTFOLIO WIDGET
/*-----------------------------------------------------------------------------------*/
add_action('widgets_init', 'ebor_latest_portfolio_load_widget');
function ebor_latest_portfolio_load_widget(){
	register_widget('ebor_latest_portfolio_widget');
}

class ebor_latest_portfolio_widget extends WP_Widget {
	
	function ebor_latest_portfolio_widget()
	{
		$widget_ops = array('classname' => 'ebor-portfolio-widget', 'description' => '');

		$control_ops = array('id_base' => 'ebor_portfolio-widget');

		$this->WP_Widget('ebor_portfolio-widget', 'Ebor: Recent Portfolio', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if($title) {
			echo  $before_title.$title.$after_title;
		} ?>
		
		  <?php $popular = new WP_Query('post_type=portfolio&posts_per_page=4'); if( $popular->have_posts() ) : while ( $popular->have_posts() ): $popular->the_post(); ?>
		
		  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('portfolio-index'); ?></a>
		  
		  <?php endwhile; endif; wp_reset_query(); ?> 
		  <div class="clear break half"></div><!--clear images-->
		
		<?php echo $after_widget;
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form($instance){
		$defaults = array('title' => 'Recent Portfolio');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
	<?php
	}
}
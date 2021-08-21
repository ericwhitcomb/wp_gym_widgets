<?php
/*
 * Plugin Name: WP Gym - Widgets
 * Plugin URI: 
 * Description: Adds a Custom Widget in the WordPress Panel
 * Version: 1.0.0
 * Author: Eric Whitcomb
 * Author URI: http://www.ericwhitcomb.com/
 * Text Domain: wp_gym
 */ 

 if (!defined('ABSPATH')) die();

 // Create Custom Widget extending WP_Widget class
class WP_Gym_Classes_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wp_gym_classes', // Base ID
			esc_html__( 'WP Gym - Classes List', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Displays different classes in the widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; ?>

		<h3 class="text-primary text-center sidebar-class-list-header">Our Classes</h3>

		<ul class="sidebar-class-list">
			<?php
				$args = array(
					'post_type' => 'wp_gym_classes',
					'posts_per_page' => 3,
					'orderby' => 'rand'
				);

				// Use WP_Query and append the results into $classes
				$classes = new WP_Query($args);
				while ($classes->have_posts()): $classes->the_post();
			?>

			<li class="sidebar-class-item">
				<div class="sidebar-class-image">
					<?php the_post_thumbnail('thumbnail'); ?>
				</div>

				<div class="sidebar-class-content">
					<a href="<?php the_permalink(); ?>">
						<h4><?php the_title(); ?></h4>
					</a>

					<?php 
            $class_days = get_field('class_days');
            $start_time = get_field('start_time');
            $end_time = get_field('end_time');
          ?>
          <p><?php echo $class_days . '<br>' . $start_time . ' to ' . $end_time;?></p>
				</div>
			</li>

			<?php endwhile; wp_reset_postdata(); ?>
		</ul>
		
		<?php echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

}

// register WP_Gym_Classes_Widget
function register_wp_gym_classes_widget() {
    register_widget( 'WP_Gym_Classes_Widget' );
}
add_action( 'widgets_init', 'register_wp_gym_classes_widget' );
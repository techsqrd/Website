<?php
/**
 * Custom Video Widget
 *
 * Learn more: http://codex.wordpress.org/Widgets_API
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'STATUS_Video_Widget' ) ) {
	class STATUS_Video_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				'st_video',
				esc_html__( 'Video', 'status' )
			);
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 * @since 1.0.0
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		function widget( $args, $instance ) {

			// Extract args
			extract( $args );

			$title       = isset( $instance['title'] ) ? $instance['title'] : '';
			$title       = apply_filters( 'widget_title', $title );
			$video_url   = isset ( $instance['video_url'] ) ? $instance['video_url'] : '';
			$description = isset ( $instance['video_description'] ) ? $instance['video_description'] : '';
			
			// Before widget WP hook
			echo st_sanitize( $before_widget, 'html' );

				// Show widget title
				if ( $title ) {
					echo st_sanitize( $before_title . $title . $after_title, 'html' );
				}
				
				// Define video height and width
				$video_args = array(
					'width' => 270
				);
				
				// Show video
				if ( $video_url )  {
					echo '<div class="st-responsive-embed st-clr">' . wp_oembed_get( $video_url, $video_args ) . '</div>';
				} else { 
					esc_html_e( 'You forgot to enter a video URL.', 'status' );
				}
				
				// Show video description if field isn't empty
				if ( $description ) {
					echo '<div class="st-video-widget-description">'. st_sanitize( $description, 'html' ) .'</div>';
				}

			// After widget WP hook
			echo st_sanitize( $after_widget, 'html' );
		}
		
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 * @since 1.0.0
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']             = strip_tags( $new_instance['title'] );
			$instance['video_url']         = esc_url( $new_instance['video_url'] );
			$instance['video_description'] = esc_html( $new_instance['video_description'] );
			return $instance;
		}
		

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 * @since 1.0.0
		 *
		 * @param array $instance Previously saved values from database.
		 */
		function form( $instance ) {

			// Parse args
			$instance = wp_parse_args( (array) $instance, array(
				'title'             => 'Video',
				'id'                => '',
				'video_url'         => '',
				'video_description' => '',
			) );

			// Extract args
			extract( $instance ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'status' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'video_url' ) ); ?>">
				<?php esc_html_e( 'Video URL ', 'status' ); ?></label>
				<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'video_url' ) ); ?>" type="text" value="<?php echo esc_attr( $video_url ); ?>" />
				<span style="display:block;padding:5px 0" class="description"><?php esc_html_e( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'status' ); ?> <a href="//codex.wordpress.org/Embeds" target="_blank"><?php esc_html_e( 'Learn More', 'status' ); ?></a></span>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'video_description' ) ); ?>">
				<?php esc_html_e( 'Description', 'status' ); ?></label>
				<textarea rows="5" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'video_description' ) ); ?>" type="text"><?php echo esc_html( $instance['video_description'] ); ?></textarea>
			</p>
			
		<?php }

	}
}

// Register the STATUS_Tabs_Widget custom widget
if ( ! function_exists( 'register_st_video_widget' ) ) {
	function register_st_video_widget() {
		register_widget( 'STATUS_Video_Widget' );
	}
}
add_action( 'widgets_init', 'register_st_video_widget' );
<?php
/**
 * Plugin Name: JCI Navigation Widgets
 * Description: Creates widgets to be used with the Mega Menu navigation and Site Options 
 * Version: 1.0
 * Author: Journal Communications, Inc.
 * Text Domain: jci-navigation-widgets
 */

function utm_user_scripts() {
	$plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'mm-nav',  $plugin_url . "/mm-nav.css");
}
add_action( 'wp_enqueue_scripts', 'utm_user_scripts' );

/******************************************/
// Mega Menu Find Your Place Post Widget
/******************************************/
class jci_mm_fp_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'jci_mm_fp_widget', 
  
		// Widget name will appear in UI
		__('JCI Mega Menu Find Your Place', 'jci_mm_fp_widget_domain'), 
  
		// Widget description
		array( 'description' => __( 'Displays article in Mega Menu from Site Options', 'jci_mm_fp_widget_domain' ), ) 
		);
	}
	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$article = get_field('find_your_place', 'option');
		if( $article ) {
			echo '<div class="mm-article-bgd" style="margin-top:12px;">';
			echo '<a href="' . get_permalink( $article['mm_find_place_article'] ) . '">';
				 echo '<div class="mm-featured-image" style="background: url(' . get_the_post_thumbnail_url( $article['mm_find_place_article'], "rel_article" ) . ') center no-repeat; min-height:186px"></div>';
				 echo '<div class="mm-featured-title">' . get_the_title($article['mm_find_place_article']) . '</div>';
			echo '</a>';
			echo '</div>';
		}
		echo $args['after_widget'];
	}    
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jci_mm_fp_widget_domain' );
		}
		// Widget admin form
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} 
// Register / load the widget
function jci_mm_fp_load_widget() {
    register_widget( 'jci_mm_fp_widget' );
}
add_action( 'widgets_init', 'jci_mm_fp_load_widget' );

/******************************************/
// Mega Menu Top 100 Widget
/******************************************/
class jci_mm_top100_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'jci_mm_top100_widget', 
  
		// Widget name will appear in UI
		__('JCI Mega Menu Top 100', 'jci_mm_top100_widget_domain'), 
  
		// Widget description
		array( 'description' => __( 'Displays Top 100 in Mega Menu from Site Options', 'jci_mm_top100_widget_domain' ), ) 
		);
	}
	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$top100 = get_field('nav_best_places', 'option');
		if( $top100 ) {
			echo '<div class="mm-article-bgd" style="margin-top:13px;">';
			echo '<a href="' . get_permalink( $top100['top_100'] ) . '">';
				 echo '<div class="mm-featured-image" style="background: url(' . get_the_post_thumbnail_url( $top100['top_100'], "rank_card" ) . ') center no-repeat; min-height:300px; background-size:cover;"></div>';
				 echo '<div class="mm-featured-title">' . get_the_title($top100['top_100']) . '</div>';
			echo '</a>';
			echo '</div>';
			echo '<button class="white"><a href="/best-places/">PREVIOUS YEARS</a></button>';
			echo '<button class="green"><a href="' . get_permalink( $top100['top_100'] ) . '">VIEW THE LIST</a></button>';

		}
		echo $args['after_widget'];
	}    
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jci_mm_top100_widget_domain' );
		}
		// Widget admin form
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} 
// Register / load the widget
function jci_mm_top100_load_widget() {
    register_widget( 'jci_mm_top100_widget' );
}
add_action( 'widgets_init', 'jci_mm_top100_load_widget' );



/******************************************/
// Mega Menu Best Places Widget
/******************************************/
class jci_mm_bp_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'jci_mm_bp_widget', 
  
		// Widget name will appear in UI
		__('JCI Mega Menu Best Places', 'jci_mm_bp_widget_domain'), 
  
		// Widget description
		array( 'description' => __( 'Displays article in Mega Menu from Site Options', 'jci_mm_bp_widget_domain' ), ) 
		);
	}
	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$bestplaces = get_field('nav_best_places', 'option');
		if( $bestplaces ) {
			echo '<div class="small-articles mm">';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_1'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_1'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_1']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_2'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_2'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_2']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_3'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_3'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_3']) . '</div>';
						echo '</div>';
					echo '</a>';
			echo '</div>';
			echo '<div class="small-articles mm col2">';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_4'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_4'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_4']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_5'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_5'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_5']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $bestplaces['nav_best_place_6'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $bestplaces['nav_best_place_6'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($bestplaces['nav_best_place_6']) . '</div>';
						echo '</div>';
					echo '</a>';
			echo '</div>';
			echo '<div style="width:100%; text-align:center;"><button class="green"><a href="/best-places/">VIEW ALL BEST PLACES LISTS</a></button></div>';
		}
		echo $args['after_widget'];
	}    
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jci_mm_bp_widget_domain' );
		}
		// Widget admin form
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} 
// Register / load the widget
function jci_mm_bp_load_widget() {
    register_widget( 'jci_mm_bp_widget' );
}
add_action( 'widgets_init', 'jci_mm_bp_load_widget' );

/******************************************/
// Mega Menu Topics Widget
/******************************************/
class jci_mm_topics_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'jci_mm_topics_widget', 
  
		// Widget name will appear in UI
		__('JCI Mega Menu Topics', 'jci_mm_topics_widget_domain'), 
  
		// Widget description
		array( 'description' => __( 'Displays topics in Mega Menu from Site Options', 'jci_mm_topics_widget_domain' ), ) 
		);
	}
	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$topics = get_field('topics', 'option');
		if( $topics ) {
			echo '<div class="small-articles mm">';
					echo '<a href="' . get_permalink( $topics['topics']['topic_1'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_1'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_1']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_2'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_2'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_2']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_3'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_3'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_3']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_4'] ) . '" style="padding-bottom:0;">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_4'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_4']) . '</div>';
						echo '</div>';
					echo '</a>';
			echo '</div>';
			echo '<div class="small-articles mm col2">';
					echo '<a href="' . get_permalink( $topics['topics']['topic_5'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_5'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_5']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_6'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_6'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_6']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_7'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_7'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_7']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['topics']['topic_8'] ) . '" style="padding-bottom:0;">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['topics']['topic_8'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['topics']['topic_8']) . '</div>';
						echo '</div>';
					echo '</a>';
			echo '</div>';
		}
		echo $args['after_widget'];
	}    
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jci_mm_topics_widget_domain' );
		}
		// Widget admin form
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} 
// Register / load the widget
function jci_mm_topics_load_widget() {
    register_widget( 'jci_mm_topics_widget' );
}
add_action( 'widgets_init', 'jci_mm_topics_load_widget' );


/******************************************/
// Mega Menu Series Widget
/******************************************/
class jci_mm_series_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'jci_mm_series_widget', 
  
		// Widget name will appear in UI
		__('JCI Mega Menu Series', 'jci_mm_series_widget_domain'), 
  
		// Widget description
		array( 'description' => __( 'Displays series in Mega Menu from Site Options', 'jci_mm_series_widget_domain' ), ) 
		);
	}
	// Widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		$topics = get_field('topics', 'option');
		if( $topics ) {
			echo '<div class="mm-article-bgd" style="margin-top:12px;">';
			echo '<a href="' . get_permalink( $topics['series']['series_1'] ) . '">';
				 echo '<div class="mm-featured-image" style="background: url(' . get_the_post_thumbnail_url( $topics['series']['series_1'], "rel_article" ) . ') center no-repeat; min-height:186px;  background-size:cover;"></div>';
				 echo '<div class="mm-featured-title">' . get_the_title($topics['series']['series_1']) . '</div>';
			echo '</a>';
			echo '</div>';
			echo '<div class="small-articles mm series">';
					echo '<a href="' . get_permalink( $topics['series']['series_2'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['series']['series_2'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['series']['series_2']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['series']['series_3'] ) . '">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['series']['series_3'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['series']['series_3']) . '</div>';
						echo '</div>';
					echo '</a>';
					echo '<a href="' . get_permalink( $topics['series']['series_4'] ) . '" style="padding-bottom:0;">';
						echo '<div class="article-card">';
							echo '<div class="article-img" style="background: url(' . get_the_post_thumbnail_url( $topics['series']['series_4'], "medium" ) . ') center no-repeat; background-size:cover;"></div>';
							echo '<div class="article-text">' . get_the_title($topics['series']['series_4']) . '</div>';
						echo '</div>';
					echo '</a>';
			echo '</div>';
		}
		echo $args['after_widget'];
	}    
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'jci_mm_series_widget_domain' );
		}
		// Widget admin form
		?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} 
// Register / load the widget
function jci_mm_series_load_widget() {
    register_widget( 'jci_mm_series_widget' );
}
add_action( 'widgets_init', 'jci_mm_series_load_widget' );
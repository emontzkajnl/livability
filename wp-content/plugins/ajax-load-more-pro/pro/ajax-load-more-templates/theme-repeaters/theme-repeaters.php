<?php
/**
 * Ajax Load More: Theme Repeaters
 *
 * @package ALMTemplates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ALMThemeRepeaters' ) ) :

	/**
	 * Initiate the Class.
	 */
	class ALMThemeRepeaters {

		/**
		 * Construct function.
		 */
		public function __construct() {
			add_action( 'alm_theme_repeaters_installed', [ $this, 'alm_theme_repeaters_installed' ] );
			add_action( 'alm_theme_repeaters_selection', [ $this, 'alm_theme_repeaters_selection' ] );
			add_action( 'alm_list_theme_repeaters', [ $this, 'alm_list_theme_repeaters' ] );

			add_filter( 'alm_get_theme_repeater', [ $this, 'alm_get_theme_repeater' ], 10, 6 );
			add_filter( 'alm_get_acf_gallery_theme_repeater', [ $this, 'alm_get_acf_gallery_theme_repeater' ], 10, 6 );
			add_filter( 'alm_get_term_query_theme_repeater', [ $this, 'alm_get_term_query_theme_repeater' ], 10, 6 );
			add_filter( 'alm_get_users_theme_repeater', [ $this, 'alm_get_users_theme_repeater' ], 10, 6 );
			add_filter( 'alm_get_rest_theme_repeater', [ $this, 'alm_get_rest_theme_repeater' ], 10, 1 );
		}

		/**
		 * An empty function to determine if plugin is activated.
		 *
		 * @author ConnektMedia
		 */
		public function alm_theme_repeaters_installed() {
			// Empty hook.
		}

		/**
		 * Get a list of theme repeaters.
		 *
		 * @return array An array of theme repeaters.
		 */
		public function alm_get_theme_repeaters() {
			$dir       = $this->alm_get_theme_repeaters_dir();
			$path      = is_child_theme() ? get_stylesheet_directory() : get_template_directory();
			$file_path = $path . '/' . $dir;
			$templates = [];
			$allowed   = [ 'php', 'html' ]; // Only display .php, .html files files.

			foreach ( glob( $file_path . '/*' ) as $file ) {
				$file           = realpath( $file );
				$file_extension = strtolower( substr( basename( $file ), strrpos( basename( $file ), '.' ) + 1 ) );
				if ( in_array( $file_extension, $allowed, true ) ) {
					$templates[] = [
						'path'  => basename( $file ),
						'label' => basename( $file ),
					];
				}
			}
			return $templates;
		}

		/**
		 * Get a list of theme repeaters as an array.
		 *
		 * @return void
		 */
		public function alm_list_theme_repeaters() {
			$templates = $this->alm_get_theme_repeaters();
			if ( $templates ) {
				foreach ( $templates as $template ) {
					echo '<option value="' . $template['path'] . '">' . $template['label'] . '</option>';
				}
			} else {
				echo '<option value="null">' . __( 'No Templates Found', 'ajax-load-more-theme-repeaters' ) . '</option>';
			}
		}

		/**
		 * Security prevention. Don't allow users to back out and move directories.
		 *
		 * @param string $path The full path the file.
		 * @return string
		 */
		public function alm_verfify_filepath( $path ) {
			$path = str_replace( '../', '', $path );
			$path = str_replace( '..%2f', '', $path );
			return $path;
		}

		/**
		 * Get the current Theme Repeater dir.
		 *
		 * @return string
		 */
		public function alm_get_theme_repeaters_dir() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
				$options['_alm_theme_repeaters_dir'] = 'alm_templates';
			}
			return $options['_alm_theme_repeaters_dir'];
		}

		/**
		 * Get the complete file path to the Theme Repeater.
		 *
		 * @param string $template The Theme template name.
		 * @return string
		 */
		public function alm_get_theme_repeater_file( $template = '' ) {
			if ( ! function_exists( 'alm_get_default_repeater' ) ) {
				return;
			}

			if ( is_child_theme() ) {
				$file = get_stylesheet_directory() . $template;
			} else {
				$file = get_template_directory() . $template;
			}

			// Another security check.
			$file = $this->alm_verfify_filepath( $file );

			// Confirm file exists and run secondary security check.
			if ( ! file_exists( $file ) || false !== strpos( $file, './' ) ) {
				$file = alm_get_default_repeater();
			}

			return $file;
		}

		/**
		 * Get the theme repeater template.
		 *
		 * @deprecated 1.0
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param array  $args Array of query and setup variables.
		 * @return void
		 */
		public function alm_get_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $args = [] ) {
			if ( $theme_repeater !== 'null' ) {
				$dir = $this->alm_get_theme_repeaters_dir(); // Get template directory.

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get full file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );

				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for ACF Galleries.
		 *
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $image The image path.
		 * @return void
		 */
		public function alm_get_acf_gallery_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $image = [] ) {
			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for Term Query.
		 *
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $term The term slug.
		 * @return void
		 */
		public function alm_get_term_query_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $term = [] ) {
			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for Users add-on.
		 *
		 * @param string $theme_repeater The Theme Repeater.
		 * @param string $alm_found_posts ALM Variable.
		 * @param string $alm_page ALM Variable.
		 * @param string $alm_item ALM Variable.
		 * @param string $alm_current ALM Variable.
		 * @param string $user The user slug.
		 * @return void
		 */
		public function alm_get_users_theme_repeater( $theme_repeater = 'null', $alm_found_posts = 0, $alm_page = 0, $alm_item = 0, $alm_current = 0, $user = [] ) {
			if ( 'null' !== $theme_repeater ) {

				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * Get the theme repeater template for the REST API add-on.
		 *
		 * @param string $theme_repeater The Theme Repeater.
		 * @return void
		 */
		public function alm_get_rest_theme_repeater( $theme_repeater ) {
			if ( 'null' !== $theme_repeater ) {
				// Get template directory.
				$dir = $this->alm_get_theme_repeaters_dir();

				// Security prevention.
				$theme_repeater = '/' . $dir . '/' . $this->alm_verfify_filepath( $theme_repeater );

				// Get the complete file path.
				$file = $this->alm_get_theme_repeater_file( $theme_repeater );
				include $file;

			} else {
				include alm_get_default_repeater(); // Include default repeater template.
			}
		}

		/**
		 * List the templates within the /alm_templates dir. within the current theme directory
		 *
		 * @return void
		 */
		public function alm_theme_repeaters_selection() {
			$options = get_option( 'alm_settings' );
			if ( ! isset( $options['_alm_theme_repeaters_dir'] ) ) {
				$options['_alm_theme_repeaters_dir'] = 'alm_templates';
			}
			?>
			<div class="select-theme-repeater">
				<span class="or">or</span>
				<section>
					<div class="shortcode-builder--label">
						<h4><?php _e( 'Theme Repeater', 'ajax-load-more-templates' ); ?></h4>
						<p><?php _e( 'Select a repeater template from the <span>' . $options['_alm_theme_repeaters_dir'] . '</span> (<a href="admin.php?page=ajax-load-more" target="_parent">update</a>) directory within your current theme folder.', 'ajax-load-more-templates' ); ?></p>
						</div>
						<div class="shortcode-builder--fields">
						<div class="inner">
							<?php
							// Get template location.
							if ( is_child_theme() ) {
								$dir = get_stylesheet_directory() . '/' . $options['_alm_theme_repeaters_dir'];
							} else {
								$dir = get_template_directory() . '/' . $options['_alm_theme_repeaters_dir'];
							}

							$count = 0;
							echo '<select name="theme-repeater-select" class="alm_element"><option value="" selected="selected">-- ' . __( 'Select Theme Repeater', 'ajax-load-more-templates' ) . ' --</option>';
							foreach ( glob( $dir . '/*' ) as $file ) {
								++$count;
								$file = realpath( $file );
								$link = substr( $file, strlen( $dir ) + 1 );

								// Only display .php, .html files files.
								$file_extension = strtolower( substr( basename( $file ), strrpos( basename( $file ), '.' ) + 1 ) );
								if ( 'php' === $file_extension ) {
									echo '<option value="' . basename( $file ) . '">' . basename( $file ) . '</option>';
								}
							}
							if ( $count == 0 ) {
								echo '<option value="null">' . __( 'No Templates Found', 'ajax-load-more-templates' ) . '</option>';
							}
							echo '</select>';
							?>
						</div>
					</div>
				</section>
			</div>
			<?php
		}
	}

	// Init the class.
	global $alm_theme_repeaters;
	$alm_theme_repeaters = new ALMThemeRepeaters();

endif;

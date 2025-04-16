<?php
/**
 * ALM Admin Notice Class.
 *
 * @package ALMFilters
 */

if ( ! class_exists( 'ALMNotices' ) ) {
	/**
	 * Initiate the class.
	 */
	class ALMNotices {

		/**
		 * ALM Notices.
		 *
		 * @var array
		 */
		public $notices = [];

		/**
		 * Construct class.
		 */
		public function __construct() {
			add_action( 'admin_notices', [ &$this, 'alm_admin_notices' ] );
			add_filter( 'wp_kses_allowed_html', [ &$this, 'alm_wp_kses_allowed_html' ] );
		}

		/**
		 * Add admin notices.
		 *
		 * @since 1.5
		 * @param string $text  The notice text.
		 * @param string $class The classname for the notice.
		 * @param string $icon  The notice icon/svg.
		 * @return function
		 */
		public function alm_add_admin_notice( $text, $class = '', $icon = '' ) {
			return $this->add_notice( $text, $class, $icon );
		}

		/**
		 * Add admin notices to the $notices array.
		 *
		 * @since 1.5
		 * @param string $text  The notice text.
		 * @param string $class The notice class.
		 * @param string $icon  The notice icon/svg.
		 * @return void
		 */
		public function add_notice( $text = '', $class = '', $icon = '' ) {
			$this->notices[] = [
				'text'  => $text,
				'class' => 'updated ' . $class,
				'icon'  => $icon,
			];
		}

		/**
		 * Return the $notices.
		 *
		 * @since 1.5
		 * @return array $notices The notice.
		 */
		public function get_notices() {
			if ( empty( $this->notices ) ) {
				return false; // bail early if no notices.
			}
			return $this->notices;
		}

		/**
		 *  Render admin notices in the WP admin.
		 *
		 *  @since  1.5
		 *  @return void
		 */
		public function alm_admin_notices() {
			$notices = $this->get_notices();
			if ( ! $notices ) {
				return; // bail early if no notices.
			}

			// Loop notices.
			foreach ( $notices as $notice ) {
				$open  = '<p>';
				$close = '</p>';
				?>
				<div class="alm-admin-notice notice is-dismissible <?php echo esc_attr( $notice['class'] ); ?>">
				<?php
				if ( $notice['icon'] ) {
					echo wp_kses_post( $this->alm_filters_get_svg( $notice['icon'] ) );
				}
				echo wp_kses_post( $open ) . wp_kses_post( $notice['text'] ) . wp_kses_post( $close );
				?>
				</div>
				<?php
			}
		}

		/**
		 * Render an SVG.
		 *
		 * @param string $svg The SVG to render.
		 * @return string     The SVG as HTML.
		 */
		public function alm_filters_get_svg( $svg = '' ) {
			switch ( $svg ) {
				case 'success':
					return '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00a32a"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>';
			}
		}

		/**
		 * Allow SVGs in wp_kses.
		 */
		public function alm_wp_kses_allowed_html( $tags ) {
			$tags['svg']  = [
				'xmlns'        => [],
				'fill'         => [],
				'viewbox'      => [],
				'role'         => [],
				'aria-hidden'  => [],
				'focusable'    => [],
				'stroke'       => [],
				'stroke-width' => [],
				'width'        => [],
				'height'       => [],
			];
			$tags['path'] = [
				'd'               => [],
				'fill'            => [],
				'stroke-linecap'  => [],
				'stroke-linejoin' => [],
			];
			return $tags;
		}
	}
}

<?php
/**
 * Integrations: Integrations collection class
 *
 * @package Parsely
 * @since   2.6.0
 */

declare(strict_types=1);

namespace Parsely\Integrations;

use Parsely\Parsely;

/**
 * Integrations are registered to this collection.
 *
 * The `integrate()` method is called on each registered integration, on the
 * init hook.
 *
 * @since 2.6.0
 */
class Integrations {
	/**
	 * Instance of Parsely class.
	 *
	 * @var Parsely
	 */
	private $parsely;

	/**
	 * Constructor.
	 *
	 * @param Parsely $parsely Instance of Parsely class.
	 */
	public function __construct( Parsely $parsely ) {
		$this->parsely = $parsely;
	}

	/**
	 * Collection of registered integrations.
	 *
	 * @var array<Integrations>
	 */
	private $integrations = array();

	/**
	 * Registers an integration.
	 *
	 * @since 2.6.0
	 *
	 * @param string                    $key             A unique identifier for the integration.
	 * @param class-string|Integrations $class_or_object Fully-qualified class name, or an instantiated object.
	 *                                                   If a class name is passed, it will be instantiated.
	 */
	public function register( string $key, $class_or_object ): void {
		// If a Foo::class or other fully qualified class name is passed, instantiate it.
		if ( ! is_object( $class_or_object ) ) {
			/**
			 * Variable.
			 *
			 * @var Integrations
			 */
			$class_or_object = new $class_or_object( $this->parsely );
		}
		$this->integrations[ $key ] = $class_or_object;
	}

	/**
	 * Integrates each integration by calling the method that does the
	 * add_action() and add_filter() calls.
	 *
	 * @since 2.6.0
	 */
	public function integrate(): void {
		foreach ( $this->integrations as $integration ) {
			$integration->integrate();
		}
	}
}

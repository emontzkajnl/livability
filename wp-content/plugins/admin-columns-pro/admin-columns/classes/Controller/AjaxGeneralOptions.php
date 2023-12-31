<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Capabilities;
use AC\Registerable;
use AC\Request;
use AC\Settings\GeneralOption;

class AjaxGeneralOptions implements Registerable {

	/**
	 * @var GeneralOption
	 */
	private $general_option;

	public function __construct( GeneralOption $general_option ) {
		$this->general_option = $general_option;
	}

	public function register(): void
    {
		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_admin_general_options' )
			->set_callback( [ $this, 'handle_request' ] );

		return $handler;
	}

	public function handle_request() {
		$this->get_ajax_handler()->verify_request();

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			exit;
		}

		$request = new Request();

		$name = (string) $request->filter( 'option_name' );
		$value = (string) $request->filter( 'option_value' );

		$options = $this->general_option->get();

		$options[ $name ] = $value;

		$this->general_option->save( $options );

		exit;
	}

}
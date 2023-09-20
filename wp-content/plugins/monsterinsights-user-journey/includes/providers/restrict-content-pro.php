<?php
/**
 * User Journey Restrict Content Pro Processing.
 *
 * @since 1.0.2
 *
 * @package MonsterInsights
 * @subpackage MonsterInsights_User_Journey
 */

/**
 * Class to process user journey for Restrict Content Pro.
 *
 * This class extends MonsterInsights_User_Journey_Process base class for interactivity with
 * databse.
 *
 * @since 1.0.2
 */
class MonsterInsights_User_Journey_Process_Restrict_Content_Pro extends MonsterInsights_User_Journey_Process {

	/**
	 * Initialize.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		add_action( 'rcp_update_payment_status_complete', array( $this, 'process_user_journey' ), 10 );
	}

	/**
	 * Provider name/slug.
	 *
	 * @return string
	 * @since 1.0.2
	 *
	 */
	protected function get_provider() {
		return 'restrict-content-pro';
	}

	/**
	 * Process User Journey data once the order has been placed by the user.
	 *
	 * @param int $payment_id RCP Payment ID.
	 *
	 * @return void
	 * @since 1.0.2
	 *
	 */
	public function process_user_journey( $payment_id ) {

		$rcp_payments = MonsterInsights_User_Journey_Helper::rcp_payments();

		$is_in_ga = $rcp_payments->get_meta( $payment_id, '_monsterinsights_is_in_ga', true );
		$skip_ga  = apply_filters( 'monsterinsights_restrict_content_pro_transaction_skip_user_journey', false, $payment_id );

		// If it's already in GA or filtered to skip, then skip adding
		if ( $skip_ga || 'yes' !== $is_in_ga ) {
			return;
		}

		$already_completed = $rcp_payments->get_meta( $payment_id, '_monsterinsights_user_journey_completed', true );

		if ( $already_completed && 'yes' === $already_completed ) {
			return;
		}

		$this->process_entry_meta( $payment_id );
	}
}

if ( MonsterInsights_User_Journey_Helper::is_rcp_active() ) {
	new MonsterInsights_User_Journey_Process_Restrict_Content_Pro();
}

<?php
namespace sgpbgamification;

class Filters
{
	public function __construct()
	{
		add_filter('sgpbAddPopupTypePath', array($this, 'typePaths'), 10, 1);
		if (isset($_GET['post_type']) && $_GET['post_type'] == SG_POPUP_POST_TYPE) {
			add_filter('sgpbAddPopupType', array($this, 'popupType'), 10, 1);
			add_filter('sgpbAddPopupTypeLabels', array($this, 'addPopupTypeLabels'), 11);
		}
		add_filter('sgpbHidePageBuilderEditButtons', array($this, 'hidePageBuilderEditButtons'), 10, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'popupDefaultOptions'));
		add_filter('sgpbGetAllSubscriptionArgs', array($this, 'addGamificationType'), 10, 1);
	}

	public function addGamificationType($args)
	{
		$args['type'][] = SGPB_POPUP_TYPE_GAMIFICATION;

		return $args;
	}

	public function popupDefaultOptions($defaultOptions)
	{
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-width', 'type' => 'text', 'defaultValue' => '442px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-height', 'type' => 'text', 'defaultValue' => '68px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-border-radius', 'type' => 'text', 'defaultValue' => '5px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-border-width', 'type' => 'text', 'defaultValue' => '0px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-border-color', 'type' => 'text', 'defaultValue' => '#fab41c');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-title', 'type' => 'text', 'defaultValue' => __('Subscribe', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-progress-title', 'type' => 'text', 'defaultValue' => __('Please wait...', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-bg-color', 'type' => 'text', 'defaultValue' => '#fab41c');
		$defaultOptions[] = array('name' => 'sgpb-gamification-btn-text-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');

		$defaultOptions[] = array('name' => 'sgpb-gamification-text-placeholder', 'type' => 'text', 'defaultValue' => __('Email', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-width', 'type' => 'text', 'defaultValue' => '442px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-height', 'type' => 'text', 'defaultValue' => '68px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-border-radius', 'type' => 'text', 'defaultValue' => '0px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-border-width', 'type' => 'text', 'defaultValue' => '1px');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-border-color', 'type' => 'text', 'defaultValue' => '#CCCCCC');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-bg-color', 'type' => 'text', 'defaultValue' => '#FFFFFF');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-color', 'type' => 'text', 'defaultValue' => '#858383');
		$defaultOptions[] = array('name' => 'sgpb-gamification-text-placeholder-color', 'type' => 'text', 'defaultValue' => '#bbbab5');
		$defaultOptions[] = array('name' => 'sgpb-gamification-gift-image', 'type' => 'text', 'defaultValue' => SGPB_GAMIFICATION_IMAGE_URL);

		$startScreen = $this->getStartHtmlContent();
		$playScreen = $this->getPlayScreen();
		$winScreen = $this->getWinScreen();
		$loseScreen = $this->getLoserText();

		$defaultOptions[] = array('name' => 'sgpb-gamification-start-text', 'type' => 'sgpb', 'defaultValue' =>  __($startScreen, SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-play-text', 'type' => 'sgpb', 'defaultValue' =>  __($playScreen, SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-lose-text', 'type' => 'sgpb', 'defaultValue' =>  __($loseScreen, SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-gamification-win-text', 'type' => 'sgpb', 'defaultValue' =>  __($winScreen, SG_POPUP_TEXT_DOMAIN));

		$defaultOptions[] = array('name' => 'sgpb-gamification-error-message', 'type' => 'text', 'defaultValue' => __('There was an error while trying to send your request. Please try again', SG_POPUP_TEXT_DOMAIN).'.');
		$defaultOptions[] = array('name' => 'sgpb-gamification-invalid-message', 'type' => 'text', 'defaultValue' => __('Please enter a valid email address', SG_POPUP_TEXT_DOMAIN).'.');
		$defaultOptions[] = array('name' => 'sgpb-gamification-validation-message', 'type' => 'text', 'defaultValue' => __('This field is required', SG_POPUP_TEXT_DOMAIN).'.');
		$defaultOptions[] = array('name' => 'sgpb-gamification-gdpr-term', 'type' => 'text', 'defaultValue' => __('* By giving your email address you agree with our terms and conditions', SG_POPUP_TEXT_DOMAIN).'.');
		$defaultOptions[] = array('name' => 'sgpb-gamification-already-subscribed', 'type' => 'checkbox', 'defaultValue' => 'on');

		return $defaultOptions;
	}


	private function getLoserText()
	{
		ob_start();
		?>
			<h3 class="sgpb-gamification-loser-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #000000 !important;"><?php _e('Oops!!!', SG_POPUP_TEXT_DOMAIN); ?></h3>
			<p class="sgpb-gamification-loser-paragraph" style="text-align: center;font-size: 15px !important;font-family: Segoe UI;color: #8f8f8f !important; margin-bottom: 0;"><?php _e('Not your lucky day! Next time you\'ll win!', SG_POPUP_TEXT_DOMAIN); ?></p>
			<div class="sgpb-gamification-loser-img-wrapper" style="text-align: center;">
				<img src="<?php echo SGPB_GAMIFICATION_LOSER_IMG_URL;?>" style="margin: 30px auto;">
			</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getWinScreen()
	{
		ob_start();
		?>
			<h3 class="sgpb-gamification-start-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #f1a528 !important;"><?php _e('Congratulations!!!', SG_POPUP_TEXT_DOMAIN); ?></h3>
			<p class="sgpb-gamification-start-paragraph" style="text-align: center;font-size: 21px !important;font-family: Segoe UI;color: #8f8f8f !important; margin-bottom: 0;"><?php _e('Your Discount Code Is: <span><b>PBHAPPYCUSTOMER</b></span>', SG_POPUP_TEXT_DOMAIN); ?></p>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getStartHtmlContent()
	{
		ob_start();
		?>
			<h3 class="sgpb-gamification-start-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #479dcb !important;"><?php _e('Choose your gift', SG_POPUP_TEXT_DOMAIN); ?></h3>
			<p class="sgpb-gamification-start-paragraph" style="text-align: center;font-size: 15px !important;font-family: Segoe UI;color: #000000 !important; margin-bottom: 0;"><?php _e('Start the game to reveal your prize', SG_POPUP_TEXT_DOMAIN); ?></p>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function getPlayScreen()
	{
		ob_start();
		?>
			<h3 class="sgpb-gamification-play-header" style="text-align: center;font-family: Segoe UI;font-size: 40px !important;color: #479dcb !important;"><?php _e('Pick a gift to see what you\'ve won', SG_POPUP_TEXT_DOMAIN); ?></h3>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public function typePaths($typePaths)
	{
		$typePaths[SGPB_POPUP_TYPE_GAMIFICATION] = SGPB_GAMIFICATION_CLASSES_PATH;

		return $typePaths;
	}

	public function addPopupTypeLabels($labels)
	{
		$labels[SGPB_POPUP_TYPE_GAMIFICATION] = __(SGPB_POPUP_TYPE_GAMIFICATION_DISPLAY_NAME, SG_POPUP_TEXT_DOMAIN);

		return $labels;
	}

	public function popupType($popupType)
	{
		$popupType[SGPB_POPUP_TYPE_GAMIFICATION] = SGPB_GAMIFICATION_AVAILABLE_VERSION;

		return $popupType;
	}

	public function hidePageBuilderEditButtons($popupTypes = array())
	{
		$popupTypes[] = SGPB_POPUP_TYPE_GAMIFICATION;

		return $popupTypes;
	}
}

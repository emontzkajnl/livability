<?php
namespace sgpb;
use sgpb\AdminHelper as AdminHelper;
use sgpbsocial\AdminHelper as SocialAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'/SGPopup.php');

class SocialPopup extends SGPopup
{
	private $socialNetworks = array(
		'email',
		'twitter',
		'facebook',
		'googleplus',
		'linkedin',
		'pinterest'
	);

	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'), 1, 100);
	}

	public function adminFilters()
	{
		add_filter('sgpbAdminJsFiles', array($this, 'popupJsFilter'), 1, 1);
		add_filter('sgpbAdminJsLocalizedData', array($this, 'popupJsLocalizedFilter'), 1, 1);
		add_filter('sgpbAdminCssFiles', array($this, 'popupAdminCssFilter'), 1, 1);
		add_filter('sgpbFrontendCssFiles', array($this, 'popupFrontCssFilter'), 1, 1);
		add_filter('sgpbFrontendJsFiles', array($this, 'popupJsFilter'), 1, 1);
	}

	public function popupJsFilter($jsFiles)
	{
		$jsFiles[] = array('folderUrl' => SGPB_SOCIAL_JS_URL, 'filename' => 'jssocials.min.js', 'ver' => SG_VERSION_POPUP_SOCIAL);
		$jsFiles[] = array('folderUrl' => SGPB_SOCIAL_JS_URL, 'filename' => 'Social.js', 'ver' => SG_VERSION_POPUP_SOCIAL);

		return $jsFiles;
	}

	public function popupJsLocalizedFilter($localizedData)
	{
		$localizedData[] = array(
			'handle' => 'Social.js',
			'name' => 'SGPB_SOCIAL',
			'data' => array(
				'socialNetworks' => $this->socialNetworks
			)
		);

		return $localizedData;
	}

	public function popupAdminCssFilter($cssFiles)
	{
		$jsSocialsUlr = SGPB_SOCIAL_CSS_URL.'jssocial/';
		$cssFiles[] = array('folderUrl' => $jsSocialsUlr, 'filename' => 'jssocials.css', 'ver' => SG_VERSION_POPUP_SOCIAL);
		$cssFiles[] = array('folderUrl' => $jsSocialsUlr, 'filename' => 'font-awesome.min.css', 'ver' => SG_VERSION_POPUP_SOCIAL);

		return $cssFiles;
	}

	public function popupFrontCssFilter($cssFiles)
	{
		$jsSocialsUlr = SGPB_SOCIAL_CSS_URL.'jssocial/';
		$socialTheme = $this->getOptionValue('sgpb-social-share-theme');
		$cssFiles[] = array('folderUrl' => $jsSocialsUlr, 'filename' => 'jssocials.css', 'ver' => SG_VERSION_POPUP_SOCIAL);
		$cssFiles[] = array('folderUrl' => $jsSocialsUlr, 'filename' => 'font-awesome.min.css', 'ver' => SG_VERSION_POPUP_SOCIAL);
		$cssFiles[] = array('folderUrl' => $jsSocialsUlr, 'filename' => "jssocials-theme-$socialTheme.css", 'ver' => SG_VERSION_POPUP_SOCIAL);

		return $cssFiles;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$defaultOptions[] = array('name' => 'sgpb-social-share-url-type', 'type' => 'text', 'defaultValue' => 'shareUrl');
		$defaultOptions[] = array('name' => 'sgpb-social-share-url', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-social-share-theme', 'type' => 'text', 'defaultValue' => 'classic');
		$defaultOptions[] = array('name' => 'sgpb-social-theme-size', 'type' => 'text', 'defaultValue' => 14);
		$defaultOptions[] = array('name' => 'sgpb-social-show-labels', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-share-count', 'type' => 'text', 'defaultValue' => 'true');
		$defaultOptions[] = array('name' => 'sgpb-social-round-buttons', 'type' => 'checkbox', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-social-status-email', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-email', 'type' => 'text', 'defaultValue' => __('E-mail', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-status-facebook', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-facebook', 'type' => 'text', 'defaultValue' => __('Share', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-status-linkedin', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-linkedin', 'type' => 'text', 'defaultValue' => __('Share', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-status-googleplus', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-googleplus', 'type' => 'text', 'defaultValue' => __('+1', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-status-twitter', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-twitter', 'type' => 'text', 'defaultValue' => __('Tweet', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-status-pinterest', 'type' => 'checkbox', 'defaultValue' => 'on');
		$defaultOptions[] = array('name' => 'sgpb-social-label-pinterest', 'type' => 'text', 'defaultValue' => __('Pin it', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-social-to-bottom', 'type' => 'checkbox', 'defaultValue' => '');

		return $defaultOptions;
	}

	public function includeSocialButton()
	{
		$this->adminFilters();

		return $this->createSocialButtons();
	}

	private function createSocialButtons()
	{
		$activeNetworks = $this->getActiveSocialNetworks();
		$shareUrl = $this->getShareUrl();
		$showLabels = false;
		$shareCount = $this->getOptionValue('sgpb-social-share-count');
		$roundButton = $this->getOptionValue('sgpb-social-round-buttons');

		if ($this->getOptionValue('sgpb-social-show-labels')) {
			$showLabels = true;
		}

		$popupId = $this->getId();
		$themeSize = (int)$this->getOptionValue('sgpb-social-theme-size').'px';
		$socialIdName = 'sgpb-share-btns-container-'.$popupId;
		ob_start();
		?>
			<div id="<?php echo $socialIdName; ?>"></div>
			<script type="text/javascript">
				var sgpbActiveNetworks = <?php echo json_encode($activeNetworks); ?>;

				if (sgpbActiveNetworks === undefined || sgpbActiveNetworks === null) {
					sgpbActiveNetworks = [];
				}

				var sgpbSocialOptions = {
					shares: sgpbActiveNetworks,
					url: '<?php echo $shareUrl; ?>',
					showLabel: <?php echo ($showLabels == false) ? 0 : 1 ?>,
					showCount: <?php echo ($shareCount == 'false') ? 0 : '"'.$shareCount.'"'; ?>
				};
				function sgAddEvent(element, eventName, fn)
				{
					if (element.addEventListener)
						element.addEventListener(eventName, fn, false);
					else if (element.attachEvent)
						element.attachEvent('on' + eventName, fn);
				}
				sgAddEvent(document, 'DOMContentLoaded', function(e) {
					jQuery(document).ready(function() {
						jQuery('#<?php echo $socialIdName; ?>').attr('data-social-conf', JSON.stringify(sgpbSocialOptions)).jsSocials(sgpbSocialOptions);
						var socialObj = new SGPBSocial();
						socialObj.init(<?php echo $popupId; ?>);
						<?php if ($roundButton) : ?>
						jQuery('#<?php echo $socialIdName; ?> .jssocials-share-link').each(function() {
							jQuery(this).addClass('js-social-round-btn');
						});
						<?php endif;?>
					});
				});
			</script>
			<style type="text/css">
				#<?php echo $socialIdName; ?> {
					font-size: <?php echo $themeSize; ?>;
					text-align: center;
					padding: 5px;
				}
			</style>
		<?php
		$socialContent = ob_get_contents();
		ob_get_clean();

		return $socialContent;
	}

	private function getShareUrl()
	{
		$shareUrl = SocialAdminHelper::getCurrentUrl();
		$shareUrlType = $this->getOptionValue('sgpb-social-share-url-type');

		if ($shareUrlType == 'shareUrl') {
			$shareUrl = $this->getOptionValue('sgpb-social-share-url');
		}

		return $shareUrl;
	}

	private function getActiveSocialNetworks()
	{
		$socialNetworks = $this->socialNetworks;
		$activeNetworks = array();
		if (empty($socialNetworks)) {
			return $activeNetworks;
		}

		foreach ($socialNetworks as $socialNetwork) {
			if (!$this->getOptionValue('sgpb-social-status-'.$socialNetwork)) {
				continue;
			}

			$networkLabel = $this->getOptionValue('sgpb-social-label-'.$socialNetwork);

			$activeNetworks[] = array(
				'share' => $socialNetwork,
				'label' => $networkLabel
			);
		}

		return $activeNetworks;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		$this->adminFilters();
		$optionsViewData = array(
			'filePath' => SGPB_SOCIAL_VIEWS_PATH.'social.php',
			'metaboxTitle' => 'Social Settings',
			'short_description' => 'Select the social media buttons and add them to the specific content for liking and sharing'
		);

		return $optionsViewData;
	}

	public function getPopupTypeContent()
	{
		$selector = '';
		$popupId = $this->getId();
		$pushToBottom = $this->getOptionValue('sgpb-social-to-bottom');
		$popupSizingMode = $this->getOptionValue('sgpb-popup-dimension-mode');
		$popupContent = $this->getContent();
		$popupContent .= $this->includeSocialButton();
		if ($pushToBottom) {
			$selector = '#sgpb-share-btns-container-'.$popupId;
			if ($popupSizingMode == 'customMode') {
				$popupContent .= SocialAdminHelper::setPushToBottom($selector);
			}
		}

		return $popupContent;
	}

	public function getExtraRenderOptions()
	{
		return array();
	}
}

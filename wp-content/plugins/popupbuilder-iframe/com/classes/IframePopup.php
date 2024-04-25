<?php
namespace sgpb;
use sgpbiframe\AdminHelper as IframeAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class IframePopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbAdminJsFiles', array($this, 'adminJsFilter'), 1, 1);
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'));
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJsFiles', array($this, 'popupFrontJsFilter'), 1, 1);
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$changingOptions = array(
			'sgpb-content-padding' => array('name' => 'sgpb-content-padding', 'type' => 'text', 'defaultValue' => 0),
			'sgpb-width' => array('name' => 'sgpb-width', 'type' => 'text', 'defaultValue' => '60%'),
			'sgpb-height' => array('name' => 'sgpb-height', 'type' => 'text', 'defaultValue' => '60%')
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		$defaultOptions[] = array('name' => 'sgpb-iframe-url', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-iframe-invalid-url', 'type' => 'text', 'defaultValue' => __('Invalid URL.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-iframe-protocol-warning', 'type' => 'text', 'defaultValue' => __('This url may not work, as it is HTTP and you are running HTTPS.', SG_POPUP_TEXT_DOMAIN));
		$defaultOptions[] = array('name' => 'sgpb-iframe-same-origin-warning', 'type' => 'text', 'defaultValue' => __('This url may not work, as it doesn\'t allow embedding in iframes.', SG_POPUP_TEXT_DOMAIN));

		return $defaultOptions;
	}

	public function popupFrontJsFilter($jsFiles)
	{
		$jsFiles[] = array('folderUrl'=> SGPB_IFRAME_JS_URL, 'filename' => 'IVideo.js', 'dep' => array('PopupBuilder.js'));

		return $jsFiles;
	}

	public function adminJsFilter($jsFiles)
	{
		$jsFiles[] = array('folderUrl' => SGPB_IFRAME_JS_URL, 'filename' => 'Iframe.js');

		return $jsFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SGPB_IFRAME_VIEWS_PATH.'iframe.php',
			'metaboxTitle' => 'Iframe Settings',
			'short_description' => 'Add the URL for showing the content in the iframe'
		);
	}

	private function getIframeTag()
	{
		$options = $this->getOptions();
		$iframeScroll = '';
		$id = $this->getId();
		$iframeUrl = $options['sgpb-iframe-url'];

		// Iframe wrapped to div for fix mobile issue
		$iframe = '<div class="sgpb-scroll-wrapper">';
		// Added iframe random name for iframe must be have one name
		$iframe .= '<iframe allowfullscreen src="" data-attr-src="'.$iframeUrl.'" class="sgpb-iframe-spiner sgpb-iframe-'.$id.'" name="1507897640139" '.$iframeScroll.'></iframe>';
		$iframe .= '</div>';

		return $iframe;
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$popupContent = $this->getContent();
		$iframeTag = $this->getIframeTag();
		$popupContent .= $iframeTag;
		$popupContent .= '<style>';
		$popupContent .= '.sgpb-popup-builder-content-html {';
		$popupContent .= 'width: 100%;';
		$popupContent .= 'height: 100%;';
		$popupContent .= 'overflow: auto';
		$popupContent .= '}';
		$popupContent .= '</style>';

		return $popupContent;
	}

	public function getRemoveOptions()
	{
		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-content-click' => 1,
			'sgpb-popup-dimension-mode' => 1,
			'sgpb-force-rtl' => 1
		);
		$parentOptions = parent::getRemoveOptions();

		return $removeOptions + $parentOptions;
	}

	/**
	 * It returns what the current post supports (for example: title, editor, etc...)
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function getPopupTypeSupports()
	{
		return array('title');
	}

	public function getExtraRenderOptions()
	{
		$options = $this->getOptions();

		return $options;
	}
}

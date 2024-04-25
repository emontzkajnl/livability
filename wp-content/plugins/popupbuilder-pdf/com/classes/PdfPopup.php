<?php
namespace sgpb;
use sgpbpdf\AdminHelper as PdfAdminHelper;
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');

class PdfPopup extends SGPopup
{
	public function __construct()
	{
		add_filter('sgpbPopupDefaultOptions', array($this, 'filterPopupDefaultOptions'));
	}

	public function adminCssInit()
	{
		add_filter('sgpbPdfAdminCss', array($this, 'adminCssFilter'), 1, 1);
	}

	public function adminJsInit()
	{
		add_filter('sgpbPdfAdminJs', array($this, 'adminJsFilter'), 1, 1);
	}

	private function frontendFilters()
	{
		add_filter('sgpbFrontendJsFiles', array($this, 'popupFrontJsFilter'), 1, 1);
		add_filter('sgpbFrontendCssFiles', array($this, 'popupFrontCssFilter'), 1, 1);
	}

	public function popupFrontJsFilter($jsFiles)
	{
		$jsFiles[] = array('folderUrl'=> SGPB_PDF_JS_URL.'/', 'filename' => 'PdfFrontend.js');

		return $jsFiles;
	}

	public function popupFrontCssFilter($cssFiles)
	{
		$cssFiles[] = array('folderUrl' => SGPB_PDF_CSS_URL, 'filename' => 'pdf.css');
		$cssFiles[] = array('folderUrl' => SGPB_PDF_CSS_URL, 'filename' => 'viewer.css');

		return $cssFiles;
	}

	public function filterPopupDefaultOptions($defaultOptions)
	{
		$changingOptions = array(
			'sgpb-content-padding' => array('name' => 'sgpb-content-padding', 'type' => 'text', 'defaultValue' => 0),
			'sgpb-width' => array('name' => 'sgpb-width', 'type' => 'text', 'defaultValue' => '40%'),
			'sgpb-height' => array('name' => 'sgpb-height', 'type' => 'text', 'defaultValue' => '80%')
		);

		$defaultOptions = $this->changeDefaultOptionsByNames($defaultOptions, $changingOptions);

		$defaultOptions[] = array('name' => 'sgpb-pdf-url', 'type' => 'text', 'defaultValue' => '');
		$defaultOptions[] = array('name' => 'sgpb-pdf-zoom-level', 'type' => 'text', 'defaultValue' => 'automatic');
		$defaultOptions[] = array('name' => 'sgpb-pdf-selected-page', 'type' => 'number', 'defaultValue' => '1');

		return $defaultOptions;
	}

	public function adminJsFilter($jsFiles)
	{
		$jsFiles['jsFiles'][] = array('folderUrl' => SGPB_PDF_JS_URL, 'filename' => 'PdfAdmin.js');

		return $jsFiles;
	}

	public function adminCssFilter($cssFiles)
	{
		$cssFiles['cssFiles'][] = array('folderUrl' => SGPB_PDF_CSS_URL, 'filename' => 'pdf.css');

		return $cssFiles;
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeMainView()
	{
		$this->adminJsInit();
		$this->adminCssInit();
		return array(
			'filePath' => SGPB_PDF_VIEWS_PATH.'pdf.php',
			'metaboxTitle' => 'PDF Settings',
			'short_description' => 'Upload your own PDF file for the popup'
		);
	}

	public function getPopupTypeContent()
	{
		$this->frontendFilters();
		$options = $this->getOptions();
		$popupId = $this->getId();
		$pdfUrl = $options['sgpb-pdf-url'];
		$attrs = '';


		if (isset($options['sgpb-pdf-zoom-level']) && $options['sgpb-pdf-zoom-level']) {
			$zoom = $options['sgpb-pdf-zoom-level'];
			$zoom = $zoom == 'automatic' ? 'auto' : $zoom*100;
			$zoom = '#zoom='.$zoom;
			if ($options['sgpb-pdf-zoom-level'] == 'automatic') {
				$zoom = '';
			}

			$attrs .= $zoom;
		}
		if (isset($options['sgpb-pdf-selected-page']) && $options['sgpb-pdf-selected-page']) {
			$selectedPage = $options['sgpb-pdf-selected-page'];
			if ($zoom == '') {
				$attrs = '#';
			}
			else {
				$attrs .= '&';
			}

			$attrs .= 'page='.$selectedPage;
		}

		$pdfUrl = SGPB_PDF_JS_URL.'pdfjs/web/viewer.html?file='.$pdfUrl.$attrs;
		$pdfTag = '<div class="sgpb-scroll-wrapper"><iframe class="sgpb-pdf-iframe-'.$popupId.'" src="'.$pdfUrl.'" allowfullscreen="" webkitallowfullscreen="">pdf link</iframe></div>';
		$pdfTag .= '<style>';
		$pdfTag .= '.sgpb-popup-builder-content-html {width: 100%;height: 100%;overflow: auto}';
		$pdfTag .= '</style>';

		return $pdfTag;
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

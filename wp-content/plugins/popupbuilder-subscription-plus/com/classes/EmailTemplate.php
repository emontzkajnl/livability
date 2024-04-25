<?php
namespace sgpbsubscriptionplus;
use sgpb\SGPopup;
use \ConfigDataHelper;
if (!file_exists(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php')) {
	return false;
}
require_once(@SG_POPUP_CLASSES_POPUPS_PATH.'SGPopup.php');
require_once(SG_POPUP_HELPERS_PATH.'ConfigDataHelper.php');

class EmailTemplate
{
	public $params = array();
	public $emailTemplateHeader = '';
	public $emailTemplateFooter = '';
	public $emailTemplateContent = '';

	public function getParams()
	{
		return $this->params;
	}

	public function setParams($params)
	{
		$this->params = $params;
	}

	public function getEmailTemplateHeader()
	{
		return $this->emailTemplateHeader;
	}

	public function setEmailTemplateHeader($emailTemplateHeader)
	{
		$this->emailTemplateHeader = $emailTemplateHeader;
	}

	public function getEmailTemplateFooter()
	{
		return $this->emailTemplateFooter;
	}

	public function setEmailTemplateFooter($emailTemplateFooter)
	{
		$this->emailTemplateFooter = $emailTemplateFooter;
	}

	public function getEmailTemplateContent()
	{
		return $this->emailTemplateContent;
	}

	public function setEmailTemplateContent($emailTemplateContent)
	{
		$this->emailTemplateContent = $emailTemplateContent;
	}

	public static function getAllTemplatesData()
	{
		$args = array(
			'post_type' => SG_POPUP_TEMPLATE_POST_TYPE
		);
		$allTemplates = array();
		$allPostData = ConfigDataHelper::getQueryDataByArgs($args);

		if (empty($allPostData)) {
			return $allTemplates;
		}

		foreach ($allPostData->posts as $postData) {
			if (empty($postData)) {
				continue;
			}
			$allTemplates[] = $postData;
		}

		return $allTemplates;
	}

	public static function getTemplatesIdAndTitle()
	{
		$allTemplates = self::getAllTemplatesData();
		$templateIdTitles = array();

		if (empty($allTemplates)) {
			return $templateIdTitles;
		}

		foreach ($allTemplates as $template) {
			if (empty($template)) {
				continue;
			}
			$id = $template->ID;

			$title = $template->post_title;

			$templateIdTitles[$id] = $title;
		}

		return $templateIdTitles;
	}

	public static function getAllDefaultTemplatesData()
	{
		$templatesCount = 10;
		$templatesData = array();
		for ($i = 1; $i < $templatesCount; $i++) {
			$templatesImg = self::getPreviewImageUrlById($i);
			$templatesContent = self::getHtmlContentById($i);
			if (!$templatesContent) {
				continue;
			}
			$templatesData[$i]['index'] = $i;
			$templatesData[$i]['name'] = 'template'.$i;
			$templatesData[$i]['img'] = $templatesImg;
			$templatesData[$i]['content'] = $templatesContent;
		}

		return $templatesData;
	}

	public static function getPreviewImageUrlById($id = 0, $fromFiles = true)
	{
		return SGPB_SUBSCRIPTION_PLUS_TEMPLATE_IMG_URL.'template'.$id.'.png';
	}

	public static function getHtmlContentById($id = 0, $fromFiles = true)
	{
		if (file_exists(SGPB_SUBSCRIPTION_PLUS_TEMPLATES_PATH.'template'.$id.'.html')) {
			return file_get_contents(SGPB_SUBSCRIPTION_PLUS_TEMPLATES_PATH.'template'.$id.'.html');
		}

		return false;
	}

	public static function getCreateTemplateUrl($id = 0)
	{
		$templateType = '';
		if ($id) {
			$templateType = '&sgpb_template_type='.$id;
		}

		return SG_POPUP_ADMIN_URL.'post-new.php?post_type='.SG_POPUP_TEMPLATE_POST_TYPE.$templateType;
	}

	public static function getTemplateThumbClass($templateName)
	{
		$templateTypeClassName = 'sgpb-'.$templateName.'-template-icon';

		return $templateTypeClassName;
	}

	public static function getFullTemplateHtml($params = array())
	{
		$actionsObj = new self();
		$actionsObj->setParams($params);

		apply_filters('sgpbEmailTemplateHeader', $actionsObj);
		apply_filters('sgpbEmailTemplateContent', $actionsObj);
		apply_filters('sgpbEmailTemplateFooter', $actionsObj);

		$templateContent = $actionsObj->getEmailTemplateHeader();
		$templateContent .= $actionsObj->getEmailTemplateContent();
		$templateContent .= $actionsObj->getEmailTemplateFooter();

		return $templateContent;
	}

	public static function getTemplateBodyHtmlByIdFromDB($templateId = 0)
	{
		$template = self::find($templateId);
		if (!empty($template)) {
			return $template->post_content;
		}

		return array();
	}

	public static function find($templateId = 0)
	{
		$template = get_post($templateId);
		return $template;
	}
}

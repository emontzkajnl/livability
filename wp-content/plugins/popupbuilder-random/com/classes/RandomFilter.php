<?php
namespace sgpbrandom;

Class RandomFilter
{
	private $popups;

	public function setPopups($popups)
	{
		$this->popups = $popups;
	}

	public function getPopups()
	{
		return $this->popups;
	}

	public function filter()
	{
		$randomGroup = $this->getRandomPopups();
		$staticPopups = $this->getStaticPopups($randomGroup);

		$randomPopup = $this->filterRandomPopups($randomGroup);
		if (!empty($randomPopup)) {
			$staticPopups[] = $randomPopup;
		}

		return $staticPopups;
	}

	private function filterRandomPopups($randomPopups)
	{
		$randomPopup = array();
		$randomPopupKey = '';

		if (!empty($randomPopups)) {
			$randomPopupKey = @array_rand($randomPopups);
		}
		if (isset($randomPopups[$randomPopupKey])) {
			$randomPopup = $randomPopups[$randomPopupKey];
		}

		return $randomPopup;
	}

	public function getRandomPopups()
	{
		$popups = $this->getPopups();

		$termPopups = get_posts(
			array(
				'post_type' => 'popupbuilder',
				'numberposts' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => SG_POPUP_CATEGORY_TAXONOMY,
						'field' => 'slug',
						'terms' => SG_RANDOM_TAXONOMY_SLUG
					)
				)
			)
		);

		$randomGroup = array();

		foreach ($popups as $popup) {
			if (in_array($popup->getId(), array_column($termPopups, 'ID'))) {
				$randomGroup[] = $popup;
			}
		}

		return $randomGroup;
	}

	public function getStaticPopups($randomGroup)
	{
		$staticPopups = array();
		$popups = $this->getPopups();

		if (empty($randomGroup)) {
			return $popups;
		}

		$randomPopupIds = array_map(function ($randomPopup) {
			return $randomPopup->getId();
		}, $randomGroup);

		foreach ($popups as $popup) {
			if (empty($popup)) {
				continue;
			}
			if (!in_array($popup->getId(), $randomPopupIds)) {
				$staticPopups[] = $popup;
			}
		}

		return $staticPopups;
	}
}

<?php
namespace sgpb;
require_once(dirname(__FILE__).'/SGPopup.php');

class ImagePopup extends SGPopup
{
	public function save()
	{
		$imageData = '';
		$savedImageUrl = '';
		$data = $this->getSanitizedData();
		$imageUrl = @$data['sgpb-image-url'];
		$savedPopup = $this->getSavedPopup();

		if (is_object($savedPopup)) {
			$imageData = $savedPopup->getOptionvalue('sgpb-image-data');
			$savedImageUrl = $savedPopup->getOptionValue('sgpb-image-url');
		}

		if ($imageUrl != $savedImageUrl) {
			$imageUrl = AdminHelper::getImageDataFromUrl($imageUrl, true);
		}

		$data['sgpb-image-url'] = $imageUrl;
		$this->setSanitizedData($data);

		parent::save();
	}

	public function getOptionValue($optionName, $forceDefaultValue = false)
	{
		return parent::getOptionValue($optionName, $forceDefaultValue);
	}

	public function getPopupTypeOptionsView()
	{
		return array();
	}

	public function getRemoveOptions()
	{

		// Where 1 mean this options must not show for this popup type
		$removeOptions = array(
			'sgpb-reopen-after-form-submission' => 1,
			'sgpb-background-image' => 1,
			'sgpb-background-image-mode' => 1,
			'sgpb-force-rtl' => 1,
			'sgpb-content-padding' => 1
		);
		$parentOptions = parent::getRemoveOptions();
		if ($this->getType() != 'image') {
			return $parentOptions;
		}

		return $removeOptions + $parentOptions;
	}

	public function getPopupTypeMainView()
	{
		return array(
			'filePath' => SG_POPUP_TYPE_MAIN_PATH.'image.php',
			'metaboxTitle' => 'Image Popup Main Options'
		);
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

	public function getPopupTypeContent()
	{
		$id = $this->getId();
		$imageUrl = $this->getOptionValue('sgpb-image-url');

		return '<img width="1" height="1" class="sgpb-preloaded-image-'.$id.'" src="'.$imageUrl.'" style="position:absolute;right:9999999999999px;">';
	}

	public function getExtraRenderOptions()
	{
		return array();
	}
}

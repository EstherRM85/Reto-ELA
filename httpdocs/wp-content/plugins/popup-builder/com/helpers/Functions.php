<?php
namespace sgpb;

class Functions
{
	public static function renderForm($formFields)
	{
		$form = '';

		if (empty($formFields) || !is_array($formFields)) {
			return $form;
		}
		$simpleElements = array(
			'text',
			'email',
			'password',
			'hidden',
			'submit',
			'button'
		);

		$form = '<form class="sgpb-form" id="sgpb-form" method="post">';
		$fields = '<div class="sgpb-form-wrapper">';
		foreach ($formFields as $fieldKey => $formField) {
			$params = $formField;
			$htmlElement = '';
			$hideClassName = '';
			$type = 'text';

			if (!empty($formField['attrs']['type'])) {
				$type = $formField['attrs']['type'];
				if ($type == 'customCheckbox') {
					$formField['attrs']['type'] = 'checkbox';
				}
			}

			$styles = '';
			$attrs = '';
			$label = '';
			$gdprWrapperStyles = '';
			$gdprText = '';
			$errorMessageBoxStyles = '';

			if (!isset($formField['attrs']['name']) || $formField['attrs']['name'] == '') {
				continue;
			}
			$errorWrapperClassName = @$formField['attrs']['name'].'-error-message';
			if (isset($formField['errorMessageBoxStyles'])) {
				$errorMessageBoxStyles = 'style="width:'.$formField['errorMessageBoxStyles'].'"';
			}
			if (!empty($formField['label'])) {
				$label = $formField['label'];
				if (isset($formField['text'])) {
					$gdprText = $formField['text'];
				}
				$formField['style'] = array('color' => @$formField['style']['color'], 'width' => $formField['style']['width']);
				$gdprWrapperStyles = 'style="color:'.$formField['style']['color'].'"';
			}

			if ($type == 'checkbox') {
				$formField['style']['max-width'] = $formField['style']['width'];
				unset($formField['style']['width']);
			}
			if (!empty($formField['style'])) {
				$styles = 'style="';
				if (strpos(@$formField['attrs']['name'], 'gdpr') !== false) {
					unset($formField['style']['height']);
				}
				foreach ($formField['style'] as $styleKey => $styleValue) {
					if ($styleKey == 'placeholder') {
						$styles .= '';
					}
					$styles .= $styleKey.':'.$styleValue.'; ';
				}
				$styles .= '"';
			}

			if (!empty($formField['attrs'])) {
				foreach ($formField['attrs'] as $attrKey => $attrValue) {
					$attrs .= $attrKey.' = "'.esc_attr($attrValue).'" ';
				}
			}

			if (isset($formField['isShow']) && !$formField['isShow']) {
				$hideClassName = 'sg-js-hide';
			}

			if (in_array($type, $simpleElements)) {
				if (!isset($formField['attrs']['hasLabel']) || !$formField['attrs']['hasLabel']) {
					$params = array();
				}
				$htmlElement = self::createInputElement($attrs, $styles, $errorWrapperClassName, $errorMessageBoxStyles, $params);
			}
			else if ($type == 'checkbox') {
				$htmlElement = self::createCheckbox($attrs, $styles);

			}
			else if ($type == 'customCheckbox') {
				$label = $formField['label'];
				if (isset($formField['text'])) {
					$gdprText = $formField['text'];
				}
				$formField['style'] = array('color' => @$formField['style']['color'], 'width' => @$formField['style']['width']);
				$gdprWrapperStyles = 'style="color:'.@$formField['style']['color'].'"';
				$htmlElement = self::createGdprCheckbox($attrs, $styles, $label, $gdprWrapperStyles, $gdprText);
			}
			else if ($type == 'textarea') {
				$htmlElement = self::createTextArea($attrs, $styles, $errorWrapperClassName);
			}

			ob_start();
			?>
			<div class="sgpb-inputs-wrapper js-<?php echo $fieldKey; ?>-wrapper js-sgpb-form-field-<?php echo $fieldKey; ?>-wrapper <?php echo $hideClassName; ?>">
				<?php echo $htmlElement; ?>
			</div>
			<?php
			$fields .= ob_get_contents();
			ob_get_clean();
		}
		$fields .= '</div>';

		$form .= $fields;
		$form .= '</form>';

		return $form;
	}

	public static function createInputElement($attrs, $styles = '', $errorWrapperClassName = '', $errorMessageBoxStyles = '', $labelArgs = array())
	{
		$inputElement = "<input $attrs $styles>";
		if (!empty($labelArgs)) {
			$inputElement = '<label for="'.@$labelArgs['attrs']['sgpb-login-username'].'"><p class="sgpb-login-input-label '.@$labelArgs['attrs']['labelClass'].'">'.@$labelArgs['attrs']['hasLabel'].'</p>'.$inputElement.'</label>';
		}
		if (!empty($errorWrapperClassName)) {
			$inputElement .= "<div class='$errorWrapperClassName'></div>";
		}

		return $inputElement;
	}

	public static function createCheckbox($attrs, $styles)
	{
		$inputElement = "<input $attrs $styles>";

		return $inputElement;
	}

	public static function createGdprCheckbox($attrs, $styles, $label = '', $gdprWrapperStyles = '', $text = '')
	{
		$inputElement = "<input $attrs>";
		$inputElement = '<div class="sgpb-gdpr-label-wrapper" '.$styles.'>'.$inputElement.'<label class="js-login-remember-me-label-edit" for="sgpb-gdpr-field-label">'.$label.'</label><div class="sgpb-gdpr-error-message"></div></div>';
		if ($text == '') {
			return $inputElement;
		}
		$text = html_entity_decode($text);
		$inputElement .= '<div class="sgpb-alert-info sgpb-alert sgpb-gdpr-info js-subs-text-checkbox sgpb-gdpr-text-js" '.$styles.'>'.$text.'</div>';

		return $inputElement;
	}

	public static function createTextArea($attrs, $styles, $errorWrapperClassName = '')
	{
		$inputElement = "<textarea $attrs $styles></textarea>";
		if (!empty($errorWrapperClassName)) {
			$inputElement .= "<div class='$errorWrapperClassName'></div>";
		}

		return $inputElement;
	}

	public static function getDatabaseEngine()
	{
		global $wpdb;
		$dbName = $wpdb->dbname;
		$engine = 'InnoDB';
		$engineCheckSql = "SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbName'";
		$result = $wpdb->get_results($engineCheckSql, ARRAY_A);
		if (!empty($result)) {
			$engineCheckSql = "SHOW TABLE STATUS WHERE Name = '".$wpdb->prefix."users' AND Engine = 'MyISAM'";
			$result = $wpdb->get_results($engineCheckSql, ARRAY_A);
			if (isset($result[0]['Engine']) && $result[0]['Engine'] == 'MyISAM') {
				$engine = 'MyISAM';
			}
		}

		return $engine;
	}

	public static function getPopupTypeToAllowToShowMetabox()
	{
		global $post;

		if ($post->post_type != SG_POPUP_POST_TYPE) {
			return false;
		}
		if (!empty($_GET['sgpb_type'])) {
			$type = $_GET['sgpb_type'];
		}
		else {
			$popupId = $post->ID;
			$popup = SGPopup::find($popupId);
			if (empty($popup) || !is_object($popup)) {
				return false;
			}
			$type = $popup->getType();
		}

		return $type;
	}

	public static function getExtensionDirectory($extensionFolderName = '', $popupMainFolderName = '')
	{
		$directoryExisits = is_dir(SGPB_POPUP_EXTENSIONS_PATH.$extensionFolderName);
		$dir = SGPB_POPUP_EXTENSIONS_PATH.$extensionFolderName;

		if (!$directoryExisits) {
			$directoryExisits = is_dir(WP_PLUGIN_DIR.'/'.$extensionFolderName);
			$dir = WP_PLUGIN_DIR.'/'.$extensionFolderName;
			if (!$directoryExisits) {
				$dir = false;
			}
		}

		return $dir;
	}

	public static function getIpAddress()
	{
		$ipAddress = 'UNKNOWN';

		if (getenv('HTTP_CLIENT_IP')) {
			$ipAddress = getenv('HTTP_CLIENT_IP');
		}
		else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		}
		else if (getenv('HTTP_X_FORWARDED')) {
			$ipAddress = getenv('HTTP_X_FORWARDED');
		}
		else if (getenv('HTTP_FORWARDED_FOR')) {
			$ipAddress = getenv('HTTP_FORWARDED_FOR');
		}
		else if (getenv('HTTP_FORWARDED')) {
			$ipAddress = getenv('HTTP_FORWARDED');
		}
		else if (getenv('REMOTE_ADDR')) {
			$ipAddress = getenv('REMOTE_ADDR');
		}

		return $ipAddress;
	}

	public static function clearAllTransients()
	{
		global $SGPB_TRANSIENT_CONFIG;

		if (empty($SGPB_TRANSIENT_CONFIG)) {
			return true;
		}

		foreach ($SGPB_TRANSIENT_CONFIG as $eachTransient) {
			delete_transient($eachTransient);
		}
	}
}

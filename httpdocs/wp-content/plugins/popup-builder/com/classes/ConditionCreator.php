<?php
namespace sgpb;
class ConditionCreator
{
	public function __construct($targetData)
	{
		if (!empty($targetData)) {
			$this->setConditionsObj($targetData);
		}
	}

	private $conditionsObj;
	//When there is not group set groupId -1
	private $prevGroupId = -1;

	public function setPrevGroupId($prevGroupId)
	{
		$this->prevGroupId = $prevGroupId;
	}

	public function getPrevGroupId()
	{
		return $this->prevGroupId;
	}

	public function setConditionsObj($conditionsObj)
	{
		$this->conditionsObj = $conditionsObj;
	}

	public function getConditionsObj()
	{
		return $this->conditionsObj;
	}

	public function render()
	{
		$conditionsObj = $this->getConditionsObj();
		$view = '';

		if (empty($conditionsObj)) {
			return array();
		}

		foreach ($conditionsObj as $conditionObj) {

			$currentGroupId = $conditionObj->getGroupId();

			$prevGroupId =  $this->getPrevGroupId();
			$openGroupDiv = '';
			$separator = '';
			$closePrevGroupDiv = '';

			if ($currentGroupId > $prevGroupId) {
				if ($currentGroupId != 0) {
					$closePrevGroupDiv = '</div>';
					$separator = ConditionCreator::getOrRuleSeparator();
				}
				$openGroupDiv = '<div class="sgpb-wrapper sgpb-box-'.$conditionObj->getConditionName().' sg-target-group sg-target-group-'.$conditionObj->getGroupId().'" data-group-id="'.$currentGroupId.'">';
			}

			$view .= $closePrevGroupDiv;
			$view .= $separator;
			$view .= $openGroupDiv;
			$view .= ConditionCreator::createConditionRuleRow($conditionObj);

			$this->setPrevGroupId($currentGroupId);
		}

		$view .= '</div>';

		return $view;
	}

	public static function getOrRuleSeparator()
	{
		return '<h4 class="sg-rules-or"><span>'.__('OR', SG_POPUP_TEXT_DOMAIN).'</span></h4>';
	}

	public static function createConditionRuleRow($conditionDataObj)
	{
		ob_start();
		?>
		<div class="sg-target-rule sg-target-rule-<?php echo $conditionDataObj->getRuleId(); ?> sgpb-event-row" data-rule-id="<?php echo $conditionDataObj->getRuleId(); ?>">
			<div class="row">
				<?php
				$savedData = $conditionDataObj->getSavedData();

				if (!isset($savedData['value'])) {
					$savedData['value'] = '';
				}
				?>
				<?php $idHiddenDiv = $conditionDataObj->getConditionName().'_'.$conditionDataObj->getGroupId().'_'.$conditionDataObj->getRuleId();?>
				<?php foreach ($savedData as $conditionName => $conditionSavedData): ?>
					<?php
					$showRowStatusClass = '';
					$hideStatus = self::getParamRowHideStatus($conditionDataObj, $conditionName);
					$ruleElementData = self::getRuleElementData($conditionDataObj, 'param');
					$ruleSavedData = $ruleElementData['saved'];
					$currentArgs = array('savedData' => $ruleSavedData, 'conditionName' => $conditionName);

					if (!self::allowToShowOperatorColumn($conditionDataObj, $currentArgs)) {
						$hideStatus = true;
					}
					$showRowStatusClass = ($hideStatus) ? 'sg-hide-condition-row' : $showRowStatusClass;
					?>
					<?php if ($conditionName != 'hiddenOption'): ?>
						<div data-condition-name="<?php echo $conditionName;?>" class="<?php echo 'col-sm-3 sg-condition-'.$conditionName.'-wrapper'.' '.$showRowStatusClass; ?>">
							<?php
							if (!$hideStatus) {
								echo self::createConditionElement($conditionDataObj, $conditionName);
							}
							?>
						</div>
					<?php endif; ?>
					<?php if (($conditionName == 'hiddenOption')): ?>
						<?php $hiddenContent = self::getHiddenDataContent($conditionDataObj); ?>
							<div class="sg-hide-condition-row"><div id="<?php echo $idHiddenDiv;?>"><?php echo $hiddenContent; ?></div></div>
					<?php endif; ?>
				<?php endforeach;?>
				<?php echo self::createConditionOperators($conditionDataObj, $idHiddenDiv); ?>
			</div>
		</div>
		<?php
		$targetOptionRow = ob_get_contents();
		ob_end_clean();

		return $targetOptionRow;
	}

	private static function allowToShowOperatorColumn($conditionDataObj, $currentArgs = array())
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$conditionName = $conditionDataObj->getConditionName();
		$conditionData = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$operatorAllowInConditions = array();

		if (!empty($conditionData['operatorAllowInConditions'])) {
			$operatorAllowInConditions  = $conditionData['operatorAllowInConditions'];
		}

		$savedData = $conditionDataObj->getSavedData();

		$status = true;

		if ($currentArgs['conditionName'] == 'operator') {
			$currentSavedData = $currentArgs['savedData'];

			if (($currentSavedData == 'not_rule' || $currentSavedData == 'select_role' || $currentSavedData == 'select_event')) {
				$status = false;
			}

			// unset old customOperator
			$SGPB_DATA_CONFIG_ARRAY[$conditionName]['paramsData']['customOperator'] = '';
			if (is_array($operatorAllowInConditions)) {

				if (in_array($savedData['param'], $operatorAllowInConditions)) {
					$operator = '';
					if (!empty($conditionData['paramsData'][$savedData['param'].'Operator'])) {
						$operator = $conditionData['paramsData'][$savedData['param'].'Operator'];
					}
					$SGPB_DATA_CONFIG_ARRAY[$conditionName]['paramsData']['customOperator'] = $operator;
					return true;
				}
				if (!empty($savedData['tempParam']) && in_array($savedData['tempParam'], $operatorAllowInConditions)) {
					$SGPB_DATA_CONFIG_ARRAY[$conditionName]['paramsData']['operator'] = $conditionData['paramsData'][$savedData['tempParam'].'Operator'];
				}
			}

			if (empty($SGPB_DATA_CONFIG_ARRAY[$conditionName]['paramsData']['operator'])) {
				$status = false;
			}
		}

		return $status;
	}

	public static function createConditionOperators($conditionDataObj, $idHiddenDiv = '')
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$groupId = $conditionDataObj->getRuleId();
		$groupTotal = $conditionDataObj->getGroupTotal();

		$conditionName = $conditionDataObj->getConditionName();
		$operatorsHtml = '';
		$conditionData = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$eventsData = $SGPB_DATA_CONFIG_ARRAY['events'];

		$operatorsData = $conditionData['operators'];
		$eventButtonClasses = '';
		$eventButtonWrapperClass = '';

		if (empty($operatorsData)) {
			return $operatorsHtml;
		}

		foreach ($operatorsData as $operator) {
			$identificatorClass = '';
			$style = '';
			if (!isset($eventsData['hiddenOptionData'])) {
				continue;
			}
			$saveData = $conditionDataObj->getSavedData();
			if (empty($saveData['hiddenOption']) && $operator['name'] == 'Edit' && $saveData["param"] != 'load') {
				continue;
			}
			if ($operator['operator'] == 'edit') {
				$identificatorClass = $idHiddenDiv;
				$eventButtonClasses = 'btn btn-success btn-xs';
				$eventButtonWrapperClass = 'col-sm-2 ';
			}
			if ($operator['operator'] == 'add') {
				$eventButtonClasses = 'btn btn-primary btn-xs';
				$eventButtonWrapperClass = 'col-sm-2 ';
				$style = '';
				//Don't show add button if it's not for last element
				if ($groupId < $groupTotal) {
					$style = 'style="display: none;"';
				}
			}
			if ($operator['operator'] == 'delete') {
				$eventButtonClasses = 'btn btn-danger btn-xs';
				$eventButtonWrapperClass = 'col-sm-1 ';
			}
			if ($operator['name'] == 'Edit') {
				$operator['name'] = 'Settings';
			}

			$operatorsHtml .= '<div class="'.$eventButtonWrapperClass.'sg-rules-'.$operator['operator'].'-button-wrapper sgpb-static-padding-top" '.$style.'>';
			$operatorsHtml .= '<a href="javascript:void(0)" class="sg-rules-'.$operator['operator'].'-rule '.$eventButtonClasses.'" data-id="'.$identificatorClass.'"><span>'.__(' '.$operator['name'], SG_POPUP_TEXT_DOMAIN).'</span></a>';
			$operatorsHtml .= '</div>';
		}

		return $operatorsHtml;
	}

	public static function createConditionElement($conditionDataObj, $ruleName)
	{
		//more code added because of the lack of abstraction
		//todo: remove ASAP if possible
		$sData = $conditionDataObj->getSavedData();
		if ($ruleName == 'param' && !empty($sData['tempParam'])) {
			$sData['param'] = $sData['tempParam'];
			$newObj = clone $conditionDataObj;
			$newObj->setSavedData($sData);
			$conditionDataObj = $newObj;
		}

		$element = '';

		$ruleElementData = self::getRuleElementData($conditionDataObj, $ruleName);
		$elementHeader = self::createRuleHeader($ruleElementData);
		$field = self::createRuleField($ruleElementData);
		$element .= $elementHeader;
		$element .= $field;

		return $element;
	}

	public static function createConditionField($conditionDataObj, $ruleName)
	{
		$ruleElementData = self::getRuleElementData($conditionDataObj, $ruleName);

		return self::createRuleField($ruleElementData);
	}

	public static function createConditionFieldHeader($conditionDataObj, $ruleName)
	{
		$ruleElementData = self::getRuleElementData($conditionDataObj, $ruleName);

		return self::createRuleHeader($ruleElementData);
	}

	public static function optionLabelSupplement($conditionDataObj, $ruleName)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$conditionName = $conditionDataObj->getConditionName();
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$attrs = $conditionConfig['attrs'][$ruleName];

		if (isset($attrs['infoAttrs']['rightLabel'])) {
			$labelData = $attrs['infoAttrs']['rightLabel'];
			$value = $labelData['value'];
			$classes = $labelData['classes'];
			return '<span class="'.esc_attr($classes).'">'.$value.'</span>';
		}

		return '';
	}

	private static function getRuleElementData($conditionDataObj, $ruleName)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$ruleElementData = array();
		$savedParam = '';
		$conditionName = $conditionDataObj->getConditionName();
		$saveData = $conditionDataObj->getSavedData();
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$rulesType = $conditionConfig['columnTypes'];
		$paramsData = $conditionConfig['paramsData'];

		$attrs = $conditionConfig['attrs'];

		if (!empty($saveData[$ruleName])) {
			$savedParam =  $saveData[$ruleName];
		}
		else if (!empty($saveData['hiddenOption'])) {
			$savedParam = @$saveData['hiddenOption'][$ruleName];
		}

		$ruleElementData['ruleName'] = $ruleName;
		if ($ruleName == 'value' && !empty($saveData[$conditionDataObj->getTakeValueFrom()])) {
			$index = $conditionDataObj->getTakeValueFrom();
			$ruleName = $saveData[$index];
		}

		$type = array();
		if (!empty($rulesType[$ruleName])) {
			$type = $rulesType[$ruleName];
		}
		$data = array();
		if (!empty($paramsData[$ruleName])) {
			$data = $paramsData[$ruleName];
		}

		// if exists customOperator it takes the custom one
        if ($ruleName == 'operator' && !empty($paramsData['customOperator'])) {
            $data = $paramsData['customOperator'];
        }

		$optionAttr = array();
		if (!empty($attrs[$ruleName])) {
			$optionAttr = $attrs[$ruleName];
		}

		$attr = array();

		if (!empty($optionAttr['htmlAttrs'])) {
			$attr = $optionAttr['htmlAttrs'];
		}

		$ruleElementData['type'] = $type;
		$ruleElementData['data'] = apply_filters('sgpb'.$ruleName.'ConditionCreator', $data, $saveData);
		$ruleElementData['saved'] = $savedParam;
		$ruleElementData['attr'] = $attr;
		$ruleElementData['conditionDataObj'] = $conditionDataObj;

		return $ruleElementData;
	}

	private static function createRuleHeader($ruleElementData)
	{
		return self::createElementHeader($ruleElementData);
	}

	public static function createRuleField($ruleElementData)
	{
		$attr = array();
		$type = $ruleElementData['type'];
		$conditionObj = $ruleElementData['conditionDataObj'];

		$name = 'sgpb-'.$conditionObj->getConditionName().'['.$conditionObj->getGroupId().']['.$conditionObj->getRuleId().']['.$ruleElementData['ruleName'].']';
		$attr['name'] = $name;

		if (is_array($ruleElementData['attr'])) {
			$attr += $ruleElementData['attr'];
			$attr['data-rule-id'] = $conditionObj->getRuleId();
		}
		$rowField = '';

		switch($type) {

			case 'select':
				if (!empty($attr['multiple'])) {
					$attr['name'] .= '[]';
				}
				$savedData = $ruleElementData['saved'];

				if (empty($ruleElementData['data'])) {
					$ruleElementData['data'] = $ruleElementData['saved'];
					$savedData = array();

					if (!empty($ruleElementData['saved'])) {
						$savedData = array_keys($ruleElementData['saved']);
					}
				}

				$rowField .= AdminHelper::createSelectBox($ruleElementData['data'], $savedData, $attr);
				break;
			case 'text':
			case 'url':
			case 'number':
				$attr['type'] = $type;

				//this is done to override the initial input value
				if (!empty($ruleElementData['saved'])) {
					$attr['value'] = esc_attr($ruleElementData['saved']);
				}

				$rowField .= AdminHelper::createInput($ruleElementData['data'], $ruleElementData['saved'], $attr);
				break;
			case 'checkbox':
				$attr['type'] = $type;
				$rowField .= AdminHelper::createCheckBox($ruleElementData['data'], $ruleElementData['saved'], $attr);
				break;
			case  'conditionalText':
				$popupId = self::getPopupId($conditionObj);
				if(!empty($popupId)) {
					$attr['value'] = $attr['value'].$popupId;
					$rowField .= AdminHelper::createInput($ruleElementData['data'], $ruleElementData['saved'].$popupId, $attr);
				}
				else {
					$rowField .= '<div class="sgpb-show-alert-before-save">'.$attr['beforeSaveLabel'].'</div>';
				}
				break;
		}

		return $rowField;
	}

	public static function getPopupId($conditionObj)
	{
		$popupId = 0;
		$conditionPopupId = $conditionObj->getPopupId();

		if (!empty($conditionPopupId)) {
			$popupId = $conditionObj->getPopupId();
		}
		else if(!empty($_GET['post'])) {
			$popupId = $_GET['post'];
		}

		return $popupId;
	}

	public static function createElementHeader($ruleElementData)
	{
		$labelAttributes = '';
		$info = '';
		$conditionObj = $ruleElementData['conditionDataObj'];
		$conditionName = $conditionObj->getConditionName();
		$ruleName = $ruleElementData['ruleName'];
		global $SGPB_DATA_CONFIG_ARRAY;
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$conditionAttrs = $conditionConfig['attrs'];

		$saveData = $conditionObj->getSavedData();
		$optionTitle = $ruleName;
		$titleKey = $ruleName;


		if ($ruleName == 'value' && !empty($saveData[$conditionObj->getTakeValueFrom()])) {
			$titleKey = $saveData[$conditionObj->getTakeValueFrom()];
		}

		if (!empty($conditionAttrs[$titleKey])) {
			$optionAttrs = $conditionAttrs[$titleKey];
			if (!empty($optionAttrs['infoAttrs'])) {
				// $conditionName => events, conditions, targets...
				// $ruleName => param, operator, value (1-st, 2-nd, 3-rd columns)
				$optionAttrs = apply_filters('sgpb'.$conditionName.$ruleName.'Param', $optionAttrs, $saveData);
				$optionTitle = $optionAttrs['infoAttrs']['label'];
				if (!empty($optionAttrs['infoAttrs']['labelAttrs'])) {
					$labelAttributes = AdminHelper::createAttrs($optionAttrs['infoAttrs']['labelAttrs']);
				}
			}
		}
		if (isset($optionAttrs['infoAttrs']['info']) && $optionAttrs['infoAttrs']['info']) {
			$info .= '<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>';
			$info .= '<span class="infoSelectRepeat samefontStyle sgpb-info-text">'.$optionAttrs['infoAttrs']['info'].'</span>';
		}

		return "<label $labelAttributes>$optionTitle</label>$info";
	}

	public static function getHiddenDataContent($conditionDataObj)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$savedData = $conditionDataObj->getSavedData();
		$conditionName = $savedData['param'];
		$eventsData = $SGPB_DATA_CONFIG_ARRAY['events'];
		$hiddenOptions = $eventsData['hiddenOptionData'];
		$ruleId = $conditionDataObj->getRuleId();
		if (empty($hiddenOptions[$conditionName])) {
			return __('No Data', SG_POPUP_TEXT_DOMAIN);
		}

		$hiddenOptionsData = $hiddenOptions[$conditionName];

		$tabs = array_keys($hiddenOptionsData);
		ob_start();
		?>

		<div class="sgpb-wrapper">
			<div class="tab">
				<?php
				$activeTab = '';
				if (!empty($tabs[0])) {
					$activeTab = $tabs[0];
				}
				?>
				<?php foreach ($tabs as $tab): ?>
					<?php
					$activeClassName = '';
					if ($activeTab == $tab) {
						$activeClassName = 'sgpb-active';
					}
					?>
					<button class="tablinks sgpb-tab-links <?php echo $activeClassName;?>" data-rule-id="<?php echo $ruleId; ?>" data-content-id="<?php echo $tab.'-'.$ruleId; ?>"><?php echo ucfirst($tab); ?></button>
				<?php endforeach;?>
			</div>
			<?php echo self::createHiddenFields($hiddenOptionsData, $conditionDataObj, $ruleId); ?>
			<div class="modal-footer">
				<button type="button" class="sgpb-no-button events-option-close btn btn-default btn-sm" href="#"><?php _e('Cancel', SG_POPUP_TEXT_DOMAIN); ?></button>
				<button class="btn btn-primary btn-sm sgpb-popup-option-save"><?php _e('Save', SG_POPUP_TEXT_DOMAIN); ?></button>
			</div>
		</div>
		<?php
		$hiddenPopupContent = ob_get_contents();
		ob_end_clean();

		return $hiddenPopupContent;
	}

	private static function createHiddenFields($hiddenOptionsData, $conditionDataObj, $ruleId)
	{
		ob_start();
		?>
		<?php foreach ($hiddenOptionsData as $key => $hiddenData): ?>
		<div id="<?php echo $key.'-'.$ruleId; ?>" class="sgpb-tab-content-<?php echo $ruleId;?>">
			<div id="<?php echo $key; ?>" class="sgpb-tab-content-options">
				<?php foreach ($hiddenData as $name => $label): ?>
					<?php
					$hiddenOptionsView = self::optionLabelSupplement($conditionDataObj, $name);
					$colMdValue = 6;
					if (!empty($hiddenOptionsView)) {
						$colMdValue = 2;
					}
					?>
					<div class="row form-group">
						<div class="col-md-6">
							<?php echo self::createConditionFieldHeader($conditionDataObj, $name); ?>
						</div>
						<div class="col-md-<?php echo $colMdValue; ?>">
							<?php echo self::createConditionField($conditionDataObj, $name); ?>
						</div>
						<?php if (!empty($hiddenOptionsView)): ?>
							<div class="col-md-4">
								<?php echo $hiddenOptionsView; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach;?>
		<?php
		$hiddenPopupContent = ob_get_contents();
		ob_end_clean();

		return $hiddenPopupContent;
	}

	public static function hiddenSubOptionsView($parentOptionName, $conditionDataObj)
	{
		$subOptionsContent = '';
		$subOptions = self::getHiddenOptionSubOptions($parentOptionName);
		if (!empty($subOptions)) {
			$subOptionsContent =  self::createHiddenSubOptions($parentOptionName, $conditionDataObj, $subOptions);
		}

		return $subOptionsContent;
	}

	private static function createHiddenSubOptions($parentOptionName, $conditionDataObj, $subOptions)
	{
		$name = $parentOptionName;
		ob_start();
		?>
		<div class="row <?php echo 'sgpb-popup-hidden-content-'.$name.'-'.$conditionDataObj->getRuleId().'-wrapper'?> form-group">
			<?php foreach ($subOptions as $subOption): ?>
				<div class="col-md-6">
					<?php echo self::createConditionFieldHeader($conditionDataObj, $subOption); ?>
				</div>
				<div class="col-md-6">
					<?php echo self::createConditionField($conditionDataObj, $subOption); ?>
				</div>
				<?php  echo self::hiddenSubOptionsView($subOption, $conditionDataObj)?>
			<?php endforeach;?>
		</div>
		<?php
		$hiddenPopupContent = ob_get_contents();
		ob_end_clean();

		return $hiddenPopupContent;
	}

	public static function getHiddenOptionSubOptions($optionName)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$childOptionNames = array();
		$eventsData = $SGPB_DATA_CONFIG_ARRAY['events'];
		$targetDataAttrs = $eventsData['attrs'];

		if (empty($targetDataAttrs[$optionName])) {
			return $childOptionNames;
		}

		if (empty($targetDataAttrs[$optionName]['childOptions'])) {
			return $childOptionNames;
		}
		$childOptionNames = $targetDataAttrs[$optionName]['childOptions'];

		return $childOptionNames;
	}

	private static function getParamRowHideStatus($conditionDataObj, $ruleName)
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		if ($ruleName == 'hiddenOption') {
			return '';
		}
		$status = false;
		$conditionName = $conditionDataObj->getConditionName();
		$saveData = $conditionDataObj->getSavedData();
		$conditionConfig = $SGPB_DATA_CONFIG_ARRAY[$conditionName];
		$paramsData = array();
		if (!empty($conditionConfig['paramsData'])) {
			$paramsData = $conditionConfig['paramsData'];
		}

		$ruleElementData['ruleName'] = $ruleName;
		if ($ruleName == 'value' && !empty($saveData) && !empty($saveData[$conditionDataObj->getTakeValueFrom()])) {
			$ruleName = $saveData[$conditionDataObj->getTakeValueFrom()];
		}
		if ((!isset($paramsData[$ruleName]) && empty($paramsData[$ruleName])) || is_null($paramsData[$ruleName])) {
			$status = true;
		}

		return $status;
	}

	public function targetHeader($targetName = '')
	{
		global $SGPB_DATA_CONFIG_ARRAY;
		$data = $SGPB_DATA_CONFIG_ARRAY[$targetName];
		$columnLabels = $data['columns'];
		$header = '<div class="sg-target-header-wrapper">';

		foreach ($columnLabels as $key => $columnLabel) {
			$header .= '<div class="sg-col-md">'.$columnLabel.'</div>';
		}
		$header .= '</div>';
		return $header;
	}
}

<?php
class formsModelCfs extends modelCfs {
	private $_linksReplacement = array();
	private $_lastForm = null;
	private $_lastSaveContactId = 0;

	public function __construct() {
		$this->_setTbl('forms');
	}
	public function getLastForm() {
		return $this->_lastForm;
	}
	public function getAllForms() {
		return $this->addWhere('original_id != 0 AND ab_id = 0')->getFromTbl();
	}
	public function contact( $d ) {
		$id = isset($d['id']) ? (int) $d['id'] : false;
		if($id) {
			$d = dbCfs::prepareHtmlIn($d);
			$form = $this->getById( $id );
			if($form) {
				if($this->validateFields($d['fields'], $form, $d)) {
					// If subscribe feature is available - it should go before sending any contact data
					if(isset($form['params']['tpl']['enb_subscribe'])
						&& $form['params']['tpl']['enb_subscribe']
						&& frameCfs::_()->getModule('subscribe')
					) {
						$subRes = frameCfs::_()->getModule('subscribe')->getModel()->subscribe($d['fields'], $form);
						if(!$subRes) {
							$this->pushError( frameCfs::_()->getModule('subscribe')->getModel()->getErrors() );
							return false;
						}
					}
					// Publish here
					if(isset($form['params']['tpl']['enb_publish'])
						&& $form['params']['tpl']['enb_publish']
						&& frameCfs::_()->getModule('publish')
					) {
						$pubRes = frameCfs::_()->getModule('publish')->publish($d['fields'], $form);
						if(!$pubRes) {
							$this->pushError( frameCfs::_()->getModule('publish')->getErrors() );
							return false;
						}
					}
					if($this->sendContact($d['fields'], $form)) {
						// Registration
						if(isset($form['params']['tpl']['enb_reg'])
							&& $form['params']['tpl']['enb_reg']
							&& frameCfs::_()->getModule('publish')
						) {
							$regRes = frameCfs::_()->getModule('publish')->registrate($d['fields'], $form);
							if(!$regRes) {
								$this->pushError( frameCfs::_()->getModule('publish')->getErrors() );
								return false;
							}
						}
						dispatcherCfs::doAction('afterFormSuccessSubmit', $d['fields'], $form);
						if(isset($form['params']['tpl']['save_contacts']) && $form['params']['tpl']['save_contacts']) {
							$this->_lastSaveContactId = $this->saveContact($d, $form);
						}
						$this->_lastForm = $form;
						return true;
					}
				}
			} else
				$this->pushError(__('Can\'t find form', CFS_LANG_CODE));
		} else
			$this->pushError(__('Empty Form ID', CFS_LANG_CODE));
		return false;
	}
	public function getLastSavedContactId() {
		return $this->_lastSaveContactId;
	}
	public function saveContact($d, $form) {
		$saveData = array(
			'form_id' => $form['id'],
			'fields' => utilsCfs::encodeArrayTxt($d['fields']),
			'ip' => utilsCfs::getIP(),
			'url' => $d['url'],
		);
		return frameCfs::_()->getTable('contacts')->insert( $saveData );
	}
	public function getContactsForForm( $id ) {
		$contacts = frameCfs::_()->getTable('contacts')->get('*', array('form_id' => $id));
		if(!empty($contacts)) {
			foreach($contacts as $i => $c) {
				$contacts[ $i ] = $this->_contactAfterGetFromTbl( $c );
			}
		}
		return $contacts;
	}
	private function _contactAfterGetFromTbl( $contact ) {
		if(isset($contact['fields']) && !empty($contact['fields'])) {
			$contact['fields'] = utilsCfs::decodeArrayTxt($contact['fields']);
		}
		return $contact;
	}
	public function validateReCaptcha($field, $response) {
		$response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
			'body' => array(
				'secret' => $field['recap-secret'],
				'response' => $response,
				'remoteip' => utilsCfs::getIP(),
			),
		));
		if (!is_wp_error($response)) {
			if(isset($response['body']) && !empty($response['body']) && ($resArr = utilsCfs::jsonDecode($response['body']))) {
				if(isset($resArr['success']) && $resArr['success']) {
					return true;
				} else {
					$errorsDesc = array(
						'missing-input-secret' => __('reCaptcha: The secret parameter is missing.', CFS_LANG_CODE),
						'invalid-input-secret' => __('reCaptcha: The secret parameter is invalid or malformed.', CFS_LANG_CODE),
						'missing-input-response' => __('Please prove that you are not a robot - check reCaptcha.', CFS_LANG_CODE),
						'invalid-input-response' => __('reCaptcha: The response parameter is invalid or malformed.', CFS_LANG_CODE),
					);
					$errors = array();
					foreach($resArr['error-codes'] as $errCode) {
						if(isset($errorsDesc[ $errCode ])) {
							$errors[] = $errorsDesc[ $errCode ];
						}
					}
					$this->pushError(empty($errors) ? $resArr['error-codes'] : $errors);
				}
			} else
				$this->pushError(__('There was a problem with sending request to Google reCaptcha validation server. Please make sure that your server have possibility to send server-server requests. Ask your hosting provider about this.', CFS_LANG_CODE));
		} else
			$this->pushError( $response->get_error_message() );
		return false;
	}
	public function validateFields($fieldsData, $form, $d = array()) {
		if(isset($form['params']['fields']) && !empty($form['params']['fields'])) {
			$errors = array();
			$error = false;
			foreach($form['params']['fields'] as $f) {
				$errorMsg = '';
				$k = isset($f['name']) ? $f['name'] : '';
				if(empty($k)) continue;
				$htmlType = $f['html'];
				$isDate = in_array( $htmlType, array('date', 'month', 'week', 'time') );
				$value = isset($fieldsData[ $k ]) ? $fieldsData[ $k ] : false;
				$label = isset($f['label']) && !empty($f['label']) ? $f['label'] : (isset($f['placeholder']) && !empty($f['placeholder']) ? $f['placeholder'] : '');
				$formInvalidError = isset($form['params']['tpl']['field_error_invalid']) && !empty($form['params']['tpl']['field_error_invalid'])
					? trim($form['params']['tpl']['field_error_invalid'])
					: false;
				if($value) {
					$value = is_array($value) ? array_map('trim', $value) : trim( $value );
				}
				if(!empty($formInvalidError)) {
					$formInvalidError = str_replace('[label]', '%s', $formInvalidError);
				}
				if(isset($f['mandatory']) && $f['mandatory']) {
					// Server-side email validation
					if($htmlType == 'email' && $value && !is_email($value)) {
						$value = false;
					}
					if(empty($value)) {
						switch($f['html']) {
							case 'selectbox':
								$errorMsg = empty($formInvalidError) ? __('Please select %s', CFS_LANG_CODE) : $formInvalidError;
								break;
							case 'checkbox': case 'radiobutton':
								if($value === false) {
									$errorMsg = empty($formInvalidError) ? __('Please check %s', CFS_LANG_CODE) : $formInvalidError;
								}
								break;
							default:
								$errorMsg = empty($formInvalidError) ? __('Please enter %s', CFS_LANG_CODE) : $formInvalidError;
								break;
						}
					}
				}
				if(empty($errorMsg)) {	// Start other validation process
					if(!$isDate && isset($f['min_size']) && !empty($f['min_size']) && ($minSize = (int) $f['min_size'])) {
						$isNumber = $htmlType == 'number' && $value && is_numeric($value);
						if(($isNumber && $value < $minSize)
							|| ((!$isNumber && $value && strlen($value) < $minSize) || !$value)
						) {
							$errorMsg = sprintf(__('Minimum value for %s is %d', CFS_LANG_CODE), '%s', $minSize);
						}
					}
					if(!$isDate
						&& isset($f['max_size'])
						&& !empty($f['max_size'])
						&& ($maxSize = (int) $f['max_size'])
						&& !in_array($htmlType, array('file'))	// We already checked files size when we uploaded it - in add_fieldsModel::uploadFile()
					) {
						$isNumber = $htmlType == 'number' && $value && is_numeric($value);
						if(($isNumber && $value > $maxSize)
							|| (!$isNumber && $value && is_string($value) && strlen($value) > $maxSize)
						) {
							$errorMsg = sprintf(__('Maximum value for %s is %d', CFS_LANG_CODE), '%s', $maxSize);
						}
					}
					if(isset($f['vn_pattern']) && !empty($f['vn_pattern']) && !in_array($htmlType, array('file'))) {
						if(($value && !@preg_match('/'. $f['vn_pattern']. '/', $value)) || !$value) {
							$errorMsg = __('Invalid value for %s', CFS_LANG_CODE);
						}
					}
					if(isset($f['vn_equal']) && !empty($f['vn_equal'])) {
						$eqToValue = isset($fieldsData[ $f['vn_equal'] ]) ? $fieldsData[ $f['vn_equal'] ] : false;
						if($eqToValue !== false && $value !== false && $eqToValue != $value) {
							$eqToLabel = '';
							foreach($form['params']['fields'] as $fEqTo) {
								if($fEqTo['name'] == $f['vn_equal']) {
									$eqToLabel = isset($fEqTo['label']) && !empty($fEqTo['label']) ? $fEqTo['label'] : $fEqTo['placeholder'];
									break;
								}
							}
							$errorMsg = sprintf(__('%s does not match %s', CFS_LANG_CODE), $label, $eqToLabel);
						}
					}
				}
				if(!empty($errorMsg)) {
					$errors[ $k ] = sprintf($errorMsg, $label);
				}
				// Validate reCaptcha
				if($htmlType == 'recaptcha' && !$this->validateReCaptcha( $f, $d['g-recaptcha-response'] )) {
					$error = true;	// Errors was just pushed before, in validateReCaptcha() method
				}
				if(empty($errors[ $k ])) {	// Additional check in pro module
					$fieldTypeData = $this->getModule()->getFieldTypeByCode( $htmlType );
					if($fieldTypeData && isset($fieldTypeData['pro'])) {
						$addFieldsMod = frameCfs::_()->getModule('add_fields');
						if($addFieldsMod) {
							$invalidError = $addFieldsMod->validateField($htmlType, $f, $value, $form);
							if($invalidError) {
								$errors[ $k ] = $invalidError;
							}
						}
					}
				}
			}
			if(!empty($errors) || $error) {
				if(!empty($errors))
					$this->pushError($errors);
				return false;
			}
		}
		return true;
	}
	public function generateSendFormDataFull($fieldsData, $form) {
		$blogName = wp_specialchars_decode(get_bloginfo('name'));
		$siteUrl = get_bloginfo('wpurl');
		$sendFormData = $this->_generateSendFormData( $fieldsData, $form );
		$sendFormDataStr = $this->_generateFormDataStr( $sendFormData, $form );
		$variables = array(
			'sitename' => $blogName,
			'siteurl' => $siteUrl,
		);
		foreach($form['params']['fields'] as $f) {
			$sendName = isset($f['name']) ? $f['name'] : '';
			if(empty($sendName)) continue;
			if(isset($sendFormData[ $sendName ])) {
				$variables[ 'user_'. $sendName ] = $sendFormData[ $sendName ];
			}
		}
		return nl2br(utilsCfs::replaceVariables($sendFormDataStr, array_merge($variables)));
	}
	public function sendContact($fieldsData, $form) {
		if(isset($form['params']['submit']) && !empty($form['params']['submit'])) {
			$blogName = wp_specialchars_decode(get_bloginfo('name'));
			$adminEmail = get_bloginfo('admin_email');
			$siteUrl = get_bloginfo('wpurl');
			$sendFormData = $this->_generateSendFormData( $fieldsData, $form );
			$sendFormDataStr = $this->_generateFormDataStr( $sendFormData, $form );
			$variables = $variablesWithSelectVals = array(
				'sitename' => $blogName,
				'siteurl' => $siteUrl,
			);
			foreach($form['params']['fields'] as $f) {
				$sendName = isset($f['name']) ? $f['name'] : '';
				if(empty($sendName)) continue;
				if(isset($sendFormData[ $sendName ])) {
					switch($f['html']) {
						case 'selectbox':
							// This is requiredfor case when you hav select box with vals = mail1@mail.com, ... and names User Name 1, ...
							// To send to it's values, not to it's names
							$variablesWithSelectVals[ 'user_'. $sendName ] = $fieldsData[ $sendName ];
							break;
						default:
							$variablesWithSelectVals[ 'user_'. $sendName ] = $sendFormData[ $sendName ];
							break;
					}
					$variables[ 'user_'. $sendName ] = $sendFormData[ $sendName ];
				}
			}
			$sendForceTo = dispatcherCfs::applyFilters('sendContactTo', '', $fieldsData, $form);
			foreach($form['params']['submit'] as $s) {
				// We can re-modify this address in other parts of plugin, for example - in conditional logic for now
				$to = empty($sendForceTo) ? trim($s['to']) : $sendForceTo;
				if(empty($to)) continue;	// Any reasn to send to empty email
				$msg = trim($s['msg']);
				$additionalHeaders = array();
				if(isset($s['enb_cc']) && (int) $s['enb_cc'] && isset($s['cc']) && !empty($s['cc'])) {
					$additionalHeaders[] = 'Cc: '. utilsCfs::replaceVariables($s['cc'], $variables);
				}
				if(isset($s['from']) && !empty($s['from'])) {
					$additionalHeaders[] = 'From: '. utilsCfs::replaceVariables($s['from'], $variables);
				}
				if(isset($s['reply']) && !empty($s['reply'])) {
					$additionalHeaders[] = 'Reply-To: '. utilsCfs::replaceVariables($s['reply'], $variables);
				}
				$to = utilsCfs::replaceVariables($to, $variablesWithSelectVals);
				$subject = utilsCfs::replaceVariables($s['subject'], $variables);
				// Let it be only for Message field
				//$msg = nl2br(utilsCfs::replaceVariables($msg, array_merge($variables, array('form_data' => $sendFormDataStr))));
				$sendFormDataStr = nl2br($sendFormDataStr);
				$msg = utilsCfs::replaceVariables($msg, array_merge($variables, array('form_data' => $sendFormDataStr)));
				$msg = str_replace(array('</p><br />', '</p><br/>', '</p><br>'), '</p>', $msg);
				if(!frameCfs::_()->getModule('options')->get('disable_email_html_type')) {
					$lang = function_exists('get_locale') ? get_locale() : false;
					$isRtl = function_exists('is_rtl') ? is_rtl() : false;
					$rtlAttrs = $isRtl ? ' dir="rtl" style="text-align:right; direction:rtl;"' : '';
					$msg = '<html'. ($lang ? ' lang="'. $lang. '"' : ''). $rtlAttrs. '><head><title>'. $subject. '</title></head><body'. $rtlAttrs. '>'. $msg. '</body></html>';
				}
				$sendRes = frameCfs::_()->getModule('mail')->send(
					$to,
					$subject,
					$msg,
					$blogName,
					$adminEmail,
					$blogName,
					$adminEmail,
					$additionalHeaders
				);
				if(!$sendRes) {
					$this->pushError(frameCfs::_()->getModule('mail')->getMailErrors());
					return false;
				}
			}
		}
		return true;
	}
	private function _generateSendFormData( $fieldsData, $form ) {
		$res = array();
		foreach($form['params']['fields'] as $f) {
			$htmlType = $f['html'];
			if(in_array($htmlType, array('submit', 'reset', 'button', 'recaptcha', 'htmldelim'))) continue;
			$sendName = isset($f['name']) ? $f['name'] : '';
			if(empty($sendName)) continue;
			$sendValue = '';
			$value = isset($fieldsData[$sendName]) ? $fieldsData[$sendName] : false;
			$fieldTypeData = $this->getModule()->getFieldTypeByCode( $htmlType );
			if($fieldTypeData && isset($fieldTypeData['pro'])) {
				$addFieldsMod = frameCfs::_()->getModule('add_fields');
				if(!$addFieldsMod) continue;

				$sendValue = $addFieldsMod->generateFormData($htmlType, $f, $value, $form, $fieldsData);
				//var_dump($f, $sendValue);
			} elseif(in_array($htmlType, array('checkbox', 'radiobutton'))) {
				if($value === false) {
					$sendValue = __('No', CFS_LANG_CODE);
				} else {
					$sendValue = empty($value) ? __('Yes', CFS_LANG_CODE) : $value;
				}
			} elseif(in_array($htmlType, array('checkboxlist', 'selectlist'))) {
				if($value) {
					$sendValue = array();
					$options = dbCfs::prepareHtmlIn( $f['options'] );
					foreach($options as $fOpt) {
						if(in_array($fOpt['name'], $value)) {
							$sendValue[] = $fOpt['label'];
						}
					}
				}
			} elseif(in_array($htmlType, array('countryListMultiple'))) {
				if($value) {
					$sendValue = array();
					$options = fieldAdapterCfs::getCountries();
					foreach($value as $fVal) {
						$sendValue[] = $options[ $fVal ];
					}
				}
			} elseif(in_array($htmlType, array('countryList'))) {
				if($value) {
					$options = fieldAdapterCfs::getCountries();
					$sendValue = $options[ $value ];
				}
			} elseif(in_array($htmlType, array('radiobuttons', 'selectbox'))) {
				if($value) {
					$options = dbCfs::prepareHtmlIn( $f['options'] );
					foreach($options as $fOpt) {
						if($fOpt['name'] == $value) {
							$sendValue = $fOpt['label'];
							break;
						}
					}
				}
			} else {
				$sendValue = $value;
			}
			if($sendValue && is_array($sendValue)) {
				$sendValue = implode(', ', $sendValue);
			}
			$res[ $sendName ] = $sendValue;
		}
		return $res;
	}
	private function _generateFormDataStr( $sendFormData, $form ) {
		$res = array();
		$dsblSendLabels = isset($form['params']['tpl']['dsbl_send_labels']) && $form['params']['tpl']['dsbl_send_labels'];
		$dsblHtmlEmail = frameCfs::_()->getModule('options')->get('disable_email_html_type');
		$maxCols = 1;
		$emailAsnForm = isset($form['params']['tpl']['email_form_data_as_tbl']) && $form['params']['tpl']['email_form_data_as_tbl'];
		$resNamed = array();
		if( !$dsblHtmlEmail && $emailAsnForm ) {
			$totalMaxCols = 12;	// From Bootstrap
			// Check if there are more then one column data
			foreach($form['params']['fields'] as $f) {
				if(isset($f['bs_class_id']) && $f['bs_class_id'] != $totalMaxCols) {
					$currMaxCols = $totalMaxCols / (int) $f['bs_class_id'];
					if( $currMaxCols > $maxCols ) {
						$maxCols = $currMaxCols;
					}
				}
			}
		}
		foreach($form['params']['fields'] as $f) {
			$sendName = isset($f['name']) ? $f['name'] : '';
			if(empty($sendName)) continue;
			if(isset($sendFormData[ $sendName ])) {
				$sendLabel = empty($f['label']) ? $f['placeholder'] : $f['label'];
				$fieldRow = '';
				if(!$dsblSendLabels) {
					$fieldRow .= '<b>'. $sendLabel. '</b>: ';
				}
				$fieldRow .= $sendFormData[ $sendName ];
				$res[] = $resNamed[ $sendName ] = $fieldRow;
			}
		}
		if( !$dsblHtmlEmail && $maxCols > 1 && $emailAsnForm ) {
			$resHtml = '<table cellspacing="0" cellpadding="0">';
			$rows = array();
			foreach($form['params']['fields'] as $f) {
				$added = false;
				$name = isset($f['name']) ? $f['name'] : '';
				if(empty($name)) continue;
				if(isset( $resNamed[ $name ] )) {
					$bsClassId = isset($f['bs_class_id']) && !empty($f['bs_class_id']) ? (int) $f['bs_class_id'] : 12;
					if($bsClassId < 12) {	// Try to add it to prev. row
						$prevRowI = count( $rows ) - 1;
						if($prevRowI >= 0) {
							if($rows[ $prevRowI ]['id'] < 12) {
								$rows[ $prevRowI ]['id'] += $bsClassId;
								$rows[ $prevRowI ]['cols'][] = $resNamed[ $name ];
								$added = true;
							}
						}
					}

					if(!$added) {	// New row
						$rows[] = array('id' => $bsClassId, 'cols' => array( $resNamed[ $name ] ));
					}
				}
			}
			foreach($rows as $r) {
				$resHtml .= '<tr><td width="100%"><table width="100%" cellspacing="0" cellpadding="0"><tr>';
				$colsWidth = 100 / count($r['cols']);
				foreach($r['cols'] as $col) {
					$resHtml .= '<td width="'. $colsWidth. '%">'. $col. '</td>';
				}
				$resHtml .= '</tr></table></td></tr>';
			}
			$resHtml .= '</table>';
			return $resHtml;
		}
		$res = dispatcherCfs::applyFilters('formRowsToSend', $res, $form);
		return implode(CFS_EOL, $res);
	}
	/**
	 * Exclude some data from list - to avoid memory overload
	 */
	public function getSimpleList($where = array(), $params = array()) {
		if($where)
			$this->setWhere ($where);
		return $this->setSelectFields('id, label, original_id, img_preview')->getFromTbl( $params );
	}
	protected function _prepareParamsAfterDb($params) {
		if(is_array($params)) {
			foreach($params as $k => $v) {
				$params[ $k ] = $this->_prepareParamsAfterDb( $v );
			}
		} else
			$params = stripslashes ($params);
		return $params;
	}
	private function _getLinksReplacement() {
		if(empty($this->_linksReplacement)) {
			$this->_linksReplacement = array(
				'modUrl' => array('url' => $this->getModule()->getModPath(), 'key' => 'CFS_MOD_URL'),
				'siteUrl' => array('url' => CFS_SITE_URL, 'key' => 'CFS_SITE_URL'),
				'assetsUrl' => array('url' => $this->getModule()->getAssetsUrl(), 'key' => 'CFS_ASSETS_URL'),
			);
		}
		return $this->_linksReplacement;
	}
	protected function _beforeDbReplace($data) {
		static $replaceFrom, $replaceTo;
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[ $k ] = $this->_beforeDbReplace($v);
			}
		} else {
			if(!$replaceFrom) {
				$this->_getLinksReplacement();
				foreach($this->_linksReplacement as $k => $rData) {
					$replaceFrom[] = $rData['url'];
					$replaceTo[] = '['. $rData['key']. ']';
				}
			}
			$data = str_replace($replaceFrom, $replaceTo, $data);
		}
		return $data;
	}
	protected function _afterDbReplace($data) {
		static $replaceFrom, $replaceTo;
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[ $k ] = $this->_afterDbReplace($v);
			}
		} else {
			if(!$replaceFrom) {
				$this->_getLinksReplacement();
				foreach($this->_linksReplacement as $k => $rData) {
					$replaceFrom[] = '['. $rData['key']. ']';
					$replaceTo[] = $rData['url'];
				}
			}
			$data = str_replace($replaceFrom, $replaceTo, $data);
		}
		return $data;
	}
	protected function _afterGetFromTbl($row) {
		if(isset($row['params'])) {
			$row['params'] = $this->_prepareParamsAfterDb( utilsCfs::decodeArrayTxt($row['params']) );
			if(isset($row['params']['tpl']['test_email']))
				$row['params']['tpl']['test_email'] = utilsCfs::toAdminEmail ($row['params']['tpl']['test_email']);
			if(isset($row['params']['submit']) && !empty($row['params']['submit'])) {
				foreach($row['params']['submit'] as $i => $sub) {
					$row['params']['submit'][ $i ]['to'] = isset($row['params']['submit'][ $i ]['to']) ? utilsCfs::toAdminEmail ($row['params']['submit'][ $i ]['to']) : '';
					$row['params']['submit'][ $i ]['from'] = isset($row['params']['submit'][ $i ]['from']) ? utilsCfs::toAdminEmail ($row['params']['submit'][ $i ]['from']) : '';
				}
			}
		}
		if(empty($row['img_preview'])) {
			$row['img_preview'] = str_replace(' ', '-', strtolower( trim($row['label']) )). '.jpg';
		}
		$row['img_preview_url'] = uriCfs::_($this->getModule()->getAssetsUrl(). 'img/preview/'. $row['img_preview']);
		$row['view_id'] = $row['id']. '_'. mt_rand(1, 999999);
		$row['view_html_id'] = 'cspFormShell_'. $row['view_id'];
		$row = $this->_afterDbReplace($row);
		return $row;
	}
	protected function _dataSave($data, $update = false) {
		$data = $this->_beforeDbReplace($data);
		if(isset($data['params']))
			$data['params'] = utilsCfs::encodeArrayTxt( $data['params'] );
		return $data;
	}
	protected function _escTplData($data) {
		$data['label'] = dbCfs::prepareHtmlIn($data['label']);
		$data['html'] = dbCfs::escape($data['html']);
		$data['css'] = dbCfs::escape($data['css']);
		return $data;
	}
	public function createFromTpl($d = array()) {
		$d['label'] = isset($d['label']) ? trim($d['label']) : '';
		$d['original_id'] = isset($d['original_id']) ? (int) $d['original_id'] : 0;
		if(!empty($d['label'])) {
			if(!empty($d['original_id'])) {
				$original = $this->getById($d['original_id']);
				frameCfs::_()->getModule('supsystic_promo')->getModel()->saveUsageStat('create_from_tpl.'. strtolower(str_replace(' ', '-', $original['label'])));
				unset($original['id']);
				$original['label'] = $d['label'];
				$original['original_id'] = $d['original_id'];
				return $this->insertFromOriginal( $original );
			} else
				$this->pushError (__('Please select Form template from list below', CFS_LANG_CODE));
		} else
			$this->pushError (__('Please enter Name', CFS_LANG_CODE), 'label');
		return false;
	}
	public function insertFromOriginal($original) {
		// Clear statistics data for new form
		$original['views'] = $original['unique_views'] = $original['actions'] = 0;
		$original = $this->_escTplData( $original );
		return $this->insert( $original );
	}
	public function remove($id) {
		$id = (int) $id;
		if($id) {
			if(frameCfs::_()->getTable( $this->_tbl )->delete(array('id' => $id))) {
				return true;
			} else
				$this->pushError (__('Database error detected', CFS_LANG_CODE));
		} else
			$this->pushError(__('Invalid ID', CFS_LANG_CODE));
		return false;
	}
	/**
	 * Do not remove pre-set templates
	 */
	public function clear() {
		if(frameCfs::_()->getTable( $this->_tbl )->delete(array('additionalCondition' => 'original_id != 0'))) {
			return true;
		} else
			$this->pushError (__('Database error detected', CFS_LANG_CODE));
		return false;
	}
	public function save($d = array()) {
		$forms = $this->getById($d['id']);
		if(isset($d['params']['opts_attrs']['txt_block_number']) && !empty($d['params']['opts_attrs']['txt_block_number'])) {
			for($i = 0; $i < (int) $d['params']['opts_attrs']['txt_block_number']; $i++) {
				$sendValKey = 'params_tpl_txt_val_'. $i;
				if(isset($d[ $sendValKey ])) {
					$d['params']['tpl']['txt_'. $i] = urldecode( $d[ $sendValKey ] );
				}
			}
		}
		if(isset($d['params']['tpl']['use_sss_prj_id'])) {
			$oldSssProjId = isset($forms['params']['tpl']['use_sss_prj_id']) ? (int) $forms['params']['tpl']['use_sss_prj_id'] : 0;
			$newSssProjId = (int) $d['params']['tpl']['use_sss_prj_id'];
			if($oldSssProjId != $newSssProjId) {
				if(!$this->_updateSocSharingProject( $newSssProjId, $d['id'])) {	// For just changed Proj ID - set it, if it was set to 0 - clear prev. selected
					return false;	// Something wrong go there - let's try to detect thos issues for now
				}
			}
		}
		if(isset($d['params']['enableForMembership'])) {
			$membershipModel = frameCfs::_()->getModule('membership')->getModel();
			if($membershipModel) {
				$membershipRes = $membershipModel->updateRow(array('form_id' => $d['id'], 'allow_use' => $d['params']['enableForMembership'],));
			}
		}
		if(isset($d['css']) && empty($d['css'])) {
			unset($d['css']);
		}
		if(isset($d['html']) && empty($d['html'])) {
			unset($d['html']);
		}
		$res = $this->updateById($d);
		if($res) {
			dispatcherCfs::doAction('afterFormUpdate', $d);
		}
		return $res;
	}
	public function updateParamsById($d) {
		foreach($d as $k => $v) {
			if(!in_array($k, array('id', 'params')))
				unset($d[ $k ]);
		}
		return $this->updateById($d);
	}
	public function changeTpl($d = array()) {
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		$d['new_tpl_id'] = isset($d['new_tpl_id']) ? (int) $d['new_tpl_id'] : 0;
		if($d['id'] && $d['new_tpl_id']) {
			$currentForm = $this->getById( $d['id'] );
			$newTpl = $this->getById( $d['new_tpl_id'] );
			$originalForm = $this->getById( $currentForm['original_id'] );
			$diffFromOriginal = $this->getDifferences($currentForm, $originalForm);
			if(!empty($diffFromOriginal)) {
				if(isset($newTpl['params'])) {
					$keysForMove = array('params.fields', 'params.submit', 'params.tpl.label', 'params.tpl.anim_key', 'params.tpl.enb_foot_note', 'params.tpl.foot_note',
						'params.tpl.enb_sm',
						'params.tpl.enb_subscribe');
					foreach($diffFromOriginal as $k) {
						if(in_array($k, $keysForMove)
							|| strpos($k, 'params.tpl.enb_sm_') === 0
							|| strpos($k, 'params.tpl.sm_') === 0
							|| strpos($k, 'params.tpl.enb_sub_') === 0
							|| strpos($k, 'params.tpl.sub_') === 0
							|| strpos($k, 'params.tpl.enb_txt_') === 0
							|| strpos($k, 'params.tpl.txt_') === 0
						) {
							$this->_assignKeyArr($currentForm, $newTpl, $k);
						}
					}
				}
			}
			// Save main settings - as they should not influence for display settings
			$this->_assignKeyArr($currentForm, $newTpl, 'params.main');
			frameCfs::_()->getModule('supsystic_promo')->getModel()->saveUsageStat('change_to_tpl.'. strtolower(str_replace(' ', '-', $newTpl['label'])));
			$newTpl['original_id'] = $newTpl['id'];	// It will be our new original
			$newTpl['id'] = $currentForm['id'];
			$newTpl['label'] = $currentForm['label'];
			$newTpl = dispatcherCfs::applyFilters('formsChangeTpl', $newTpl, $currentForm);
			$newTpl = $this->_escTplData( $newTpl );
			return $this->update( $newTpl, array('id' => $newTpl['id']) );
		} else
			$this->pushError (__('Provided data was corrupted', CFS_LANG_CODE));
		return false;
	}
	private function _assignKeyArr($from, &$to, $key) {
		$subKeys = explode('.', $key);
		// Yeah, hardcode, I know.............
		switch(count($subKeys)) {
			case 4:
				if(isset( $from[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ][ $subKeys[3] ] ))
					$to[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ][ $subKeys[3] ] = $from[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ][ $subKeys[3] ];
				else
					unset($to[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ][ $subKeys[3] ]);
				break;
			case 3:
				if(isset( $from[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ] ))
					$to[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ] = $from[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ];
				else
					unset($to[ $subKeys[0] ][ $subKeys[1] ][ $subKeys[2] ]);
				break;
			case 2:
				if(isset( $from[ $subKeys[0] ][ $subKeys[1] ] ))
					$to[ $subKeys[0] ][ $subKeys[1] ] = $from[ $subKeys[0] ][ $subKeys[1] ];
				else
					unset($to[ $subKeys[0] ][ $subKeys[1] ]);
				break;
			case 1:
				if(isset( $from[ $subKeys[0] ] ))
					$to[ $subKeys[0] ] = $from[ $subKeys[0] ];
				else
					unset( $to[ $subKeys[0] ] );
				break;
		}
	}
	public function getDifferences($forms, $original) {
		$difsFromOriginal = $this->_computeDifferences($forms, $original);
		$difsOfOriginal = $this->_computeDifferences($original, $forms);	// Some options may be present in original, but not present in current forms
		if(!empty($difsFromOriginal) && empty($difsOfOriginal)) {
			return $difsFromOriginal;
		} elseif(empty($difsFromOriginal) && !empty($difsOfOriginal)) {
			return $difsOfOriginal;
		} else {
			$difs = array_merge($difsFromOriginal, $difsOfOriginal);
			return array_unique($difs);
		}
	}
	private function _computeDifferences($forms, $original, $key = '', $keysImplode = array()) {
		$difs = array();
		$checkAsArray = false;
		if(!empty($key)) {
			$checkAsArrayKeys = array('params.fields', 'params.submit');
			$fullKey = $this->_prepareDiffKeys($key, $keysImplode);
			$checkAsArray = in_array( $fullKey, $checkAsArrayKeys );
		}
		if(is_array($forms) && !$checkAsArray) {
			$excludeKey = array('id', 'label', 'active', 'original_id', 'img_preview',
				'date_created', 'view_id', 'view_html_id', 'actions', 'unique_views', 'views', 'img_preview_url', 'show_on', 'show_to', 'show_pages');
			if(!empty($key))
				$keysImplode[] = $key;
			foreach($forms as $k => $v) {
				if(in_array($k, $excludeKey) && empty($key)) continue;
				if(!isset($original[ $k ])) {
					$difs[] = $this->_prepareDiffKeys($k, $keysImplode);
					continue;
				}
				$currDifs = $this->_computeDifferences($forms[ $k ], $original[ $k ], $k, $keysImplode);
				if(!empty($currDifs)) {
					$difs = array_merge($difs, $currDifs);
				}
			}
		} else {
			if($forms != $original) {
				$difs[] = $this->_prepareDiffKeys($key, $keysImplode);
			}
		}
		return $difs;
	}
	private function _prepareDiffKeys($key, $keysImplode) {
		return empty($keysImplode) ? $key : implode('.', $keysImplode). '.'. $key;
	}
	public function clearCachedStats($id) {
		$tbl = $this->getTbl();
		$id = (int) $id;
		return dbCfs::query("UPDATE @__$tbl SET `views` = 0, `unique_views` = 0, `actions` = 0 WHERE `id` = $id");
	}
	public function addCachedStat($id, $statColumn) {
		$tbl = $this->getTbl();
		$id = (int) $id;
		return dbCfs::query("UPDATE @__$tbl SET `$statColumn` = `$statColumn` + 1 WHERE `id` = $id");
	}
	public function addViewed($id) {
		return $this->addCachedStat($id, 'views');
	}
	public function addUniqueViewed($id) {
		return $this->addCachedStat($id, 'unique_views');
	}
	public function addActionDone($id) {
		return $this->addCachedStat($id, 'actions');
	}
	public function saveAsCopy($d = array()) {
		$d['copy_label'] = isset($d['copy_label']) ? trim($d['copy_label']) : '';
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		if(!empty($d['copy_label'])) {
			if(!empty($d['id'])) {
				$original = $this->getById($d['id']);
				unset($original['id']);
				unset($original['date_created']);
				$original['label'] = $d['copy_label'];
				$original['views'] = $original['unique_views'] = $original['actions'] = 0;
				//frameCfs::_()->getModule('supsystic_promo')->getModel()->saveUsageStat('save_as_copy');
				return $this->insertFromOriginal( $original );
			} else
				$this->pushError (__('Invalid ID', CFS_LANG_CODE));
		} else
			$this->pushError (__('Please enter Name', CFS_LANG_CODE), 'copy_label');
		return false;
	}
	public function switchActive($d = array()) {
		$d['active'] = isset($d['active']) ? (int)$d['active'] : 0;
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		if(!empty($d['id'])) {
			$tbl = $this->getTbl();
			return frameCfs::_()->getTable($tbl)->update(array(
				'active' => $d['active'],
			), array(
				'id' => $d['id'],
			));
		} else
			$this->pushError (__('Invalid ID', CFS_LANG_CODE));
		return false;
	}
	public function updateLabel($d = array()) {
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		if(!empty($d['id'])) {
			$d['label'] = isset($d['label']) ? trim($d['label']) : '';
			if(!empty($d['label'])) {
				return $this->updateById(array(
					'label' => $d['label']
				), $d['id']);
			} else
				$this->pushError (__('Name can not be empty', CFS_LANG_CODE));
		} else
			$this->pushError (__('Invalid ID', CFS_LANG_CODE));
		return false;
	}
	public function setSimpleGetFields() {
		$this->setSelectFields('id, label, active, views, unique_views, actions, date_created, sort_order');
		return parent::setSimpleGetFields();
	}
	/**
	 * Names of Background for each Form template - to not display standard "Background 1" etc. labels there
	 */
	public function getBgNames() {
		return array(
			'wefj2' => array( // Base Contact
				__('Form background', CFS_LANG_CODE),
				__('Inputs and Buttons background', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'foe42k' => array( // Neon
				__('Form background', CFS_LANG_CODE),
				__('Inputs and Buttons shadow', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'uwi23o' => array( // Intransigent
				__('Form background', CFS_LANG_CODE),
				__('Inputs and Buttons background', CFS_LANG_CODE),
			),
			'vbn23a' => array( // Simple White
				__('Form background', CFS_LANG_CODE),
				__('Inputs and Buttons background', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'bso15i' => array( // Time for tea
				__('Form background', CFS_LANG_CODE),
				__('Form background blackout', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'bjl17b' =>array( // Opacity Grey
				__('Form background', CFS_LANG_CODE),
				__('Inputs and Buttons background', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'bjl17a' => array( // Spearmint
				__('Form background', CFS_LANG_CODE),
				__('Inputs background', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Reset buttons style', CFS_LANG_CODE),
			),
			'bjl17c' => array( // Support Service
				__('Inputs background', CFS_LANG_CODE),
				__('Text color', CFS_LANG_CODE),
				__('Submit buttons style', CFS_LANG_CODE),
				__('Form background', CFS_LANG_CODE),
			),
			'ajl17d' => array( // Ho Ho Ho
				__('Form background', CFS_LANG_CODE),
				__('Inputs background', CFS_LANG_CODE),
				__('Submit button background', CFS_LANG_CODE),
				__('Reset button background', CFS_LANG_CODE),
				__('Top image background', CFS_LANG_CODE),
				__('Top overlap background', CFS_LANG_CODE),
				__('Image 1', CFS_LANG_CODE),
				__('Image 2', CFS_LANG_CODE),
			),
			'cbrl7b' => array( // Merry Christmas
				__('Form background', CFS_LANG_CODE),
				__('Inputs background', CFS_LANG_CODE),
				__('Submit button background', CFS_LANG_CODE),
				__('Reset button background', CFS_LANG_CODE),
				__('Image 1', CFS_LANG_CODE),
				__('Image 2', CFS_LANG_CODE),
			),
		);
	}
	public function getBgNamesForForm( $uniqueId ) {
		$bgNames = $this->getBgNames();
		return isset($bgNames[ $uniqueId ]) ? $bgNames[ $uniqueId ] : false;
	}
}

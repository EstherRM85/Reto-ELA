<?php
class formsControllerCfs extends controllerCfs {
	private $_prevFormId = 0;
	private $_forceModelName = '';
	
	public function createFromTpl() {
		$res = new responseCfs();
		if(($id = $this->getModel()->createFromTpl(reqCfs::get('post'))) != false) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	protected function _prepareListForTbl($data) {
		if(!empty($data)) {
			if($this->_forceModelName == 'contacts') {
				$fieldsHtml = reqCfs::getVar('contact_fields_to_html');
				foreach($data as $i => $v) {
					$viewLinkSet = false;
					if(isset($data[ $i ]['fields']) && !empty($data[ $i ]['fields'])) {
						foreach($data[ $i ]['fields'] as $fK => $fV) {
							$fieldVal = $fV;
							if(!empty($fieldVal) && is_array($fieldVal)) {
								$fieldVal = implode(', ', $fieldVal);
							}
							if(!empty($fieldVal) && !$viewLinkSet) {
								$data[ $i ][ 'user_field_'. $fK ] = '<a href="'. $data[ $i ]['id']. '" class="cfsFormContactPrevLnk">'. $fieldVal. '&nbsp;<i class="fa fa-search"></i></a>';
								$viewLinkSet = true;
							} else {
								$html = !empty($fieldsHtml) && isset($fieldsHtml[ $fK ]) ? $fieldsHtml[ $fK ] : false;
								switch($html) {
									case 'file':
										if(frameCfs::_()->getModule('add_fields')) {
											$idPubHash = explode('|', $fieldVal);
											$id = is_numeric($idPubHash[ 0 ]) ? (int) $idPubHash[ 0 ] : false;
											if($id) {
												$file = frameCfs::_()->getTable('files')->get('*', array('id' => $id), '', 'row');
												if(!empty($file)) {
													$data[ $i ][ 'user_field_'. $fK ] = '<a href="'. frameCfs::_()->getModule('add_fields')->getFileUrl($file).'"><i class="fa fa-cloud-download"></i>&nbsp;'. __('Download', CFS_LANG_CODE). '</a>';
												}
											}
										}
										break;
									default:
										$data[ $i ][ 'user_field_'. $fK ] = $fieldVal;
										break;
								}
								
							}
						}
						unset( $data[ $i ]['fields'] );
					}
				}
			} else {
				foreach($data as $i => $v) {
					$data[ $i ]['label'] = '<a class="" href="'. $this->getModule()->getEditLink($data[ $i ]['id']). '">'. $data[ $i ]['label']. '&nbsp;<i class="fa fa-fw fa-pencil" style="margin-top: 2px;"></i></a>';
					$conversion = 0;
					if(!empty($data[ $i ]['unique_views']) && !empty($data[ $i ]['actions'])) {
						$conversion = number_format( ((int) $data[ $i ]['actions'] / (int) $data[ $i ]['unique_views']), 3);
					}
					$data[ $i ]['conversion'] = $conversion;
					$data[ $i ]['active'] = $data[ $i ]['active'] ? '<span class="alert alert-success">'. __('Yes', CFS_LANG_CODE). '</span>' : '<span class="alert alert-danger">'. __('No', CFS_LANG_CODE). '</span>';
				}
			}
		}
		return $data;
	}
	protected function _prepareTextLikeSearch($val) {
		if($this->_forceModelName == 'contacts') {
			$query = '(ip LIKE "%'. $val. '%" OR url LIKE "%'. $val. '%"';
		} else {
			$query = '(label LIKE "%'. $val. '%"';
		}
		if(is_numeric($val)) {
			$query .= ' OR id LIKE "%'. (int) $val. '%"';
		}
		$query .= ')';
		return $query;
	}
	protected function _prepareModelBeforeListSelect($model) {
		if($this->_forceModelName == 'contacts') {
			
		} else {
			$where = 'original_id != 0';
			$abTestCondAdded = false;
			if(frameCfs::_()->getModule('ab_testing')) {
				$abBaseId = frameCfs::_()->getModule('ab_testing')->getListForBaseId();
				if(!empty($abBaseId)) {
					$where .= ' AND ab_id = '. $abBaseId;
					$abTestCondAdded = true;
				}
			}
			if(!$abTestCondAdded) {
				$where .= ' AND ab_id = 0';
			}
			$model->addWhere( $where );
			dispatcherCfs::doAction('formsModelBeforeGetList', $model);
		}
		return $model;
	}
	protected function _prepareSortOrder($sortOrder) {
		if($sortOrder == 'conversion') {
			$sortOrder = '(actions / unique_views)';	// Conversion in real-time calculation
		}
		return $sortOrder;
	}
	public function remove() {
		$res = new responseCfs();
		if($this->getModel()->remove(reqCfs::getVar('id', 'post'))) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function save() {
		$res = new responseCfs();
		if($this->getModel()->save( reqCfs::get('post') )) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function getPreviewHtml() {
		$this->_prevFormId = (int) reqCfs::getVar('id', 'get');
		$this->outPreviewHtml();
		//add_action('init', array($this, 'outPreviewHtml'));
	}
	public function outPreviewHtml() {
		if($this->_prevFormId) {
			$form = $this->getModel()->getById( $this->_prevFormId );
			$formContent = $this->getView()->generateHtml( $form );
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html dir="'. (function_exists('is_rtl') && is_rtl() ? 'rtl' : 'ltr'). '"><head>'
			. '<meta content="'. get_option('html_type'). '; charset='. get_option('blog_charset'). '" http-equiv="Content-Type">'
			. '<script type="text/javascript" src="'. includes_url('js/jquery/jquery.js'). '"></script>'
			//. '<link rel="stylesheet" href="'. get_stylesheet_uri(). '" type="text/css" media="all" />'
			. $this->_generateRecaptchaAssetsForPrev( $form )
			. $this->_generateGoogleMapsAssetsForPrev( $form )
			. $this->getModule()->getAssetsforPrevStr($form)
			. '<style type="text/css"> 
				html { overflow: visible !important; } 
				.cfsFormShell {
					display: block;
					position: static;
				}
				</style>'
			. '</head>';
			echo '<body id="cfsFormPreviewBody">';
			echo $formContent;
			echo '<body></html>';
		}
		exit();
	}
	private function _generateRecaptchaAssetsForPrev( $form ) {
		// check if there are recaptcha field in fields list
		if(!empty($form['params']['fields'])) {
			foreach($form['params']['fields'] as $f) {
				if($f['html'] == 'recaptcha') {
					return '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
				}
			}
		}
		return '';
	}
	private function _generateGoogleMapsAssetsForPrev( $form ) {
		// check if there are google maps field in fields list
		$res = '';
		if(!empty($form['params']['fields'])) {
			$setAssets = array();
			foreach($form['params']['fields'] as $f) {
				if($f['html'] == 'googlemap') {
					if(class_exists('frameGmp') && defined('GMP_VERSION_PLUGIN')) {
						$scripts = frameGmp::_()->getScripts();
						if(!empty($scripts)) {
							frameGmp::_()->getModule('gmap')->getView()->addMapDataToJs();
							$res .= $this->_connectMainJsLibsForPrev();
							$scVars = frameGmp::_()->getJSVars();
							foreach($scripts as $s) {
								if(isset($s['src']) && !empty($s['src']) && !in_array($s['handle'], $setAssets)) {
									if($scVars && isset($scVars[ $s['handle'] ]) && !empty($scVars[ $s['handle'] ])) {
										$res .= "<script type='text/javascript'>"; // CDATA and type='text/javascript' is not needed for HTML 5
										$res .= "/* <![CDATA[ */";
										foreach($scVars[ $s['handle'] ] as $name => $value) {
											if($name == 'dataNoJson' && !is_array($value)) {
												$res .= $value;
											} else {
												$res .= "var $name = ". utilsGmp::jsonEncode($value). ";";
											}
										}
										$res .= "/* ]]> */";
										$res .= "</script>";
									}
									$res .= '<script type="text/javascript" src="'. $s['src']. '"></script>';
									$setAssets[] = $s['handle'];
								}
							}
						}
						$styles = frameGmp::_()->getStyles();
						if(!empty($styles)) {
							foreach($styles as $s) {
								if(isset($s['src']) && !empty($s['src']) && !in_array($s['handle'], $setAssets)) {
									$res .= '<link rel="stylesheet" type="text/css" href="'. $s['src']. '" />';
									$setAssets[] = $s['handle'];
								}
							}
						}
					}
				}
			}
		}
		return $res;
	}
	public function changeTpl() {
		$res = new responseCfs();
		if($this->getModel()->changeTpl(reqCfs::get('post'))) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
			$id = (int) reqCfs::getVar('id', 'post');
			// Redirect after change template - to Design tab, as change tpl btn is located there - so, user was at this tab before changing tpl
			$res->addData('edit_link', $this->getModule()->getEditLink( $id, 'cfsFormTpl' ));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function exportForDb() {
		$eol = "\r\n";
		
		$forPro = (int) reqCfs::getVar('for_pro', 'get');
		$tblsCols = array(
			'@__forms' => array('unique_id','label','active','original_id','params','html','css','sort_order','date_created','is_pro','img_preview'),
		);
		if($forPro) {
			echo 'db_install=>';
			foreach($tblsCols as $tbl => $cols) {
				echo $this->_makeExportQueriesLogicForPro($tbl, $cols);
			}
		} else {
			foreach($tblsCols as $tbl => $cols) {
				echo "if(function_exists('base64_encode')) {". $eol;
				echo $this->_makeExportQueriesLogic($tbl, $cols);
				echo "} else {	//--not-base64--". $eol;
				echo $this->_makeExportQueriesLogic($tbl, $cols, true);
				echo "}";
			}
		}
		exit();
	}
	private function _makeExportQueriesLogicForPro($table, $cols) {
		global $wpdb;
		$octoList = $this->_getExportData($table, $cols, true);
		$res = array();

		foreach($octoList as $octo) {
			$uId = '';
			$rowData = array();
			foreach($octo as $k => $v) {
				if(!in_array($k, $cols)) continue;
				$val = $wpdb->_real_escape($v);
				if($k == 'unique_id') $uId = $val;
				$rowData[ $k ] = $val;

			}
			$res[ $uId ] = $rowData;
		}
		echo str_replace(array('@__'), '', $table). '|'. base64_encode( utilsCfs::serialize($res) );
	}
	private function _getExportData($table, $cols, $forPro = false) {
		return dbCfs::get('SELECT '. implode(',', $cols). ' FROM '. $table. ' WHERE original_id = 0 and is_pro = '. ($forPro ? '1' : '0'));;
	}
	/**
	 * new usage
	 */
	private function _makeExportQueriesLogic($table, $cols, $forceOrd = false) {
		global $wpdb;
		$eol = "\r\n";
		$tab = "\t";
		$octoList = $this->_getExportData($table, $cols);
		$valuesArr = array();
		$allKeys = array();
		$uidIndx = 0;
		$i = 0;
		foreach($octoList as $octo) {
			$arr = array();
			$addToKeys = empty($allKeys);
			$i = 0;
			foreach($octo as $k => $v) {
				$value = $v;
				if(!in_array($k, $cols)) continue;
				if($addToKeys) {
					$allKeys[] = $k;
					if($k == 'unique_id') {
						$uidIndx = $i;
					}
				}
				if($k == 'params' && $forceOrd) {
					$value = utilsCfs::encodeArrayTxt( utilsCfs::decodeArrayTxt( $value ), true );
				}
				$arr[] = ''. $wpdb->_real_escape($value). '';
				$i++;
			}
			$valuesArr[] = $arr;
		}
		$out = '';
		//$out .= "\$cols = array('". implode("','", $allKeys). "');". $eol;
		$out .= "\$data = array(". $eol;
		foreach($valuesArr as $row) {
			$uid = str_replace(array('"'), '', $row[ $uidIndx ]);
			$installData = array();
			foreach($row as $i => $v) {
				$installData[] = "'{$allKeys[ $i ]}' => '{$v}'";
			}
			$out .= $tab. "'$uid' => array(". implode(',', $installData). "),". $eol;
		}
		$out .= ");". $eol;
		return $out;
	}
	public function saveAsCopy() {
		$res = new responseCfs();
		if(($id = $this->getModel()->saveAsCopy(reqCfs::get('post'))) != false) {
			$res->addMessage(__('Done, redirecting to new Form...', CFS_LANG_CODE));
			$res->addData('edit_link', $this->getModule()->getEditLink( $id ));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function switchActive() {
		$res = new responseCfs();
		if($this->getModel()->switchActive(reqCfs::get('post'))) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function updateLabel() {
		$res = new responseCfs();
		if($this->getModel()->updateLabel(reqCfs::get('post'))) {
			$res->addMessage(__('Done', CFS_LANG_CODE));
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function updateNonce() {
		$res = new responseCfs();
		$getFor = reqCfs::getVar('get_for', 'post');
		$id = (int) reqCfs::getVar('id', 'post');
		$updateFor = array();
		if(!empty($getFor) && !empty($id)) {
			$generateKeys = array(
				'cfsContactForm' => 'contact-'. $id,
			);
			foreach($getFor as $gf) {
				if(isset($generateKeys[ $gf ])) {
					$updateFor[ $gf ] = wp_create_nonce( $generateKeys[ $gf ] );
				}
			}
		}
		if(!empty($updateFor)) {
			$res->addData('update_for', $updateFor);
		}
		return $res->ajaxExec();
	}
	public function contact() {
		$res = new responseCfs();
		$data = reqCfs::get('post');
		$id = isset($data['id']) ? (int) $data['id'] : 0;
		$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : reqCfs::getVar('_wpnonce');
		if(!wp_verify_nonce($nonce, 'contact-'. $id)) {
			die('Some error with your request.........');
		}
		// Add some statistics
		frameCfs::_()->getModule('statistics')->getModel()->add(array('id' => $id, 'type' => 'submit'));
		if($this->getModel()->contact( $data )) {
			$lastForm = $this->getModel()->getLastForm();
			$successMsg = isset($lastForm['params']['tpl']['form_sent_msg']) 
					? $lastForm['params']['tpl']['form_sent_msg'] : 
					__('Thank you for contacting us!', CFS_LANG_CODE);
			$successMsg = dispatcherCfs::applyFilters('contactSuccessMsg', $successMsg, $lastForm);
			$res->addMessage( $successMsg );
			$redirectUrl = isset($lastForm['params']['tpl']['redirect_on_submit']) && !empty($lastForm['params']['tpl']['redirect_on_submit'])
					? $lastForm['params']['tpl']['redirect_on_submit']
					: false;
			$redirectUrl = dispatcherCfs::applyFilters('contactSuccessRedirectUrl', $redirectUrl, $lastForm);
			if(!empty($redirectUrl)) {
				if(isset($lastForm['params']['tpl']['redirect_to_submitted']) && $lastForm['params']['tpl']['redirect_to_submitted']) {
					$lastSavedContactId = $this->getModel()->getLastSavedContactId();
					$redirectUrl = uriCfs::_(array(
						'baseUrl' => $redirectUrl, 
						'fid' => $lastForm['id'], 
						'cid' => $lastSavedContactId, 
						'hash' => md5(AUTH_KEY. $lastForm['id']. $lastSavedContactId)));
				}
				$res->addData('redirect', uriCfs::normal($redirectUrl));
			}
			
		} else {
			$res->pushError($this->getModel()->getErrors());
		}
		frameCfs::_()->getModule('statistics')->getModel()->add(array(
			'id' => $id, 
			'type' => $res->error() ? 'submit_error' : 'submit_success',
		));
		return $res->ajaxExec();
	}
	public function exportCsv() {
		$id = (int) reqCfs::getVar('id');
		$delim = trim(reqCfs::getVar('delim'));
		$form = $this->getModel()->getById( $id );

		importClassCfs('filegeneratorCfs');
		importClassCfs('csvgeneratorCfs');

		$fileTitle = sprintf(__('Contacts from %s', CFS_LANG_CODE), htmlspecialchars( $form['label'] ));
		$csvGenerator = new csvgeneratorCfs( $fileTitle );
		if(!empty($delim)) {
			$csvGenerator->setDelimiter( $delim );
		}
		$labels = array();
		// Add additional subscribe fields
		if(isset($form['params']['fields']) && !empty($form['params']['fields'])) {
			foreach($form['params']['fields'] as $f) {
				$labels[ 'user_field_'. $f['name'] ] = $f['label'];
			}
		}
		$labels = array_merge($labels, array(
			'ip' => __('IP', CFS_LANG_CODE),
			'url' => __('URL', CFS_LANG_CODE),
			'form_id' => __('Form ID', CFS_LANG_CODE),
			'date_created' => __('Date Created', CFS_LANG_CODE),
		));
		$contacts = $this->getModel()->getContactsForForm( $id );

		$row = $cell = 0;
		foreach($labels as $l) {
			$csvGenerator->addCell($row, $cell, $l);
			$cell++;
		}
		$row = 1;
		if(!empty($contacts)) {
			foreach($contacts as $c) {
				$cell = 0;
				foreach($labels as $k => $l) {
					$getKey = $k;
					if(strpos($getKey, 'user_field_') === 0) {
						$getKey = str_replace('user_field_', '', $getKey);
						$value = isset($c['fields'][ $getKey ]) ? $c['fields'][ $getKey ] : '';
					} else {
						$value = $c[ $getKey ];
					}
					if(is_array($value)) {
						$value = implode(', ', $value);
					}
					$csvGenerator->addCell($row, $cell, $value);
					$cell++;
				}
				$row++;
			}
		} else {
			$cell = 0;
			$noUsersMsg = __('There are no Contacts for now', CFS_LANG_CODE);
			$csvGenerator->addCell($row, $cell, $noUsersMsg);
		}
		$csvGenerator->generate();
	}
	public function getNoncedMethods() {
		return array('save');
	}
	public function getContactsListForTbl() {
		$this->_forceModelName = 'contacts';
		return parent::getListForTbl();
	}
	public function getModel($name = '') {
		if(empty($name) && !empty($this->_forceModelName)) {
			$name = $this->_forceModelName;
		}
		return parent::getModel( $name );
	}
	public function getContactDetails() {
		$res = new responseCfs();
		if(($contact = $this->getModel('contacts')->getById(reqCfs::getVar('id'))) != false) {
			$res->addData('contact', $contact);
			$form = $this->getModel()->getById( $contact['form_id'] );
			if($form && $form['params']['fields']) {
				$res->addData('form_fields', $form['params']['fields']);
			}
			$res->addData('form_label', $form['label']);
		} else
			$res->pushError ($this->getModel('contacts')->getErrors());
		return $res->ajaxExec();
	}
	public function removeContactsGroup() {
		$this->_forceModelName = 'contacts';
		return parent::removeGroup();
	}
	public function getPermissions() {
		return array(
			CFS_USERLEVELS => array(
				CFS_ADMIN => array('createFromTpl', 'getListForTbl', 'remove', 'removeGroup', 'clear', 
					'save', 'getPreviewHtml', 'exportForDb', 'changeTpl', 'saveAsCopy', 'switchActive', 
					'outPreviewHtml', 'updateLabel', 'exportCsv', 'getContactsListForTbl', 'getContactDetails',
					'removeContactsGroup')
			),
		);
	}
}


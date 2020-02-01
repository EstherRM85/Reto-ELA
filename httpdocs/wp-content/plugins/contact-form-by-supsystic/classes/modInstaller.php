<?php
class modInstallerCfs {
    static private $_current = array();
    /**
     * Install new moduleCfs into plugin
     * @param string $module new moduleCfs data (@see classes/tables/modules.php)
     * @param string $path path to the main plugin file from what module is installed
     * @return bool true - if install success, else - false
     */
    static public function install($module, $path) {
        $exPlugDest = explode('plugins', $path);
        if(!empty($exPlugDest[1])) {
            $module['ex_plug_dir'] = str_replace(DS, '', $exPlugDest[1]);
        }
        $path = $path. DS. $module['code'];
        if(!empty($module) && !empty($path) && is_dir($path)) {
            if(self::isModule($path)) {
                $filesMoved = false;
                if(empty($module['ex_plug_dir']))
                    $filesMoved = self::moveFiles($module['code'], $path);
                else
                    $filesMoved = true;     //Those modules doesn't need to move their files
                if($filesMoved) {
                    if(frameCfs::_()->getTable('modules')->exists($module['code'], 'code')) {
                        frameCfs::_()->getTable('modules')->delete(array('code' => $module['code']));
                    }
					if($module['code'] != 'license')
						$module['active'] = 0;
                    frameCfs::_()->getTable('modules')->insert($module);
                    self::_runModuleInstall($module);
                    self::_installTables($module);
                    return true;
                } else {
                    errorsCfs::push(sprintf(__('Move files for %s failed'), $module['code']), errorsCfs::MOD_INSTALL);
                }
            } else
                errorsCfs::push(sprintf(__('%s is not plugin module'), $module['code']), errorsCfs::MOD_INSTALL);
        }
        return false;
    }
    static protected function _runModuleInstall($module, $action = 'install') {
        $moduleLocationDir = CFS_MODULES_DIR;
        if(!empty($module['ex_plug_dir']))
            $moduleLocationDir = utilsCfs::getPluginDir( $module['ex_plug_dir'] );
        if(is_dir($moduleLocationDir. $module['code'])) {
			if(!class_exists($module['code']. strFirstUp(CFS_CODE))) {
				importClassCfs($module['code'], $moduleLocationDir. $module['code']. DS. 'mod.php');
			}
            $moduleClass = toeGetClassNameCfs($module['code']);
            $moduleObj = new $moduleClass($module);
            if($moduleObj) {
                $moduleObj->$action();
            }
        }
    }
    /**
     * Check whether is or no module in given path
     * @param string $path path to the module
     * @return bool true if it is module, else - false
     */
    static public function isModule($path) {
        return true;
    }
    /**
     * Move files to plugin modules directory
     * @param string $code code for module
     * @param string $path path from what module will be moved
     * @return bool is success - true, else - false
     */
    static public function moveFiles($code, $path) {
        if(!is_dir(CFS_MODULES_DIR. $code)) {
            if(mkdir(CFS_MODULES_DIR. $code)) {
                utilsCfs::copyDirectories($path, CFS_MODULES_DIR. $code);
                return true;
            } else
                errorsCfs::push(__('Cannot create module directory. Try to set permission to '. CFS_MODULES_DIR. ' directory 755 or 777', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        } else
            return true;
        return false;
    }
    static private function _getPluginLocations() {
        $locations = array();
        $plug = reqCfs::getVar('plugin');
        if(empty($plug)) {
            $plug = reqCfs::getVar('checked');
            $plug = $plug[0];
        }
        $locations['plugPath'] = plugin_basename( trim( $plug ) );
        $locations['plugDir'] = dirname(WP_PLUGIN_DIR. DS. $locations['plugPath']);
		$locations['plugMainFile'] = WP_PLUGIN_DIR. DS. $locations['plugPath'];
        $locations['xmlPath'] = $locations['plugDir']. DS. 'install.xml';
        $locations['extendModPath'] = $locations['plugDir']. DS. 'install.php';
        return $locations;
    }
    static private function _getModulesFromXml($xmlPath) {
        if($xml = utilsCfs::getXml($xmlPath)) {
            if(isset($xml->modules) && isset($xml->modules->mod)) {
                $modules = array();
                $xmlMods = $xml->modules->children();
                foreach($xmlMods->mod as $mod) {
                    $modules[] = $mod;
                }
                if(empty($modules))
                    errorsCfs::push(__('No modules were found in XML file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
                else
                    return $modules;
            } else
                errorsCfs::push(__('Invalid XML file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        } else
            errorsCfs::push(__('No XML file were found', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        return false;
    }
    static private function _getExtendModules($locations) {
        $modules = array();
		$isExtendModPath = file_exists($locations['extendModPath']);
		$modulesList = $isExtendModPath ? include $locations['extendModPath'] : self::_getModulesFromXml($locations['xmlPath']);
        if(!empty($modulesList)) {
            foreach($modulesList as $mod) {
				$modData = $isExtendModPath ? $mod : utilsCfs::xmlNodeAttrsToArr($mod);
                array_push($modules, $modData);
            }
            if(empty($modules))
                errorsCfs::push(__('No modules were found in installation file', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
            else
                return $modules;
        } else
            errorsCfs::push(__('No installation file were found', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        return false;
    }
    /**
     * Check whether modules is installed or not, if not and must be activated - install it
     * @param array $codes array with modules data to store in database
     * @param string $path path to plugin file where modules is stored (__FILE__ for example)
     * @return bool true if check ok, else - false
     */
    static public function check($extPlugName = '') {
		if(CFS_TEST_MODE) {
			add_action('activated_plugin', array(frameCfs::_(), 'savePluginActivationErrors'));
		}
        $locations = self::_getPluginLocations();
        if($modules = self::_getExtendModules($locations)) {
            foreach($modules as $m) {
                if(!empty($m)) {
					//If module Exists - just activate it, we can't check this using frameCfs::moduleExists because this will not work for multy-site WP
                    if(frameCfs::_()->getTable('modules')->exists($m['code'], 'code') /*frameCfs::_()->moduleExists($m['code'])*/) {
                        self::activate($m);
                    } else {                                           //  if not - install it
                        if(!self::install($m, $locations['plugDir'])) {
                            errorsCfs::push(sprintf(__('Install %s failed'), $m['code']), errorsCfs::MOD_INSTALL);
                        }
                    }
                }
            }
        } else
            errorsCfs::push(__('Error Activate module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
        if(errorsCfs::haveErrors(errorsCfs::MOD_INSTALL)) {
            self::displayErrors();
            return false;
        }
		update_option(CFS_CODE. '_full_installed', 1);
        return true;
    }
    /**
	 * Public alias for _getCheckRegPlugs()
	 */
	/**
	 * We will run this each time plugin start to check modules activation messages
	 */
	static public function checkActivationMessages() {

	}
    /**
     * Deactivate module after deactivating external plugin
     */
    static public function deactivate() {
        $locations = self::_getPluginLocations();
        if($modules = self::_getExtendModules($locations)) {
            foreach($modules as $m) {
                if(frameCfs::_()->moduleActive($m['code'])) { //If module is active - then deacivate it
                    if(frameCfs::_()->getModule('options')->getModel('modules')->put(array(
                        'id' => frameCfs::_()->getModule($m['code'])->getID(),
                        'active' => 0,
                    ))->error) {
                        errorsCfs::push(__('Error Deactivation module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
                    }
                }
            }
        }
        if(errorsCfs::haveErrors(errorsCfs::MOD_INSTALL)) {
            self::displayErrors(false);
            return false;
        }
        return true;
    }
    static public function activate($modDataArr) {
		if(!empty($modDataArr['code']) && !frameCfs::_()->moduleActive($modDataArr['code'])) { //If module is not active - then acivate it
			if(frameCfs::_()->getModule('options')->getModel('modules')->put(array(
				'code' => $modDataArr['code'],
				'active' => 1,
			))->error) {
				errorsCfs::push(__('Error Activating module', CFS_LANG_CODE), errorsCfs::MOD_INSTALL);
			} else {
				$dbModData = frameCfs::_()->getModule('options')->getModel('modules')->get(array('code' => $modDataArr['code']));
				if(!empty($dbModData) && !empty($dbModData[0])) {
					$modDataArr['ex_plug_dir'] = $dbModData[0]['ex_plug_dir'];
				}
				self::_runModuleInstall($modDataArr, 'activate');
			}
		}
    }
    /**
     * Display all errors for module installer, must be used ONLY if You realy need it
     */
    static public function displayErrors($exit = true) {
        $errors = errorsCfs::get(errorsCfs::MOD_INSTALL);
        foreach($errors as $e) {
            echo '<b style="color: red;">'. $e. '</b><br />';
        }
        if($exit) exit();
    }
    static public function uninstall() {
        $locations = self::_getPluginLocations();
        if($modules = self::_getExtendModules($locations)) {
            foreach($modules as $m) {
                self::_uninstallTables($m);
                frameCfs::_()->getModule('options')->getModel('modules')->delete(array('code' => $m['code']));
                utilsCfs::deleteDir(CFS_MODULES_DIR. $m['code']);
            }
        }
    }
    static protected  function _uninstallTables($module) {
        if(is_dir(CFS_MODULES_DIR. $module['code']. DS. 'tables')) {
            $tableFiles = utilsCfs::getFilesList(CFS_MODULES_DIR. $module['code']. DS. 'tables');
            if(!empty($tableNames)) {
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameCfs::_()->getTable($tableName))
                        frameCfs::_()->getTable($tableName)->uninstall();
                }
            }
        }
    }
    static public function _installTables($module, $action = 'install') {
		$modDir = empty($module['ex_plug_dir']) ?
            CFS_MODULES_DIR. $module['code']. DS :
            utilsCfs::getPluginDir($module['ex_plug_dir']). $module['code']. DS;
        if(is_dir($modDir. 'tables')) {
            $tableFiles = utilsCfs::getFilesList($modDir. 'tables');
            if(!empty($tableFiles)) {
                frameCfs::_()->extractTables($modDir. 'tables'. DS);
                foreach($tableFiles as $file) {
                    $tableName = str_replace('.php', '', $file);
                    if(frameCfs::_()->getTable($tableName))
                        frameCfs::_()->getTable($tableName)->$action();
                }
            }
        }
    }
}

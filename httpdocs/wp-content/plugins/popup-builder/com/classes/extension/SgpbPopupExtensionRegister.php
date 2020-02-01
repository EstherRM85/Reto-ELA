<?php
use sgpb\AdminHelper;

class SgpbPopupExtensionRegister
{
	public static function register($pluginName, $classPath, $className, $options = array())
	{
		$registeredData = array();
		$registeredPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');

		if(!empty($registeredPlugins)) {
			$registeredData = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');
		}

		if (!empty($registeredData)) {
			$registeredData = json_decode($registeredData, true);
		}

		if(empty($registeredData)) {
			$registeredData = array();
		}

		if(empty($classPath) || empty($className)) {
			if(!empty($registeredData[$pluginName])) {
				/*Delete the plugin from the registered plugins' list if the class name or the class path is empty.*/
				unset($registeredData[$pluginName]);
				AdminHelper::updateOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS', $registeredData);
			}

			return;
		}
		$classPath = str_replace(SG_POPUP_PLUGIN_PATH, '', $classPath);
		$pluginData['classPath'] = $classPath;
		$pluginData['className'] = $className;
		$pluginData['options'] = $options;

		$registeredData[$pluginName] = $pluginData;
		$registeredData = json_encode($registeredData);

		AdminHelper::updateOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS', $registeredData);
		// it seems we have an inactive extension now
		AdminHelper::updateOption('SGPB_INACTIVE_EXTENSIONS', 'inactive');

		do_action('sgpb_extension_activation_hook', $pluginData);
	}

	public static function remove($pluginName)
	{
		$registeredPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');

		if (!$registeredPlugins) {
			return false;
		}

		$registeredData = json_decode($registeredPlugins, true);

		if(empty($registeredData)) {
			return false;
		}

		if (empty($registeredData[$pluginName])) {
			return false;
		}
		unset($registeredData[$pluginName]);
		$registeredData = json_encode($registeredData);

		AdminHelper::updateOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS', $registeredData);

		return true;
	}
}

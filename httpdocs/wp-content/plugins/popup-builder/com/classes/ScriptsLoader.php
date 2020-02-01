<?php

namespace sgpb;

// load popups data's from popups object
class ScriptsLoader
{
	// all loadable popups objects
	private $loadablePopups = array();
	private $isAdmin = false;
	private static $alreadyLoadedPopups = array();

	public function setLoadablePopups($loadablePopups)
	{
		$this->loadablePopups = $loadablePopups;
	}

	public function getLoadablePopups()
	{
		return apply_filters('sgpbLoadablePopups', $this->loadablePopups);
	}

	public function setIsAdmin($isAdmin)
	{
		$this->isAdmin = $isAdmin;
	}

	public function getIsAdmin()
	{
		return $this->isAdmin;
	}

	/**
	 * Get encoded popup options
	 *
	 * @since 3.0.4
	 *
	 * @param object $popup
	 *
	 * @return array|mixed|string|void $popupOptions
	 */
	private function getEncodedOptionsFromPopup($popup)
	{
		$extraOptions = $popup->getExtraRenderOptions();
		$popupOptions = $popup->getOptions();
		$popupOptions = apply_filters('sgpbPopupRenderOptions', $popupOptions);
		$popupCondition = $popup->getConditions();

		$popupOptions = array_merge($popupOptions, $extraOptions);
		$popupOptions['sgpbConditions'] = apply_filters('sgpbRenderCondtions',  $popupCondition);
		// These two lines have been added in order to not use the json_econde and to support PHP 5.3 version.
		$popupOptions = AdminHelper::serializeData($popupOptions);
		$popupOptions = base64_encode($popupOptions);

		return $popupOptions;
	}

	// load popup scripts and styles and add popup data to the footer
	public function loadToFooter()
	{
		$alreadyLoadedPopups = array();
		$popups = $this->getLoadablePopups();
		$currentPostType = AdminHelper::getCurrentPostType();
		if ($currentPostType == SG_POPUP_POST_TYPE) {
			return false;
		}

		if (empty($popups)) {
			return false;
		}

		if ($this->getIsAdmin()) {
			$this->loadToAdmin();
			return true;
		}

		global $post;
		$postId = 0;

		if (!empty($post)) {
			$postId = $post->ID;
		}

		foreach ($popups as $popup) {
			$popupId = $popup->getId();

			$popupContent = apply_filters('sgpbPopupContentLoadToPage', $popup->getPopupTypeContent(), $popupId);

			$events = $popup->getPopupAllEvents($postId, $popupId, $popup);
			// if popup's data has already loaded into the page with the same event
			if (isset(self::$alreadyLoadedPopups[$popupId])) {
				if (self::$alreadyLoadedPopups[$popupId] == $events) {
					continue;
				}
			}
			foreach ($events as $event) {
				if (isset($event['param'])) {
					if (isset(self::$alreadyLoadedPopups[$popupId])) {
						if (self::$alreadyLoadedPopups[$popupId] == $event['param']) {
							continue;
						}
					}
				}
			}
			self::$alreadyLoadedPopups[$popupId] = $events;
			$events = json_encode($events);

			$popupOptions = $this->getEncodedOptionsFromPopup($popup);
			$popupOptions = apply_filters('sgpbLoadToFooterOptions', $popupOptions);

			add_action('wp_footer', function() use ($popupId, $events, $popupOptions, $popupContent) {
				$footerPopupContent = '<div style="position:fixed;bottom: -999999999999999999999px;">
							<div class="sg-popup-builder-content" id="sg-popup-content-wrapper-'.$popupId.'" data-id="'.esc_attr($popupId).'" data-events="'.esc_attr($events).'" data-options="'.esc_attr($popupOptions).'">
								<div class="sgpb-popup-builder-content-'.esc_attr($popupId).' sgpb-popup-builder-content-html">'.$popupContent.'</div>
							</div>
						  </div>';

				echo $footerPopupContent;
			});
		}

		$this->includeScripts();
		$this->includeStyles();
	}

	public function loadToAdmin()
	{
		$popups = $this->getLoadablePopups();

		foreach ($popups as $popup) {
			$popupId = $popup->getId();

			$events = array();

			$events = json_encode($events);

			$popupOptions = $this->getEncodedOptionsFromPopup($popup);

			$popupContent = apply_filters('sgpbPopupContentLoadToPage', $popup->getPopupTypeContent(), $popupId);

			add_action('admin_footer', function() use ($popupId, $events, $popupOptions, $popupContent) {
				$footerPopupContent = '<div style="position:absolute;top: -999999999999999999999px;">
							<div class="sg-popup-builder-content" id="sg-popup-content-wrapper-'.$popupId.'" data-id="'.esc_attr($popupId).'" data-events="'.esc_attr($events).'" data-options="'.esc_attr($popupOptions).'">
								<div class="sgpb-popup-builder-content-'.esc_attr($popupId).' sgpb-popup-builder-content-html">'.$popupContent.'</div>
							</div>
						  </div>';

				echo $footerPopupContent;
			});
		}
		$this->includeScripts();
		$this->includeStyles();

	}

	private function includeScripts()
	{
		global $post;
		$popups = $this->getLoadablePopups();
		$registeredPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');

		if (!$registeredPlugins) {
			return;
		}
		$registeredPlugins = json_decode($registeredPlugins, true);

		if (empty($registeredPlugins)) {
			return;
		}

		foreach ($registeredPlugins as $pluginName => $pluginData) {

			if (!is_plugin_active($pluginName)) {
				continue;
			}

			if (empty($pluginData['classPath']) || empty($pluginData['className'])) {
				continue;
			}
			$classPath = $pluginData['classPath'];
			$classPath = SG_POPUP_PLUGIN_PATH.$classPath;

			if (!file_exists($classPath)) {
				continue;
			}

			require_once($classPath);

			$classObj = new $pluginData['className']();
			$extensionInterface = 'SgpbIPopupExtension';

			if (!$classObj instanceof $extensionInterface) {
				continue;
			}

			$scriptData = $classObj->getFrontendScripts(
				$post, array(
					'popups' => $popups
				)
			);

			$scripts[] = $scriptData;
		}

		if (empty($scripts)) {
			return;
		}

		foreach ($scripts as $script) {
			if (empty($script['jsFiles'])) {
				continue;
			}

			foreach ($script['jsFiles'] as $jsFile) {

				if (empty($jsFile['folderUrl'])) {
					wp_enqueue_script(@$jsFile['filename']);
					continue;
				}

				$dirUrl = $jsFile['folderUrl'];
				$dep = (!empty($jsFile['dep'])) ? $jsFile['dep'] : '';
				$ver = (!empty($jsFile['ver'])) ? $jsFile['ver'] : '';
				$inFooter = (!empty($jsFile['inFooter'])) ? $jsFile['inFooter'] : '';

				ScriptsIncluder::registerScript($jsFile['filename'], array(
						'dirUrl' => $dirUrl,
						'dep' => $dep,
						'ver' => $ver,
						'inFooter' => $inFooter
					)
				);
				ScriptsIncluder::enqueueScript($jsFile['filename']);
			}

			if (empty($script['localizeData'])) {
				continue;
			}

			$localizeData = $script['localizeData'];

			if (!empty($localizeData[0])) {
				foreach ($localizeData as $valueData) {
					if (empty($valueData)) {
						continue;

					}

					ScriptsIncluder::localizeScript($valueData['handle'], $valueData['name'], $valueData['data']);
				}
			}
		}
	}

	private function includeStyles()
	{
		global $post;
		$styles = array();
		$popups = $this->getLoadablePopups();
		$registeredPlugins = AdminHelper::getOption('SG_POPUP_BUILDER_REGISTERED_PLUGINS');

		if (!$registeredPlugins) {
			return;
		}
		$registeredPlugins = json_decode($registeredPlugins, true);

		if (empty($registeredPlugins)) {
			return;
		}

		foreach ($registeredPlugins as $pluginName => $pluginData) {

			if (!is_plugin_active($pluginName)) {
				continue;
			}

			if (empty($pluginData['classPath']) || empty($pluginData['className'])) {
				continue;
			}

			$classPath = $pluginData['classPath'];
			$classPath = SG_POPUP_PLUGIN_PATH.$classPath;

			if (!file_exists($classPath))  {
				continue;
			}

			require_once($classPath);

			$classObj = new $pluginData['className']();
			$extensionInterface = 'SgpbIPopupExtension';

			if (!$classObj instanceof $extensionInterface) {
				continue;
			}

			$scriptData = $classObj->getFrontendStyles(
				$post , array(
					'popups' => $popups
				)
			);

			$styles[] = $scriptData;
		}

		if (empty($styles)) {
			return;
		}

		foreach ($styles as $style) {

			if (empty($style['cssFiles'])) {
				continue;
			}

			foreach ($style['cssFiles'] as $cssFile) {

				if (empty($cssFile['folderUrl'])) {
					ScriptsIncluder::enqueueStyle($cssFile['filename']);
					continue;
				}

				$dirUrl = $cssFile['folderUrl'];
				$dep = (!empty($cssFile['dep'])) ? $cssFile['dep'] : '';
				$ver = (!empty($cssFile['ver'])) ? $cssFile['ver'] : '';
				$inFooter = (!empty($cssFile['inFooter'])) ? $cssFile['inFooter'] : '';

				ScriptsIncluder::registerStyle($cssFile['filename'], array(
						'dirUrl' => $dirUrl,
						'dep' => $dep,
						'ver' => $ver,
						'inFooter' => $inFooter
					)
				);
				ScriptsIncluder::enqueueStyle($cssFile['filename']);
			}
		}
	}
}

<?php
class supsystic_promoViewCfs extends viewCfs {
    public function displayAdminFooter() {
        parent::display('adminFooter');
    }
	public function showAdditionalmainAdminShowOnOptions($forms) {
		$this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=onexit&utm_campaign=forms'));
		parent::display('additionalmainAdminShowOnOptions');
	}
	public function getOverviewTabContent() {
		frameCfs::_()->getModule('templates')->loadJqueryUi();
		
		frameCfs::_()->getModule('templates')->loadSlimscroll();
		frameCfs::_()->addScript('admin.overview', $this->getModule()->getModPath(). 'js/admin.overview.js');
		frameCfs::_()->addStyle('admin.overview', $this->getModule()->getModPath(). 'css/admin.overview.css');
		$this->assign('mainLink', $this->getModule()->getMainLink());
		$this->assign('faqList', $this->getFaqList());
		$this->assign('serverSettings', $this->getServerSettings());
		$this->assign('news', $this->getNewsContent());
		$this->assign('contactFields', $this->getModule()->getContactFormFields());
		return parent::getContent('overviewTabContent');
	}
	public function getFaqList() {
		return array(
			__('Why Form by Supsystic is "must have" for your website?', CFS_LANG_CODE) 
				=> sprintf(__('Increase your sales by 500%% using Form by Supsystic! More subscribers = more sales! It\'s that simple!<br />More info you can find here <a target="_blank" href="%s">Form by Supsystic is "must have" for your website</a>', CFS_LANG_CODE), 'http://supsystic.com/why-contact-form-by-supsystic-is-must-have-for-your-website/'),
			__('What is A/B testing?', CFS_LANG_CODE) 
				=> sprintf(__('A/B testing is one of the easiest ways to increase conversion rates and learn more about your audience!<br />A/B test in Form plugin involves testing two or more versions of a forms window - an A version (original) and a B versions (the variation) - with live traffic and measuring the effect each version has on your conversion rate.<br />To know more detail – click <a target="_blank" href="%s">here</a>', CFS_LANG_CODE), 'http://supsystic.com/what-is-ab-testing/'),
			__('How to enable subscription to Aweber?', CFS_LANG_CODE)
				=> sprintf(__('In order to subscribe to Aweber you need to know unique list id of your aweber account - check this <a target="_blank" href="%s">page</a> for more details.', CFS_LANG_CODE), 'http://supsystic.com/what-is-the-unique-list-id/'),
			__('How to subscribe to MailChimp?', CFS_LANG_CODE)
				=> __('To subscribe to MailChimp you need enter your MailChimp API key and name of list for subscription. To find your MailChimp API key - follow the instructions below:<br />
				1. Login to your mailchimp account at http://mailchimp.com<br />
				2. From the left main menu, click on your Username, then select "Account" in the flyout menu.<br />
				3. From the account page select "Extras" -> "API Keys".<br />
				4. Your API Key will be listed in the table labeled "Your API Keys".<br />
				5. Copy / Paste your API key into "MailChimp API key" field in Form edit screen -> Subscribe section.', CFS_LANG_CODE),
			__('Where to find css code for the pop-up window?', CFS_LANG_CODE)
				=> __('With Form by Supsystic you can edit CSS style directly from the plugin. <br />
				In WordPress admin area - 
go to Form by Supsystic -> choose a forms, what you need -> click Code tab. <br />
Here you can edit css style of the pop-up window.', CFS_LANG_CODE),
			__('How to get PRO version of plugin for FREE?', CFS_LANG_CODE)
				=> sprintf(__('You have an incredible opportunity to get PRO version for free. Make Translation of the plugin! It will be amazing if you take advantage of this offer!<br />
					More info you can find here <a target="_blank" href="%s">“Get PRO version of any plugin for FREE”</a>', CFS_LANG_CODE), 'http://supsystic.com/get-pro-version-of-any-plugin-for-free/'),
			__('Translation', CFS_LANG_CODE)
				=> sprintf(__('All available languages are provided with the Supsystic Form plugin. If your language isn’t available, your plugin will be in English by default.<br />
					Available Translations: English, French, German, Spanish, Russian<br />
					Translate or update a translation Form WordPress plugin in your language and get a Premium license for FREE. <a target="_blank" href="%s">Contact us.</a>', CFS_LANG_CODE), $this->getModule()->getContactLink()),
		);
	}
	public function getMostFaqList() {
		return array(
			__("Where's my subscribers?", CFS_LANG_CODE) 
				=> sprintf(__("By default all subscribers add to the WordPress. 
					To find your subscribers go to Users tab on the left navigation menu of WordPress admin area. 
					Also available subscription to the Aweber, MailChimp, MailPoet <a href='%s' target='_blank'>and other</a>. 
					If you want to add another subscription service - just <a href='%s' target='_blank'>contact us</a> and provide URL of the subscription service.", CFS_LANG_CODE), $this->getModule()->getMainLink(). '#subscribe-to-email-forms-settings', $this->getModule()->getContactLink()),
			__("Form doesn't appear on the website", CFS_LANG_CODE) 
				=> sprintf(__("If you setup you're Form properly, and it still doesn't show on the page - there are can be conflict with your WordPress theme or other plugins. <a href='%s' target='_blank'>Contact us</a> with the URL of the webpage you add forms and screenshots / text of the error messages, if you have one - and we will help you resolve your issue.", CFS_LANG_CODE), $this->getModule()->getContactLink()),
		);
	}
	public function getNewsContent() {
		$getData = wp_remote_get('http://supsystic.com/news/main.html');
		$content = '';
		if($getData 
			&& is_array($getData) 
			&& isset($getData['response']) 
			&& isset($getData['response']['code']) 
			&& $getData['response']['code'] == 200
			&& isset($getData['body'])
			&& !empty($getData['body'])
		) {
			$content = $getData['body'];
		} else {
			$content = sprintf(__('There were some problems while trying to retrieve our news, but you can always check all list <a target="_blank" href="%s">here</a>.', CFS_LANG_CODE), 'http://supsystic.com/news');
		}
		return $content;
	}
	public function getServerSettings() {
		global $wpdb;
		return array(
			'Operating System' => array('value' => PHP_OS),
            'PHP Version' => array('value' => PHP_VERSION),
            'Server Software' => array('value' => $_SERVER['SERVER_SOFTWARE']),
			'MySQL' => array('value' =>  $wpdb->db_version()),
            'PHP Allow URL Fopen' => array('value' => ini_get('allow_url_fopen') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE)),
            'PHP Memory Limit' => array('value' => ini_get('memory_limit')),
            'PHP Max Post Size' => array('value' => ini_get('post_max_size')),
            'PHP Max Upload Filesize' => array('value' => ini_get('upload_max_filesize')),
            'PHP Max Script Execute Time' => array('value' => ini_get('max_execution_time')),
            'PHP EXIF Support' => array('value' => extension_loaded('exif') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE)),
            'PHP EXIF Version' => array('value' => phpversion('exif')),
            'PHP XML Support' => array('value' => extension_loaded('libxml') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE), 'error' => !extension_loaded('libxml')),
            'PHP CURL Support' => array('value' => extension_loaded('curl') ? __('Yes', CFS_LANG_CODE) : __('No', CFS_LANG_CODE), 'error' => !extension_loaded('curl')),
		);
	}
	public function getLayeredStylePromo() {
		$this->assign('promoLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=layered&utm_campaign=forms'));
		return parent::getContent('layeredStylePromo');
	}
	public function showWelcomePage() {
		frameCfs::_()->getModule('templates')->loadJqueryUi();
		frameCfs::_()->getModule('templates')->loadBootstrapPartialOnlyCss();
		frameCfs::_()->addStyle('admin.welcome', $this->getModule()->getModPath(). 'css/admin.welcome.css');
		$createNewLink = frameCfs::_()->getModule('options')->getTabUrl('forms_add_new');
		$goToAdminLink = frameCfs::_()->getModule('options')->getTabUrl('forms');
		$skipTutorLink = uriCfs::_(array('baseUrl' => $goToAdminLink, 'skip_tutorial' => 1));
		$this->assign('createNewLink', $this->_makeWelcomeLink( $createNewLink ));
		$this->assign('skipTutorLink', $this->_makeWelcomeLink( $skipTutorLink ));
		$this->assign('faqList', $this->getMostFaqList());
		$this->assign('mainLink', $this->getModule()->getMainLink());
		parent::display('welcomePage');
	}
	private function _makeWelcomeLink($link) {
		return uriCfs::_(array('baseUrl' => $link, 'from' => 'welcome-page', 'pl' => CFS_CODE));
	}
	public function getTourHtml() {
		$this->assign('contactFormLink', $this->getModule()->getContactLink());
		$this->assign('finishSiteLink', $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=final_step_b_step&utm_campaign=forms'));
		return parent::getContent('adminTour');
	}
	public function showFeaturedPluginsPage() {
		frameCfs::_()->getModule('templates')->loadBootstrapSimple();
		frameCfs::_()->addStyle('admin.featured-plugins', $this->getModule()->getModPath(). 'css/admin.featured-plugins.css');
		frameCfs::_()->getModule('templates')->loadGoogleFont('Montserrat');
		$siteUrl = 'https://supsystic.com/';
		$pluginsUrl = $siteUrl. 'plugins/';
		$uploadsUrl = $siteUrl. 'wp-content/uploads/';
		$downloadsUrl = 'https://downloads.wordpress.org/plugin/';
		$imgUrl = frameCfs::_()->getModule('supsystic_promo')->getModPath(). 'img/';
		$promoCampaign = 'forms';
		$this->assign('pluginsList', array(
			array('label' => __('Popup Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'popup-plugin/', 'img' => $uploadsUrl. '2016/07/Popup_256.png', 'desc' => __('The Best WordPress PopUp option plugin to help you gain more subscribers, social followers or advertisement. Responsive pop-ups with friendly options.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'popup-by-supsystic.zip'),
			array('label' => __('Photo Gallery Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'photo-gallery/', 'img' => $uploadsUrl. '2016/07/Gallery_256.png', 'desc' => __('Photo Gallery Plugin with a great number of layouts will help you to create quality respectable portfolios and image galleries.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'gallery-by-supsystic.zip'),
			array('label' => __('Contact Form Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'contact-form-plugin/', 'img' => $uploadsUrl. '2016/07/Contact_Form_256.png', 'desc' => __('One of the best plugin for creating Contact Forms on your WordPress site. Changeable fonts, backgrounds, an option for adding fields etc.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'contact-form-by-supsystic.zip'),
			array('label' => __('Newsletter Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'newsletter-plugin/', 'img' => $uploadsUrl. '2016/08/icon-256x256.png', 'desc' => __('Supsystic Newsletter plugin for automatic mailing of your letters. You will have no need to control it or send them manually. No coding, hard skills or long hours of customizing are required.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'newsletter-by-supsystic.zip'),
			array('label' => __('Membership by Supsystic', CFS_LANG_CODE), 'url' => $pluginsUrl. 'membership-plugin/', 'img' => $uploadsUrl. '2016/09/256.png', 'desc' => __('Create online membership community with custom user profiles, roles, FrontEnd registration and login. Members Directory, activity, groups, messages.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'membership-by-supsystic.zip'),
			array('label' => __('Data Tables Generator', CFS_LANG_CODE), 'url' => $pluginsUrl. 'data-tables-generator-plugin/', 'img' => $uploadsUrl. '2016/07/Data_Tables_256.png', 'desc' => __('Create and manage beautiful data tables with custom design. No HTML knowledge is required.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'data-tables-generator-by-supsystic.zip'),
			array('label' => __('Social Share Buttons', CFS_LANG_CODE), 'url' => $pluginsUrl. 'social-share-plugin/', 'img' => $uploadsUrl. '2016/07/Social_Buttons_256.png', 'desc' => __('Social share buttons to increase social traffic and popularity. Social sharing to Facebook, Twitter and other social networks.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'social-share-buttons-by-supsystic.zip'),
			array('label' => __('Live Chat Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'live-chat/', 'img' => $uploadsUrl. '2016/07/Live_Chat_256.png', 'desc' => __('Be closer to your visitors and customers with Live Chat Support by Supsystic. Help you visitors, support them in real-time with exceptional Live Chat WordPress plugin by Supsystic.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'live-chat-by-supsystic.zip'),
			array('label' => __('Pricing Table', CFS_LANG_CODE), 'url' => $pluginsUrl. 'pricing-table/', 'img' => $uploadsUrl. '2016/07/Pricing_Table_256.png', 'desc' => __('It’s never been so easy to create and manage pricing and comparison tables with table builder. Any element of the table can be customise with mouse click.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'pricing-table-by-supsystic.zip'),
			array('label' => __('Coming Soon Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'coming-soon-plugin/', 'img' => $uploadsUrl. '2016/07/Coming_Soon_256.png', 'desc' => __('Coming soon page with drag-and-drop builder or under construction | maintenance mode to notify visitors and collects emails.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'coming-soon-by-supsystic.zip'),
			array('label' => __('Backup Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'backup-plugin/', 'img' => $uploadsUrl. '2016/07/Backup_256.png', 'desc' => __('Backup and Restore WordPress Plugin by Supsystic provides quick and unhitched DropBox, FTP, Amazon S3, Google Drive backup for your WordPress website.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'backup-by-supsystic.zip'),
			array('label' => __('Google Maps Easy', CFS_LANG_CODE), 'url' => $pluginsUrl. 'google-maps-plugin/', 'img' => $uploadsUrl. '2016/07/Google_Maps_256.png', 'desc' => __('Display custom Google Maps. Set markers and locations with text, images, categories and links. Customize google map in a simple and intuitive way.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'google-maps-easy.zip'),
			array('label' => __('Digital Publication Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'digital-publication-plugin/', 'img' => $uploadsUrl. '2016/07/Digital_Publication_256.png', 'desc' => __('Digital Publication WordPress Plugin by Supsystic for Magazines, Catalogs, Portfolios. Convert images, posts, PDF to the page flip book.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'digital-publications-by-supsystic.zip'),
			array('label' => __('Slider Plugin', CFS_LANG_CODE), 'url' => $pluginsUrl. 'slider/', 'img' => $uploadsUrl. '2016/07/Slider_256.png', 'desc' => __('Creating slideshows with Slider plugin is fast and easy. Simply select images from your WordPress Media Library, Flickr, Instagram or Facebook, set slide captions, links and SEO fields all from one page.', CFS_LANG_CODE), 'download' => $downloadsUrl. 'slider-by-supsystic.zip'),
			array('label' => __('Kinsta Hosting', CFS_LANG_CODE), 'url' => 'https://kinsta.com?kaid=MNRQQASUYJRT', 'external' => true, 'img' => $imgUrl. 'kinsta_banner.png', 'desc' => __('If you want to host a business site or a blog, Kinsta managed WordPress hosting is the best place to stop on. Without any hesitation, we can say Kinsta is incredible when it comes to uptime and speed.', CFS_LANG_CODE)),
		));
		foreach($this->pluginsList as $i => $p) {
			if(empty($p['external'])) {
				$this->pluginsList[ $i ]['url'] = $this->pluginsList[ $i ]['url']. '?utm_source=plugin&utm_medium=featured_plugins&utm_campaign='. $promoCampaign;
			}
		}
		$this->assign('bundleUrl', $siteUrl. 'product/plugins-bundle/'. '?utm_source=plugin&utm_medium=featured_plugins&utm_campaign='. $promoCampaign);
		return parent::getContent('featuredPlugins');
	}
	public function getDiscountMsg($buyLink = '#') {
		$this->assign('bundlePageLink', '//supsystic.com/all-plugins/');
		$this->assign('buyLink', $buyLink);
		parent::display('discountMsg');
	}
}

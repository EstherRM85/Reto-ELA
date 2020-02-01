<?php
/**
 * Plugin Name: Contact Form by Supsystic
 * Description: Contact Form Builder with drag-and-drop editor to create responsive, mobile ready contact forms in a second. Custom fields and contact form templates
 * Version: 1.7.3
 * Author: supsystic.com
 * Author URI: https://supsystic.com
 * Text Domain: contact-form-by-supsystic
 * Domain Path: /languages
 **/
	/**
	 * Base config constants and functions
	 */
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'config.php');
    require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'functions.php');
	/**
	 * Connect all required core classes
	 */
    importClassCfs('dbCfs');
    importClassCfs('installerCfs');
    importClassCfs('baseObjectCfs');
    importClassCfs('moduleCfs');
	importClassCfs('moduleWidgetCfs');
    importClassCfs('modelCfs');
	importClassCfs('modelSubscribeCfs');
    importClassCfs('viewCfs');
    importClassCfs('controllerCfs');
    importClassCfs('helperCfs');
    importClassCfs('dispatcherCfs');
    importClassCfs('fieldCfs');
    importClassCfs('tableCfs');
    importClassCfs('frameCfs');
	/**
	 * @deprecated since version 1.0.1
	 */
    importClassCfs('langCfs');
    importClassCfs('reqCfs');
    importClassCfs('uriCfs');
    importClassCfs('htmlCfs');
    importClassCfs('responseCfs');
    importClassCfs('fieldAdapterCfs');
    importClassCfs('validatorCfs');
    importClassCfs('errorsCfs');
    importClassCfs('utilsCfs');
    importClassCfs('modInstallerCfs');
	importClassCfs('installerDbUpdaterCfs');
	importClassCfs('dateCfs');
	/**
	 * Check plugin version - maybe we need to update database, and check global errors in request
	 */
    installerCfs::update();
    errorsCfs::init();
    /**
	 * Start application
	 */
    frameCfs::_()->parseRoute();
    frameCfs::_()->init();
    frameCfs::_()->exec();
	
	//var_dump(frameCfs::_()->getActivationErrors()); exit();

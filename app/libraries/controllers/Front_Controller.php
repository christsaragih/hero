<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Front Controller
*
* This controller is extended by all frontend controllers in modules and in the
* app core controllers.  Unlike the Admin_Controller, it doesn't authenticate the
* user or force them to be logged in.  However, it does initialize the Smarty
* Template Engine, the app hooks engine, preload all modules, and installs the
* default theme if currently uninstalled.
*
* @copyright Electric Function, Inc.
* @package Electric Framework
* @author Electric Function, Inc.
*/

class Front_Controller extends MY_Controller {
	function __construct () {
		parent::__construct();
		
		$this->load->helper('ssl');
		
		// we are in the frontend
		define("_FRONTEND","TRUE");
		
		// set current theme
		$this->config->set_item('current_theme',setting('theme'));
		
		// load Smarty template engine and configure it
		$this->load->library('smarty');
		$this->smarty->initialize();
		
		// init hooks
		$this->load->library('app_hooks');
		
		// load all modules with control panel to build navigation, etc.
		$modules = $this->module_model->get_modules();
		
		// first, reset module definitions so that we run them all as a "frontend" call and their preloads get called
		$this->module_definitions = new stdClass();
		
		foreach ($modules as $module) {
			MY_Loader::define_module($module['name'] . '/');
		}
		
		// if we don't have a theme, we'll setup the default theme
		// we do it after Smarty because some module definitions reference the Smarty library
		if (setting('theme') == FALSE and setting('default_theme')) {
			$this->settings_model->update_setting('theme',setting('default_theme'));
			
			// install the default theme
			$install_file = FCPATH . 'themes/' . setting('default_theme') . '/install.php';
			
			if (file_exists($install_file)) {
				include($install_file);
			}
			
			// redirect to home page
			redirect('/');
			die();
		}
	}
}
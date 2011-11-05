<?php
/* SVN FILE: $Id: smarty.php,v 1.1 2011/09/20 08:32:29 iwasaki Exp $ */

/**
 * Methods for displaying presentation data in the view.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 1.1 $
 * @modifiedby		$LastChangedBy: gwoo $
 * @lastmodified	$Date: 2011/09/20 08:32:29 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Included Smarty library.
 */
App::import('Vendor', 'Smarty', array('file' => 'smarty'.DS.'Smarty.class.php'));

/**
 * SmartyView, the V in the MVC triad.
 *
 * Class holding methods for displaying presentation data.
 *
 * @copyright		Copyright 2008, ECWorks.
 * @link			http://ecw.seesaa.net/ ECWorks Blog(temporary site)
 * @version			1.2.0.7296
 * @package			cake
 * @subpackage		cake.app.views
 * @lastmodified	$Date: 2011/09/20 08:32:29 $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class SmartyView extends View {

/**
 * Instance of Smarty object.
 *
 * @var object
 */
	var $smarty;

/**
 * Constructor
 *
 * @return SmartyView
 */
	function __construct(&$controller) {

		parent::__construct($controller);

		$this->subDir = '';
		$this->ext= '.tpl';
		$this->layoutPath = '';	//ver. 1.2's property
		
		//$this->smarty = &new Smarty();
		$this->smarty = new Smarty();
		$this->smarty->plugins_dir[] = VENDORS.'smarty'.DS.'plugins'.DS;
		$this->smarty->compile_dir = TMP.'smarty'.DS.'templates_c'.DS;
		$this->smarty->cache_dir = TMP.'smarty'.DS.'cache'.DS;
		$this->smarty->error_reporting = 'E_ALL & ~E_NOTICE';
		
		//$this->smarty->register_outputfilter(array($this,'SJIS_Encoding'));
		
	}
 	 /**
	  * 指定された文字列をShift-JISへ変換する。
	  *
	  * @param unknown_type $value
	  * @param unknown_type $smarty
	  * @return Shift-JISへ変更した文字列
	  */
	function SJIS_Encoding($value, &$smarty) {
		header("Content-Type: text/html; charset=Shift-JIS");
	
		echo mb_convert_encoding($value,"SJIS",'UTF-8');
		exit;
	//  return mb_convert_encoding($value,'SJIS','UTF-8');//mb_convert_encoding($value, "SJIS", "UTF-8");
	}
	
/**
 * Renders and returns output for given view filename with its
 * array of data.
 *
 * @param string $___viewFn Filename of the view
 * @param array $___dataForView Data to include in rendered view
 * @return string Rendered output
 * @access protected
 */
	function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
		$loadedHelpers = array();

		if ($this->helpers != false && $loadHelpers === true) {
			$loadedHelpers = $this->_loadHelpers($loadedHelpers, $this->helpers);

			foreach (array_keys($loadedHelpers) as $helper) {
				$camelBackedHelper = Inflector::variable($helper);
				${$camelBackedHelper} =& $loadedHelpers[$helper];
				$this->loaded[$camelBackedHelper] =& ${$camelBackedHelper};
				$this->smarty->assign_by_ref($camelBackedHelper, ${$camelBackedHelper});
			}

			foreach ($loadedHelpers as $helper) {
				if (is_object($helper)) {
					if (is_subclass_of($helper, 'Helper') || is_subclass_of($helper, 'helper')) {
						$helper->beforeRender();
					}
					if (method_exists($helper, 'beforeSmartyRender')) {
						$helper->beforeSmartyRender($this->smarty);
					}
				}
			}
		}

		foreach($___dataForView as $data => $value) {
			if(!is_object($data)) {
				$this->smarty->assign($data, $value);
			}
		}
		$this->smarty->assign_by_ref('view', $this);
		ob_start();

		echo $this->smarty->fetch($___viewFn);

		if (!empty($loadedHelpers)) {
			foreach ($loadedHelpers as $helper) {
				if (is_object($helper)) {
					if (is_subclass_of($helper, 'Helper') || is_subclass_of($helper, 'helper')) {
						$helper->afterRender();
					}
					if (method_exists($helper, 'afterSmartyRender')) {
						$helper->afterSmartyRender($this->smarty);
					}
				}
			}
		}
		$out = ob_get_clean();

		if (isset($this->loaded['cache']) && (($this->cacheAction != false)) && (Configure::read('Cache.check') === true)) {
			if (is_a($this->loaded['cache'], 'CacheHelper')) {
				$cache =& $this->loaded['cache'];
				$cache->base = $this->base;
				$cache->here = $this->here;
				$cache->helpers = $this->helpers;
				$cache->action = $this->action;
				$cache->controllerName = $this->name;
				$cache->layout	= $this->layout;
				$cache->cacheAction = $this->cacheAction;
				$cache->cache($___viewFn, $out, $cached);
			}
		}
		return $out;
	}
/**
 * Renders a layout. Returns output from _render(). Returns false on error.
 *
 * @param string $content_for_layout Content to render in a view, wrapped by the surrounding layout.
 * @return mixed Rendered output, or false on error
 */
	function renderLayout($content_for_layout, $layout = null) {
		$layout_fn = $this->_getLayoutFileName($layout);
		$debug = '';

		if (isset($this->viewVars['cakeDebug']) && Configure::read() > 2) {
			$debug = View::element('dump', array('controller' => $this->viewVars['cakeDebug']), false);
			unset($this->viewVars['cakeDebug']);
		}

		if ($this->pageTitle !== false) {
			$pageTitle = $this->pageTitle;
		} else {
			$pageTitle = Inflector::humanize($this->viewPath);
		}
		$data_for_layout = array_merge($this->viewVars,
			array(
				'title_for_layout' => $pageTitle,
				'content_for_layout' => $content_for_layout,
				'scripts_for_layout' => join("\n\t", $this->__scripts),
				'cakeDebug' => $debug
			)
		);

		if (empty($this->loaded) && !empty($this->helpers)) {
			$loadHelpers = true;
		} else {
			$loadHelpers = false;
			$data_for_layout = array_merge($data_for_layout, $this->loaded);
		}

		if (!empty($this->loaded)) {
			$helpers = array_keys($this->loaded);
			foreach ($helpers as $helperName) {
				$helper =& $this->loaded[$helperName];
				if (is_object($helper)) {
					if (is_subclass_of($helper, 'Helper') || is_subclass_of($helper, 'helper')) {
						$helper->beforeLayout();
					}
				}
			}
		}

		if (substr($layout_fn, -3) === 'ctp' || substr($layout_fn, -5) === 'thtml') {
			$this->output = View::_render($layout_fn, $data_for_layout, $loadHelpers, true);
		} else {
			$this->output = $this->_render($layout_fn, $data_for_layout, $loadHelpers, true);
		}

		if ($this->output === false) {
			$this->output = $this->_render($layout_fn, $data_for_layout);
			trigger_error(sprintf(__("Error in layout %s, got: <blockquote>%s</blockquote>", true), $layout_fn, $this->output), E_USER_ERROR);
			return false;
		}

		if (!empty($this->loaded)) {
			$helpers = array_keys($this->loaded);
			foreach ($helpers as $helperName) {
				$helper =& $this->loaded[$helperName];
				if (is_object($helper)) {
					if (is_subclass_of($helper, 'Helper') || is_subclass_of($helper, 'helper')) {
						$helper->afterLayout();
					}
				}
			}
		}
		return $this->output;
	}

}
?>
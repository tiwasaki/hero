<?php
/* SVN FILE: $Id: private_action.ctp,v 1.1 2011/09/20 08:32:26 iwasaki Exp $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 1.1 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2011/09/20 08:32:26 $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<h2><?php echo sprintf(__('Private Method in %s', true), $controller);?></h2>
<p class="error">
	<strong><?php __('Error'); ?>: </strong>
	<?php echo sprintf(__("%s%s cannot be accessed directly.", true), "<em>". $controller ."::</em>",  "<em>". $action ."()</em>");?>
</p>
<p class="notice">
	<strong><?php __('Notice'); ?>: </strong>
	<?php echo sprintf(__('If you want to customize this error message, create %s', true), APP_DIR.DS."views".DS."errors".DS."private_action.ctp");?>
</p>
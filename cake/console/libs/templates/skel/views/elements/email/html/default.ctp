<?php
/* SVN FILE: $Id: default.ctp,v 1.1 2011/09/20 08:32:28 iwasaki Exp $ */
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
 * @subpackage    cake.cake.libs.view.templates.elements.email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 1.1 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2011/09/20 08:32:28 $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<?php
$content = explode("\n", $content);

foreach ($content as $line):
	echo '<p> ' . $line . '</p>';
endforeach;
?>
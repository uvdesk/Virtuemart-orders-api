<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Wkapi
 * @author     WebKul software private limited  <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Wkapi', JPATH_COMPONENT);
JLoader::register('WkapiController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Wkapi');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

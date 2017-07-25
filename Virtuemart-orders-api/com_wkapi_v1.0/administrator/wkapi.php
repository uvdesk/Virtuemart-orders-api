<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Wkapi
 * @author     WebKul software private limited  <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_wkapi'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Wkapi', JPATH_COMPONENT_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Wkapi');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

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

/**
 * Class WkapiController
 *
 * @since  1.6
 */
class WkapiController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   mixed    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return   JController This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view = JFactory::getApplication()->input->getCmd('view', 'keys');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}

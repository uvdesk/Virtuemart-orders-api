<?php
/**
* @category   Component- Joomla Web Services
* @package		Joomla.component
* @author    WebKul software private limited 
* @copyright Copyright (C) 2010 webkul.com. All Rights reserved.
* @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @filesource  http://store.webkul.com
* @link Technical Support:  Forum - http://webkul.com/ticket
* @version 1.0
**/
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
jimport('joomla.html.html');
jimport('joomla.html.behavior');
JHTML::_('behavior.modal');
class WkapiViewKeys extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	public $can_register = null;
	/*public function __construct() {
		parent::__construct();
		$user = JFactory::getUser();
		if (!$user->get('id')){
			JFactory::getApplication()->redirect('index.php', JText::_('COM_API_NOT_AUTH_MSG'));
			exit();
		}
		$params = JComponentHelper::getParams('com_wkapi');
		//$this->set('can_register', $params->get('key_registration', false) && $user->get('gid') >= $params->get('key_registration_access', 18));
	}*/
	public function display($tpl = null) {
		$this->addToolBar();
		$this->items = $this->get('Items');
		$this->sidebar=JHtmlSidebar::render();
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->filterForm    = $this->get('FilterForm');
		$this->form    = $this->get('form');
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

     protected function addToolBar(){			
		JToolBarHelper::title(JText::_('COM_WKAPI_KEYS'),'key');
		$bar = JToolbar::getInstance('toolbar');
		//$bar->appendButton('Standard', 'edit', 'Edit','marketplace.editseller', false);
		
		$layout=JRequest::getVar('layout');
		if($layout=='edit'){
			JToolBarHelper::save('keys.saveCloseKey');
			JToolBarHelper::apply('keys.saveKey');		
			JToolbarHelper::cancel('keys.cancel');
		}
		else{
			JToolBarHelper::custom('keys.newToken','new.png','new','New',false);
			JToolbarHelper::publish('keys.enable');
			JToolbarHelper::unpublish('keys.disable');
			JToolbarHelper::custom('keys.delete','delete.png','delete','Delete',true);
			JToolBarHelper::preferences('com_wkapi');
		}
		JHtmlSidebar::setAction('index.php?option=com_wkapi&view=keys');		
	}

		
}

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

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class WkapiControllerKeys extends JControllerLegacy {

	public function display($cachable = false, $urlparams = array()) {
		parent::display();
	}

	private function checkAccess() {
		$user	= JFactory::getUser();

		if ($user->get('gid') == 25) :
			return true;
		endif;

		$params	= JComponentHelper::getParams('com_api');

		if (!$params->get('key_registration')) :
			return false;
		endif;

		$access_level = $params->get('key_registration_access');

		if ($user->get('gid') < $access_level) :
			return false;
		endif;

		return true;
	}
	public function enable(){
		$jInput=JFactory::getApplication()->input;
		$cid = $jInput->get( 'cid', array(0), 'post', 'array' );
		$msg=$this->getModel('keys')->enable($cid);
		$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys',false),count($cid).JText::_('SUCCESSFULY_PUBLISHED'),'success');
	}
	public function disable(){
		$jInput=JFactory::getApplication()->input;
		$cid = $jInput->get( 'cid', array(0), 'post', 'array' );
		$msg=$this->getModel('keys')->disable($cid);
		$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys',false),count($cid).JText::_('SUCCESSFULY_UNPUBLISHED'),'success');

	}
	public function delete(){
		$jInput=JFactory::getApplication()->input;
		$cid = $jInput->get( 'cid', array(0), 'post', 'array' );
		$msg=$this->getModel('keys')->delete($cid);
		$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys',false),count($cid).JText::_('SUCCESSFULY_DELETED'),'success');
	}
	public function generateAccessToken(){
		return bin2hex(openssl_random_pseudo_bytes(16));
	}
	function save(){
		$model=$this->getModel('keys');
		$jInput=JFactory::getApplication()->input;
		$jform=$jInput->post->get('jform', array(), 'array');
	/*	$jform['domain']=addslashes($jInput->get('domain'));*/		
		$jform['id']=$jInput->get('id');
		if(isset($jform['selectUser'])&& JString::strlen($jform['selectUser'])&&$jform['selectUser']>0){
			if($jform['id']==0){
				$jform['hash']=$this->generateAccessToken();
			}else if($jform['id']>0){
				$jform['hash']=$model->getHashByUser($jform['id']);
			}
			$this->successId=$model->saveKey($jform);

		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys&layout=edit&id='.$jform['id'],false),JText::_('PLEASE_SELECT_A_USER'),'error');
		}
	}
	public function saveKey(){
		$this->save();
		if(isset($this->successId)&& JString::strlen($this->successId)){
			$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys&layout=edit&id='.$this->successId,false),JText::_('TOKEN_SUCCESSFULLY_SAVED'),'success');
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys&layout=edit&id='.$this->successId,false),JText::_('SOMETHING_WAS_WRONG'),'error');
		}
	}
	public function saveCloseKey(){
		$this->save();
		if(isset($this->successId)&& JString::strlen($this->successId)){
			$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys',false),JText::_('TOKEN_SUCCESSFULLY_SAVED'),'success');
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys&layout=edit&id='.$this->successId,false),JText::_('SOMETHING_WAS_WRONG'),'error');
		}
	}
	public function cancel() {
		$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys', FALSE));
	}
	public function newToken(){
		$this->setRedirect(JRoute::_('index.php?option=com_wkapi&view=keys&layout=edit',false));
	}

}

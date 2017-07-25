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

defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class WkapiModelKeys extends JModelList{

	protected $option 		= null;
	protected $view			= null;
	protected $context		= null;
	protected $pagination 	= null;

	protected $list			= null;
	protected $total		= null;
	public function __construct($config = array()){
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'k.id',
				'u.name',
				'k.apikey',
				'k.domain',
				'k.created',
				'k.checked_out_time'
			);
		}
		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null){
    	$app = JFactory::getApplication();
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $search);
		$limit= $app->getUserStateFromRequest($this->context.'.limit', 'limit', '', 'string');
		$this->setState('limit', $limit);
		$limitstart= $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', '', 'string');
		$this->setState('limitstart', $limitstart);
		$filter_order= $app->getUserStateFromRequest($this->context.'.filter.order', 'filter_order', 'k.created', 'string');
		$this->setState('filter.order', $filter_order);
		$filter_order_Dir= $app->getUserStateFromRequest($this->context.'.filter.order_dir', 'filter_order_Dir', 'DESC', 'string');
		$this->setState('filter.order_dir', $filter_order_Dir);
		parent::populateState('k.id','DESC');
  	}
  	public function getForm($data = array(), $loadData = true){		
			$form = $this->loadForm('com_wkapi.keys','keys', array('control' => 'jform', 'load_data' => $loadData));
			if (empty($form)){
				return false;
			}				
		return $form;
	}
	public function enable($cid=array()){
		$db=$this->_db;
		$query=$db->getQuery(true);
		$query->update($db->quoteName('#__apikeys'))
		      ->set($db->quoteName('state')."=".$db->quote(1))
		      ->where($db->quoteName('id')." IN (".implode(',',$cid).")");
		$db->setQuery($query);
		return $db->execute();
	}
	public function disable($cid=array()){
		$db=$this->_db;
		$query=$db->getQuery(true);
		$query->update($db->quoteName('#__apikeys'))
		      ->set($db->quoteName('state')."=".$db->quote(0))
		      ->where($db->quoteName('id')." IN (".implode(',',$cid).")");
		$db->setQuery($query);
		return $db->execute();	
	}
	public function delete($cid=array()){
		$db=$this->_db;
		$query=$db->getQuery(true);
		$query->delete($db->quoteName('#__apikeys'))
		      ->where($db->quoteName('id')." IN (".implode(',',$cid).")");
		$db->setQuery($query);
		return $db->execute();	
	}
	public function getHashByUser($user){
		$db=$this->_db;
		$query=$db->getQuery(true);
		$query->select($db->quoteName('apikey'))
		      ->from($db->quoteName('#__apikeys'))
		      ->where($db->quoteName('id')."=".$db->quote($user));
		$db->setQuery($query);
		return $db->loadResult();
	}
	public function checkExistingUserById($id=0){
		$db=$this->_db;
		$query=$db->getQuery(true);
		$query->select($db->quoteName('userId'))
		      ->from($db->quoteName('#__apikeys'))
		      ->where($db->quoteName('id')."=".$db->quote($id));
		if($id>0){
			$db->setQuery($query);
			return $db->loadResult();
		}
		else{
			return null;
		}
	}
	public function getData($id){
		if(isset($id)){
			$db=$this->_db;
			$query=$db->getQuery(true);
			$query->select('*')->from($db->quoteName('#__apikeys'))->where($db->quoteName('id')."=".$db->quote($id));
			$db->setQuery($query);
			return $db->loadObject();
		}
	}
	public function saveKey($data=array()){
		$db=$this->_db;
		$insertQuery=$db->getQuery(true);
		$updateQuery=$db->getQuery(true);
		$getUser=$this->checkExistingUserById($data['id']);
		$updatedId=$data['id'];
	/*	echo "<pre>";
		print_r($data);die;*/
		$date=JFactory::getDate();
		$colums=array($db->quoteName('state'),$db->quoteName('checked_out_time'),$db->quoteName('created_by'),$db->quoteName('modified_by'),$db->quoteName('apikey'),$db->quoteName('userId'),$db->quoteName('domain'));
		$values=array($db->quote('1'),$db->quote($date->toSql()),$db->quote(JFactory::getUser()->id),$db->quote(JFactory::getUser()->id),$db->quote($data['hash']),$db->quote($data['selectUser']),$db->quote($data['domain']));
		$insertQuery->insert($db->quoteName('#__apikeys'))
		      		->columns($colums)
		      		->values(implode($values,','));
		      
		$updateQuery->update($db->quoteName('#__apikeys'))
		            ->set($db->quoteName('modified_by')."=".$db->quote(JFactory::getUser()->id))
		            ->set($db->quoteName('apikey')."=".$db->quote($data['hash']))
		            ->set($db->quoteName('domain')."=".$db->quote($data['domain']))
		            ->set($db->quoteName('userId')."=".$db->quote($data['selectUser']))
		            ->where($db->quoteName('id')."=".$db->quote($data['id']));
		if(isset($getUser )&& JString::strlen($getUser)){
			$db->setQuery($updateQuery);
			$db->execute();
						
		}else{
			$db->setQuery($insertQuery);
			try{
				$db->execute();
				if(!isset($getUser)||!JString::strlen($getUser)){
					$query=$db->getQuery(true);
					$query->select($db->quoteName('id'))
					      ->from($db->quoteName('#__apikeys'))
					      ->where($db->quoteName('userId')."=".$db->quote($data['selectUser']))
					      ->order('id DESC');
					$db->setQuery($query);
					$updatedId=$db->loadResult();
				}			
			}
			catch(Exception $e){
				JFactory::getApplication()->enqueueMessage($e->getMessage());
			}
		}
		return $updatedId;
	}
	public function getListQuery($override=false, $filter=true) {
		$db=$this->_db;
		$query=$db->getQuery(true);
		$search = $this->getState('filter.search');
		$status=$this->getState('filter.status');		
		$query->select('k.*');
		$query->select($db->quoteName('u.name'));
		$query->select($db->quoteName('u.username'));
		$query->from($db->quoteName('#__apikeys','k'));
		$query->join('LEFT',$db->quoteName('#__users','u')." ON ".$db->quoteName('u.id')."=".$db->quoteName('k.userid'));
		if (is_numeric($status)){
			$query->where('k.state = ' . (int) $status);
		}
		if ($this->getState('filter.search') !== '' && $this->getState('filter.search') !== null){
			$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($this->getState('filter.search')), true) . '%'));
			$searches   = array();
			$searches[] = 'u.id LIKE ' . $search;
			$searches[] = 'k.apikey LIKE ' . $search;
			$searches[] = 'k.domain LIKE ' . $search;
			$query->where('(' . implode(' OR ', $searches) . ')');
		}
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
		$query->where('k.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(k.state IN (0, 1))');
		}
		$orderCol       = $this->state->get('list.ordering', 'u.id');
		$orderDirn      = $this->state->get('list.direction', 'asc');
		if ($orderCol == 'id' || $orderCol == 'id') {
		$orderCol = 'id '.$orderDirn.', id';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;

	}
}

<?php

/**
 * @version    1.0
 * @package    Com_Wkapi
 * @author     WebKul software private limited  <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
/**
 * Class WkapiController
 *
 * @since  1.6
 */
if(!class_exists('REST')){
	require_once(JPATH_SITE.'/components/com_wkapi/Rest.inc.php');
}
class WkapiController extends JControllerLegacy
{
	public $rest;
	public $user;
   /**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	 public function __construct() {
	 	 $this->rest=new REST();
     	 parent::__construct();
     }
	public function display($cachable = false, $urlparams = false){
	    $app  = JFactory::getApplication();
        $view = $app->input->getCmd('view', 'keys');
        $api = new WkapiController();
        $jInput=$app->input;
        $key='';
        $checkKey=0;
        $model=$this->getModel('key');
        $key=JRequest::getVar('key');
		//getHeaders
		$appSite=JFactory::getApplication();
	    $header=apache_request_headers();
	    if(isset($header['authorization'])){

      	  $key=explode('Bearer ',$header['authorization']);
      	  if(isset($key[1])&&JString::strlen(JString::trim($key[1]))){
      	 	 $key=JString::trim($key[1]);
      	 	 $checkKey=$model->checkUserKey($key);
       		 $this->user=$checkKey;
      	 	}
      	}else{
      		 $this->rest->response(json_encode('Autherization Failed'),401,'json');
      	}       
        if($checkKey){
	   		$api->processApi();
			$app->input->set('view', $view);
			parent::display($cachable, $urlparams);
			return $this;
		}else{
			$dummy=array();
			$dummy['data']="Invalid Key";
			$this->rest->response(json_encode($dummy),401,'json');
			echo json_encode($dummy);
			JFactory::getApplication()->close();
		}
	}
	public function processApi(){
		$func=JRequest::getVar('task');
        if(isset($func)&&(int)method_exists($this,$func) > 0){
        	$this->$func();
        }
        else{
            $this->rest->response('Error code 404, Page not found',404,'json');   
        }
	}
	    function xml( $data, &$xml_data ) {
	    foreach( $data as $key => $value ) {
	        if( is_numeric($key) ){
	            $key = 'item'.$key;
	        }
	        if( is_array($value) ) {
	            $subnode = $xml_data->addChild($key);
	            $this->xml($value, $subnode);
	        } else {
	            $xml_data->addChild("$key",htmlspecialchars("$value"));
	        }
	     }
	     return $xml_data;
	}
	function html($array, $recursive = false, $null = '&nbsp;'){
	    if (empty($array) || !is_array($array)) {
	        return false;
	    }
	    if (!isset($array[0]) || !is_array($array[0])) {
	        $array = array($array);
	    }
	    $table = "<table class='table'>\n";
		$table .= "\t<tr>";
	    foreach (array_keys($array[0]) as $heading) {
	        $table .= '<th>' . $heading . '</th>';
	    }
	    $table .= "</tr>\n";
		foreach ($array as $row) {
	        $table .= "\t<tr>" ;
	        foreach ($row as $cell) {
	            $table .= '<td>';
	            if (is_object($cell)) { $cell = (array) $cell; }            
	            if ($recursive === true && is_array($cell) && !empty($cell)) {
	                $table .= "\n" . array2table($cell, true, true) . "\n";
	            } else {
	                $table .= (strlen($cell) > 0) ?
	                    htmlspecialchars((string) $cell) :
	                    $null;
	            }
	            $table .= '</td>';
	        }
	        $table .= "</tr>\n";
	    }
	    $table .= '</table>';
	    return $table;
	}
	private function getOrderData(){
		if(!class_exists('VmConfig')){
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/helpers/config.php');
			VmConfig::loadConfig();
		}
		
		VmConfig::loadJLang('com_virtuemart_orders',true);
		$countryCode=0;
		$stateCode=0;
		$app = JFactory::getApplication()->input;
		//getHeaders
		$header=apache_request_headers();
		
		$response['http_code']=$this->rest->_code;
		$response['status']=$this->rest->get_status_message();
		if(isset($header['countryid'])){
			$model=$this->getModel('key');
			if(!isset($header['stateid'])){
				$header['stateid']=0;
			}
			$responseData=$model->getCountryState($header['countryid'],$header['stateid'],$header['status'],$header['currencyid']);
			if(isset($responseData['countryName'])){
				$response['data']['countryName']=$responseData['countryName'];
				if(isset($responseData['stateName'])){
					$response['data']['stateName']=$responseData['stateName'];
				}
				else{
					$response['data']['stateName']=null;
				}
			}
			else{
				$response['data']='Country Id is incorrect';
			}
		}
		else{
			$response['data']['countryName']=null;
		}
		if(!isset($responseData['status'])){
			$response['data']['status']=null;
		}else{
			$response['data']['status']=vmText::_($responseData['status']);
		}
		if(!isset($responseData['currency'])){
			$response['data']['currency']=null;
		}else{
			$response['data']['currency']=$responseData['currency'];
		}
		$contentType=JRequest::getVar('format');
		$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
		if(JString::strtolower($contentType)=='xml'){
			$this->rest->response($this->xml($response,$xml_data)->asXML(), $this->rest->_code,'xml');
		}
		else if(JString::strtolower($contentType)=='json'){
			$this->rest->response(json_encode($response), $this->rest->_code,'json');
		}
		else if(JString::strtolower($contentType)=='html'){
			$this->rest->response($this->html($response,true), $this->rest->_code,'html');
		}else if(JString::strlen($contentType)){
			$this->rest->response(JText::_('THIS_FORMAT_DOES_NOT_SUPPORT'), $this->rest->_code,'json');
		}else{
			$this->rest->response(json_encode($response), $this->rest->_code,'json');
		}
		JFactory::getApplication()->close();


	}
	private function getOrder(){
		if(!class_exists('VmConfig')){
			require_once(JPATH_ADMINISTRATOR.'/components/com_virtuemart/helpers/config.php');
		}
		VmConfig::loadConfig();
		$orderModel=VmModel::getModel('orders');
		$response['http_code']=$this->rest->_code;
		$response['status']=$this->rest->get_status_message();
		$app = JFactory::getApplication()->input;
		$header=apache_request_headers();
		if(isset($header['orderid'])){
			$virtuemart_order_id=$header['orderid'];
			$orderId=$orderModel::getOrderIdByOrderNumber($virtuemart_order_id);
			$orderdata=$orderModel->getOrder($orderId);
			if(!isset($orderId)){
				$response['data']='Order id is incorrect';
			}else{
				$response['data']=$orderdata;
			}
		}else{

		}
		$contentType=JRequest::getVar('format');
		$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
		if(JString::strtolower($contentType)=='xml'){
			$this->rest->response($this->xml($response,$xml_data)->asXML(), $this->rest->_code,'xml');
		}
		else if(JString::strtolower($contentType)=='json'){
			$this->rest->response(json_encode($response), $this->rest->_code,'json');
		}
		else if(JString::strtolower($contentType)=='html'){
			$this->rest->response($this->html($response,true), $this->rest->_code,'html');
		}else if(JString::strlen($contentType)){
			$this->rest->response(JText::_('THIS_FORMAT_DOES_NOT_SUPPORT'), $this->rest->_code,'json');
		}else{
			$this->rest->response(json_encode($response), $this->rest->_code,'json');
		}
		JFactory::getApplication()->close();	 
	}

}


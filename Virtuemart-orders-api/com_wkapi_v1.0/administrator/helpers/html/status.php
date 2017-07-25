<?php
/*------------------------------------------------------------------------
# component_marketplace - Virtuemart MarketPlace Component 
# ------------------------------------------------------------------------
# author    WebKul software private limited 
# copyright Copyright (C) 2010 webkul.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://webkul.com
# Technical Support:  Forum - https://webkul.com/ticket/
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die();

class JHtmlStatus{	
	public static function requestStates()
	{

		
			$states = array(
				1 => array(
					'task'           => 'disable',
					'text'           => '',
					'active_title'   => 'COM_WKAPI_DISABLE_CATEGORY',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'publish',
					'inactive_class' => 'publish',
				),
				0 => array(
					'task'           => 'enable',
					'text'           => '',
					'active_title'   => 'COM_WKAPI_ENABLE_CATEGORY',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'unpublish',
					'inactive_class' => 'unpublish',
				)
			);
			
		return $states;
	}
/*	public static function productStates()
	{

		
			$states = array(
				1 => array(
					'task'           => 'unpublished',
					'text'           => '',
					'active_title'   => 'COM_MARKETPLACE_UNPUBLISHED',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'publish',
					'inactive_class' => 'publish',
				),
				0 => array(
					'task'           => 'published',
					'text'           => '',
					'active_title'   => 'COM_MARKETPLACE_PUBLISHED',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'unpublish',
					'inactive_class' => 'unpublish',
				)
			);
			
		return $states;
	}*/
	/*public static function adminproductStates()
	{

		
			$states = array(
				1 => array(
					'task'           => 'adminunpublished',
					'text'           => '',
					'active_title'   => 'COM_MARKETPLACE_UNPUBLISHED',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'publish',
					'inactive_class' => 'publish',
				),
				0 => array(
					'task'           => 'adminpublished',
					'text'           => '',
					'active_title'   => 'COM_MARKETPLACE_PUBLISHED',
					'inactive_title' => '',
					'tip'            => true,
					'active_class'   => 'unpublish',
					'inactive_class' => 'unpublish',
				)
			);
			
		return $states;
	}*/


}

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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.formvalidator');
$listDirn = $this->state->get('list.direction');
$listOrder= $this->state->get('list.ordering');
if(!class_exists('JHtmlStatus')){
	require_once(JPATH_ADMINISTRATOR.'/components/com_wkapi/helpers/html/status.php');
}
?>
<style type="text/css">
	th a,th a:hover{
		text-decoration: none!important;
		color: #FFF;
	}
</style>
<!-- <h1 class="componentheading"><?php echo JText::_('COM_WKAPI_REGISTERED_KEYS');?></h1> -->
<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_wkapi&view=keys'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
			<?php
			echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this,'options' => array('filterButton' =>false)));?>
			<div class="clearfix"> </div>
			<table class="table table-striped table-bordered" id="articleList" style="margin-top: 30px;">
				<thead>
					<tr bgcolor="#6994C4" style="color:#FFFFFF;vertical-align:top">
						<th width="" class="nowrap">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'WK_API_KEY_USER_NAME', 'u.name', $listDirn, $listOrder); ?>
						</th>
						<th width="" class="nowrap">
							<?php echo JHtml::_('searchtools.sort', 'WK_API_KEY_DOMAIN', 'k.domain', $listDirn, $listOrder); ?>
						</th>
						<th width="" class="center">
							
							<?php echo JHtml::_('searchtools.sort', 'WK_API_KEY_API_KEY', 'k.apikey', $listDirn, $listOrder); ?>
						</th>
						<th width="" class="nowrap">
							<?php echo JText::_('WK_API_KEY_STATUS')?>
						</th>
						<th width="" class="center">
							<?php echo JHtml::_('searchtools.sort', 'WK_API_KEY_LAST_USED', 'k.checked_out_time', $listDirn, $listOrder); ?>
						</th>
						
						<th width="">
							<?php echo JHTML::_('searchtools.sort',  'WK_API_KEY_ID', 'k.id', $listDirn, $listOrder ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="7">
						<?php  echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
					<tbody>
					<?php
					if ($this->items&&count ($this->items) > 0) {
						$k = 0;					
						for ($i=0, $n=count( $this->items ); $i < $n; $i++){
							$item = &$this->items[$i];
							$checked    = JHTML::_( 'grid.id', $i, $item->id );
							?>
							<tr class="<?php echo "row" . $k; ?>">
								
								<td>
									<?php  echo $checked; ?>
								</td>
								<td class="nowrap">
									
									<a href=<?php echo JRoute::_("index.php?option=com_users&view=user&layout=edit&id=".$item->userId)?> title="edit">
										<?php
										echo JFactory::getUser($item->userId)->name
										?>
									</a>
								</td>
								<td class="nowrap">
									<a href=<?php echo JRoute::_("index.php?option=com_wkapi&view=keys&layout=edit&id=".$item->id)?> title="edit"><?php echo $this->escape($item->domain); ?></a>
								</td>
								<td class="nowrap">
									<a href=<?php echo JRoute::_("index.php?option=com_wkapi&view=keys&layout=edit&id=".$item->id)?> title="edit">
									<?php
										echo $item->apikey;
									 ?></a>
								</td>
								<td class="center">
									
									<?php
											echo JHtml::_('jgrid.state', JHtmlStatus::requestStates(), $item->state, $i, 'keys.',$item->id);
									?>
								</td>
								<td class="nowrap">
									<a href=<?php echo JRoute::_("index.php?option=com_wkapi&view=keys&layout=edit&id=".$item->id)?> title="edit">
									<?php 
									$timestamp = strtotime($item->checked_out_time);
									echo date( 'd-m-Y H:i:s',$timestamp);?></a>
								</td>
								<td><a href=<?php echo JRoute::_("index.php?option=com_wkapi&view=keys&layout=edit&id=".$item->id)?> title="edit">
									<?php echo (int) $item->id; ?></a>
									
								</td>
							</tr>
							<?php
							$k = 1 - $k;
						}
						?>
						<?php
					}
					else{
						echo "<tr ><td colspan='7'><div class='alert alert-info'><strong>Info!</strong>".JText::_('NO_RECORD_FOUND')."</div></td></tr>";
						}
					?>
				</tbody>
			</table>
			<div class="alert alert-info">
				<div><strong>Help Document</strong></div>
				<table width="100%" class="table table-bordered">
					<tr>
						<th class="center">S. No.</th>
						<th class="center">purpose Of API</th>
						<th class="center">URL</th>
						<th class="center">Header Paramenter</th>
						<!-- <th class="center">Post Paramenter</th> -->
					</tr>
					<tr>
						<td class="center">1</td>
						<td class="center">To get Order Detail</td>
						<td class="center"><a href="javascript:void(0)"><?php echo JURI::root();?>index.php?option=com_wkapi&task=getOrder</a></td>
						<td class="center"><ol>
								<li>Autherization <b>:</b>  Bearer &lt;space&gt; YOUR_API_KEY</li>
								<li>orderid <b>:</b>  Virtuemart_Order_Number</li>
							</ol>
						</td>						
					</tr>
					<tr>
						<td class="center">2</td>
						<td class="center">To get Country and State</td>
						<td class="center"><a href="javascript:void(0)"><?php echo JURI::root();?>index.php?option=com_wkapi&task=getOrderData</a></td>
						<td class="center"><ol>
								<li>Autherization <b>:</b>  Bearer &lt;space&gt; YOUR_API_KEY</li>
								<li>countryid <b>:</b>  Virtuemart_Country_Id like 99</li>
								<li>stateid <b>:</b>  Virtuemart_State_Id like 254</li>
								<li>currencyid <b>:</b>  CURRENCY_ID like 144</li>
								<li>status <b>:</b>  ORDER_STATUS_CODE like P</li>
							</ol>
						</td>
									
					</tr>
				</table>

			</div>
		</div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</div>
</form>

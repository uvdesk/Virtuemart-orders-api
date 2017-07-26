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
JHTML::_('jquery.framework');
$model=$this->getModel('keys');
$id=JRequest::getVar('id');
$data=$model->getData($id);
?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="api_key_form" >
		<?php if(!empty( $this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
			<?php else : ?>
			<div id="j-main-container">
				<?php endif; ?>
				<table class="table table-bordered" >
					<tbody>
						<tr>
							<td>
								<?php echo $this->form->getLabel('keyid');?>
							</td>
							<td>
								<?php 
									if(isset($data->id)){
										$this->form->setValue('keyid','',$data->id);
									}
									echo $this->form->getInput('keyid');
								?>
							</td>
						</tr>
						<tr>
							<td style="max-width: 100px;">
								<?php echo $this->form->getLabel('selectUser');?>
							</td>
							<td >
								<?php 
								if(isset($data->userId)){
										$this->form->setValue('selectUser','',$data->userId);
									}
								echo $this->form->getInput('selectUser');
								?>
							</td>
						</tr>
						<tr>
							<td>
								<label><?php echo JText::_('WKAPI_DOMIN');?></label>
							</td>
							<td><?php
							$domain='';
								if(isset($data->domain)){
									$this->form->setValue('domain','',$data->domain);
								}
								echo $this->form->getInput('domain');
								?>
								<!-- <input type="text" name="domain" class="inputbox" value="<?php echo $domain?>" >					 -->		
							</td>
						</tr>
						
					</tbody>
				</table>
				<input type="hidden" name="option" id="option" value="com_wkapi" />
				<input type="hidden" name="task" id="task" value="keys.saveKey" />
				<input type="hidden" name="id" id="id" value="<?php echo JRequest::getVar('id')?>" />
				<!-- <input type="hidden" name="c" id="c" value="keys" /> -->
				<!-- <input type="hidden" name="return" value="<?php echo $this->return;?>" /> -->
				<input type="hidden" name="boxchecked" value="0" />
				<?php echo JHTML::_('form.token'); ?>
			</div>
		</div>
	</form>


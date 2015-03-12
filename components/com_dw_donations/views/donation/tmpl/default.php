<?php
/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_dw_donations');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_dw_donations')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_CREATED'); ?></th>
			<td><?php echo $this->item->created; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_MODIFIED'); ?></th>
			<td><?php echo $this->item->modified; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_DONOR_ID'); ?></th>
			<td><?php echo $this->item->donor_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_BENEFICIARY_ID'); ?></th>
			<td><?php echo $this->item->beneficiary_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_FNAME'); ?></th>
			<td><?php echo $this->item->fname; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_LNAME'); ?></th>
			<td><?php echo $this->item->lname; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_AMOUNT'); ?></th>
			<td><?php echo $this->item->amount; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_COUNTRY'); ?></th>
			<td><?php echo $this->item->country; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ANONYMOUS'); ?></th>
			<td><?php echo $this->item->anonymous; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_ORDER_CODE'); ?></th>
			<td><?php echo $this->item->order_code; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_TRANSACTION_ID'); ?></th>
			<td><?php echo $this->item->transaction_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_PARAMETERS'); ?></th>
			<td><?php echo $this->item->parameters; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_DONATION_LANGUAGE'); ?></th>
			<td><?php echo $this->item->language; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_dw_donations&task=donation.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_DW_DONATIONS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_dw_donations')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_dw_donations&task=donation.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_DW_DONATIONS_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_DW_DONATIONS_ITEM_NOT_LOADED');
endif;
?>

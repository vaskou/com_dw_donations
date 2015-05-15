<?php
/**
 * @version     1.1.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_dw_donations/assets/css/dw_donations.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
        
	js('input:hidden.donor_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('donor_idhidden')){
			js('#jform_donor_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_donor_id").trigger("liszt:updated");
	js('input:hidden.beneficiary_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('beneficiary_idhidden')){
			js('#jform_beneficiary_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_beneficiary_id").trigger("liszt:updated");
    });

    Joomla.submitbutton = function(task)
    {
        if (task == 'donation.cancel') {
            Joomla.submitform(task, document.getElementById('donation-form'));
        }
        else {
            
            if (task != 'donation.cancel' && document.formvalidator.isValid(document.id('donation-form'))) {
                
                Joomla.submitform(task, document.getElementById('donation-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_dw_donations&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="donation-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_DW_DONATIONS_TITLE_DONATION', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>

				<?php echo $this->form->getInput('created'); ?>
				<?php echo $this->form->getInput('modified'); ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('donor_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('donor_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->donor_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="donor_id" name="jform[donor_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('beneficiary_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('beneficiary_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->beneficiary_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="beneficiary_id" name="jform[beneficiary_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('fname'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('fname'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('lname'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('lname'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('amount'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('amount'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('country'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('anonymous'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('anonymous'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('payment_method'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('payment_method'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('order_code'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('order_code'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('transaction_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('transaction_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('parameters'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('parameters'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
			</div>


                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
<?php

defined('JPATH_BASE') or die;

$jinput = JFactory::getApplication()->input;

?>

<div class="uk-width-medium-1-1 payment-step-1" style=" <?php if($displayData['beneficiary_id']!=''){ echo 'display:none;';}?>" data-step="1">
	
	<div class="uk-text-large uk-text-center uk-hidden-small">
		<?php echo JText::_('COM_DW_DONATIONS_FORM_DONATION'); ?></br>
	</div>
	
	<form id="form-moneydonation-filters"  method="post" enctype="multipart/form-data" class="uk-form uk-container-center" >
    	
		<input type="hidden" name="req_type" value="ngo_list" />
        
		<input type="hidden" name="page" value="<?php echo $jinput->get('page',0);?>" id="ngo_page" />
       
	   <div class="ngo-form">
        	
			<div class="uk-grid uk-visible-small">
				<div class="uk-width-1-2">
					<?php echo JText::_('COM_DW_DONATIONS_FORM_DONATION'); ?></br>
				</div>
		
				<div class="uk-width-1-2 uk-form-row">
					<a class="uk-button uk-button-mini uk-width-1-1 uk-button-blank filter-button" data-uk-toggle="{target:'#list_filters',cls:'uk-hidden-small'}" onclick="jQuery('.filter-button').toggleClass('uk-hidden')"><?php echo JText::_('COM_DW_DONATIONS_FORM_SEARCH');?><i class="uk-icon-caret-down uk-margin-small-left"></i></a>
					<a class="uk-button uk-button-mini uk-width-1-1 uk-button-blank filter-button uk-hidden" data-uk-toggle="{target:'#list_filters',cls:'uk-hidden-small'}" onclick="jQuery('.filter-button').toggleClass('uk-hidden')"><?php echo JText::_('COM_DW_DONATIONS_FORM_SEARCH');?><i class="uk-icon-caret-up uk-margin-small-left"></i></a>
				</div>
            </div>
            
			<div id="list_filters" class="uk-form-row uk-grid uk-grid-small uk-hidden-small uk-margin-small-top" data-uk-margin>
            	<div class="uk-width-small-1-1">
            		<input class="search uk-form-large uk-width" placeholder="<?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_SEARCH');?>" type="text" />
                </div>
                <div class="uk-width-small-1-3">
	                <?php echo $displayData['objective']; ?>
                </div>
                <div class="uk-width-small-1-3">
	                <?php echo $displayData['actionarea']; ?>
                </div>
                <div class="uk-width-small-1-3">
					<?php echo JLayoutHelper::render('dwdonationform.sort_filter','',JPATH_ROOT . COMPONENT_PATH .'/layouts'); ?>
                </div>
            </div>
            
			<div class="ngo-list-wrapper uk-margin-top" id="ngo_list_wrapper">
                
				<div id="ngo_list" class="ngos-list">
                	<ul class="list uk-list uk-list-line">
                	<?php
						$ngo_list_row_params=array('ngo_list'=>$displayData['ngo_list']);
						echo JLayoutHelper::render('dwdonationform.ngo_list_row',$ngo_list_row_params,JPATH_ROOT . COMPONENT_PATH .'/layouts');
					?>
                    </ul>
                </div>
                
				<ul id="ngo_list_pagination" class="pagination uk-pagination uk-margin-small-top"></ul>
            
			</div>
            
			<div class="uk-form-row">
				
				<select class="uk-form-small uk-float-right ngo_filter" id="ngo_item_no_list" name="ngo_item_no">
					
					<option value="0"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_ITEMS_NO');?></option>
                    
					<?php 
                        
						$ngo_item_no=$jinput->get('ngo_item_no',0);
                        
						for($i=1;$i<=5;$i++){
                            $selected=($ngo_item_no==$i)?'selected="selected"':'';
                            echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                        }
                    ?>
                
				</select>
				
				<span class="uk-text-small uk-float-right uk-margin-small-right"><?php echo JText::_('COM_DW_DONATIONS_FORM_LBL_ITEMS_SHOW');?></span>

            
			</div>
        
		</div>
    
	</form>

</div>
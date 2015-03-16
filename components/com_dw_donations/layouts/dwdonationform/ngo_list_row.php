<?php

defined('JPATH_BASE') or die;

$ngos=$displayData['ngo_list'];
 
?>

<?php foreach($ngos as $id=>$ngo) : ?>

<li class="ngo-row " data-benef-id="<?php echo $id;?>">
    <div class="uk-grid uk-margin-small-top">
    
        <div class="uk-width-2-10 ">
            <div class="list-img uk-thumbnail">
                <img class="ngo_avatar" src="<?php echo $ngo['ngo_avatar'];?>"  alt="<?php echo $ngo['ngo_name']; ?>" title="<?php echo $ngo['ngo_name']; ?>" />
            </div>
        </div>
        
        <div class="uk-width-8-10 ">
            
            <div id="ngo_<?php echo $id; ?>" class="ngos ngoName uk-h3 uk-width-1-1 uk-text-right">
                <span class="ngo_name"><?php echo $ngo['ngo_name'];?></span><i class="uk-icon-long-arrow-right uk-margin-small-left"></i>
            </div>
            
            <div class="ngoObjectives uk-text-muted uk-text-small uk-text-right uk-width-1-1">
                <?php foreach($ngo['ngo_objectives'] as $ngo_objectives): ?>
                    <?php if(!empty($ngo_objectives)) :?>
                        <?php if($ngo_objectives!=reset($ngo['ngo_objectives'])) echo '<span>/</span>';?><span><?php echo JText::_($ngo_objectives);?></span>
                    <?php endif; ?>
                <?php endforeach;?>
            </div>
            
    
            <div class="ngoObjective" style="display:none">
                
                <?php foreach($ngo['ngo_objectives'] as $ngo_objectives) :?>
    
                        <?php if(!empty($ngo_objectives)) :?>
    
                            <span><?php echo $ngo_objectives;?></span>
                        <?php endif; ?>
                <?php endforeach; ?>
            
            </div>
            
            <div class="ngoActionArea" style="display:none;"><?php echo $ngo['ngo_actionarea'];?></div>
            
            <div class="ngoPriority" style="display:none;"><?php echo JText::_($ngo['ngo_priority']);?></div>
    
        </div>
        
    </div>		
</li>


	
<?php endforeach; ?>
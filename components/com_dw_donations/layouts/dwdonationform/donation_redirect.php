<?php
defined('JPATH_BASE') or die;

$sent_data=array();

$params=$displayData['params'];

if(isset($displayData['error'])){
	
	if($displayData['error']['error_text']==null){
		$displayData['error']['error_text']=JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST');
	}
	
	$sent_data=$displayData;
	
	$params['controller']->setMessage(JText::sprintf($displayData['error']['error_text'], $params['model']->getError()), 'warning');
	$params['controller']->setRedirect(JRoute::_('index.php?option=com_dw_donations&view=dwdonationform', false));
	
}elseif(isset($displayData['orderId'])){
	$sent_data=array('success'=>$displayData);
	
	$plus=(JFactory::getConfig()->get("sef")==1)?"?":"&";
	$params['controller']->setRedirect(JRoute::_('index.php?option=com_dw_donations&view=dwdonationredirect'.$plus.'orderId='.$displayData['orderId'], false));	
}
echo new JResponseJson($sent_data);

if(isset($params['jinput'])){
	$jinput=$params['jinput'];
	$isAjax=$jinput->get('ajax','0');
	if($isAjax){
		jexit();
	}
}
?>
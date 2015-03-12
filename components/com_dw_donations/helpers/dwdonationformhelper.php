<?php

defined('_JEXEC') or die;

class DwDonationFormHelper {
	
	public function fn_get_ngos_data()
	{	
		$cache = JFactory::getCache('com_dw_donations','');
		$cache->setCaching(true);
		if($data_array=$cache->get('data_array')){
			return $data_array;
		}
		$data_array=array();
		$ngos=self::getPaymentReceiverUsers();
		
		foreach($ngos as $ngo){
			$continue=false;
			$cuser = CFactory::getUser($ngo->id);
			
			$data_objectives = $cuser->getInfo('FIELD_OBJECTIVE');
			$data_actionarea = $cuser->getInfo('FIELD_ACTIONAREA');
			$data_avatar=$cuser->getThumbAvatar();
			$data_priority=$cuser->getInfo('FIELD_PRIORITY');
			
			$data_array[$ngo->id]['ngo_id']=$ngo->id;
			$data_array[$ngo->id]['ngo_name']=$ngo->name;
			$data_array[$ngo->id]['ngo_objectives']=explode(",",$data_objectives);
			$data_array[$ngo->id]['ngo_actionarea']=$data_actionarea;
			$data_array[$ngo->id]['ngo_avatar']=$data_avatar;
			$data_array[$ngo->id]['ngo_priority']=$data_priority;
			
			/*$sort_name[$ngo->id]=$ngo->name;
			$sort_priority[$ngo->id]=$data_priority;*/
		}
		
		/*array_multisort($sort_priority, SORT_DESC, $sort_name, SORT_ASC, $data_array);
		
		$temp=$data_array;
		$data_array=array();
		foreach($temp as $ngo){
			$data_array[$ngo['ngo_id']]=$ngo;
		}*/
		
		$cache->store($data_array,'data_array');
		return $data_array;
	}
	
	public function fn_get_beneficiary_info_ajax($ngo_id,$format)
	{
		$sent_data=array();
		$data_array=self::fn_get_ngos_data();
		$ngo_data=$data_array[$ngo_id];
		
		if($format=='ajax'){
			$html=JLayoutHelper::render('dwdonationform.beneficiary_info',$ngo_data,JPATH_ROOT.COMPONENT_PATH.'/layouts');
			$sent_data=array("ngo_info"=>array("html"=>$html,"ngo_data"=>$ngo_data));
			$sent_data=new JResponseJson($sent_data);
		}
		
		return $sent_data;
	}
	
	public function getPaymentReceiverUsers() {
        
		$params = JComponentHelper::getParams('com_dw_donations');
		$beneficiary_usergroups = $params->get('beneficiary_usergroups');
		$db = JFactory::getDBO();
		
		$selected_groups='(';
		$i=0;
		while($i<count($beneficiary_usergroups)-1)
		{
			$selected_groups.=$beneficiary_usergroups[$i].",";
			$i++;
		}
		$selected_groups.=$beneficiary_usergroups[$i];
		$selected_groups.=')';
		
		$query1='SELECT u.id,u.name FROM #__users as u INNER JOIN #__user_usergroup_map as ug on u.id=ug.user_id WHERE ug.group_id IN '.$selected_groups.' AND u.block=0 AND u.activation=""';
		$query2='SELECT cfv.user_id, cfv.field_id, cfv.value FROM #__community_fields as cf INNER JOIN #__community_fields_values as cfv ON cf.id=cfv.field_id WHERE cf.fieldcode="FIELD_PRIORITY"';
		$query3='SELECT a.id,a.name,CONVERT(b.value,UNSIGNED) as priority FROM ('.$query1.') as a INNER JOIN ('.$query2.') as b ON a.id=b.user_id ORDER BY CONVERT(b.value,UNSIGNED) DESC, a.name';

        $db->setQuery($query3);
        $db->Query();

        $ids = $db->loadColumn();
		//var_dump($ids);

        CFactory::loadUsers($ids);

        $users = array();
        foreach ($ids as $id) {
            $users[] = CFactory::getUser($id);
        }

        return $users;
    }
}

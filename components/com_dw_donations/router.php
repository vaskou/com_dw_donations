<?php

/**
 * @version     1.1.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;
include_once JPATH_ROOT.'/components/com_community/libraries/core.php';
/**
 * @param	array	A named array
 * @return	array
 */
function Dw_donationsBuildRoute(&$query) {
    $segments = array();

    if (isset($query['task'])) {
        $segments[] = implode('/', explode('.', $query['task']));
        unset($query['task']);
    }
    if (isset($query['view'])) {
       // $segments[] = $query['view'];
        unset($query['view']);
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    }
	if (isset($query['beneficiary_id'])) {
		
		$user=CFactory::getUser($query['beneficiary_id']);
		
        $segments[] = $user->_alias;//$query['beneficiary_id'];
        unset($query['beneficiary_id']);
    }

    return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/dw_donations/task/id/Itemid
 *
 * index.php?/dw_donations/id/Itemid
 */
function Dw_donationsParseRoute($segments) {
    $vars = array();

    // view is always the first element of the array
    $vars['view'] = array_shift($segments);
	$alias=array_pop($segments);
	$vars['beneficiary_id'] = getUserIdByAlias($alias);

    while (!empty($segments)) {
        $segment = array_pop($segments);
        if (is_numeric($segment)) {
            $vars['id'] = $segment;
        } else {
            $vars['task'] = $vars['view'] . '.' . $segment;
        }
    }

    return $vars;
}

function getUserIdByAlias($alias)
{
	$db = JFactory::getDBO();
	
	$query="SELECT userid,alias FROM #__community_users WHERE alias='".$alias."'";

    $db->setQuery($query);
    $db->Query();

    $id = $db->loadResult();
	
	return $id;
}
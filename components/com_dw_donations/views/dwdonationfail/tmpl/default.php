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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_dw_donations');
$doc = JFactory::getDocument();

$app = Jfactory::getApplication();
$payment=$app->getUserState('com_dw_donations.payment.data');

//$session = JFactory::getSession();
var_dump($payment);

?>
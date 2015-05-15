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

jimport('joomla.application.component.controllerform');

/**
 * Donation controller class.
 */
class Dw_donationsControllerDonation extends JControllerForm
{

    function __construct() {
        $this->view_list = 'donations';
        parent::__construct();
    }

}
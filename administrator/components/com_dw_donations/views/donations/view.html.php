<?php

/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Dw_donations.
 */
class Dw_donationsViewDonations extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        Dw_donationsHelper::addSubmenu('donations');

        $this->addToolbar();

        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        require_once JPATH_COMPONENT . '/helpers/dw_donations.php';

        $state = $this->get('State');
        $canDo = Dw_donationsHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_DW_DONATIONS_TITLE_DONATIONS'), 'donations.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/donation';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('donation.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('donation.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('donations.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('donations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'donations.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('donations.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('donations.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'donations.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('donations.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_dw_donations');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_dw_donations&view=donations');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

			//Filter for the field created
			$this->extra_sidebar .= '<small><label for="filter_from_created">'. JText::sprintf('COM_DW_DONATIONS_FROM_FILTER', 'Created') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created.from'), 'filter_from_created', 'filter_from_created', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_created">'. JText::sprintf('COM_DW_DONATIONS_TO_FILTER', 'Created') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.created.to'), 'filter_to_created', 'filter_to_created', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

			//Filter for the field modified
			$this->extra_sidebar .= '<small><label for="filter_from_modified">'. JText::sprintf('COM_DW_DONATIONS_FROM_FILTER', 'Modified') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.modified.from'), 'filter_from_modified', 'filter_from_modified', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange' => 'this.form.submit();'));
			$this->extra_sidebar .= '<small><label for="filter_to_modified">'. JText::sprintf('COM_DW_DONATIONS_TO_FILTER', 'Modified') .'</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.modified.to'), 'filter_to_modified', 'filter_to_modified', '%Y-%m-%d', array('style' => 'width:142px;', 'onchange'=> 'this.form.submit();'));
			$this->extra_sidebar .= '<hr class="hr-condensed">';

		//Filter for the field country
		$select_label = JText::sprintf('COM_DW_DONATIONS_FILTER_SELECT_LABEL', 'Country');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "AD";
		$options[0]->text = "Andorra";
		$options[1] = new stdClass();
		$options[1]->value = "AE";
		$options[1]->text = "United Arab Emirates";
		$options[2] = new stdClass();
		$options[2]->value = "AF";
		$options[2]->text = "Afghanistan";
		$options[3] = new stdClass();
		$options[3]->value = "AG";
		$options[3]->text = "Antigua and Barbuda";
		$options[4] = new stdClass();
		$options[4]->value = "AI";
		$options[4]->text = "Anguilla";
		$options[5] = new stdClass();
		$options[5]->value = "AL";
		$options[5]->text = "Albania";
		$options[6] = new stdClass();
		$options[6]->value = "AM";
		$options[6]->text = "Armenia";
		$options[7] = new stdClass();
		$options[7]->value = "AO";
		$options[7]->text = "Angola";
		$options[8] = new stdClass();
		$options[8]->value = "AQ";
		$options[8]->text = "Antarctica";
		$options[9] = new stdClass();
		$options[9]->value = "AR";
		$options[9]->text = "Argentina";
		$options[10] = new stdClass();
		$options[10]->value = "AS";
		$options[10]->text = "American Samoa";
		$options[11] = new stdClass();
		$options[11]->value = "AT";
		$options[11]->text = "Austria";
		$options[12] = new stdClass();
		$options[12]->value = "AU";
		$options[12]->text = "Australia";
		$options[13] = new stdClass();
		$options[13]->value = "AW";
		$options[13]->text = "Aruba";
		$options[14] = new stdClass();
		$options[14]->value = "AX";
		$options[14]->text = "Åland";
		$options[15] = new stdClass();
		$options[15]->value = "AZ";
		$options[15]->text = "Azerbaijan";
		$options[16] = new stdClass();
		$options[16]->value = "BA";
		$options[16]->text = "Bosnia and Herzegovina";
		$options[17] = new stdClass();
		$options[17]->value = "BB";
		$options[17]->text = "Barbados";
		$options[18] = new stdClass();
		$options[18]->value = "BD";
		$options[18]->text = "Bangladesh";
		$options[19] = new stdClass();
		$options[19]->value = "BE";
		$options[19]->text = "Belgium";
		$options[20] = new stdClass();
		$options[20]->value = "BF";
		$options[20]->text = "Burkina Faso";
		$options[21] = new stdClass();
		$options[21]->value = "BG";
		$options[21]->text = "Bulgaria";
		$options[22] = new stdClass();
		$options[22]->value = "BH";
		$options[22]->text = "Bahrain";
		$options[23] = new stdClass();
		$options[23]->value = "BI";
		$options[23]->text = "Burundi";
		$options[24] = new stdClass();
		$options[24]->value = "BJ";
		$options[24]->text = "Benin";
		$options[25] = new stdClass();
		$options[25]->value = "BL";
		$options[25]->text = "Saint Barthélemy";
		$options[26] = new stdClass();
		$options[26]->value = "BM";
		$options[26]->text = "Bermuda";
		$options[27] = new stdClass();
		$options[27]->value = "BN";
		$options[27]->text = "Brunei";
		$options[28] = new stdClass();
		$options[28]->value = "BO";
		$options[28]->text = "Bolivia";
		$options[29] = new stdClass();
		$options[29]->value = "BQ";
		$options[29]->text = "Bonaire";
		$options[30] = new stdClass();
		$options[30]->value = "BR";
		$options[30]->text = "Brazil";
		$options[31] = new stdClass();
		$options[31]->value = "BS";
		$options[31]->text = "Bahamas";
		$options[32] = new stdClass();
		$options[32]->value = "BT";
		$options[32]->text = "Bhutan";
		$options[33] = new stdClass();
		$options[33]->value = "BV";
		$options[33]->text = "Bouvet Island";
		$options[34] = new stdClass();
		$options[34]->value = "BW";
		$options[34]->text = "Botswana";
		$options[35] = new stdClass();
		$options[35]->value = "BY";
		$options[35]->text = "Belarus";
		$options[36] = new stdClass();
		$options[36]->value = "BZ";
		$options[36]->text = "Belize";
		$options[37] = new stdClass();
		$options[37]->value = "CA";
		$options[37]->text = "Canada";
		$options[38] = new stdClass();
		$options[38]->value = "CC";
		$options[38]->text = "Cocos [Keeling] Islands";
		$options[39] = new stdClass();
		$options[39]->value = "CD";
		$options[39]->text = "Democratic Republic of the Congo";
		$options[40] = new stdClass();
		$options[40]->value = "CF";
		$options[40]->text = "Central African Republic";
		$options[41] = new stdClass();
		$options[41]->value = "CG";
		$options[41]->text = "Republic of the Congo";
		$options[42] = new stdClass();
		$options[42]->value = "CH";
		$options[42]->text = "Switzerland";
		$options[43] = new stdClass();
		$options[43]->value = "CI";
		$options[43]->text = "Ivory Coast";
		$options[44] = new stdClass();
		$options[44]->value = "CK";
		$options[44]->text = "Cook Islands";
		$options[45] = new stdClass();
		$options[45]->value = "CL";
		$options[45]->text = "Chile";
		$options[46] = new stdClass();
		$options[46]->value = "CM";
		$options[46]->text = "Cameroon";
		$options[47] = new stdClass();
		$options[47]->value = "CN";
		$options[47]->text = "China";
		$options[48] = new stdClass();
		$options[48]->value = "CO";
		$options[48]->text = "Colombia";
		$options[49] = new stdClass();
		$options[49]->value = "CR";
		$options[49]->text = "Costa Rica";
		$options[50] = new stdClass();
		$options[50]->value = "CU";
		$options[50]->text = "Cuba";
		$options[51] = new stdClass();
		$options[51]->value = "CV";
		$options[51]->text = "Cape Verde";
		$options[52] = new stdClass();
		$options[52]->value = "CW";
		$options[52]->text = "Curacao";
		$options[53] = new stdClass();
		$options[53]->value = "CX";
		$options[53]->text = "Christmas Island";
		$options[54] = new stdClass();
		$options[54]->value = "CY";
		$options[54]->text = "Cyprus";
		$options[55] = new stdClass();
		$options[55]->value = "CZ";
		$options[55]->text = "Czech Republic";
		$options[56] = new stdClass();
		$options[56]->value = "DE";
		$options[56]->text = "Germany";
		$options[57] = new stdClass();
		$options[57]->value = "DJ";
		$options[57]->text = "Djibouti";
		$options[58] = new stdClass();
		$options[58]->value = "DK";
		$options[58]->text = "Denmark";
		$options[59] = new stdClass();
		$options[59]->value = "DM";
		$options[59]->text = "Dominica";
		$options[60] = new stdClass();
		$options[60]->value = "DO";
		$options[60]->text = "Dominican Republic";
		$options[61] = new stdClass();
		$options[61]->value = "DZ";
		$options[61]->text = "Algeria";
		$options[62] = new stdClass();
		$options[62]->value = "EC";
		$options[62]->text = "Ecuador";
		$options[63] = new stdClass();
		$options[63]->value = "EE";
		$options[63]->text = "Estonia";
		$options[64] = new stdClass();
		$options[64]->value = "EG";
		$options[64]->text = "Egypt";
		$options[65] = new stdClass();
		$options[65]->value = "EH";
		$options[65]->text = "Western Sahara";
		$options[66] = new stdClass();
		$options[66]->value = "ER";
		$options[66]->text = "Eritrea";
		$options[67] = new stdClass();
		$options[67]->value = "ES";
		$options[67]->text = "Spain";
		$options[68] = new stdClass();
		$options[68]->value = "ET";
		$options[68]->text = "Ethiopia";
		$options[69] = new stdClass();
		$options[69]->value = "FI";
		$options[69]->text = "Finland";
		$options[70] = new stdClass();
		$options[70]->value = "FJ";
		$options[70]->text = "Fiji";
		$options[71] = new stdClass();
		$options[71]->value = "FK";
		$options[71]->text = "Falkland Islands";
		$options[72] = new stdClass();
		$options[72]->value = "FM";
		$options[72]->text = "Micronesia";
		$options[73] = new stdClass();
		$options[73]->value = "FO";
		$options[73]->text = "Faroe Islands";
		$options[74] = new stdClass();
		$options[74]->value = "FR";
		$options[74]->text = "France";
		$options[75] = new stdClass();
		$options[75]->value = "GA";
		$options[75]->text = "Gabon";
		$options[76] = new stdClass();
		$options[76]->value = "GB";
		$options[76]->text = "United Kingdom";
		$options[77] = new stdClass();
		$options[77]->value = "GD";
		$options[77]->text = "Grenada";
		$options[78] = new stdClass();
		$options[78]->value = "GE";
		$options[78]->text = "Georgia";
		$options[79] = new stdClass();
		$options[79]->value = "GF";
		$options[79]->text = "French Guiana";
		$options[80] = new stdClass();
		$options[80]->value = "GG";
		$options[80]->text = "Guernsey";
		$options[81] = new stdClass();
		$options[81]->value = "GH";
		$options[81]->text = "Ghana";
		$options[82] = new stdClass();
		$options[82]->value = "GI";
		$options[82]->text = "Gibraltar";
		$options[83] = new stdClass();
		$options[83]->value = "GL";
		$options[83]->text = "Greenland";
		$options[84] = new stdClass();
		$options[84]->value = "GM";
		$options[84]->text = "Gambia";
		$options[85] = new stdClass();
		$options[85]->value = "GN";
		$options[85]->text = "Guinea";
		$options[86] = new stdClass();
		$options[86]->value = "GP";
		$options[86]->text = "Guadeloupe";
		$options[87] = new stdClass();
		$options[87]->value = "GQ";
		$options[87]->text = "Equatorial Guinea";
		$options[88] = new stdClass();
		$options[88]->value = "GR";
		$options[88]->text = "Greece";
		$options[89] = new stdClass();
		$options[89]->value = "GS";
		$options[89]->text = "South Georgia and the South Sandwich Islands";
		$options[90] = new stdClass();
		$options[90]->value = "GT";
		$options[90]->text = "Guatemala";
		$options[91] = new stdClass();
		$options[91]->value = "GU";
		$options[91]->text = "Guam";
		$options[92] = new stdClass();
		$options[92]->value = "GW";
		$options[92]->text = "Guinea-Bissau";
		$options[93] = new stdClass();
		$options[93]->value = "GY";
		$options[93]->text = "Guyana";
		$options[94] = new stdClass();
		$options[94]->value = "HK";
		$options[94]->text = "Hong Kong";
		$options[95] = new stdClass();
		$options[95]->value = "HM";
		$options[95]->text = "Heard Island and McDonald Islands";
		$options[96] = new stdClass();
		$options[96]->value = "HN";
		$options[96]->text = "Honduras";
		$options[97] = new stdClass();
		$options[97]->value = "HR";
		$options[97]->text = "Croatia";
		$options[98] = new stdClass();
		$options[98]->value = "HT";
		$options[98]->text = "Haiti";
		$options[99] = new stdClass();
		$options[99]->value = "HU";
		$options[99]->text = "Hungary";
		$options[100] = new stdClass();
		$options[100]->value = "ID";
		$options[100]->text = "Indonesia";
		$options[101] = new stdClass();
		$options[101]->value = "IE";
		$options[101]->text = "Ireland";
		$options[102] = new stdClass();
		$options[102]->value = "IL";
		$options[102]->text = "Israel";
		$options[103] = new stdClass();
		$options[103]->value = "IM";
		$options[103]->text = "Isle of Man";
		$options[104] = new stdClass();
		$options[104]->value = "IN";
		$options[104]->text = "India";
		$options[105] = new stdClass();
		$options[105]->value = "IO";
		$options[105]->text = "British Indian Ocean Territory";
		$options[106] = new stdClass();
		$options[106]->value = "IQ";
		$options[106]->text = "Iraq";
		$options[107] = new stdClass();
		$options[107]->value = "IR";
		$options[107]->text = "Iran";
		$options[108] = new stdClass();
		$options[108]->value = "IS";
		$options[108]->text = "Iceland";
		$options[109] = new stdClass();
		$options[109]->value = "IT";
		$options[109]->text = "Italy";
		$options[110] = new stdClass();
		$options[110]->value = "JE";
		$options[110]->text = "Jersey";
		$options[111] = new stdClass();
		$options[111]->value = "JM";
		$options[111]->text = "Jamaica";
		$options[112] = new stdClass();
		$options[112]->value = "JO";
		$options[112]->text = "Jordan";
		$options[113] = new stdClass();
		$options[113]->value = "JP";
		$options[113]->text = "Japan";
		$options[114] = new stdClass();
		$options[114]->value = "KE";
		$options[114]->text = "Kenya";
		$options[115] = new stdClass();
		$options[115]->value = "KG";
		$options[115]->text = "Kyrgyzstan";
		$options[116] = new stdClass();
		$options[116]->value = "KH";
		$options[116]->text = "Cambodia";
		$options[117] = new stdClass();
		$options[117]->value = "KI";
		$options[117]->text = "Kiribati";
		$options[118] = new stdClass();
		$options[118]->value = "KM";
		$options[118]->text = "Comoros";
		$options[119] = new stdClass();
		$options[119]->value = "KN";
		$options[119]->text = "aint Kitts and Nevis";
		$options[120] = new stdClass();
		$options[120]->value = "KP";
		$options[120]->text = "North Korea";
		$options[121] = new stdClass();
		$options[121]->value = "KR";
		$options[121]->text = "South Korea";
		$options[122] = new stdClass();
		$options[122]->value = "KW";
		$options[122]->text = "Kuwait";
		$options[123] = new stdClass();
		$options[123]->value = "KY";
		$options[123]->text = "Cayman Islands";
		$options[124] = new stdClass();
		$options[124]->value = "KZ";
		$options[124]->text = "Kazakhstan";
		$options[125] = new stdClass();
		$options[125]->value = "LA";
		$options[125]->text = "Lao People's Democratic Republic";
		$options[126] = new stdClass();
		$options[126]->value = "LB";
		$options[126]->text = "Lebanon";
		$options[127] = new stdClass();
		$options[127]->value = "LC";
		$options[127]->text = "Saint Lucia";
		$options[128] = new stdClass();
		$options[128]->value = "LI";
		$options[128]->text = "Liechtenstein";
		$options[129] = new stdClass();
		$options[129]->value = "LK";
		$options[129]->text = "Sri Lanka";
		$options[130] = new stdClass();
		$options[130]->value = "LR";
		$options[130]->text = "Liberia";
		$options[131] = new stdClass();
		$options[131]->value = "LS";
		$options[131]->text = "Lesotho";
		$options[132] = new stdClass();
		$options[132]->value = "LT";
		$options[132]->text = "Lithuania";
		$options[133] = new stdClass();
		$options[133]->value = "LU";
		$options[133]->text = "Luxembourg";
		$options[134] = new stdClass();
		$options[134]->value = "LV";
		$options[134]->text = "Latvia";
		$options[135] = new stdClass();
		$options[135]->value = "LY";
		$options[135]->text = "Libya";
		$options[136] = new stdClass();
		$options[136]->value = "MA";
		$options[136]->text = "Morocco";
		$options[137] = new stdClass();
		$options[137]->value = "MC";
		$options[137]->text = "Monaco";
		$options[138] = new stdClass();
		$options[138]->value = "MD";
		$options[138]->text = "Moldova, Republic of";
		$options[139] = new stdClass();
		$options[139]->value = "ME";
		$options[139]->text = "Montenegro";
		$options[140] = new stdClass();
		$options[140]->value = "MF";
		$options[140]->text = "Saint Martin (French part)";
		$options[141] = new stdClass();
		$options[141]->value = "MG";
		$options[141]->text = "Madagascar";
		$options[142] = new stdClass();
		$options[142]->value = "MH";
		$options[142]->text = "Marshall Islands";
		$options[143] = new stdClass();
		$options[143]->value = "MK";
		$options[143]->text = "Macedonia, the former Yugoslav Republic of";
		$options[144] = new stdClass();
		$options[144]->value = "ML";
		$options[144]->text = "Mali";
		$options[145] = new stdClass();
		$options[145]->value = "MM";
		$options[145]->text = "Myanmar";
		$options[146] = new stdClass();
		$options[146]->value = "MN";
		$options[146]->text = "Mongolia";
		$options[147] = new stdClass();
		$options[147]->value = "MO";
		$options[147]->text = "Macao";
		$options[148] = new stdClass();
		$options[148]->value = "MP";
		$options[148]->text = "Northern Mariana Islands";
		$options[149] = new stdClass();
		$options[149]->value = "MQ";
		$options[149]->text = "Martinique";
		$options[150] = new stdClass();
		$options[150]->value = "MR";
		$options[150]->text = "Mauritania";
		$options[151] = new stdClass();
		$options[151]->value = "MS";
		$options[151]->text = "Montserrat";
		$options[152] = new stdClass();
		$options[152]->value = "MT";
		$options[152]->text = "Malta";
		$options[153] = new stdClass();
		$options[153]->value = "MU";
		$options[153]->text = "Mauritius";
		$options[154] = new stdClass();
		$options[154]->value = "MV";
		$options[154]->text = "Maldives";
		$options[155] = new stdClass();
		$options[155]->value = "MW";
		$options[155]->text = "Malawi";
		$options[156] = new stdClass();
		$options[156]->value = "MX";
		$options[156]->text = "Mexico";
		$options[157] = new stdClass();
		$options[157]->value = "MY";
		$options[157]->text = "Malaysia";
		$options[158] = new stdClass();
		$options[158]->value = "MZ";
		$options[158]->text = "Mozambique";
		$options[159] = new stdClass();
		$options[159]->value = "NA";
		$options[159]->text = "Namibia";
		$options[160] = new stdClass();
		$options[160]->value = "NC";
		$options[160]->text = "New Caledonia";
		$options[161] = new stdClass();
		$options[161]->value = "NE";
		$options[161]->text = "Niger";
		$options[162] = new stdClass();
		$options[162]->value = "NF";
		$options[162]->text = "Norfolk Island";
		$options[163] = new stdClass();
		$options[163]->value = "NG";
		$options[163]->text = "Nigeria";
		$options[164] = new stdClass();
		$options[164]->value = "NI";
		$options[164]->text = "Nicaragua";
		$options[165] = new stdClass();
		$options[165]->value = "NL";
		$options[165]->text = "Netherlands";
		$options[166] = new stdClass();
		$options[166]->value = "NO";
		$options[166]->text = "Norway";
		$options[167] = new stdClass();
		$options[167]->value = "NP";
		$options[167]->text = "Nepal";
		$options[168] = new stdClass();
		$options[168]->value = "NR";
		$options[168]->text = "Nauru";
		$options[169] = new stdClass();
		$options[169]->value = "NU";
		$options[169]->text = "Niue";
		$options[170] = new stdClass();
		$options[170]->value = "NZ";
		$options[170]->text = "New Zealand";
		$options[171] = new stdClass();
		$options[171]->value = "OM";
		$options[171]->text = "Oman";
		$options[172] = new stdClass();
		$options[172]->value = "PA";
		$options[172]->text = "Panama";
		$options[173] = new stdClass();
		$options[173]->value = "PE";
		$options[173]->text = "Peru";
		$options[174] = new stdClass();
		$options[174]->value = "PF";
		$options[174]->text = "French Polynesia";
		$options[175] = new stdClass();
		$options[175]->value = "PG";
		$options[175]->text = "Papua New Guinea";
		$options[176] = new stdClass();
		$options[176]->value = "PH";
		$options[176]->text = "Philippines";
		$options[177] = new stdClass();
		$options[177]->value = "PK";
		$options[177]->text = "Pakistan";
		$options[178] = new stdClass();
		$options[178]->value = "PL";
		$options[178]->text = "Poland";
		$options[179] = new stdClass();
		$options[179]->value = "PM";
		$options[179]->text = "Saint Pierre and Miquelon";
		$options[180] = new stdClass();
		$options[180]->value = "PN";
		$options[180]->text = "Pitcairn";
		$options[181] = new stdClass();
		$options[181]->value = "PR";
		$options[181]->text = "Puerto Rico";
		$options[182] = new stdClass();
		$options[182]->value = "PS";
		$options[182]->text = "Palestine, State of";
		$options[183] = new stdClass();
		$options[183]->value = "PT";
		$options[183]->text = "Portugal";
		$options[184] = new stdClass();
		$options[184]->value = "PW";
		$options[184]->text = "Palau";
		$options[185] = new stdClass();
		$options[185]->value = "PY";
		$options[185]->text = "Paraguay";
		$options[186] = new stdClass();
		$options[186]->value = "QA";
		$options[186]->text = "Qatar";
		$options[187] = new stdClass();
		$options[187]->value = "RE";
		$options[187]->text = "Réunion";
		$options[188] = new stdClass();
		$options[188]->value = "RO";
		$options[188]->text = "Romania";
		$options[189] = new stdClass();
		$options[189]->value = "RS";
		$options[189]->text = "Serbia";
		$options[190] = new stdClass();
		$options[190]->value = "RU";
		$options[190]->text = "Russian Federation";
		$options[191] = new stdClass();
		$options[191]->value = "RW";
		$options[191]->text = "Rwanda";
		$options[192] = new stdClass();
		$options[192]->value = "SA";
		$options[192]->text = "Saudi Arabia";
		$options[193] = new stdClass();
		$options[193]->value = "SB";
		$options[193]->text = "Solomon Islands";
		$options[194] = new stdClass();
		$options[194]->value = "SC";
		$options[194]->text = "Seychelles";
		$options[195] = new stdClass();
		$options[195]->value = "SD";
		$options[195]->text = "Sudan";
		$options[196] = new stdClass();
		$options[196]->value = "SE";
		$options[196]->text = "Sweden";
		$options[197] = new stdClass();
		$options[197]->value = "SG";
		$options[197]->text = "Singapore";
		$options[198] = new stdClass();
		$options[198]->value = "SH";
		$options[198]->text = "Saint Helena, Ascension and Tristan da Cunha";
		$options[199] = new stdClass();
		$options[199]->value = "SI";
		$options[199]->text = "Slovenia";
		$options[200] = new stdClass();
		$options[200]->value = "SJ";
		$options[200]->text = "Svalbard and Jan Mayen";
		$options[201] = new stdClass();
		$options[201]->value = "SK";
		$options[201]->text = "Slovakia";
		$options[202] = new stdClass();
		$options[202]->value = "SL";
		$options[202]->text = "Sierra Leone";
		$options[203] = new stdClass();
		$options[203]->value = "SM";
		$options[203]->text = "San Marino";
		$options[204] = new stdClass();
		$options[204]->value = "SN";
		$options[204]->text = "Senegal";
		$options[205] = new stdClass();
		$options[205]->value = "SO";
		$options[205]->text = "Somalia";
		$options[206] = new stdClass();
		$options[206]->value = "SR";
		$options[206]->text = "Suriname";
		$options[207] = new stdClass();
		$options[207]->value = "SS";
		$options[207]->text = "South Sudan";
		$options[208] = new stdClass();
		$options[208]->value = "ST";
		$options[208]->text = "Sao Tome and Principe";
		$options[209] = new stdClass();
		$options[209]->value = "SV";
		$options[209]->text = "El Salvador";
		$options[210] = new stdClass();
		$options[210]->value = "SX";
		$options[210]->text = "Sint Maarten (Dutch part)";
		$options[211] = new stdClass();
		$options[211]->value = "SY";
		$options[211]->text = "Syrian Arab Republic";
		$options[212] = new stdClass();
		$options[212]->value = "SZ";
		$options[212]->text = "Swaziland";
		$options[213] = new stdClass();
		$options[213]->value = "TC";
		$options[213]->text = "Turks and Caicos Islands";
		$options[214] = new stdClass();
		$options[214]->value = "TD";
		$options[214]->text = "Chad";
		$options[215] = new stdClass();
		$options[215]->value = "TF";
		$options[215]->text = "French Southern Territories";
		$options[216] = new stdClass();
		$options[216]->value = "TG";
		$options[216]->text = "Togo";
		$options[217] = new stdClass();
		$options[217]->value = "TH";
		$options[217]->text = "Thailand";
		$options[218] = new stdClass();
		$options[218]->value = "TJ";
		$options[218]->text = "Tajikistan";
		$options[219] = new stdClass();
		$options[219]->value = "TK";
		$options[219]->text = "Tokelau";
		$options[220] = new stdClass();
		$options[220]->value = "TL";
		$options[220]->text = "Timor-Leste";
		$options[221] = new stdClass();
		$options[221]->value = "TM";
		$options[221]->text = "Turkmenistan";
		$options[222] = new stdClass();
		$options[222]->value = "TN";
		$options[222]->text = "Tunisia";
		$options[223] = new stdClass();
		$options[223]->value = "TO";
		$options[223]->text = "Tonga";
		$options[224] = new stdClass();
		$options[224]->value = "TR";
		$options[224]->text = "Turkey";
		$options[225] = new stdClass();
		$options[225]->value = "TT";
		$options[225]->text = "Trinidad and Tobago";
		$options[226] = new stdClass();
		$options[226]->value = "TV";
		$options[226]->text = "Tuvalu";
		$options[227] = new stdClass();
		$options[227]->value = "TW";
		$options[227]->text = "Taiwan, Province of China";
		$options[228] = new stdClass();
		$options[228]->value = "TZ";
		$options[228]->text = "Tanzania, United Republic of";
		$options[229] = new stdClass();
		$options[229]->value = "UA";
		$options[229]->text = "Ukraine";
		$options[230] = new stdClass();
		$options[230]->value = "UG";
		$options[230]->text = "Uganda";
		$options[231] = new stdClass();
		$options[231]->value = "UM";
		$options[231]->text = "United States Minor Outlying Islands";
		$options[232] = new stdClass();
		$options[232]->value = "US";
		$options[232]->text = "United States";
		$options[233] = new stdClass();
		$options[233]->value = "UY";
		$options[233]->text = "Uruguay";
		$options[234] = new stdClass();
		$options[234]->value = "UZ";
		$options[234]->text = "Uzbekistan";
		$options[235] = new stdClass();
		$options[235]->value = "VA";
		$options[235]->text = "Holy See (Vatican City State)";
		$options[236] = new stdClass();
		$options[236]->value = "VC";
		$options[236]->text = "Saint Vincent and the Grenadines";
		$options[237] = new stdClass();
		$options[237]->value = "VE";
		$options[237]->text = "Venezuela, Bolivarian Republic of";
		$options[238] = new stdClass();
		$options[238]->value = "VG";
		$options[238]->text = "Virgin Islands, British";
		$options[239] = new stdClass();
		$options[239]->value = "VI";
		$options[239]->text = "Virgin Islands, U.S.";
		$options[240] = new stdClass();
		$options[240]->value = "VN";
		$options[240]->text = "Viet Nam";
		$options[241] = new stdClass();
		$options[241]->value = "VU";
		$options[241]->text = "Vanuatu";
		$options[242] = new stdClass();
		$options[242]->value = "WF";
		$options[242]->text = "Wallis and Futuna";
		$options[243] = new stdClass();
		$options[243]->value = "WS";
		$options[243]->text = "Samoa";
		$options[244] = new stdClass();
		$options[244]->value = "YE";
		$options[244]->text = "Yemen";
		$options[245] = new stdClass();
		$options[245]->value = "YT";
		$options[245]->text = "Mayotte";
		$options[246] = new stdClass();
		$options[246]->value = "ZA";
		$options[246]->text = "South Africa";
		$options[247] = new stdClass();
		$options[247]->value = "ZM";
		$options[247]->text = "Zambia";
		$options[248] = new stdClass();
		$options[248]->value = "ZW";
		$options[248]->text = "Zimbabwe";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_country',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.country'), true)
		);

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.state' => JText::_('JSTATUS'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.created_by' => JText::_('COM_DW_DONATIONS_DONATIONS_CREATED_BY'),
		'a.created' => JText::_('COM_DW_DONATIONS_DONATIONS_CREATED'),
		'a.modified' => JText::_('COM_DW_DONATIONS_DONATIONS_MODIFIED'),
		'a.donor_id' => JText::_('COM_DW_DONATIONS_DONATIONS_DONOR_ID'),
		'a.beneficiary_id' => JText::_('COM_DW_DONATIONS_DONATIONS_BENEFICIARY_ID'),
		'a.fname' => JText::_('COM_DW_DONATIONS_DONATIONS_FNAME'),
		'a.lname' => JText::_('COM_DW_DONATIONS_DONATIONS_LNAME'),
		'a.email' => JText::_('COM_DW_DONATIONS_DONATIONS_EMAIL'),
		'a.amount' => JText::_('COM_DW_DONATIONS_DONATIONS_AMOUNT'),
		'a.country' => JText::_('COM_DW_DONATIONS_DONATIONS_COUNTRY'),
		'a.anonymous' => JText::_('COM_DW_DONATIONS_DONATIONS_ANONYMOUS'),
		'a.order_code' => JText::_('COM_DW_DONATIONS_DONATIONS_ORDER_CODE'),
		'a.transaction_id' => JText::_('COM_DW_DONATIONS_DONATIONS_TRANSACTION_ID'),
		'a.parameters' => JText::_('COM_DW_DONATIONS_DONATIONS_PARAMETERS'),
		'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
		);
	}

}

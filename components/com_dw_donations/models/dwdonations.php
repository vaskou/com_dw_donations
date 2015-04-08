<?php

/**
 * @version     1.0.0
 * @package     com_dw_donations
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Dw_donations records.
 */
class Dw_donationsModelDwDonations extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				                'id', 'a.id',
                'state', 'a.state',
                'ordering', 'a.ordering',
                'created_by', 'a.created_by',
                'created', 'a.created',
                'modified', 'a.modified',
                'donor_id', 'a.donor_id',
                'beneficiary_id', 'a.beneficiary_id',
                'fname', 'a.fname',
                'lname', 'a.lname',
                'email', 'a.email',
                'amount', 'a.amount',
                'country', 'a.country',
                'anonymous', 'a.anonymous',
                'order_code', 'a.order_code',
                'transaction_id', 'a.transaction_id',
                'parameters', 'a.parameters',
                'language', 'a.language',

			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
		{
			$list['ordering'] = 'ordering';
		}
		
		if (empty($list['direction']))
		{
			$list['direction'] = 'asc';
		}

		$this->setState('list.ordering', $list['ordering']);
		$this->setState('list.direction', $list['direction']);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__dw_donations` AS a');

		
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		
		if (!JFactory::getUser()->authorise('core.edit.state', 'com_dw_donations'))
		{
			$query->where('a.state = 1');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				
			}
		}

		//Filtering state
		$filter_state = $this->state->get("filter.state");
		if ($filter_state) {
			$query->where("a.state = '".$db->escape($filter_state)."'");
		}

		//Filtering created

		//Checking "_dateformat"
		$filter_created_from = $this->state->get("filter.created_from_dateformat");
		if ($filter_created_from && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_created_from) && date_create($filter_created_from) ) {
			$query->where("a.created >= '".$db->escape($filter_created_from)."'");
		}
		$filter_created_to = $this->state->get("filter.created_to_dateformat");
		if ($filter_created_to && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_created_to) && date_create($filter_created_to) ) {
			$query->where("a.created <= '".$db->escape($filter_created_to)."'");
		}

		//Filtering modified

		//Checking "_dateformat"
		$filter_modified_from = $this->state->get("filter.modified_from_dateformat");
		if ($filter_modified_from && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_modified_from) && date_create($filter_modified_from) ) {
			$query->where("a.modified >= '".$db->escape($filter_modified_from.' 00:00:00')."'");
		}
		$filter_modified_to = $this->state->get("filter.modified_to_dateformat");
		if ($filter_modified_to && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_modified_to) && date_create($filter_modified_to) ) {
			$query->where("a.modified <= '".$db->escape($filter_modified_to.' 23:59:59')."'");
		}
		

		//Filtering donor_id
		$filter_donor_id = $this->state->get("filter.donor_id");
		if ($filter_donor_id) {
			$query->where("a.donor_id = '".$db->escape($filter_donor_id)."'");
		}

		//Filtering beneficiary_id
		$filter_beneficiary_id = $this->state->get("filter.beneficiary_id");
		if ($filter_beneficiary_id) {
			$query->where("a.beneficiary_id = '".$db->escape($filter_beneficiary_id)."'");
		}

		//Filtering country
		$filter_country = $this->state->get("filter.country");
		if ($filter_country) {
			$query->where("a.country = '".$db->escape($filter_country)."'");
		}
		
		//Filtering anonymous
		$filter_anonymous = $this->state->get("filter.anonymous");
		if ($filter_anonymous) {
			$query->where("a.anonymous = '".$db->escape($filter_anonymous)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		foreach($items as $item){
			$item->country = JText::_('COM_DW_DONATIONS_DONATIONS_COUNTRY_OPTION_' . strtoupper($item->country));
		}

		return $items;
	}
	public function getSum( )
    {
        $this -> setState ('list.select', 'SUM(amount) as sum');
       
        $row = parent::getItems() ;
       
        $sum = $row[0] -> sum ;

        return $sum;
    }
	
	public function getAnnualSum( )
    {
        $this -> setState ('list.select', 'DATE_FORMAT(modified,"%Y") as year,DATE_FORMAT(modified,"%m") as month,SUM(amount) AS total_amount');
       
        $row = parent::getItems() ;
		var_dump($row);
		/*$sum = $row[0] -> sum ;

        return $sum;*/
    }

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_PRUEBA_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

}

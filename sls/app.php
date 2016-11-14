<?php

/**
 * Repsonsible for preparing data for Blade and Front end application use.
 *
 * @author rbrisita Robert Brisita <robert.brisita@gmail.com>
 */
class App_Controller extends Base_Controller
{
	/**
	 * Hold assets and information related to hosting site.
	 *
	 * @var SiteData
	 */
	protected $_siteData;

	/**
	 * Instance of the API Controller.
	 *
	 * @var Api_Controller
	 */
	protected $_api;

	/**
	 * Associative array to hold data that won't change per page request and is
	 * shared across pages. For instance menu, submenu, parties, and site data.
	 *
	 * @var array
	 */
	protected $_project;

	public function __construct()
	{
		parent::__construct();

		SiteLayoutManager::start();
		$this->_siteData = SiteLayoutManager::getLayoutData();

		$this->_api = Laravel\IoC::resolve('controller: api');
		$api =  $this->_api;

		$this->_project = array();
		$project = &$this->_project;
		$project['menu'] = $api->requestMenu($this->_siteData->company);
		$project['submenu'] = $api->requestSubmenu(); // Same as above but more variables to set up
		$project['parties'] = $api->requestParties();
		$project['siteData'] = $this->_siteData;

		// TODO: Cache project
	}

	/**
	 * Get page with most current election.
	 *
	 * @return \Laravel\View View with data to be parsed by Blade to be rendered to the requester.
	 */
	public function action_index()
	{
		$year = $this->getValidYear();
		return $this->action_year($year);
	}

	public function action_test()
	{
		return View::make('app.index')->with('siteData', $this->_siteData);
	}

	/**
	 * Get elections page in given year.
	 *
	 * @param string $year Year (YYYY) of elections to get.
	 *
	 * @return \Laravel\View View with data to be parsed by Blade to be rendered to the requester.
	 */
	public function action_year($year)
	{
		$aa = $this->_project;
		$aaSubmenuYears = $aa['submenu']['years'];

//		\Laravel\Log::Year($year);
//		\Laravel\Log::Year(print_r($aaSubmenuYears, true));

		// Is there an election in given year?
		if(empty($year))
		{
			$aa['submenu'] = NULL;
			$aa['menu']->selected = MenuBuilder::ELECTIONS;
			$aa['appPage'] = 'app.' . $aa['siteData']->company['name'] . '.elections';
			return View::make('app.index', $aa);
		}

		$chosenElection = end($aaSubmenuYears[$year]); // Get current date
		$date = $chosenElection->date;
		return $this->action_date($year, $date);
	}

	/**
	 * Get elections page in given year and date.
	 *
	 * @param string $year Year (YYYY) of given date.
	 * @param string $date Date (MM-DD) of elections to get.
	 *
	 * @return \Laravel\View View with data to be parsed by Blade to be rendered to the requester.
	 */
	public function action_date($year, $date)
	{
		$api = $this->_api;

		$aa = $this->_project;

		$aa['selected'] = array();
		$aa['selected']['year'] = $year;
		$aa['selected']['date'] = $date;

		// Rewrite dates to show in drop down
		$aa['submenu']['dates'] = $aa['submenu']['years'][$year];

		$aa['elections'] = $api->requestElections($year, $date);

		$aa['submenu']['races'] = $api->getRaces($aa['elections']);

		$aa['menu']->selected = MenuBuilder::ELECTIONS;
		$aa['appPage'] = 'app.' . $aa['siteData']->company['name'] . '.elections';

//		\Laravel\Log::AA(print_r($aa['elections'], true));

//		return View::make('app.index', $aa)->nest('apppage', 'app.elections', $aa); // NOTE: Nesting not working :-(
		return View::make('app.index', $aa);
	}

	public function action_region($region, $year, $date = NULL)
	{
		return $this->action_date($year, $date);
	}

	public function action_candidate($id = 0)
	{
		$aa = $this->_project;
		$aa['menu']->selected = MenuBuilder::CANDIDATES;
		$aa['appPage'] = 'app.profile';

		$api = $this->_api;
		if($id)
		{
			$aa['candidate'] = $api->requestCandidateById($id);
		}

//		return View::make('app.index', $aa)->nest('apppage', 'app.profile'); // NOTE: Nesting not working :-(
		return View::make('app.index', $aa);
	}

	/**
	 * Get current year and check if it has a valid election tied to it.
	 *
	 * @return int The most current year with an election.
	 */
	private function getValidYear()
	{
		$year = intval(date('Y'));
		$aa = $this->_project['submenu']['years'];
		if(!array_key_exists($year, $aa))
		{
			$keys = array_keys($aa);
			$year = end($keys); // Get last valid election
		}
		return $year;
	}
}

<?php

/**
 * Responsible for getting and converting data.
 *
 * @author rbrisita Robert Brisita <robert.brisita@gmail.com>
 */
class Api_Controller extends Base_Controller implements \DataStorage\IDataStorage
{
	public $restful = TRUE;
	protected $_version = '0.1.0';

	/**
	 * Interface to a data storage instance.
	 *
	 * @var DataStorage\IDataStorage
	 */
	private $_ids = NULL;

	public function __construct()
	{
		parent::__construct();

		/*
		 * These values are set in application/config/database.php.
		 * Overridden here for ease of use. Replace the Config::gets
		 * with other data; for example a test database name.
		 */
		$profile = Config::get('database.profile'); // TRUE | FALSE
		$dbType = Config::get('database.default'); // sqlite | mysql | pgsql | sqlsrv
		$dbName = Config::get("database.connections.{$dbType}.database");

		Config::set('database.profile', $profile);
		Config::set('database.default', $dbType);
		Config::set("database.connections.{$dbType}.database", $dbName);

		$this->_ids = Laravel\IoC::resolve('datastorage');
	}

	/**
	 * Display how the api is supposed to be used.
	 *
	 * @return HTML
	 */
	public function get_index()
	{
		return View::make('api.index');
	}

	/**
	 * Get current version of API.
	 *
	 * @return Object JSON
	 */
	public function get_version()
	{
		return $this->getJSONResponse(array('version' => $this->_version));
	}

	/**
	 * Get elections data in given year.
	 *
	 * @param int $year Year (YYYY) of elections to get.
	 *
	 * @return stdClass Data to be parsed by requester.
	 */
	public function get_year($year)
	{
		$arrElections = $this->requestElections($year);

		return $this->getJSONResponse(array('elections'=>$arrElections));
	}

	/**
	 * Get elections data in given year and date.
	 *
	 * @param int $year Year (YYYY) of given date.
	 * @param string $date Date (MM-DD) of elections to get.
	 *
	 * @return stdClass Data to be parsed by the requester.
	 */
	public function get_date($year, $date = NULL)
	{
		$dates = $this->requestDates($year);

		$chosenElection = NULL;
		if(!empty($date))
		{
			// Find chosen election
			foreach($dates[$year] as $elec)
			{
				if($elec->date === $date)
				{
					$chosenElection = $elec;
					break;
				}
			}
		}

		// This means the request has a date that is not valid for the given year
		if(empty($chosenElection))
		{
			$chosenElection = end($dates[$year]); // Get last election date of given year
			$date = $chosenElection->date;
		}

		$arrElections = $this->requestElections($year, $date);
		$arrElections['dates'] = $dates[$year];
		$arrElections['selected'] = $date;

		return $this->getJSONResponse(array('elections'=>$arrElections));
	}

	public function get_region($region, $year, $date = NULL)
	{
		return $this->get_date($year, $date);
	}

	public function requestMenu(array $company)
	{
		return $this->_ids->requestMenu($company);
	}

	public function requestSubmenu()
	{
		return $this->_ids->requestSubmenu();
	}

	public function requestDates($year = NULL)
	{
		return $this->_ids->requestDates($year);
	}

	public function requestParties()
	{
		return $this->_ids->requestParties();
	}

	public function getRaces(array $aa)
	{
		return $this->_ids->getRaces($aa);
	}

	public function requestElections($year, $date = NULL)
	{
		return $this->_ids->requestElections($year, $date);
	}

	public function requestCandidateById($id = 0)
	{
		return $this->_ids->requestCandidateById($id);
	}
}

<?php

namespace DataStorage;

/**
 * Contains getters for Voter Guide Project.
 *
 * @author rbrisita Robert Brisita <robert.brisita@gmail.com>
 */
interface IDataStorage
{
	public function requestMenu(array $company);
	public function requestSubMenu();
	public function requestDates($year = NULL);
	public function requestParties();
	public function requestElections($year, $date = NULL);
	public function requestCandidateById($id = 0);
	public function getRaces(array $aa);
}

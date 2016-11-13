<?php

/**
 * MatchBot judges profiles in various ways.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
class MatchBot
{
	private $_judges = NULL;
	private $_allResultsMsgs = NULL;

	public function __construct(array $iJudges)
	{
		$this->_judges = $iJudges;

		$this->_allResultsMsgs = array();
	}

	public function judge($profile)
	{
		foreach($this->_judges as $iJudge)
		{
			/* @var $iJudge IJudge */
			$clsName = get_class($iJudge);
			$aspect = $iJudge->getAspect();

			$this->_allResultsMsgs[$clsName] = $iJudge->decide($profile[$aspect]);
		}

		return $this->_allResultsMsgs;
	}

	public function getResultMessages()
	{
		return $this->_allResultsMsgs;
	}
}

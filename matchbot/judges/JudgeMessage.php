<?php

/**
 * Judge message by looping through rules.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
class JudgeMessage extends AbstractJudge
{
	/**
	 * Loop through rules to decide if messages meets criteria.
	 *
	 * @param string $msg Message to judge.
	 * @return array An array of messages from excercised rules.
	 */
	public function decide($msg)
	{
		foreach($this->_rules as $iRule)
		{
			$clsName = get_class($iRule);

			/* @var $iRule IRule */
			$this->_resultMessages[$clsName] = $iRule->excercise($msg);
		}

		return $this->_resultMessages;
	}
}

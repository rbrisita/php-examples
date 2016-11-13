<?php

/**
 * Interface for concreate classes to excercise logic rules.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
interface IRule
{
	/**
	 * Excercises a rule and returns result.
	 *
	 * @param type $obj Object to rule on.
	 *
	 * @return string Result string for display.
	 */
	public function excercise($obj);
}

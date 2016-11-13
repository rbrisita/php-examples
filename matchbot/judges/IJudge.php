<?php

/**
 * Interface for concreate classes that run various rules.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
interface IJudge
{
	/**
	 * Returns judge's aspect.
	 *
	 * @note Aspect in this sense is a particular part of a profile.
	 */
	public function getAspect();

	/**
	 * Add rules to internal rule array.
	 *
	 * @param IRule $irule Interface to a class that implements IRule.
	 */
	public function addRule(IRule $irule);

	/**
	 * Loop through rules saving result messages.
	 *
	 * @param type $obj Object to loop rules on.
	 *
	 * @return array An associative array of result messages.
	 */
	public function decide($obj);
}

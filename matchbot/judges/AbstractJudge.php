<?php

/**
 * AbstractJudge is a base class for judges.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
abstract class AbstractJudge implements IJudge
{
	/**
	 * @var string Aspect that a concrete judge cares about.
	 */
	protected $_aspect = NULL;

	/**
	 * Hold instances of IRule to loop through.
	 *
	 * @var array An array of instance that are of IRule.
	 */
	protected $_rules = NULL;

	/**
	 * Hold result messages from rules.
	 *
	 * @var array An associative array of result messages with rule key.
	 */
	protected $_resultMessages = NULL;

	public function __construct($aspect)
	{
		$this->_aspect = $aspect;
		$this->_rules = array();
		$this->_resultMessages = array();
	}

	public function getAspect()
	{
		return $this->_aspect;
	}

	public function addRule(\IRule $irule)
	{
		$this->_rules[] = $irule;
	}

	abstract public function decide($obj);
}

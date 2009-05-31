<?php
/*
 * +-----------------------------------------+
 * | Copyright (c) 2009 Tobias Friebel       |
 * +-----------------------------------------+
 * | Authors: Tobias Friebel <TobyF@Web.de>	 |
 * +-----------------------------------------+
 *
 * CC Namensnennung-Keine kommerzielle Nutzung-Keine Bearbeitung
 * http://creativecommons.org/licenses/by-nc-nd/2.0/de/
 *
 * $Id: UserOptionOutputOnlinetime.class.php 305 2009-04-14 22:24:50Z toby $
 */

require_once (WCF_DIR . 'lib/data/user/option/UserOptionOutput.class.php');
require_once (WCF_DIR . 'lib/data/user/User.class.php');

class UserOptionOutputOnlinetime implements UserOptionOutput
{
	/**
	 * @see UserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, $optionData, $value)
	{
		return $this->getOutput($user, $optionData, $value);
	}

	/**
	 * @see UserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, $optionData, $value)
	{
		return $this->getOutput($user, $optionData, $value);
	}

	/**
	 * @see UserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, $optionData, $value)
	{
		$string = ONLINETIMER_FORMAT;

		$format = array ('%y' => array('year', 60 * 60 * 24 * 365),
						 '%b' => array('month', 60 * 60 * 24 * 30),
						 '%w' => array('week', 60 * 60 * 24 * 7),
						 '%d' => array('day', 60 * 60 * 24),
						 '%h' => array('hour', 60 * 60),
						 '%m' => array('minute', 60),
						 '%s' => array('second', 1),
						);

		foreach ($format as $key => $vars)
		{
			if (stripos($string, $key) !== false)
			{
				$count = floor($value / $vars[1]);

				if ($count == 1)
				{
					$count .= ' ' . WCF :: getLanguage()->get('wcf.user.onlineTimer.' . $vars[0]);
				}
				else
				{
					$count .= ' ' . WCF :: getLanguage()->get('wcf.user.onlineTimer.' . $vars[0] . 's');
				}

				$string = str_replace($key, $count, $string);

				$value -= floor($value / $vars[1]) * $vars[1];
			}
		}

		return $string;
	}
}
?>
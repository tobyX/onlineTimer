<?php
namespace wcf\system\option\user;

use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\WCF;


/**
 * Format time user stays in forum
 *
 * @author	Tobias Friebel <woltlab@tobyf.de>
 * @copyright	2014 Tobias Friebel
 * @license	Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International <http://creativecommons.org/licenses/by-nc-nd/4.0/deed.en>
 * @package	com.toby.wcf.onlinetimer
 */
class OnlineTimerUserOptionOutput implements IUserOptionOutput
{
	/**
	 * @see	\wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value)
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

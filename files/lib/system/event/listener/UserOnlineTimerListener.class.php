<?php

namespace wbb\system\event\listener;

use wcf\system\event\IEventListener;
use wcf\system\WCF;

/**
 * Count time user stays in forum
 *
 * @author	Tobias Friebel <woltlab@tobyf.de>
 * @copyright	2014 Tobias Friebel
 * @license	Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International <http://creativecommons.org/licenses/by-nc-nd/4.0/deed.en>
 * @package	com.toby.wcf.onlinetimer
 */
class UserOnlineTimerListener implements IEventListener
{
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		//real users only
		if (WCF :: getUser()->userID > 0 && WCF :: getUser()->userOnlineTimerLastactivity < TIME_NOW)
		{
			$update = 0;

			// Letzte Aktivität berechnen
			$lastactivity_diff = TIME_NOW - WCF :: getUser()->userOnlineTimerLastactivity;

			// Onlinetime berechen
			// wen die Letzte Aktivität kleiner als Timeout ist wird berechnet
			if ($lastactivity_diff < ONLINETIMER_TIMEOUT)
			{
				$update = $lastactivity_diff;
			}

			WCF :: getUser()->getEditor()->updateOptions(array (
														'userOnlineTimerLastactivity' => TIME_NOW,
														'userOnlineTimerOnlinetime' => WCF :: getUser()->userOnlineTimerOnlinetime + $update
														)
							   						);

			//Lets be a bastard and overwrite the lastactivity with time_now
			//This should avoid multiple executions
			WCF :: getUser()->userOnlineTimerLastactivity = TIME_NOW;

			//to keep the session up to date just set the value, else we will get "jumping" times.
			WCF :: getUser()->userOnlineTimerOnlinetime = WCF :: getUser()->userOnlineTimerOnlinetime + $update;
		}
	}
}

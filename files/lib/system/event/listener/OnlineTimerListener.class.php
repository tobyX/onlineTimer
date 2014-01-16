<?php

namespace wcf\system\event\listener;

use wcf\system\event\IEventListener;
use wcf\system\WCF;
use wcf\data\user\User;
use wcf\data\user\UserAction;


/**
 * Count time user stays in forum
 *
 * @author	Tobias Friebel <woltlab@tobyf.de>
 * @copyright	2014 Tobias Friebel
 * @license	Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International <http://creativecommons.org/licenses/by-nc-nd/4.0/deed.en>
 * @package	com.toby.wcf.onlinetimer
 */
class OnlineTimerListener implements IEventListener
{
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName)
	{
		$user = WCF :: getUser();
		//real users only
		if ($user->userID > 0 && $user->onlineTimerLastActivity < TIME_NOW)
		{
			$update = 0;

			// Calculate seconds since last activity
			$lastactivity_diff = TIME_NOW - $user->onlineTimerLastActivity;

			// if seconds since last activity is less than the timeout we will raise the counter
			if ($lastactivity_diff < ONLINETIMER_TIMEOUT)
			{
				$update = $lastactivity_diff;
			}

			$objectAction = new UserAction(array($user->userID), 'update', array(
					'options' => array(
						User :: getUserOptionID('onlineTimerLastActivity') => TIME_NOW,
						User :: getUserOptionID('onlineTimerCounter') => $user->onlineTimerCounter + $update
					)
			));
			$objectAction->executeAction();

			//Lets be a bastard and overwrite the lastactivity with time_now
			//This should avoid multiple executions
			$user->onlineTimerLastActivity = TIME_NOW;

			//to keep the session up to date just set the value, else we will get "jumping" times.
			$user->onlineTimerCounter = WCF :: getUser()->onlineTimerCounter + $update;
		}
	}
}

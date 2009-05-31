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
 * $Id: UserOnlineTimerListener.class.php 192 2009-02-15 15:36:52Z toby $
 */

require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

class UserOnlineTimerListener implements EventListener
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
?>
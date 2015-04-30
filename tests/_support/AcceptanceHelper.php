<?php

namespace Codeception\Module;

use Codeception\Exception\ElementNotFound;
use Codeception\Exception\TestRuntime;
use Codeception\Util\Locator;
use Vreasy\Models\Reservation;
use Vreasy\Models\Listing;
use Vreasy\Models\MessageTemplate;
use Vreasy\Models\Email;
use Vreasy\Models\Form;


class AcceptanceHelper extends \Codeception\Module
{
    public function amOnPage($page)
    {
        $browser = $this->getModule('WebDriver');
        $browser->amOnPage($page);
        $this->waitForAjax();
    }

    public function haveTask($params = [])
    {
    	$I = $this->getModule('DbzHelper');
    	$params = array_merge(
    			[
    					'deadline' => gmdate(DATE_FORMAT),
    					'assigned_phone' => '+34666666666',
    					'assigned_name' => 'John Doe',
    			],
    			$params
    	);
    	if (isset($params['id'])) {
    		$task = Task::findOrInit($params['id']);
    		$task = Task::hydrate($task, $params);
    	} else {
    		$task = Task::instanceWith($params);
    	}
    	\PHPUnit_Framework_Assert::assertTrue((bool) $task->save());
    	return $task;
    }
   
}

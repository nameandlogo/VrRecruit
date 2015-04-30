<?php
namespace Codeception\Module;

use Vreasy\Models\Task;
use Codeception\TestCase;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class UnitHelper extends \Codeception\Module
{
    public function doDie()
    {
        die;
    }

    function _before(TestCase $test)
    {
        $GLOBALS['isDebugging'] = null;
    }

    function _after(TestCase $test)
    {
        $GLOBALS['isDebugging'] = null;
        unset($GLOBALS['isDebugging']);
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

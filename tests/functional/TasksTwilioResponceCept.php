<?php
$I = new FunctionalTester($scenario);
$I->wantTo('Process a responce from Twilio');
$task = $I->haveTask(['task_id' => '13','twilio_phone' => '+54423456','status' => '2','twilio_message' => 'I accept the task']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twilio/{$task}?format=json");
$I->seeResponseCodeIs(200);
<?php
$I = new FunctionalTester($scenario);
$I->wantTo('Send assignment message via Twilio');
$task = $I->haveTask(['task_id' => '13','twilio_phone' => '+54423456','status' => '1','twilio_message' => 'You have a new task']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST("/twilio/{$task}?format=json");
$I->seeResponseCodeIs(200);
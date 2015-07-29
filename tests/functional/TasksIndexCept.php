<?php
$I = new FunctionalTester($scenario);
$I->wantTo('List all the tasks');

$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/task?format=json');
$I->seeResponseCodeIs(200);

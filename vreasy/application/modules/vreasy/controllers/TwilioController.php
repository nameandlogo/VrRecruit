<?php

/**
@TODO create function to send message via twilio 

// Get the PHP helper library from twilio.com/docs/php/install
require_once('/path/to/twilio-php/Services/Twilio.php'); // Loads the library
 
// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC5ef8732a3c49700934481addd5ce1659"; 
$token = "{{ auth_token }}"; 
$client = new Services_Twilio($sid, $token);
 
$client->account->messages->sendMessage("+14158141829", "+15558675309", "Jenny please?! I love you <3", "http://www.example.com/hearts.png");
*/



use Vreasy\Models\Twilio;

class Vreasy_TwilioController extends Vreasy_Rest_Controller
{
    protected $twilio_msg, $twilio_msgs;

    public function preDispatch()
    {
        parent::preDispatch();
        $req = $this->getRequest();
        $action = $req->getActionName();
        $contentType = $req->getHeader('Content-Type');
        $rawBody     = $req->getRawBody();
        if ($rawBody) {
            if (stristr($contentType, 'application/json')) {
                $req->setParams(['twiml' => Zend_Json::decode($rawBody)]);
            }
        }
        if($req->getParam('format') == 'json') {
            switch ($action) {
                case 'index':
                    // @TODO get id for where to avoid filter $this->twilio_msgs = Twilio::where(['task_id' => (int)$req->getParam('id')]);
					$this->twilio_msgs = Twilio::where([]);
                    break;
                case 'new':
                    $this->twilio_msg = new Twilio();
                    break;
                case 'create':
                    $this->twilio_msg = Twilio::instanceWith($req->getParam('twiml'));
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $this->twilio_msg = Twilio::findOrInit($req->getParam('id'));
                    break;
            }
        }

        if( !in_array($action, [
                'index',
                'new',
                'create',
                'update',
                'destroy'
            ]) && !$this->twilio_msgs && !$this->twilio_msg->id) {
            throw new Zend_Controller_Action_Exception('Resource not found', 404);
        }

    }

    public function indexAction()
    {
		$this->view->twilio_msgs = $this->twilio_msgs;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->twilio_msgs]);
        
    }

    public function newAction()
    {
        $this->view->$twilio_msg = $this->twilio_msg;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->twilio_msg]);
    }

    public function createAction()
    {
        if ($this->twilio_msg->isValid() && $this->twilio_msg->save()) {
            $this->view->twilio_msg = $this->twilio_msg;
        } else {
            $this->view->errors = $this->twilio_msg->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function showAction()
    {
        	
        $this->view->twilio_msg = $this->twilio_msg;
        $this->_helper->conditionalGet()->sendFreshWhen(
            ['etag' => [$this->twilio_msg]]
        );
    }

    public function updateAction()
    {
        Twilio::hydrate($this->twilio_msg, $this->_getParam('twiml'));
        if ($this->twilio_msg->isValid() && $this->twilio_msg->save()) {
            $this->view->twilio_msg = $this->twilio_msg;
        } else {
            $this->view->errors = $this->twilio_msg->errors();
            $this->getResponse()->setHttpResponseCode(422);
        }
    }

    public function destroyAction()
    {
        if($this->twilio_msg->destroy()) {
            $this->view->twilio_msg = $this->twilio_msg;
        } else {
            $this->view->errors = ['delete' => 'Unable to delete resource'];
        }
    }
}

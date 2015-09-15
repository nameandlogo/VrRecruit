angular.module('taskConfirmationApp',  ['ui.router', 'ngResource'])
.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    // Use hashtags in URL
    $locationProvider.html5Mode(false);

    $urlRouterProvider.otherwise("/");
    $stateProvider
    .state('index', {
      url: "/",
      templateUrl: "/taskConfirmationApp/templates/index.html",
      controller: 'TaskCtrl'
    })
    .state('show', {
      url: "/show/:id",
      templateUrl: "/taskConfirmationApp/templates/show.html",
      controller: function($scope, Task, Twilio, $stateParams) {
     		var id =  $stateParams.id;
    		$scope.task = Task.get({ id: + id });
    		$scope.msgs = Twilio.query();
  		}
    })
     .state('showTwilio', {
      url: "/showtwilio/:id",
      templateUrl: "/taskConfirmationApp/templates/show-twilio.html",
      controller: function($scope, Task, Twilio, $stateParams, $location) {
     		var id =  $stateParams.id;
     		Twilio.get({ id: + id }).$promise.then(function(taskMsg) {
	      		$scope.twilioMsg = taskMsg;
	      		Task.get({ id: + taskMsg.task_id }).$promise.then(function(task) {
	      			$scope.task = task;
	      		});
      	 	});
      	 
  		}
    });
})
.factory('Task', function($resource) {
    return $resource('/task/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.factory('Twilio', function($resource) {
    return $resource('/twilio/:id?format=json',
        {id:'@id'},
        {
            'get': {method:'GET'},
            'save': {method: 'PUT'},
            'create': {method: 'POST'},
            'query':  {method:'GET', isArray:true},
            'remove': {method:'DELETE'},
            'delete': {method:'DELETE'}
        }
    );
})
.controller('TaskCtrl', function($scope, Task, Twilio, $location) {
    $scope.tasks = Task.query();
    
    $scope.addTask = function(task) {
       Task.create(task).$promise.then(function(newTask) {
       	
	      twiml = {};
	      twiml.task_id = newTask.id;
	      twiml.status = 1;
	      twiml.twilio_phone = newTask.assigned_phone;
	      twiml.twilio_message = 'You have a new task';
	      
      	  Twilio.create(twiml).$promise.then(function(newMsg) {
	      		$location.path('/showtwilio/'+ newMsg.id);
      	 	});
      	 
    	});
    };
    
    $scope.msgTest = function(taskID) {
       Twilio.query().$promise.then(function(msgs) {
       	
	      for(msg of msgs){
	      	if (msg.task_id == taskID){
	      		$location.path('/showtwilio/'+ msg.id);
	      		return false;
	      	}
	      }
	     
    	});
    }; 
})
.controller('sendMsg', function($scope, Twilio, $location, $state) {
     var currentState = $state.$current;
 	 if (currentState =='show'){
 	 	$scope.status = 4;
 	 	$scope.message = 'This task is completed';
 	 }
     else $scope.status= 0;
     $scope.submit = function() {
        if ($scope.message) {
	          var twiml = {
	          task_id : $scope.task.id,
	          status  : $scope.status,
	          twilio_phone  : $scope.task.assigned_phone,
	          twilio_message  : $scope.message
	       	  };
	     
	     	  Twilio.create(twiml).$promise.then(function(newMsg) {
		      		$location.path('/');
	      	   });
        }
     };
        
});

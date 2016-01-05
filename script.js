/////////////////////////LOGIN/////////////////////////////////
var loginFormApp = angular.module('loginFormApp', []);

loginFormApp.controller('loginCtrl',function($scope, $http){
	$scope.master = {};
	$scope.isUnchanged = function(user) {
		return angular.equals(user, $scope.master);
	};
	/*вспомагательные скрипты, на данный момент не используются
	//создаём запрос к php-скрпту на получение данных из БД
	$http.get('get_login_data.php').success(function(data) 
   	{
        //заносим в scope полученные данные
    	$scope.usersData = data;
	});
	*/

	/*
	$scope.update = function(user) 
	{
		$scope.master= angular.copy(user);
	};
			
	$scope.reset = function() 
	{
		$scope.user = angular.copy($scope.master);
	};*/
	
		//$scope.reset();
						
});
//////////////////////REGISTRATION//////////////////////////////
var regFormApp = angular.module('regFormApp', []);

regFormApp.controller('regCtrl', function($scope, $http){
	$scope.master = {};
	/*$scope.Update = function(newUser) {
		$scope.master= angular.copy(newUser);
	};*/
	$scope.ConfirmPass = function(newUser) {
		return !(angular.equals(newUser.password, newUser.confirmPassword));
	};

	$scope.SignUp = function(newUser){
		newUser.checkEmail = false;//переменная для проверки на существующий email
		$http.get('php_scripts/check_user.php').success(function(data){
			$scope.dbInfo = data;
			for(var i = 0; i < $scope.dbInfo.length; i++){
				if(newUser.email==$scope.dbInfo[i].email){
					newUser.checkEmail = true;
				}
			}
			if(newUser.checkEmail==true){
				alert("К сожалению, такой E-Mail уже зарегестрирован");
			}
			else{
				$http.post('php_scripts/user_registration.php', {'name': newUser.name, 'email': newUser.email, 'password': newUser.password});
			}
		});
	}
});

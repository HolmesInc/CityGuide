/////////////////////////INDEX/////////////////////////////////
var indexApp = angular.module('indexApp', []);
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
var regFormApp = angular.module('regFormApp', ['vcRecaptcha']);

regFormApp.controller('regCtrl', function($scope, $http, vcRecaptchaService){
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
				$http.post('php_scripts/user_registration.php', {'name': newUser.name, 'email': newUser.email, 'password': newUser.password}).success(function(){
					alert("Поздравляем, Вы зарегистрировались!");
					window.location.replace("http://arrow.ru/login.php");
				});
			}
		});
	}
});
////////////////////CLUBS SELECTION/////////////////////////////
var clubsApp = angular.module('clubsApp', ['tableSort']);

clubsApp.controller('clubCtrl', function($scope, $http){
	$scope.nearMetro = 0;
	$scope.lowPrice = 0;
	$scope.highPrice = 0;
	$scope.moreTime = 0;
	$scope.splicedMetro = [];
	$http.get('../../php_scripts/get_clubs_data.php').then(function (response) {
				$scope.dbInfo = response.data;
			});
	$scope.ShowInfo = function(checker) {
		switch (checker) {
			case 1: 
				$scope.nearMetro += 1;
				if($scope.nearMetro % 2 != 0){
					for(var i = 0; i < $scope.dbInfo.length; i++) {
						if($scope.dbInfo[i].metro == '-') {
							$scope.splicedMetro.push({'name':$scope.dbInfo[i].name, 'pryce_index':$scope.dbInfo[i].pryce_index,
								'rating':$scope.dbInfo[i].rating, 'open_time':$scope.dbInfo[i].open_time,
								'close_time':$scope.dbInfo[i].close_time, 'adress':$scope.dbInfo[i].adress,
								'metro':$scope.dbInfo[i].metro, 'phone':$scope.dbInfo[i].phone, 'site':$scope.dbInfo[i].site});
							$scope.dbInfo.splice(i, 1);
						}
					}
					for(var i = 0; i < $scope.splicedMetro.length; i++) {
						console.log($scope.splicedMetro[i].name + $scope.splicedMetro[i].phone);
					}
				}
				else
					if($scope.nearMetro % 2 == 0) {
					for(var i = 0; i < $scope.splicedMetro.length; i++) {
						$scope.dbInfo.push({'name':$scope.splicedMetro[i].name, 'pryce_index':$scope.splicedMetro[i].pryce_index,
								'rating':$scope.splicedMetro[i].rating, 'open_time':$scope.splicedMetro[i].open_time,
								'close_time':$scope.splicedMetro[i].close_time, 'adress':$scope.splicedMetro[i].adress,
								'metro':$scope.splicedMetro[i].metro, 'phone':$scope.splicedMetro[i].phone, 'site':$scope.splicedMetro[i].site});
					}
					$scope.splicedMetro = [];
				}
				break;
			case 2: 
				$scope.lowPrice += 1;
				alert("Low"+$scope.lowPrice);
				break;
			case 3: 
				$scope.highPrice += 1;
				alert("High"+$scope.highPrice);
				break;
			case 4: 
				$scope.moreTime += 1;
				alert("Time"+$scope.moreTime);
				break;

		}
	}
});
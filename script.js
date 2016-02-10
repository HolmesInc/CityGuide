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
	$scope.withSite = 0;
	$http.get('../../php_scripts/get_clubs_data.php').then(function (response) {
				$scope.dbInfo = response.data;
			});

	$scope.ReturnData = function(splicedData) {
		for(var i = 0; i < splicedData.length; i++) {
			$scope.dbInfo.push({
				'name':splicedData[i].name, 
				'pryce_index':splicedData[i].pryce_index,
				'rating':splicedData[i].rating, 
				'open_time':splicedData[i].open_time,
				'close_time':splicedData[i].close_time, 
				'adress':splicedData[i].adress,
				'metro': splicedData[i].metro,
				'phone':splicedData[i].phone, 
				'site':splicedData[i].site
			});
		}
		splicedData = [];
	}
	$scope.ShowInfo = function(checker) {
		var dbInfoLength = $scope.dbInfo.length
		switch (checker) {
			case 1: 
				$scope.nearMetro += 1;
				if($scope.nearMetro % 2 != 0) {
					$scope.splicedMetro = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < $scope.dbInfo.length; i++) {
							if($scope.dbInfo[i].metro == '-') {							
								$scope.splicedMetro.push({
									'name':$scope.dbInfo[i].name, 
									'pryce_index':$scope.dbInfo[i].pryce_index,
									'rating':$scope.dbInfo[i].rating,
									'open_time':$scope.dbInfo[i].open_time,
									'close_time':$scope.dbInfo[i].close_time, 
									'adress':$scope.dbInfo[i].adress,
									'metro':$scope.dbInfo[i].metro,
									'phone':$scope.dbInfo[i].phone, 
									'site':$scope.dbInfo[i].site
								});
								$scope.dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.nearMetro % 2 == 0) {
						$scope.ReturnData($scope.splicedMetro);
					}
				break;
			case 2: 
				$scope.lowPrice += 1;
				if($scope.lowPrice % 2 != 0) {
					$scope.splicedLowPrice = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < $scope.dbInfo.length; i++) {
							if( parseInt($scope.dbInfo[i].pryce_index) >= 3 ) {
								$scope.splicedLowPrice.push({
									'name':$scope.dbInfo[i].name, 
									'pryce_index':$scope.dbInfo[i].pryce_index,
									'rating':$scope.dbInfo[i].rating,
									'open_time':$scope.dbInfo[i].open_time,
									'close_time':$scope.dbInfo[i].close_time, 
									'adress':$scope.dbInfo[i].adress,
									'metro':$scope.dbInfo[i].metro,
									'phone':$scope.dbInfo[i].phone, 
									'site':$scope.dbInfo[i].site
								});
								$scope.dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.lowPrice % 2 == 0) {
						$scope.ReturnData($scope.splicedLowPrice);
					}
				break;
			case 3: 
				$scope.highPrice += 1;
				if($scope.highPrice % 2 != 0) {
						$scope.splicedHighPrice = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < $scope.dbInfo.length; i++) {
							if( parseInt($scope.dbInfo[i].pryce_index) < 3 ) {
								$scope.splicedHighPrice.push({
									'name':$scope.dbInfo[i].name, 
									'pryce_index':$scope.dbInfo[i].pryce_index,
									'rating':$scope.dbInfo[i].rating,
									'open_time':$scope.dbInfo[i].open_time,
									'close_time':$scope.dbInfo[i].close_time, 
									'adress':$scope.dbInfo[i].adress,
									'metro':$scope.dbInfo[i].metro,
									'phone':$scope.dbInfo[i].phone, 
									'site':$scope.dbInfo[i].site
								});
								$scope.dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.highPrice % 2 == 0) {					
						$scope.ReturnData($scope.splicedHighPrice);
					}
				break;
			case 4: 
				$scope.withSite += 1;
				if($scope.withSite % 2 != 0) {
						$scope.splicedWithSite = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < $scope.dbInfo.length; i++) {
							if( $scope.dbInfo[i].site == '-' ) {
								$scope.splicedWithSite.push({
									'name':$scope.dbInfo[i].name, 
									'pryce_index':$scope.dbInfo[i].pryce_index,
									'rating':$scope.dbInfo[i].rating,
									'open_time':$scope.dbInfo[i].open_time,
									'close_time':$scope.dbInfo[i].close_time, 
									'adress':$scope.dbInfo[i].adress,
									'metro':$scope.dbInfo[i].metro,
									'phone':$scope.dbInfo[i].phone, 
									'site':$scope.dbInfo[i].site
								});
								$scope.dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.withSite % 2 == 0) {						
						$scope.ReturnData($scope.splicedWithSite);
					}
				break;
		}
	}
});
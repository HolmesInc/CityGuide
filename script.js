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
////////////////////////// Карта

	var customIcons = {
			club: {
				icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
			}
		};
	var map; //переменная для оперирования картой
	var markers = []; //массив маркеров
	var city = new google.maps.LatLng(49.9935, 36.230383); //начальная позиция на карте
	var zoom = 11;

	$scope.ShowMap = function() {
		map = new google.maps.Map(document.getElementById("map"), {
				center: city,
				zoom: zoom,
				mapTypeId: 'roadmap'
			});
		var infoWindow = new google.maps.InfoWindow;
		downloadUrl("../../../php_scripts/functionality/getClubLocation.php", function(data) {
			var xml = data.responseXML;
			markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				var name = markers[i].getAttribute("name");
				var adress = markers[i].getAttribute("adress");
				var phone = markers[i].getAttribute("phone");
				var point = new google.maps.LatLng(
					parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng"))
				);
				var html = "<b>" + name + "</b> <br/>" + adress + "<br/>" + phone ;
				var icon = customIcons[0] || {};
				var marker = new google.maps.Marker({
					map: map,
					position: point,
					icon: icon.icon
				});
				bindInfoWindow(marker, map, infoWindow, html);
			}
		});
	}

	function bindInfoWindow(marker, map, infoWindow, html) {
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent(html);
			infoWindow.open(map, marker);
		});
	}

	function downloadUrl(url, callback, async) { //AJAX-функция для получения данный из БД
		async = async || true; //задание значения по умолчанию 
		var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;
		request.onreadystatechange = function() {
			if (request.readyState == 4) {
				request.onreadystatechange = doNothing;
				callback(request, request.status);
			}
		};
		request.open('GET', url, async); 
		request.send(null);
	}

	function doNothing() {}	

	$scope.NewZoom = function (id) {
		zoom = 17;
		var point = new google.maps.LatLng(
			parseFloat(markers[id].getAttribute("lat")),
			parseFloat(markers[id].getAttribute("lng"))
		);
		map.setZoom(zoom);
		map.setCenter(point);
	}
////////////////////////// Карта
////////////////////////// Работа с даными из БД
	$scope.nearMetro = 0;
	$scope.lowPrice = 0;
	$scope.highPrice = 0;
	$scope.withSite = 0;

	$http.get('../../php_scripts/get_clubs_data.php').then(function (response) { //запрос на получение данных о заведении из БД
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
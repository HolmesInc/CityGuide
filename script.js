///////////////////////INDEX//////////////////////////////////////////////////////////////////////////////////////////////
var indexApp = angular.module('indexApp', []);
///////////////////////LOGIN//////////////////////////////////////////////////////////////////////////////////////////////
var loginFormApp = angular.module('loginFormApp', []);

loginFormApp.controller('loginCtrl',function($scope, $http){
	$scope.master = {};
	$scope.isUnchanged = function(user) {
		return angular.equals(user, $scope.master);
	};
	//$scope.reset();				
});
///////////////////////REGISTRATION///////////////////////////////////////////////////////////////////////////////////////
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
///////////////////////SELECTION//////////////////////////////////////////////////////////////////////////////////////////
var selectionApp = angular.module('selectionApp', ['tableSort']);

selectionApp.controller('selectionCtrl', function($scope, $http){
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
		var mapPath = 'getClubLocation.php';
		downloadUrl("../../../php_scripts/functionality/" + mapPath, function(data) {
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

	$http.get('../../php_scripts/functionality/selection/get_clubs_data.php').then(function (response) { //запрос на получение данных о заведении из БД
				$scope.dbClubInfo = response.data;
			});
	$http.get('../../php_scripts/functionality/selection/get_cinema_data.php').then(function (response) { //запрос на получение данных о заведении из БД
				$scope.dbCinemaInfo = response.data;
			});
	$http.get('../../php_scripts/functionality/selection/get_sushi_data.php').then(function (response) { //запрос на получение данных о заведении из БД
				$scope.dbSushiInfo = response.data;
			});
	$http.get('../../php_scripts/functionality/selection/get_pizza_data.php').then(function (response) { //запрос на получение данных о заведении из БД
				$scope.dbPizzaInfo = response.data;
			});

	$scope.ShowInfo = function(checker, institution) {
		switch (institution) {
			case 1:
				$scope.ManipulateData(checker, $scope.dbClubInfo);
				break;
			case 2:
				$scope.ManipulateData(checker, $scope.dbCinemaInfo);
				break;
			case 3:
				$scope.ManipulateData(checker, $scope.dbSushiInfo);
				break;
			case 4:
				$scope.ManipulateData(checker, $scope.dbPizzaInfo);
				break;
		}

	}
	$scope.ReturnData = function(splicedData, dbInfo) {
		for(var i = 0; i < splicedData.length; i++) {
			dbInfo.push({
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
	$scope.ManipulateData = function(checker, dbInfo) {
		var dbInfoLength = dbInfo.length
		switch (checker) {
			case 1: 
				$scope.nearMetro += 1;
				if($scope.nearMetro % 2 != 0) {
					$scope.splicedMetro = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < dbInfo.length; i++) {
							if(dbInfo[i].metro == '-') {							
								$scope.splicedMetro.push({
									'name':dbInfo[i].name, 
									'pryce_index':dbInfo[i].pryce_index,
									'rating':dbInfo[i].rating,
									'open_time':dbInfo[i].open_time,
									'close_time':dbInfo[i].close_time, 
									'adress':dbInfo[i].adress,
									'metro':dbInfo[i].metro,
									'phone':dbInfo[i].phone, 
									'site':dbInfo[i].site
								});
								dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.nearMetro % 2 == 0) {
						$scope.ReturnData($scope.splicedMetro, dbInfo);
					}
				break;
			case 2: 
				$scope.lowPrice += 1;
				if($scope.lowPrice % 2 != 0) {
					$scope.splicedLowPrice = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < dbInfo.length; i++) {
							if( parseInt(dbInfo[i].pryce_index) >= 3 ) {
								$scope.splicedLowPrice.push({
									'name':dbInfo[i].name, 
									'pryce_index':dbInfo[i].pryce_index,
									'rating':dbInfo[i].rating,
									'open_time':dbInfo[i].open_time,
									'close_time':dbInfo[i].close_time, 
									'adress':dbInfo[i].adress,
									'metro':dbInfo[i].metro,
									'phone':dbInfo[i].phone, 
									'site':dbInfo[i].site
								});
								dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.lowPrice % 2 == 0) {
						$scope.ReturnData($scope.splicedLowPrice, dbInfo);
					}
				break;
			case 3: 
				$scope.highPrice += 1;
				if($scope.highPrice % 2 != 0) {
						$scope.splicedHighPrice = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < dbInfo.length; i++) {
							if( parseInt(dbInfo[i].pryce_index) < 3 ) {
								$scope.splicedHighPrice.push({
									'name':dbInfo[i].name, 
									'pryce_index':dbInfo[i].pryce_index,
									'rating':dbInfo[i].rating,
									'open_time':dbInfo[i].open_time,
									'close_time':dbInfo[i].close_time, 
									'adress':dbInfo[i].adress,
									'metro':dbInfo[i].metro,
									'phone':dbInfo[i].phone, 
									'site':dbInfo[i].site
								});
								dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.highPrice % 2 == 0) {					
						$scope.ReturnData($scope.splicedHighPrice, dbInfo);
					}
				break;
			case 4: 
				$scope.withSite += 1;
				if($scope.withSite % 2 != 0) {
						$scope.splicedWithSite = [];
					for(var j = 0; j < dbInfoLength; j++) {
						for(var i = 0; i < dbInfo.length; i++) {
							if( dbInfo[i].site == '-' ) {
								$scope.splicedWithSite.push({
									'name':dbInfo[i].name, 
									'pryce_index':dbInfo[i].pryce_index,
									'rating':dbInfo[i].rating,
									'open_time':dbInfo[i].open_time,
									'close_time':dbInfo[i].close_time, 
									'adress':dbInfo[i].adress,
									'metro':dbInfo[i].metro,
									'phone':dbInfo[i].phone, 
									'site':dbInfo[i].site
								});
								dbInfo.splice(i, 1);
							}
						}
					}
				}
				else
					if($scope.withSite % 2 == 0) {						
						$scope.ReturnData($scope.splicedWithSite, dbInfo);
					}
				break;
		}
	}
});
///////////////////////PROPOSE////////////////////////////////////////////////////////////////////////////////////////////
var proposeApp = angular.module('proposeApp', []);

proposeApp.directive('ngTextValidation', function(){
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function textValidation(value) {
				var regular = /[A-ZА-Яa-zа-я]/;
				if ( regular.test(value) == true ){
					ctrl.$setValidity('text', true);
				}
				else {
					ctrl.$setValidity('text', false);
				}
				return value;
			}
			ctrl.$parsers.push(textValidation);
		}
	}
});

proposeApp.directive('ngTimeValidation', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function timeValidation(value) {
				var regular = /^\d{1,2}:\d{2}([ap]m)?$/;
				if ( regular.test(value) == true ) {
					ctrl.$setValidity('time', true);
				}
				else {
					ctrl.$setValidity('time', false);	
				}
				return value;
			}
			ctrl.$parsers.push(timeValidation);
		}
	}
});

proposeApp.directive('ngPhoneValidation', function(){
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function phoneValidation(value) {
				var regular = /\(\d{3}\)\-\d{3}\-\d{2}\-\d{2}/;
				if ( regular.test(value) == true ){
					ctrl.$setValidity('phone', true);
				}
				else {
					ctrl.$setValidity('phone', false);
				}
				return value;
			}
			ctrl.$parsers.push(phoneValidation);
		}
	}
});

proposeApp.directive('ngSiteValidation', function(){
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function phoneValidation(value) {
				var regular1 = /^(http(s?):\/\/)[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/;
				var regular2 = /^(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/;
				if ( (regular1.test(value) == true) || (regular2.test(value) == true) ) {
					ctrl.$setValidity('site', true);
				}
				else {
					ctrl.$setValidity('site', false);
				}
				return value;
			}
			ctrl.$parsers.push(phoneValidation);
		}
	}
});

proposeApp.controller('proposeCtrl', function($scope, $http) {
	$scope.category = ['Club', 'Kino', 'Pizza', 'Sushi'];
	$scope.price = ['1', '2', '3', '4', '5'];
	$scope.ProposePlace = function() {
		var checkPlace = false;
		$http.get('../../php_scripts/functionality/propose/check_new_place.php').then(function(data) {
			$scope.dbInfo = data;
			for(var i = 0; i < $scope.dbInfo.data.length; i++) {
				if( $scope.dbInfo.data[i].name == $scope.name ) {
					checkPlace = true;
				}
			}
			if (checkPlace == true) {
				alert("Мы уже знакомы с данным заведением, спасибо=)");
			}
			else {
				$http.post('../../php_scripts/functionality/propose/add_new_place.php', {
					'name': $scope.name,
					'placeCategory': $scope.placeCategory,
					'priceIndex': $scope.priceIndex,
					'openTime': $scope.openTime,
					'closeTime': $scope.closeTime,
					'address': $scope.address,
					'metro': $scope.metro,
					'phone': $scope.phone,
					'site': $scope.site,
				}).success(function(){
					alert("Спасибо за новое место!");
					window.location.replace("http://arrow.ru/");
				});		
			}
		});
	};
});
//////////////////////VOTES///////////////////////////////////////////////////////////////////////////////////////////////
var ratingApp = angular.module('ratingApp', ['angular-raphael-gauge','ngRoute']);
ratingApp.controller('ratingCtrl', function($scope, $http) {
////////////////////////// Отрисовка графиков	
	var graphOpacity = 0.55;
	$scope.placeObject = {
		place: [
		{
			opacity: graphOpacity,
			value: 0,
			text: 'Some place'	
		},
		{
			opacity: graphOpacity,
			value: 0,
			text: 'Some place'	
		},
		{
			opacity: graphOpacity,
			value: 0,
			text: 'Some place'
		},
		{
			opacity: graphOpacity,
			value: 0,
			text: 'Some place'
		},
		]
	};
	$http.get('../../php_scripts/functionality/votes/get_new_place_data.php').then(function(response) {
		for(var i = 0; i < response.data.length; i++) {
			$scope.placeObject.place[i].value = response.data[i].index_of_validity;
			$scope.placeObject.place[i].name = response.data[i].name;
		}
		$scope.placeData = response.data;
	});
	////////////////////////
	//////////////////////// Отображение данных по графикам
	$scope.showInfoPressed = 'false';
	$scope.placeCSSStatus = ['inline', 'inline', 'inline', 'inline'];
	$scope.placePathes = ['#/place1', '#/place2', '#/place3', '#/place4'];
	$scope.placeEtalonPathes = ['#/place1', '#/place2', '#/place3', '#/place4'];
	$scope.placeSelectedStatus = [false, false, false, false];

	$scope.VisibleObjects = function(param) {
		if($scope.placeSelectedStatus[param] == false) {
			for (var i = 0; i < $scope.placeSelectedStatus.length; i++) {
				if(i != param) {
					$scope.placeCSSStatus[i] = 'none';
				}
			}
			$scope.placeSelectedStatus[param] = true;
			$scope.placePathes[param] = $scope.placeEtalonPathes[param];
		} 
		else if($scope.placeSelectedStatus[param] == true) {
			for (var i = 0; i < $scope.placeCSSStatus.length; i++) {
				if(i != param) {
					$scope.placeCSSStatus[i] = 'inline';
					$scope.confirmCSSPlace = 'inline';
				}					
			}
			$scope.placeSelectedStatus[param] = false;
			$scope.placePathes[param] = '#';
		}
	}

	$scope.ShowInfo = function(param) {
		switch (param) {
			case 0:
					$scope.VisibleObjects(param);
					$scope.showInfoPressed = 'true';
				break;
			case 1:
					$scope.VisibleObjects(param);
					$scope.showInfoPressed = 'true';
				break;
			case 2:
					$scope.VisibleObjects(param);
					$scope.showInfoPressed = 'true';
				break;
			case 3:
					$scope.VisibleObjects(param);
					$scope.showInfoPressed = 'true';
				break;		
		}		
	}

	$scope.confirmCSSPlace = 'inline'; // стиль для исчезающий объектов в подгруженных place-ах
	$scope.ConfirmPlace = function(placeName, placeId, answer) {
		var cookie = document.cookie; // получаем куки из браузера
		var cookieParams = cookie.split('='); // отделяем имя куки от данных
		var userLogin = cookieParams[0];
		var checkUserVote = false;
		$http.get('../../php_scripts/functionality/votes/check_user_votes.php').then(function(response) { // получаем данные о голосовавших пользователях
			var votedUsers = response;
			for (var i = 0; i < votedUsers.data.length; i++) {
				if ( (userLogin == votedUsers.data[i].userLogin) && (placeName == votedUsers.data[i].placeName) && (placeId == votedUsers.data[i].placeId) ) {
					checkUserVote = true;
				}
			}
			if (checkUserVote == true) {
				alert("Спасибо за участие, но ваш голос уже был принят:)");
				window.location.replace("http://arrow.ru/functionality/votes/rating.php");
			}
			else if (checkUserVote == false) {
				$http.post('../../php_scripts/functionality/votes/post_new_vote.php', {
					'userLogin': userLogin, 
					'placeId': placeId, 
					'placeName': placeName, 
					'vote': answer
				}).then(function() { // записываем данные о голосовании
					$scope.confirmCSSPlace = 'none';
				});
			}
		});
	}
////////////////////////// 
});

////////////////////////// Роутинг данных по графикам
ratingApp.config(function($routeProvider) {
	$routeProvider
	.when('/place1', {
		templateUrl: '../../pages/functionality/votes/place_1.php'
	})
	.when('/place2', {
		templateUrl: '../../pages/functionality/votes/place_2.php'
	})
	.when('/place3', {
		templateUrl: '../../pages/functionality/votes/place_3.php'
	})
	.when('/place4', {
		templateUrl: '../../pages/functionality/votes/place_4.php'
	})
});
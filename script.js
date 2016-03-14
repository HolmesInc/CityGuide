///////////////////////INDEX//////////////////////////////////////////////////////////////////////////////////////////////
var indexApp = angular.module('indexApp', []);
///////////////////////LOGIN//////////////////////////////////////////////////////////////////////////////////////////////
var loginFormApp = angular.module('loginFormApp', []); // модуль страницы ввода логина

loginFormApp.controller('loginCtrl',function($scope, $http) {
	$scope.master = {};
	$scope.isUnchanged = function(user) { // функция проверки на заполненность поля
		return angular.equals(user, $scope.master);
	};			
});
///////////////////////REGISTRATION///////////////////////////////////////////////////////////////////////////////////////
var regFormApp = angular.module('regFormApp', ['vcRecaptcha']); // модуль формы регистрации (с подключённым модулем Капчи)

regFormApp.controller('regCtrl', function($scope, $http, vcRecaptchaService) {
	$scope.master = {};
	$scope.ConfirmPass = function(newUser) { // функция проверки на соответствие пароля и его подтверждения
		return !(angular.equals(newUser.password, newUser.confirmPassword));
	};

	$scope.SignUp = function(newUser) { // функция регистрации пользователя
		newUser.checkEmail = false; // переменная для проверки на существующий email
		$http.get('php_scripts/check_user.php').then(function(data) { // получение данных о пользователях и их сравнение с регистрируемым
			$scope.dbInfo = data;
			for(var i = 0; i < $scope.dbInfo.data.length; i++) {
				if(newUser.email == $scope.dbInfo.data[i].email) {
					newUser.checkEmail = true;
				}
			}
			if(newUser.checkEmail==true) {
				alert("К сожалению, такой E-Mail уже зарегестрирован");
			}
			else {
				$http.post('php_scripts/user_registration.php', {'name': newUser.name, 'email': newUser.email, 'password': newUser.password}).then(function() {
					alert("Поздравляем, Вы зарегистрировались!");
					window.location.replace("login.php");
				});
			}
		});
	}
});
///////////////////////SELECTION//////////////////////////////////////////////////////////////////////////////////////////
var selectionApp = angular.module('selectionApp', ['tableSort']); // модуль выборки по заведениям

selectionApp.controller('selectionCtrl', function($scope, $http) {
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

	$scope.ShowMap = function(placeType) {
		map = new google.maps.Map(document.getElementById("map"), {
				center: city,
				zoom: zoom,
				mapTypeId: 'roadmap'
			});
		var infoWindow = new google.maps.InfoWindow;
		// ссылки на php-скрипты для получения дпнных о заведениях для карты
		var clubMapPath = 'getClubLocation.php';
		var cinemaMapPath = 'getCinemaLocation.php';
		var sushiMapPath = 'getSushiLocation.php';
		var pizzaMapPath = 'getPizzaLocation.php';
		var placePath = '';
		switch (placeType) {
			case 1:
				placePath = clubMapPath;
				break;
			case 2:
				placePath = cinemaMapPath;
				break;
			case 3:
				placePath = sushiMapPath;
				break;
			case 4:
				placePath = pizzaMapPath;
				break;
		}
		downloadUrl("../../../php_scripts/functionality/selection/" + placePath, function(data) {
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
	$scope.tableRowCSSStatus = []; // массив для хранения статусов строк таблицы выборки(скрыта/показана)
	// переменные, хранящие состояние соответствующих переключателей
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

//////////////////////Новый алгоритм сортировки заведений
//(Добавить возможность перекрёсной сортировки. Возможно с помощью массива, содержащего информацию о том, какой чек скрыл данную строку)
	$scope.SortInfo = function(checker, dbPlaceData) {
		if ($scope.tableRowCSSStatus.length < dbPlaceData.length) { //создаём пустой массив состояний строк таблицы
			for (var i = 0; i < dbPlaceData.length; i++) {
				$scope.tableRowCSSStatus.push('');
			}
		}
		switch (checker) { // выбираем значения пришедшего переключателя(1-Поближе к метро; 2-Подшевле; 3-Подороже; 4-Есть сайт)
			case 1:
				$scope.nearMetro += 1;
				if ($scope.nearMetro % 2 != 0) { // скрываем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (dbPlaceData[i].metro == '-') {
							$scope.tableRowCSSStatus[i] = 'none';
						}
					}
				}
				else if ($scope.nearMetro % 2 == 0) { // возвращаем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (dbPlaceData[i].metro == '-') {
							$scope.tableRowCSSStatus[i] = '';
						}	
					}
				}
				break;
			case 2:
				$scope.lowPrice += 1;
				if ($scope.lowPrice % 2 != 0) { // скрываем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (parseInt(dbPlaceData[i].pryce_index) > 3) {
							$scope.tableRowCSSStatus[i] = 'none';
						}
					}
				}
				else if ($scope.lowPrice % 2 == 0) { // возвращаем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (parseInt(dbPlaceData[i].pryce_index) > 3) {
							$scope.tableRowCSSStatus[i] = '';
						}
					}
				}
				break;
			case 3:
				$scope.highPrice += 1;
				if ($scope.highPrice % 2 != 0) { // скрываем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (parseInt(dbPlaceData[i].pryce_index) <= 3) {
							$scope.tableRowCSSStatus[i] = 'none';
						}
					}
				}
				else if ($scope.highPrice % 2 == 0) { // возвращаем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (parseInt(dbPlaceData[i].pryce_index) <= 3) {
							$scope.tableRowCSSStatus[i] = '';
						}
					}
				}
				break;
			case 4:
				$scope.withSite += 1;
				if ($scope.withSite % 2 != 0) { // скрываем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (dbPlaceData[i].site == '-') {
							$scope.tableRowCSSStatus[i] = 'none';
						}
					}
				}
				else if ($scope.withSite % 2 == 0) { // возвращаем строку
					for (var i = 0; i < dbPlaceData.length; i++) {
						if (dbPlaceData[i].site == '-') {
							$scope.tableRowCSSStatus[i] = '';
						}
					}
				}
				break;
		}
	}

	$scope.ShowInfo = function(checker, institution) { // функция проверяет, какой тип заведения будет отсортирован по пришедшему значению:
		// (1-Клубы; 2-Кинотеатры; 3-Суши-бары; 4-Пиццерии)
		switch (institution) {
			case 1:
				$scope.SortInfo(checker, $scope.dbClubInfo);
				break;
			case 2:
				$scope.SortInfo(checker, $scope.dbCinemaInfo);
				break;
			case 3:
				$scope.SortInfo(checker, $scope.dbSushiInfo);
				break;
			case 4:
				$scope.SortInfo(checker, $scope.dbPizzaInfo);
				break;
		}
	}
//////////////////////
});
///////////////////////PROPOSE////////////////////////////////////////////////////////////////////////////////////////////
var proposeApp = angular.module('proposeApp', []); // модуль страницы для рекомендации заведения на голосование

proposeApp.directive('ngTextValidation', function() { // директива проверяет введённую информацию на соответствие регулярному выражению
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function textValidation(value) {
				var regular = /[A-ZА-Яa-zа-я]/; // текст на латинице или киррилице
				if ( regular.test(value) == true ) {
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

proposeApp.directive('ngTimeValidation', function() { // директива проверяет введённую информацию на соответствие регулярному выражению
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function timeValidation(value) {
				var regular = /^\d{1,2}:\d{2}([ap]m)?$/; // формат времени 00:00
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

proposeApp.directive('ngPhoneValidation', function() { // директива проверяет введённую информацию на соответствие регулярному выражению
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function phoneValidation(value) {
				var regular = /\(\d{3}\)\-\d{3}\-\d{2}\-\d{2}/; // формат телефонного номера (000)-000-00-00
				if ( regular.test(value) == true ) {
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

proposeApp.directive('ngSiteValidation', function() { // директива проверяет введённую информацию на соответствие регулярному выражению
	return {
		require: 'ngModel',
		link: function(scope, element, attr, ctrl) {
			function phoneValidation(value) {
				var regular1 = /^(http(s?):\/\/)[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/; // формат веб-сайта, начинающийся с http/s
				var regular2 = /^(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/; // формат веб-сайта, начинающийся с www
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
	$scope.category = ['Club', 'Kino', 'Pizza', 'Sushi']; // массив катигорий для выпадающего списка
	$scope.price = ['1', '2', '3', '4', '5']; // массив ценовых индексов для выпадающег осписка
	$scope.ProposePlace = function() { // функция проверияет, имеется ли уже данное заведение в БД. Если нет, записывает предложенное
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
					window.location.reload();
				});		
			}
		});
	};
});
//////////////////////VOTES///////////////////////////////////////////////////////////////////////////////////////////////
var ratingApp = angular.module('ratingApp', ['angular-raphael-gauge','ngRoute']); // модуль страницы голосований(с подключёнными модулями отрисовки графика и Роутинга)

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
	$scope.showInfoPressed = 'false'; // хранит состояние отображенных с помощью роутинга данных
	$scope.placeCSSStatus = ['inline', 'inline', 'inline', 'inline']; // массив состояний графиков(скрыты/отображены)
	$scope.placePathes = ['#/place1', '#/place2', '#/place3', '#/place4']; // массив изменяемых ссылок для роутинга 
	$scope.placeEtalonPathes = ['#/place1', '#/place2', '#/place3', '#/place4']; // массив исходных ссылок для роутинга
	$scope.placeSelectedStatus = [false, false, false, false]; // массив состояний графиков(открыта/скрыта по ним дополнительная информация)

	$scope.VisibleObjects = function(param) { // функция срывает/отображает графики и открывает по ним дополнительную информацию
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
				window.location.replace("../votes/rating.php");
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
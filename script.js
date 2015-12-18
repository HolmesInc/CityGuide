		var loginFormApp = angular.module('loginFormApp', []);

		loginFormApp.controller('loginCtrl',function($scope, $http){
			/*вспомагательные скрипты, на данный момент не используются
			//создаём запрос к php-скрпту на получение данных из БД
			$http.get('get_login_data.php').success(function(data) 
		   	{
		        //заносим в scope полученные данные
		    	$scope.usersData = data;
			});
			
			$scope.master = {};
			$scope.update = function(user) 
			{
				$scope.master= angular.copy(user);
			};
					
			$scope.reset = function() 
			{
				$scope.user = angular.copy($scope.master);
			};

			$scope.isUnchanged = function(user) 
			{
				return angular.equals(user, $scope.master);
			};

			$scope.reset();
			*/
		});
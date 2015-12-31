<!--<?php
	$a=htmlspecialchars("<div>12345</div>");
	$b="<div>12345</div>";
	echo $a;
	echo $b;
?>
-->
<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title></title>
	<script type="text/javascript" src="addons/angular_for_login.js"></script>
	<script type="text/javascript">
var app = angular.module('myApp', []);



app.directive("passwordVerify", function() {
	return {
    	require: "ngModel",
    	scope: {
    		passwordVerify: '='
    	},
    	link: function(scope, element, attrs, ctrl) {
    		scope.$watch(function() {
    			var combined;
    			if (scope.passwordVerify || ctrl.$viewValue) {
    				combined = scope.passwordVerify + '_' + ctrl.$viewValue; 
    			}                    
    			return combined;
    		}, 
    		function(value) {
    			if (value) {
    				ctrl.$parsers.unshift(function(viewValue) {
    					var origin = scope.passwordVerify;
    					if (origin !== viewValue) {
    						ctrl.$setValidity("passwordVerify", false);
    						return undefined;
    					} else {
    						ctrl.$setValidity("passwordVerify", true);
    						return viewValue;
    					}
    				});
    			}
    		});
    	}
    };
});
	</script>

</head>
<body>
	<div >
   <form name='form'>
	    <input data-ng-model='user.password' type="password" name='password' placeholder='password' required>
	      
	    <div ng-show="form.password.$error.required">
	        1 пусто
	    </div>

	    <input ng-model='user.password_verify' type="password" name='confirm_password' placeholder='confirm password' required data-password-verify="user.password">
	    
	    <div ng-show="form.confirm_password.$error.required">
	        2 пусто
	    </div>
	    <div ng-show="form.confirm_password.$error.passwordVerify">
	        не равны
	    </div>
   </form>
</div>
</body>
</html>

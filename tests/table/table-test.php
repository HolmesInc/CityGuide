<!DOCTYPE html>
<html ng-app="tableApp">
<head>
	<title>Table-Test</title>
	<script type="text/javascript" src="angular.js"></script>
	<script type="text/javascript" src="angular-tablesort.js"></script>
	<script type="text/javascript">
		var tableApp = angular.module('tableApp', ['tableSort']);
		tableApp.controller('tableCtrl', function($scope, $http){
			$http.get('data.php').then(function (response) {
				$scope.dbInfo = response.data;
			});
		});
	</script>
	<link rel="stylesheet" type="text/css" href="tablesort.css">
</head>
<body ng-controller="tableCtrl">
	<table ts-wrapper>
		<thead>
			<tr>
				<th ts-criteria="id">id</th>
				<th ts-criteria="name|lowercase" ts-default>name</th>
				<th ts-criteria="category|lowercase" ts-default>category</th>
				<th ts-criteria="pryce_index|parseInt">pryce_index</th>
				<th ts-criteria="rating|parseInt">rating</th>
				<th ts-criteria="open_time|parseInt">open_time</th>
				<th ts-criteria="close_time|parseInt">close_time</th>
				<th ts-criteria="adress|lowercase" ts-default>adress</th>
				<th ts-criteria="metro|lowercase" ts-default>metro</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat=" data in dbInfo track by data.id" ts-repeat>
				<td>{{data.id}}</td>
				<td>{{data.name}}</td>
				<td>{{data.category}}</td>
				<td>{{data.pryce_index}}</td>
				<td>{{data.rating}}</td>
				<td>{{data.open_time}}</td>
				<td>{{data.close_time}}</td>
				<td>{{data.adress}}</td>
				<td>{{data.metro}}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
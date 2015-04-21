/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

.controller('LoginCtrl', function($scope, $state, LoginService, $ionicLoading, $ionicPopup, $ionicHistory) {

        if(LoginService.isLoggedIn()) {
            $state.go('noobs');
        }

        $ionicHistory.nextViewOptions({
            disableBack: true
        });

        $scope.credentials = {username: 'kortstraw', password: 'f3'}

        $scope.login = function() {
            $ionicLoading.show({
                templateUrl: 'templates/loading/login.html',
                hideOnStateChange: true
            });
            LoginService.login($scope.credentials.username, $scope.credentials.password).then(
                function(token) {
                    $state.go('noobs');
                },
                function() {
                    $ionicLoading.hide();
                    $ionicPopup.alert({
                        title: 'Error',
                        templateUrl: 'templates/popup/login.html'
                    });
                }
            );
        }

    })
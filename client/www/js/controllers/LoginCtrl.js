/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

.controller('LoginCtrl', function($scope, $state, LoginService, $ionicLoading, $ionicPopup, $ionicHistory) {

        if(LoginService.isLoggedIn()) {
            $state.go('noobs');
        }

        $scope.credentials = {username: '', password: ''}

        $scope.login = function() {
            $ionicLoading.show({
                templateUrl: 'templates/loading/login.html',
                hideOnStateChange: true
            });
            LoginService.login($scope.credentials.username, $scope.credentials.password).then(
                function(token) {
                    $ionicHistory.nextViewOptions({
                        disableBack: true
                    });
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
/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .controller('NoobsCtrl', function($scope, $state, $ionicNavBarDelegate, $ionicHistory, NoobService, noobs) {

        $scope.noobs = noobs;

        $scope.refreshNoobs = function() {
            NoobService.get_noobs(true).then(
                function (new_noobs) {
                    $scope.noobs = new_noobs;
                    $scope.$broadcast('scroll.refreshComplete');
                }
            );
        }

        $scope.add_points = function(noob_id, points) {
            $state.go('add_points', {noob_id: noob_id, points: points});
        }

    })
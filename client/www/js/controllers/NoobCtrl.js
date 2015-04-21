/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .controller('NoobCtrl', function($scope, $state, $stateParams, NoobService, noob_actions) {

        $scope.noob = {};

        NoobService.get_noobs().then(function(noobs) {
            for(n in noobs) {
                if(noobs[n].id == $stateParams.noob_id) {
                    $scope.noob = noobs[n];
                }
            }
        });

        $scope.noob_actions = noob_actions;

        $scope.refreshNoobActions = function() {
            NoobService.get_noob_actions($stateParams.noob_id, true).then(
                function (new_noob_actions) {
                    $scope.noob_actions = new_noob_actions;
                    $scope.$broadcast('scroll.refreshComplete');
                }
            );
        }

    })
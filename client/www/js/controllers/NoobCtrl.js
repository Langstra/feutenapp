/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .controller('NoobCtrl', function($scope, $state, $stateParams, $ionicModal, NoobService, noob_actions) {

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

        $scope.view_proof = function(url) {
            if(url !== null) {
                $scope.url = url;
                $scope.openModal();
            }
        }

        $ionicModal.fromTemplateUrl('templates/modal/noob_action_photo.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {
            $scope.modal = modal;
        });
        $scope.openModal = function() {
            $scope.modal.show();
        };
        $scope.closeModal = function() {
            $scope.modal.hide();
        };
        //Cleanup the modal when we're done with it!
        $scope.$on('$destroy', function() {
            $scope.modal.remove();
        });
        // Execute action on hide modal
        $scope.$on('modal.hidden', function() {
            // Execute action
        });
        // Execute action on remove modal
        $scope.$on('modal.removed', function() {
            // Execute action
        });

    })
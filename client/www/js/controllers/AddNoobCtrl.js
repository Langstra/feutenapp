/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .controller('AddNoobCtrl', function($scope, $ionicActionSheet, $ionicLoading, $ionicPopup, Camera, NoobService) {

        $scope.noob = {
            img: "",
            name: ""
        }

        $scope.add_noob = function() {
            $ionicLoading.show({
                templateUrl: 'templates/loading/add_noob.html'
            });
            NoobService.upload_media($scope.noob.img).then(
                function(result) {
                    $scope.noob.img = result.response;
                    NoobService.add_noob($scope.noob).then(
                        function(result) {
                            $ionicLoading.hide();
                            if(result === true) {
                                $ionicPopup.alert({
                                    title: 'Succes',
                                    templateUrl: 'templates/popup/add_noob_succes.html'
                                });
                            } else {
                                $ionicPopup.alert({
                                    title: 'Fail',
                                    templateUrl: 'templates/popup/add_noob_fail.html'
                                });
                            }
                        },
                        function() {
                            $ionicLoading.hide();
                            $ionicPopup.alert({
                                title: 'Fail',
                                templateUrl: 'templates/popup/add_noob_fail.html'
                            });
                        }
                    );
                }
            );
        };

        $scope.take_picture = function() {
            $ionicActionSheet.show({
                buttons: [
                    { text: '<i class="icon ion-android-camera"></i> Camera' },
                    { text: '<i class="icon ion-android-image"></i> Gallery' }
                ],
                titleText: 'Take your picture source',
                cancelText: 'Cancel',
                cancel: function() {
                    // add cancel code..
                },
                buttonClicked: function(index) {
                    if(index == 0) {
                        Camera.take_picture().then(
                            function(img) {
                                $scope.noob.img = img;
                            }
                        );
                    } else if(index == 1) {
                        Camera.from_gallery().then(
                            function(img) {
                                $scope.noob.img = img;
                            }
                        );
                    }
                    return true;
                }
            });
        };



    })
/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .controller('PointsCtrl', function($scope, $ionicActionSheet, $ionicLoading, $ionicPopup, $cordovaFileOpener2, $cordovaFile, $stateParams, Camera, NoobService) {

        $scope.selected_noobs = [];

        $scope.noob = {reason_text: "", points: parseInt($stateParams.points)}

        $scope.scroll_pane_width = '100%';

        $scope.proof = {};

        NoobService.get_noobs().then(function(noobs) {
            $scope.noobs = noobs;
            for(n in $scope.noobs) {
                $scope.noobs[n].selected = false;
            }
            $scope.scroll_pane_width = $scope.noobs.length * 105 + 'px';
        });

        $scope.toggle_noob = function(noob_id) {
            var pos = $scope.selected_noobs.indexOf(noob_id)
            if(pos == -1) {
                $scope.selected_noobs.push(noob_id);
            } else {
                $scope.selected_noobs.splice(pos, 1);
            }
            console.log($scope.selected_noobs);
            for(n in $scope.noobs) {
                if($scope.noobs[n].id == noob_id) {
                    $scope.noobs[n].selected = !$scope.noobs[n].selected;
                    console.log(noob_id)
                }
            }
        };

        $scope.capture_image = function() {
            Camera.take_picture().then(function(result) {
                console.log(JSON.stringify(result));
                $scope.proof = {filePath: result, type: 'image/jpeg'};
                $('.proof_preview').show();
            }, function(err) {

            });
        };

        $scope.view_proof = function () {
            console.log($scope.proof.filePath);
            $cordovaFileOpener2.open(
                $scope.proof.filePath,
                $scope.proof.type
            ).then(function() {
                // Success!
            }, function(err) {
                console.log(JSON.stringify(err));
            });
        };

        $scope.remove_proof = function() {
            $('.proof_preview').hide();
            $cordovaFile.removeFile($scope.proof.filePath.substring(0, $scope.proof.filePath.lastIndexOf('/')+1), $scope.proof.filePath.substring($scope.proof.filePath.lastIndexOf('/')+1));
            $scope.proof = {};
        };

        $scope.add_points = function() {

            add_points = function(result) {
                var response = typeof result !== 'undefined' ? result.response : null;
                NoobService.add_points(
                    {
                        noob_ids: $scope.selected_noobs,
                        reason_text: $scope.noob.reason_text,
                        reason_file: response,
                        amount: $scope.noob.points
                    }
                ).then(
                    function(result) {
                        $ionicLoading.hide();
                        if(result === true) {
                            $ionicPopup.alert({
                                title: 'Succes',
                                templateUrl: 'templates/popup/add_points_succes.html'
                            });
                        } else {
                            $ionicPopup.alert({
                                title: 'Fail',
                                templateUrl: 'templates/popup/add_points_fail.html'
                            });
                        }
                    },
                    function() {
                        $ionicLoading.hide();
                        $ionicPopup.alert({
                            title: 'Fail',
                            templateUrl: 'templates/popup/add_points_fail.html'
                        });
                    }
                );
            };

            $ionicLoading.show({
                templateUrl: 'templates/loading/add_points.html'
            });
            if($.isEmptyObject($scope.proof.lenght)) {
                add_points();
            } else {
                $cordovaFile.moveFile($scope.proof.filePath.substring(0, $scope.proof.filePath.lastIndexOf('/') + 1), $scope.proof.filePath.substring($scope.proof.filePath.lastIndexOf('/') + 1), cordova.file.externalDataDirectory, $scope.proof.filePath.substring($scope.proof.filePath.lastIndexOf('/') + 1)).then(function (result) {

                    NoobService.upload_media(result.toURL()).then(
                        add_points(result)
                    );
                });
            }
        };

        $('.proof_icon').css('font-size', $('.add_proof_icons').width()/4);

        if($stateParams.noob_id != -1) {
            $scope.toggle_noob($stateParams.noob_id);
        }
    })
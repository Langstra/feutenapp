/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .service('Camera', function ($cordovaCamera, IMG, $cordovaCapture) {

        return {
            'take_picture': function (width, height) {

                var deferred = $.Deferred();

                var options = {
                    quality: 50,
                    destinationType: Camera.DestinationType.NATIVE_URI,
                    sourceType: Camera.PictureSourceType.CAMERA,
                    allowEdit: true,
                    encodingType: Camera.EncodingType.JPEG,
                    saveToPhotoAlbum: false
                };

                if(typeof width !== 'undefined') {
                    options.targetWidth = width;
                }
                if(typeof height !== 'undefined') {
                    options.targetHeight = height;
                }

                $cordovaCamera.getPicture(options).then(function(imageData) {
                    console.log(imageData);
                    deferred.resolve(imageData);
                }, function(err) {
                    console.log(JSON.stringify(err));
                    deferred.reject(err);
                });

                return deferred.promise();
            },

            'from_gallery': function (width, height) {
                var deferred = $.Deferred();

                var options = {
                    quality: 50,
                    destinationType: Camera.DestinationType.FILE_URI,
                    sourceType: Camera.PictureSourceType.PHOTOLIBRARY,
                    allowEdit: true
                };

                if(typeof width !== 'undefined') {
                    options.targetWidth = width;
                }
                if(typeof height !== 'undefined') {
                    options.targetHeight = height;
                }

                $cordovaCamera.getPicture(options).then(function(imageData) {
                    deferred.resolve(imageData);
                }, function(err) {
                    deferred.reject(err);
                });

                return deferred.promise();
            },

            'capture_video': function() {
                var deferred = $.Deferred();

                var options = { limit: 1, duration: 15 };

                $cordovaCapture.captureVideo(options).then(function(videoData) {
                    deferred.resolve(videoData);
                }, function(err) {
                    deferred.reject(err);
                });

                return deferred.promise();
            },

            'capture_audio': function() {
                var deferred = $.Deferred();

                var options = { limit: 1, duration: 120 };

                $cordovaCapture.captureAudio(options).then(function(audioData) {
                    deferred.resolve(audioData);
                }, function(err) {
                    deferred.reject(err);
                });

                return deferred.promise();
            }
        }
    })
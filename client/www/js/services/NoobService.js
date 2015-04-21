/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

    .service('NoobService', function (JsonRPC, LocalStorage, $cordovaNetwork, $cordovaFileTransfer, $cordovaFile, ENV) {

        return {
            'get_noobs': function (refresh) {
                refresh = typeof refresh !== 'undefined' ? refresh : false;
                var deferred = $.Deferred();

                if(refresh || LocalStorage.getObject('noobs', null) == null) {
                    JsonRPC.queryServer('get_noobs', {token: LocalStorage.get('logintoken')}).then(
                        function (data) {
                            LocalStorage.setObject('noobs', data)
                            deferred.resolve(data);
                        }
                    );
                } else {
                    deferred.resolve(LocalStorage.getObject('noobs'));
                }

                return deferred.promise();
            },

            'get_noob_actions': function(noob_id, refresh) {
                refresh = typeof refresh !== 'undefined' ? refresh : false;
                var deferred = $.Deferred();

                if(refresh || LocalStorage.getObject('noob_actions', null) === null || typeof LocalStorage.getObject('noob_actions', null)[noob_id] === 'undefined') {
                    JsonRPC.queryServer('get_points', {token: LocalStorage.get('logintoken'), noob_id: noob_id}).then(
                        function (data) {
                            var noob_actions = LocalStorage.getObject('noob_actions', '{}');
                            console.log(noob_actions)
                            noob_actions[noob_id] = data;
                            LocalStorage.setObject('noob_actions', noob_actions);
                            deferred.resolve(data);
                        }
                    );
                } else {
                    var noob_actions = LocalStorage.getObject('noob_actions', {});
                    console.log(noob_actions[noob_id])
                    deferred.resolve(noob_actions[noob_id]);
                }

                return deferred.promise();
            },

            'add_noob': function (noob) {
                return JsonRPC.queryServer(
                    'add_noob', {
                        token: LocalStorage.get('logintoken'),
                        name: noob.name,
                        img_url: noob.img
                });
            },

            'add_points': function (points) {
                return JsonRPC.queryServer(
                    'add_points', {
                        token: LocalStorage.get('logintoken'),
                        noob_ids: points.noob_ids,
                        reason_text: points.reason_text,
                        reason_file: points.reason_file,
                        amount: points.amount
                    });
            },

            'upload_media': function(url) {
                var deferred = $.Deferred();
                console.log('Start uploading file')
                if ($cordovaNetwork.isOnline()) {

                    console.log('Upload will begin');

                    var target = ENV.uploadLink + '?token=' + LocalStorage.get('logintoken');
                    var path = url;
                    var options = {};
                    var trustHosts = true;
                    console.log(path);
                    $cordovaFileTransfer.upload(target, path, options, trustHosts).then(function(fileEntry) {
                        console.log("SUCCESS: " + JSON.stringify(fileEntry));
                        deferred.resolve(fileEntry);
                    }, function (e) {
                        console.log("An error has occurred: Code = " + e.code);
                        console.log("upload error source " + e.source);
                        console.log("upload error target " + e.target);
                        deferred.reject();
                    });


                }else{
                    console.log("No active connection");
                    deferred.reject();
                }
                return deferred.promise();
            }
        }
    })
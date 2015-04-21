/**
 * Created by langstra on 11-4-15.
 */
angular.module('noob-app')

.service('JsonRPC', function ($http, ENV, $cordovaNetwork) {
        /**
         * Sends a request to the server
         * The function handles the errors from the server by calling the error handler
         * @param {string} method - Method to call on the server
         * @param {{}} params - Object with named parameters
         * @param {boolean} store - When there is no internet this boolean tells the function if the call should be stored so it can be called later
         * @returns {*} - Promise that resolves into the returned value from the server or the error message.
         */
        this.queryServer = function (method, params) {

            var deferred = $.Deferred();

            if (ENV.debug || $cordovaNetwork.isOnline()) {
                $http.jsonrpc(ENV.apiServerLink, method, params)
                    .success(function (data, status, headers, config) {
                        console.log(JSON.stringify(data));
                        if (data.error || data.result.error) {
                            if (data.error) {
                                deferred.resolve(data.error);
                            } else {
                                deferred.resolve(data.result.error);
                            }
                        } else {
                            deferred.resolve(data.result);
                        }
                    }).error(function (data, status, headers, config) {
                        console.log('JsonFailed: ' + data);
                        deferred.resolve(data);
                    });
            } else {
                deferred.resolve(false);
            }
            return deferred.promise();
        }
    })

.service('LoginService', function (JsonRPC, LocalStorage) {

        /**
         * Checks if the username and password match by asking the server
         * @param {string} username - Username
         * @param {string} password - Password
         * @returns {*} - Promise that resolved into false when credentials are wrong or authentication token when correct
         */
        this.login = function (username, password) {
            var deferred = $.Deferred();
            var promise = JsonRPC.queryServer('authenticate', {username: username, password: password});
            promise.then(function (loginResult) {
                console.log(JSON.stringify(loginResult));
                console.log(typeof loginResult.token);
                if (typeof loginResult.token !== 'undefined' ) {
                    LocalStorage.set('loggedIn', true);
                    LocalStorage.set('username', username);
                    LocalStorage.set('logintoken', loginResult.token);
                    LocalStorage.addToUsedKeys('loggedIn');
                    LocalStorage.addToUsedKeys('logintoken');
                    deferred.resolve(loginResult);
                } else {
                    deferred.reject(false);
                }

            });
            return deferred.promise();

        }

        /**
         * Checks if the uesr is logged in.
         * @returns {boolean} - True when logged in, otherwise false
         */
        this.isLoggedIn = function () {
            return LocalStorage.get('loggedIn');
        }
    })
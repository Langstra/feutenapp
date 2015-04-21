// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('noob-app', ['ionic', 'ngCordova', 'config', 'angular-json-rpc'])

    .run(function ($ionicPlatform, $state, LoginService) {
        $ionicPlatform.ready(function () {
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs)
            if (window.cordova && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
            }
            if (window.StatusBar) {
                StatusBar.styleDefault();
            }

            if (LoginService.isLoggedIn() && $state.is('login')) {
                $state.go('noobs');
            }
            //$state.go('add_noob');
        });
    })

    .config(function($compileProvider){
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|tel):/);
    })

    .config(function ($stateProvider, $urlRouterProvider) {

        $urlRouterProvider.otherwise('/login')

        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'templates/login.html',
                controller: 'LoginCtrl'
            })
            .state('noobs', {
                url: '/noob',
                templateUrl: 'templates/noobs.html',
                controller: 'NoobsCtrl',
                resolve: {
                    noobs: function(NoobService) {
                        return NoobService.get_noobs()
                    }
                }
            })
            .state('noob', {
                url: '/noob/:noob_id',
                templateUrl: 'templates/noob.html',
                controller: 'NoobCtrl',
                resolve: {
                    noob_actions: function($stateParams, NoobService) {
                        return NoobService.get_noob_actions($stateParams.noob_id)
                    }
                }
            })
            .state('add_points', {
                url: '/add_points/:noob_id/:points',
                templateUrl: 'templates/add_points.html',
                controller: 'PointsCtrl'
            })
            .state('add_noob', {
                url: '/add_noob',
                templateUrl: 'templates/add_noob.html',
                controller: 'AddNoobCtrl'
            })
    })

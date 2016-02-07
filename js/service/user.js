/*global CryptoJS, angular*/
var requestModule = angular.module("userModule", []);

requestModule.factory("UserService", function ($http, config) {
    "use strict";
    /************************************************/
    /** General functions                          **/
    /************************************************/
    var theServiceObject = {};

    theServiceObject.login = function (user, callback) {

        $http.post(config.backend + "users/login", user)
            .success(function (data) {
                callback(data);
            })
            .error(function () {
                callback(null);
            });
    };

    theServiceObject.logout = function (callback) {
        $http.get(config.backend + "users/logout")
            .success(function (data) {
                callback(data);
            })
            .error(function () {
                callback(null);
            });
    };

    theServiceObject.isLoggedIn = function (callback) {

        $http.get(config.backend + "users/isLoggedIn")
            .success(function (data) {
                callback(data);
            })
            .error(function () {
                callback(null);
            });
    };

    theServiceObject.getUsers = function (callback) {
        $http.get(config.backend + "users")
            .success(function (data) {
                callback(data);
            })
            .error(function () {
                callback(null);
            });
    };

    return theServiceObject;
});
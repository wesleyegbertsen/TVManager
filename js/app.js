var theApp = angular.module("tvApp", ["TVManager.development", "ngRoute", "ngAnimate", "ngCookies", "userModule"]);

theApp.config(['$routeProvider', function ($routeProvider) {
    "use strict";
    $routeProvider.
        when('/', {
            templateUrl: 'templates/home.html',
            controller: 'ctrlHome'
        }).
        when('/settings', {
            templateUrl: 'templates/settings.html',
            controller: 'ctrlSettings'
        }).
        when('/login', {
            templateUrl: 'templates/login.html',
            controller: 'ctrlLogin'
        }).
        when('/profile', {
            templateUrl: 'templates/profile.html',
            controller: 'ctrlProfile'
        }).
        otherwise({
            redirectTo: '/'
        });
    }]);

theApp.controller("ctrlMain",function($scope, $location,$rootScope, UserService) {
    "use strict";

    $scope.isLoggedIn = function () {
        UserService.isLoggedIn(function (data) {
            if (data) {
                if (data.success) {
                    $scope.user = data.user;
                    enableUserDropDown();
                } else {
                    $scope.user = {};
                    $location.url("/login");
                }
            } else {
                $scope.user = {};
                $location.url("/login");
            }
        });
    };

    //TODO: Implement better way of client-side authentication
    //Check if user is logged in on every route/page.
    $rootScope.$on("$routeChangeStart", function(args){
        $scope.isLoggedIn();
    });

    $scope.user = {};

    //Main option variables
    $scope.main = {
        currentPage: "",
        pageTitle: ""
    };

    $scope.logout = function () {
        UserService.logout(function (data) {
            if (data) {
                if (data.success) {
                    $scope.user = {};
                    $location.url("/login");
                }
            } else {
                //Something went wrong
            }
        });
    };

    $scope.setMainOptions = function (currentPage, pageTitle) {
        $scope.main.currentPage = currentPage;
        $scope.main.pageTitle = pageTitle;
    };
});

theApp.controller("ctrlHome",function($scope) {
    "use strict";
    $scope.setMainOptions("home", "Home");

});

theApp.controller("ctrlSettings",function($scope) {
    "use strict";
    $scope.setMainOptions("settings", "Settings");

});

theApp.controller("ctrlProfile",function($scope) {
    "use strict";
    $scope.setMainOptions("profile", "Profile");

});

theApp.controller("ctrlLogin",function($scope, $cookies,$location, UserService) {
    "use strict";
    $scope.setMainOptions("login", "Log-in");
    UserService.isLoggedIn(function (data) {
        if (data) {
            if(data.success) {
                $location.url("/");
            }
        }
    });

    $scope.userForm = {
        username: "",
        password: ""
    };

    $scope.error = {
        show: false,
        text: "Error"
    };

    $scope.rememberUsername = false;


    if ($cookies.get("username")) {
        $scope.userForm.username = $cookies.get("username");
        $scope.rememberUsername = true;
    }

    $scope.login = function () {
        $scope.error.show = false;
        UserService.login($scope.userForm, function (data) {
            if(data) {
                if(data.success) {
                    $scope.user = data.user;
                    enableUserDropDown();

                    if($scope.rememberUsername) {
                        $cookies.put("username", data.user.username);
                    } else {
                        $cookies.remove("username");
                    }

                    $location.path("/");

                } else {
                    $scope.error.text = data.message;
                    $scope.error.show = true;
                }
            }
        });
    };

    $scope.formChanged = function () {
        if ($scope.error.show) {
            $scope.error.show = false;
        }
    };

});

theApp.directive("tvShowCard", function () {
    "use strict";
    return {
        restrict: 'E',
        scope: {
            id: "@id",
            name: "@name",
            info: "@info",
            poster: "@poster",
            ytid: "@ytid"
        },
        templateUrl: "directives/tvshowcard.html",
        link: function (scope, element, attrs) {
            scope.ytVideo = function(ytId, title) {
                $(function () {
                    $.fancybox.open([
                        {
                            type: 'iframe',
                            href : 'http://www.youtube.com/embed/'+ytId+'?autoplay=1&wmode=opaque',
                            title : title
                        }
                    ], {
                        padding : 0,
                        width: 800,
                        height: 450,
                        aspectRatio: true,
                        scrolling: 'no'
                    });
                });
            };
        }
    };
});

/*
 Using timeout, to make sure the dropdown function is called after the ng-if is finished
 As browsers by default keep all events in a queue, therefore, when digest loop is running,
 the callback function from setTimeout will enter the queue and get executed as soon digest loop is over from the ng-if.
 */
function enableUserDropDown () {
    "use strict";

    setTimeout(function() {
        $(".dropdown-button").dropdown({
            belowOrigin: true
        });
    }, 0);
}
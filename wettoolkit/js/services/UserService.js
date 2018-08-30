(function () {
    'use strict';

    angular
            .module('portal')
            .factory('user', user);

    function user($resource, $q) {
        var publicKey = localStorage.getItem('publicKey');
        var privateKey = localStorage.getItem('privateKey');

        function getUsers(filter) {
            var params = {'public_key': publicKey};

            //check for a filter
            filter = (typeof filter === 'undefined') ? null : filter;
            if (filter) {
                for (var key in filter) {
                    if (filter.hasOwnProperty(key)) {
                        params[key] = filter[key];
                    }
                }
            }

            var queryString = angular.toJson(params);

            var User = $resource('/portal/internapi/users/getUsers', {},
                    {
                        'query': {
                            'params': params,
                        }
                    }
            );

            return User.query().$promise.then(function (results) {
                return results;
            }, function (error) {
                return error;
            });
        }

        function getAllUsers() {
            var params = {'public_key': publicKey};

            var User = $resource('/portal/internapi/users/getAllUsers', {},
                {
                    'query': {
                        'params': params,
                    }
                }
            );

            return User.query().$promise.then(function (results) {
                return results;
            }, function (error) {
                return error;
            });
        }

        function getUser(id) {
            var params = {'public_key': publicKey};

            var User = $resource('/portal/internapi/users/:id',
                    {id: '@id'},
                    {
                        "get": {
                            'params': params
                        }
                    }
            );

            return User.get(null, {'id': id}).$promise.then(function (result) {
                return result;
            }, function (error) {
                return $q.reject(error);
            });
        }

        function update(data, id) {
            data.public_key = publicKey;

            var queryString = angular.toJson(data);

            var User = $resource('/portal/internapi/users/:id',
                    {id: '@id'},
                    {
                        "update": {
                            method: "PUT",
                        }
                    }
            );

            return User.update({'id': id}, data).$promise.then(function (results) {
                return results;
            }, function (error) {
                return $q.reject(error);
            });
        }

        function register(data) {
            var User = $resource('api/users');

            return User.save(data).$promise.then(function (results) {
                return results;
            }, function (error) {
                return $q.reject(error);
            });
        }

        function disable(id) {
            var User = $resource('api/users/:id/disable',
                    {id: '@id'},
                    {
                        "disable":
                                {
                                    method: "PUT"
                                }
                    }
            );

            return User.disable({'id': id}).$promise.then(function (results) {
                return results;
            }, function (error) {
                return $q.reject(error);
            });
        }

        return {
            getUsers: getUsers,
            getAllUsers: getAllUsers,
            getUser: getUser,
            update: update,
            register: register,
            disable: disable
        }
    }
})();
'use strict';

/* Services */

// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', ['ngResource']).
        factory('Base64', function () {
            var keyStr = 'ABCDEFGHIJKLMNOP' +
                    'QRSTUVWXYZabcdef' +
                    'ghijklmnopqrstuv' +
                    'wxyz0123456789+/' +
                    '=';
            return {
                encode: function (input) {
                    var output = "";
                    var chr1, chr2, chr3 = "";
                    var enc1, enc2, enc3, enc4 = "";
                    var i = 0;

                    do {
                        chr1 = input.charCodeAt(i++);
                        chr2 = input.charCodeAt(i++);
                        chr3 = input.charCodeAt(i++);

                        enc1 = chr1 >> 2;
                        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                        enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                        enc4 = chr3 & 63;

                        if (isNaN(chr2)) {
                            enc3 = enc4 = 64;
                        } else if (isNaN(chr3)) {
                            enc4 = 64;
                        }

                        output = output +
                                keyStr.charAt(enc1) +
                                keyStr.charAt(enc2) +
                                keyStr.charAt(enc3) +
                                keyStr.charAt(enc4);
                        chr1 = chr2 = chr3 = "";
                        enc1 = enc2 = enc3 = enc4 = "";
                    } while (i < input.length);

                    return output;
                },
                decode: function (input) {
                    var output = "";
                    var chr1, chr2, chr3 = "";
                    var enc1, enc2, enc3, enc4 = "";
                    var i = 0;

                    // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
                    var base64test = /[^A-Za-z0-9\+\/\=]/g;
                    if (base64test.exec(input)) {
                        alert("There were invalid base64 characters in the input text.\n" +
                                "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                                "Expect errors in decoding.");
                    }
                    input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                    do {
                        enc1 = keyStr.indexOf(input.charAt(i++));
                        enc2 = keyStr.indexOf(input.charAt(i++));
                        enc3 = keyStr.indexOf(input.charAt(i++));
                        enc4 = keyStr.indexOf(input.charAt(i++));

                        chr1 = (enc1 << 2) | (enc2 >> 4);
                        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                        chr3 = ((enc3 & 3) << 6) | enc4;

                        output = output + String.fromCharCode(chr1);

                        if (enc3 != 64) {
                            output = output + String.fromCharCode(chr2);
                        }
                        if (enc4 != 64) {
                            output = output + String.fromCharCode(chr3);
                        }

                        chr1 = chr2 = chr3 = "";
                        enc1 = enc2 = enc3 = enc4 = "";

                    } while (i < input.length);

                    return output;
                }
            };
        }).
        factory('TokenHandler', ['$http', 'Base64', function ($http, Base64) {
                var tokenHandler = {};
                var token = 'none';

                tokenHandler.set = function (newToken) {
                    token = newToken;
                };

                tokenHandler.get = function () {
                    return token;
                };

                // Generate random string of length
                tokenHandler.randomString = function (length) {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for (var i = 0; i < length; i++) {
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    }
                    return text;
                };

                tokenHandler.getCredentials = function (username, secret) {
                    // Generate nonce
                    var nonce = tokenHandler.randomString(30);

                    // Creation time of the token
                    var created = formatDate(new Date());

                    // Generating digest from secret, creation and nonce
                    var hash = CryptoJS.SHA1(nonce + created + secret);
                    var digest = hash.toString(CryptoJS.enc.Base64);

                    // Base64 Encode digest
                    var b64nonce = Base64.encode(nonce);
                    // alert('UsernameToken Username="'+username+'", PasswordDigest="'+digest+'", Nonce="'+b64nonce+'", Created="'+created+'"');
                    // Return generated token
                    return 'UsernameToken Username="' + username + '", PasswordDigest="' + digest + '", Nonce="' + b64nonce + '", Created="' + created + '"';

                };


                // Token Reinitializer
                tokenHandler.clearCredentials = function () {
                    // Clear token from cache
                    $cookieStore.remove('username');
                    $cookieStore.remove('digest');
                    $cookieStore.remove('nonce');
                    $cookieStore.remove('created');

                    // Clear token variable
                    delete $http.defaults.headers.common['x-wsse'];
                };

                // Token wrapper for resource actions
                tokenHandler.wrapActions = function (resource, actions) {
                    var wrapperResource = resource;

                    for (var i = 0; i < actions.length; i++) {
                        tokenWrapper(wrapperResource, actions[i]);
                    }

                    return wrapperResource;
                };

                // Token wrapper
                var tokenWrapper = function (resource, action) {
                    resource['_' + action] = resource[action];
                    resource[action] = function (data, success, error) {
                        if ((typeof data.username != 'undefined') && (typeof data.secret != 'undefined')) {
                            $http.defaults.headers.common['x-wsse'] = tokenHandler.getCredentials(data.username, data.secret);
                            delete data.username;
                            delete data.secret;
                        }
                        return resource['_' + action](
                                data,
                                success,
                                error
                                );
                    };
                };

                // Date formater to UTC
                var formatDate = function (d) {
                    // Padding for date creation
                    var pad = function (num) {
                        return ("0" + num).slice(-2);
                    };

                    return [d.getUTCFullYear(),
                        pad(d.getUTCMonth() + 1),
                        pad(d.getUTCDate())].join("-") + "T" +
                            [pad(d.getUTCHours()),
                                pad(d.getUTCMinutes()),
                                pad(d.getUTCSeconds())].join(":") + "Z";
                };

                return tokenHandler;
            }]).
        factory('Salt', ['$resource', function ($resource) {
                // Service to load Salt
                return $resource(BaseUrl + '/app/:username/salt', {username: '@id'});
            }]).
        factory('Digest', ['$q', function ($q) {
                var factory = {
                    // Symfony SHA512 encryption provider
                    cipher: function (secret, salt) {
                        var deferred = $q.defer();

                        var salted = secret + '{' + salt + '}';
                        var digest = CryptoJS.SHA512(salted);
                        for (var i = 1; i < 5000; i++) {
                            digest = CryptoJS.SHA512(digest.concat(CryptoJS.enc.Utf8.parse(salted)));
                        }
                        digest = digest.toString(CryptoJS.enc.Base64);

                        deferred.resolve(digest);
                        return deferred.promise;
                    },
                    // Default Symfony plaintext encryption provider
                    plain: function (secret, salt) {
                        var deferred = $q.defer();

                        var salted = secret + '{' + salt + '}';
                        var digest = salted;

                        deferred.resolve(digest);
                        return deferred.promise;
                    }
                };
                return factory;
            }]).
        factory('Hello', ['$resource', 'TokenHandler', function ($resource, tokenHandler) {
                var resource = $resource(BaseUrl + '/api/hello');
                resource = tokenHandler.wrapActions(resource, ['get']);
                return resource;
            }]).
        factory('Todos', ['$resource', 'TokenHandler', function ($resource, tokenHandler) {
                var resource = $resource(BaseUrl + '/api/todos', {}, {
                    query: {method: 'GET', params: {}, isArray: true}
                });
                resource = tokenHandler.wrapActions(resource, ['get', 'query']);
                return resource;
            }]).
        factory('Todo', ['$resource', 'TokenHandler', function ($resource, tokenHandler) {
                var resource = $resource(BaseUrl + '/api/todo', {}, {
                    update: {method: 'PUT'},
                });
                resource = tokenHandler.wrapActions(resource, ['get', 'update']);
                return resource;
            }]).
        factory('getCountPagination', ['$http', '$resource', 'TokenHandler', function ($http, $resource, tokenHandler) {
                return {
                    TableCount: function (url) {
                        var countPosts = $resource(url);
                        countPosts = tokenHandler.wrapActions(countPosts, ['get']);
                        return countPosts;
                    }
                };
            }]).
        //get Array pagination for controller
        factory('paginationCreateArray', function () {
            return {
                array: function (arr, CountItems, constPageItems, current) {
                    arr = new Array();

                    var CountPaginate = Math.floor(CountItems / constPageItems);
                    var CountPaginateRemane = CountItems % constPageItems;

                    if (CountPaginateRemane === 0)
                    {
                        CountPaginate--;
                    }

                    var difference = CountPaginate - current;
                    var low_range = current - 1;
                    var high_range = current + 3;

                    if (CountPaginate < 10)
                    {  // kamtar az 10
                        for (var i = 0; i < CountPaginate; i++) {
                            arr[i] = i + 1;
                        }
                    } else {
                        if (current > 8 && current <= CountPaginate - 8)
                        {
                            // vasat
                            arr[0] = 1;
                            arr[1] = 0;
                            var j = 2;
                            for (var i = current - 2; i < current + 4; i++) {
                                arr[j] = i;
                                j++;
                            }
                            arr[8] = 0;
                            arr[9] = CountPaginate;
                        } else if (current > CountPaginate - 8)
                        {
                            //last
                            arr[0] = 1;
                            arr[1] = 0;
                            var j = 2;
                            for (var i = CountPaginate - 7; i <= CountPaginate; i++) {
                                arr[j] = i;
                                j++;
                            }
                        } else {
                            //first
                            for (var i = 0; i <= 7; i++) {
                                arr[i] = i + 1;
                            }
                            arr[8] = 0;
                            arr[9] = CountPaginate;
                        }
                    }
                    return arr;
                }
            };
        })
        .factory('ConvertShamsiToMiladi', function () {
            return {
                Convert: function (datetimeGet) {
                    return moment(datetimeGet, 'jYYYY/jM/jD HH:mm').format('YYYY-M-D HH:mm:ss');
                }
            }
        })
        .factory('ConvertMiladiToShamsi', function () {
            return {
                Convert: function (datetimeGet) {
                    return moment(datetimeGet, 'YYYY-M-D HH:mm:ss').format('jYYYY/jM/jD HH:mm:ss');
                }
            }
        })
        .factory('Reddit', function ($http) {
            var Reddit = function (itemG) {
                this.items = [];
                this.busy = false;
                this.after = '';
                this.itemG = itemG;
                console.log("itemG : "+itemG);
            };

            Reddit.prototype.nextPage = function () {
                  console.log("NextPage Event");
                if (this.busy)
                    return;
                this.busy = true;
                //posts/{offset}/paginations/{itemGroup}/scroll
                var url = BaseUrl+'/posts/'+this.after+'/paginations/'+this.itemG+'/scroll.json';
                console.log("BaseUrl : " + BaseUrl);
                $http.json(this.url).success(function (data) {
                    var items = data.children;
                    for (var i = 0; i < items.length; i++) {
                        this.items.push(items[i].entities[i]);
                    }
                    console.log("items : " + this.items);
                    this.after = this.items[this.items.length - 1].id;
                    console.log("after : " + this.after);
                    this.busy = false;
                }.bind(this));
            };

            return Reddit;
        })
.value('version', '0.1');

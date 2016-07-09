'use strict';

/* Services */

// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('myApp.services', []).
        factory('paginationCreateArray', function () {
            return {
                array: function (CountPaginate, current) {
                    var arr = new Array();
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
        });

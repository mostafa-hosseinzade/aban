//'use strict';
//var app = angular.module('myApp.services', ['ngCookies']);
//app.service('Paginator', function ($rootScope) {
//    this.page = 0;
//    this.rowsPerPage = 50;
//    this.itemCount = 0;
//    this.limitPerPage = 5;
//
//    this.setPage = function (page) {
//        if (page > this.pageCount()) {
//            return;
//        }
//
//        this.page = page;
//    };
//
//    this.nextPage = function () {
//        if (this.isLastPage()) {
//            return;
//        }
//
//        this.page++;
//    };
//
//    this.perviousPage = function () {
//        if (this.isFirstPage()) {
//            return;
//        }
//
//        this.page--;
//    };
//
//    this.firstPage = function () {
//        this.page = 0;
//    };
//
//    this.lastPage = function () {
//        this.page = this.pageCount() - 1;
//    };
//
//    this.isFirstPage = function () {
//        return this.page === 0;
//    };
//
//    this.isLastPage = function () {
//        return this.page == this.pageCount() - 1;
//    };
//
//    this.pageCount = function () {
//        return Math.ceil(parseInt(this.itemCount) / parseInt(this.rowsPerPage));
//    };
//
//    this.lowerLimit = function () {
//        var pageCountLimitPerPageDiff = this.pageCount() - this.limitPerPage;
//
//        if (pageCountLimitPerPageDiff < 0) {
//            return 0;
//        }
//
//        if (this.page > pageCountLimitPerPageDiff + 1) {
//            return pageCountLimitPerPageDiff;
//        }
//
//        var low = this.page - (Math.ceil(this.limitPerPage / 2) - 1);
//
//        return Math.max(low, 0);
//    };
//})
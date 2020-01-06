/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./public/js/backend.js":
/*!******************************!*\
  !*** ./public/js/backend.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).on('click', '.blog-comments', function (e) {
  e.preventDefault();
  Swal.fire({
    type: 'success',
    title: 'Release soon',
    showConfirmButton: false,
    timer: 1000
  });
});
$(document).on('click', '.blog-view', function (e) {
  e.preventDefault();
  Swal.fire({
    type: 'success',
    title: 'Release soon',
    showConfirmButton: false,
    timer: 1000
  });
});
$(document).on('click', '.blog-delete', function (e) {
  e.preventDefault();
  var slug = $(this).data('slug');
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        url: '/dashboard/blogs/' + slug,
        method: "DELETE",
        data: {
          '_token': $('input[name=_token]').val()
        },
        beforeSend: function beforeSend() {
          Swal.fire({
            title: 'Sending....',
            html: '<span class="text-success">Waiting for data to be sent</span>',
            showConfirmButton: false,
            onBeforeOpen: function onBeforeOpen() {
              Swal.showLoading();
            }
          });
        },
        success: function success(data) {
          Swal.disableLoading();
          Swal.close();

          if (data.error) {
            Swal.fire({
              type: 'error',
              title: 'Oops',
              html: '<span class="text-danger">' + data.error + '</span>',
              showConfirmButton: false,
              timer: 1500
            });
          } else {
            Swal.fire({
              type: 'success',
              title: 'Successfully delete data!',
              html: '<span class="text-success">Your page will be refreshed shortly.</span>',
              showConfirmButton: false
            });
            window.setTimeout(function () {
              location.reload();
            }, 1000);
          }

          ;
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          formatErrorMessage(jqXHR, errorThrown);
        }
      });
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      // Cancel button is pressed
      Swal.fire({
        type: 'info',
        title: 'Your data is safe!',
        showConfirmButton: false,
        timer: 1500
      });
    }

    ;
  });
});

/***/ }),

/***/ "./public/js/frontend.js":
/*!*******************************!*\
  !*** ./public/js/frontend.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

var botmanWidget = {
  title: 'M2M Connector Chat Bot',
  aboutText: 'M2M Connector Website',
  introMessage: "✋ Hi! I'm the awesome automated chat bot."
};
$(document).ready(function () {
  $(".alert-danger").fadeTo(2000, 700).slideUp(700, function () {
    $(".alert-danger").slideUp(700);
  });
  var pageNumber = 2;
  var slug = $('#_slug').val();
  $('.index-load-more').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: "?page=" + pageNumber,
      method: "GET",
      success: function success(data) {
        pageNumber += 1;

        if (data.button) {
          $('.index-load-button').empty();
          $('.index-load-button').append(data.button);
        } else {
          $('.index-load-data').append(data.html);
        }
      },
      error: function error(data) {}
    });
  });
  $('.blog-load-more').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: "blogs?page=" + pageNumber,
      method: "GET",
      success: function success(data) {
        pageNumber += 1;

        if (data.button) {
          $('.blog-button').empty();
          $('.blog-button').append(data.button);
        } else {
          $('.blog-data').append(data.html);
        }
      },
      error: function error(data) {}
    });
  });
  $('.categories-load-more').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: "categories?page=" + pageNumber,
      method: "GET",
      success: function success(data) {
        pageNumber += 1;

        if (data.button) {
          $('.categories-button').empty();
          $('.categories-button').append(data.button);
        } else {
          $('.categories-load-data').append(data.html);
        }
      },
      error: function error(data) {}
    });
  });
  $('.category-load-more').on('click', function (e) {
    e.preventDefault();
    $.ajax({
      url: slug + "?page=" + pageNumber,
      method: "GET",
      beforeSend: function beforeSend() {
        Swal.fire({
          title: 'Requesting....',
          html: '<span class="text-success">Waiting for data to be sent</span>',
          showConfirmButton: false,
          onBeforeOpen: function onBeforeOpen() {
            Swal.showLoading();
          }
        });
      },
      success: function success(data) {
        console.clear();
        Swal.disableLoading();
        Swal.close();
        pageNumber += 1;

        if (data.button) {
          $('.category-button').empty();
          $('.category-button').append(data.button);
        } else {
          $('.category-load-data').append(data.html);
        }
      },
      error: function error(data) {}
    });
  });
});

function sweetAlertError(message) {
  Swal.fire({
    type: 'error',
    title: message,
    showConfirmButton: false,
    timer: 1000
  });
}

;

function formatErrorMessage(jqXHR, exception) {
  if (jqXHR.status === 0) {
    return sweetAlertError('Not connected.\nPlease verify your network connection.');
  } else if (jqXHR.status == 404) {
    return sweetAlertError('The request not found.');
  } else if (jqXHR.status == 401) {
    Swal.fire({
      type: 'error',
      title: 'Sorry!! You session has expired. Please login to continue access.',
      showConfirmButton: false,
      timer: 1500
    });
    return window.setTimeout(function () {
      location.reload();
    }, 1000);
  } else if (jqXHR.status == 500) {
    return sweetAlertError('Internal Server Error.');
  } else if (exception === 'parsererror') {
    return sweetAlertError('Requested JSON parse failed.');
  } else if (exception === 'timeout') {
    return sweetAlertError('Time out error.');
  } else if (exception === 'abort') {
    return sweetAlertError('Ajax request aborted.');
  } else {
    return sweetAlertError('Unknown error occured. Please try again.');
  }

  ;
}

;

/***/ }),

/***/ 1:
/*!************************************************************!*\
  !*** multi ./public/js/frontend.js ./public/js/backend.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/tringuyen/Desktop/RMIT-COURSE/BITS/better-design/public/js/frontend.js */"./public/js/frontend.js");
module.exports = __webpack_require__(/*! /Users/tringuyen/Desktop/RMIT-COURSE/BITS/better-design/public/js/backend.js */"./public/js/backend.js");


/***/ })

/******/ });
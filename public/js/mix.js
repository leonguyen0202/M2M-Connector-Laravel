!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=36)}({36:function(e,t,n){n(37),e.exports=n(38)},37:function(e,t){$(document).ready((function(){$(".alert-danger").fadeTo(2e3,700).slideUp(700,(function(){$(".alert-danger").slideUp(700)}));var e=2,t=$("#_slug").val();$(".index-load-more").on("click",(function(t){t.preventDefault(),$.ajax({url:"?page="+e,method:"GET",success:function(t){e+=1,t.button?($(".index-load-button").empty(),$(".index-load-button").append(t.button)):$(".index-load-data").append(t.html)},error:function(e){}})})),$(".blog-load-more").on("click",(function(t){t.preventDefault(),$.ajax({url:"blogs?page="+e,method:"GET",success:function(t){e+=1,t.button?($(".blog-button").empty(),$(".blog-button").append(t.button)):$(".blog-data").append(t.html)},error:function(e){}})})),$(".categories-load-more").on("click",(function(t){t.preventDefault(),$.ajax({url:"categories?page="+e,method:"GET",success:function(t){e+=1,t.button?($(".categories-button").empty(),$(".categories-button").append(t.button)):$(".categories-load-data").append(t.html)},error:function(e){}})})),$(".category-load-more").on("click",(function(n){n.preventDefault(),$.ajax({url:t+"?page="+e,method:"GET",beforeSend:function(){Swal.fire({title:"Requesting....",html:'<span class="text-success">Waiting for data to be sent</span>',showConfirmButton:!1,onBeforeOpen:function(){Swal.showLoading()}})},success:function(t){console.clear(),Swal.disableLoading(),Swal.close(),e+=1,t.button?($(".category-button").empty(),$(".category-button").append(t.button)):$(".category-load-data").append(t.html)},error:function(e){}})}))}))},38:function(e,t){$(document).ready((function(){})),$(document).on("click",".blog-comments",(function(e){e.preventDefault(),Swal.fire({type:"success",title:"Release soon",showConfirmButton:!1,timer:1e3})})),$(document).on("click",".blog-view",(function(e){e.preventDefault(),Swal.fire({type:"success",title:"Release soon",showConfirmButton:!1,timer:1e3})})),$(document).on("click",".blog-delete",(function(e){e.preventDefault();var t=$(this).data("slug");Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",type:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then((function(e){e.value?$.ajax({url:"/dashboard/blogs/"+t,method:"DELETE",data:{_token:$("input[name=_token]").val()},beforeSend:function(){Swal.fire({title:"Sending....",html:'<span class="text-success">Waiting for data to be sent</span>',showConfirmButton:!1,onBeforeOpen:function(){Swal.showLoading()}})},success:function(e){Swal.disableLoading(),Swal.close(),e.error?Swal.fire({type:"error",title:"Oops",html:'<span class="text-danger">'+e.error+"</span>",showConfirmButton:!1,timer:1500}):(Swal.fire({type:"success",title:"Successfully delete data!",html:'<span class="text-success">Your page will be refreshed shortly.</span>',showConfirmButton:!1}),window.setTimeout((function(){location.reload()}),1e3))},error:function(e,t,n){formatErrorMessage(e,n)}}):e.dismiss===Swal.DismissReason.cancel&&Swal.fire({type:"info",title:"Your data is safe!",showConfirmButton:!1,timer:1500})}))}))}});
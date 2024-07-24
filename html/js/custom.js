$(function () {
  "use strict";

  // Fade out the preloader after a specified delay
  $(".preloader").delay(920).fadeOut('slow'); // Delay for 2000ms (2 seconds)

  // This is for the close icon when navigation opens in mobile view
  $(".nav-toggler").on("click", function () {
    $("#main-wrapper").toggleClass("show-sidebar");
    $(".nav-toggler i").toggleClass("ti-menu");
  });

  // Toggle search box visibility
  $(".search-box a, .search-box .app-search .srh-btn").on("click", function () {
    $(".app-search").toggle(200);
    $(".app-search input").focus();
  });

  // ==============================================================
  // Resize all elements
  // ==============================================================
  $("body, .page-wrapper").trigger("resize");
  $(".page-wrapper").delay(20).show();

  //****************************
  /* This is for the mini-sidebar if width is less than 1170 */
  //****************************
  var setsidebartype = function () {
    var width = window.innerWidth > 0 ? window.innerWidth : this.screen.width;
    if (width < 1170) {
      $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
    } else {
      $("#main-wrapper").attr("data-sidebartype", "full");
    }
  };

  // Set sidebar type on window ready and resize
  $(window).ready(setsidebartype);
  $(window).on("resize", setsidebartype);
});

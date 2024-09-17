(function ($) {
  "use strict";
  $(document).ready(function () {
    /* global astrum */

    /*----------------------------------------------------*/
    /*	Sticky Header
    /*----------------------------------------------------*/

    var stickyheader = astrum.sticky;
    if ($.browser.msie && $.browser.version == 8.0) {
      stickyheader = "disable";
    }
    var searchform = $("#search-form"),
      logo = $("#logo"),
      header = $("#header"),
      menu = $("#header .menu ul > li > a");

    var smallHeightSet = 70,
      durationAnim = 200,
      defaultHeight,
      defSearchformMarginTop,
      defLogoMarginTop,
      defMenuPaddingTop,
      defMenuPaddingBottom = 0,
      small_height;

    defaultHeight = parseInt(header.css("height"));
    defSearchformMarginTop = parseInt(searchform.css("margin-top"));
    defLogoMarginTop = -2;
    defMenuPaddingTop = defaultHeight / 2 - 10;
    defMenuPaddingBottom = defaultHeight / 2 - 10;
    small_height = defaultHeight - smallHeightSet;

    function stickymenu() {
      var offset = $(window).scrollTop(),
        header = $("#header"),
        menuPaddingTop,
        menuPaddingBottom,
        logoMarginTop,
        half_height = small_height / 2;

      menuPaddingTop = defMenuPaddingTop - half_height;
      menuPaddingBottom = defMenuPaddingBottom - half_height;
      logoMarginTop = defLogoMarginTop - half_height - 1;

      if ($(window).width() > astrum.breakpoint) {
        if (offset > 60) {
          $("#blogdesc").fadeOut();
          if (!header.hasClass("compact")) {
            header.animate(
              {
                height: defaultHeight - small_height,
              },
              {
                queue: false,
                duration: durationAnim,
                complete: function () {
                  header.addClass("compact").css("overflow", "visible");
                },
              }
            );
            searchform.animate(
              {
                marginTop: menuPaddingTop - 10,
              },
              {
                queue: false,
                duration: durationAnim,
              }
            );

            logo.animate(
              {
                marginTop: logoMarginTop,
              },
              {
                queue: false,
                duration: durationAnim,
              }
            );
            $(".cart_products").animate(
              {
                top: defaultHeight - small_height,
              },
              {
                queue: false,
                duration: durationAnim,
              }
            );
            $("#astrum_header_cart").animate(
              {
                paddingTop: menuPaddingTop - 10,
              },
              {
                queue: false,
                duration: durationAnim,
              }
            );
            menu.animate(
              {
                paddingTop: menuPaddingTop,
                paddingBottom: menuPaddingBottom,
                margin: 0,
              },
              {
                queue: false,
                duration: durationAnim,
              }
            );
          }
        } else if (offset > -1 && offset < 60) {
          $("#blogdesc").fadeIn();
          header.animate(
            {
              height: defaultHeight,
            },
            {
              queue: false,
              duration: durationAnim,
              complete: function () {
                header.removeClass("compact").css("overflow", "visible");
              },
            }
          );
          searchform.animate(
            {
              marginTop: defMenuPaddingTop - 10,
            },
            {
              queue: false,
              duration: durationAnim,
            }
          );
          logo.stop().animate(
            {
              marginTop: defLogoMarginTop,
            },
            {
              queue: false,
              duration: durationAnim,
            }
          );
          $(".cart_products").animate(
            {
              top: defaultHeight,
            },
            {
              queue: false,
              duration: durationAnim,
            }
          );
          $("#astrum_header_cart").animate(
            {
              paddingTop: defMenuPaddingTop - 10,
            },
            {
              queue: false,
              duration: durationAnim,
            }
          );
          menu.animate(
            {
              paddingTop: defMenuPaddingTop,
              paddingBottom: defMenuPaddingBottom,
            },
            {
              queue: false,
              duration: durationAnim,
            }
          );
        }
      }
    }

    if (stickyheader === "enable") {
      var stickyValue = defaultHeight;
      $(window).scroll(function () {
        stickymenu();
      });

      $(window).resize(function () {
        var winWidth = $(window).width();
        if (winWidth < astrum.breakpoint) {
        } else {
          stickymenu();
        }
      });
    }

    /*----------------------------------------------------*/
    /*	Navigation
    /*----------------------------------------------------*/

    /*$("nav ul").superfish({
      delay: 300, // one second delay on mouseout
      animation: { opacity: "show", height: "show" }, // fade-in and slide-down animation
      speed: 200, // animation speed
      speedOut: 50, // out animation speed
    });*/

    /*----------------------------------------------------*/
    /*	Ajax Portfolio
    /*----------------------------------------------------*/

    function load_pf($id) {
      var pfwrapp = $("#portfolio_ajax"),
        loader = $("#astrum-ajax-loader");
      loader.fadeIn();
      $.ajax({
        url: astrum.ajaxurl,
        type: "POST",
        data: {
          action: "astrum_ajax_portfolio",
          nonce: astrum.nonce,
          post: $id,
        },
        success: function (data) {
          if (data) {
            pfwrapp.slideUp(500, function () {
              $(".added_item").hide();
              $("html, body").animate({ scrollTop: 0 }, "slow");
              pfwrapp.append(data).slideDown(500, function () {
                loader.fadeOut();
                $(".ajaxarrows").fadeIn();
                setTimeout(function () {
                  astrumfn.flexinit();
                }, 500);
              });
            });
          } else {
            loader.fadeOut();
          }
        },
      });
    }

    $(".portfolio-item-ajax").click(function (e) {
      e.preventDefault();
      var pfwrapp = $("#portfolio_ajax"),
        postid = $(this).attr("id").substring(5);
      if ($("#portfolio_ajax #post-" + postid).length > 0) {
        pfwrapp.slideUp(500, function () {
          $(".added_item").hide();
          $("#portfolio_ajax #post-" + postid).show();
          pfwrapp.slideDown().data("current-id", postid);
          pfwrapp.parent().parent().fadeIn();
        });
      } else {
        load_pf(postid);
        pfwrapp.data("current-id", postid);
      }
      $(".ajaxarrows").removeClass("disabled");
    });

    $("#portfolio_ajax_wrapper .close").click(function (e) {
      e.preventDefault();
      var pfwrapp = $("#portfolio_ajax");
      pfwrapp.slideUp(500, function () {
        $(".added_item").hide();
      });
    });

    $("#portfolio_ajax_wrapper .ajaxarrows").click(function (e) {
      e.preventDefault();
      var postid = $("#portfolio_ajax").data("current-id"),
        side,
        ajax_action,
        pfwrapp = $("#portfolio_ajax");

      $(".ajaxarrows").removeClass("active");
      $(this).addClass("active");
      if ($(this).hasClass("rightarrow")) {
        side = ".rightarrow";
      } else {
        side = ".lefttarrow";
      }

      if (postid === 0) {
        if (side === ".rightarrow") {
          $(".rightarrow").fadeIn();
        } else {
          $(".leftarrow").fadeIn();
        }
      } else {
        $(".ajaxarrows").removeClass("disabled");
        if (side === ".rightarrow") {
          ajax_action = "astrum_get_prev_post_id";
        } else {
          ajax_action = "astrum_get_next_post_id";
        }

        $.ajax({
          url: astrum.ajaxurl,
          type: "POST",
          data: {
            action: ajax_action,
            nonce: astrum.nonce,
            post: postid,
          },
          success: function (data) {
            if (data === 0) {
              if (side === ".rightarrow") {
                $(".rightarrow").addClass("disabled");
              } else {
                $(".leftarrow").addClass("disabled");
              }
            } else {
              if ($("#portfolio_ajax #post-" + data).length > 0) {
                pfwrapp.slideUp(500, function () {
                  $(".added_item").hide();
                  pfwrapp.css({ display: "none" });
                  $("#portfolio_ajax #post-" + data).show();
                  pfwrapp.slideDown().data("current-id", data);
                });
              } else {
                load_pf(data);
                pfwrapp.data("current-id", data);
              }
            }
          },
        });
      }
    });

    /*----------------------------------------------------*/
    /*	Mobile Navigation
    /*----------------------------------------------------*/

    var jPanelMenu = $.jPanelMenu({
      menu: "#responsive",
      animated: true,
      keyboardShortcuts: true,
    });
    jPanelMenu.on();

    $(document).on("click", jPanelMenu.menu + " li a", function (e) {
      if (jPanelMenu.isOpen() && $(e.target).attr("href").substring(0, 1) === "#") {
        jPanelMenu.close();
      }
    });

    $(document).on("touchend", ".menu-trigger", function (e) {
      jPanelMenu.triggerMenu();
      e.preventDefault();
      return false;
    });

    $("#jPanelMenu-menu").removeClass("sf-menu");
    $("#jPanelMenu-menu li ul").removeAttr("style");

    // ========================
    // ====== Mobile menu =====
    // ========================
    var header = $("#header"),
      scrollPrev = 0;
    $(window).scroll(function () {
      var scrolled = $(window).scrollTop();
      if (scrolled > 100 && scrolled > scrollPrev) {
        header.addClass("out");
      } else {
        header.removeClass("out");
      }
      scrollPrev = scrolled;
    });
    if (!$(".icons-for-closed").length) {
      $("#jPanelMenu-menu").prepend('<li class="icons-for-closed"><i class="icon-remove"></i></li>');
    }
    $(".icons-for-closed").click(function () {
      jPanelMenu.close(true);
    });

    /*----------------------------------------------------*/
    /*	Mobile Search
    /*----------------------------------------------------*/


// CLEAN ZONE >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function handleToggleSearch() {
  const menuSearch = $("#menu-search");
  if (menuSearch.is(":visible")) {
    $(".menu-trigger,#logo").show();
    menuSearch.hide();
    $(".search-trigger .icon-remove").removeClass("icon-remove").addClass("icon-search");
  } else {
    $(".menu-trigger, #logo").hide();
    menuSearch.show();
    $(".search-trigger .icon-search").removeClass("icon-search").addClass("icon-remove");
    $("#menu-search #s").focus();
  }
}

$(".search-trigger").click(handleToggleSearch);

    // CLEAN ZONE <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

    /*$(".search-trigger").click(function () {
      if ($("#menu-search").is(":visible")) {
        $(".menu-trigger,#logo").show();
        $("#menu-search").hide();
        $(".search-trigger .icon-remove").removeClass("icon-remove").addClass("icon-search");
      } else {
        $(".menu-trigger, #logo").hide();
        $("#menu-search").show();
        $(".search-trigger .icon-search").removeClass("icon-search").addClass("icon-remove");
      }
    });*/

    $(window).resize(function () {
      var winWidth = $(window).width();
      if (winWidth > astrum.breakpoint) {
        jPanelMenu.close();
        $(".menu-trigger, #logo").show();
        $("#menu-search").hide();
        $(".search-trigger .icon-remove").removeClass("icon-remove").addClass("icon-search");
      }
    });

    $("#astrum_header_cart .cart_contents").click(function () {
      var prod = $(".cart_products");
      if (prod.hasClass("visible")) {
        prod.fadeOut().removeClass("visible");
      } else {
        prod.fadeIn().addClass("visible");
      }
    });

    $("body").on("added_to_cart", function () {
      $(".cart_products").fadeIn().addClass("visible");
    });

    /*----------------------------------------------------*/
    /*	ShowBiz Carousel
    /*----------------------------------------------------*/

    function unifyHeight(selector) {
      $(selector).addClass("unify--height");
    }

    function runSentinels(sentinels, callback) {
      sentinels.forEach((sentinel) => {
        const observer = new IntersectionObserver(([{ isIntersecting }]) => {
          if (isIntersecting) {
            callback(sentinel);
          }
        });

        observer.observe(sentinel);
      });
    }

    function isMobile() {
      return $(window).width() < 768;
    }

    function onSentinelTrigger(sentinel, targetSelector = ".pp_testimonials_block .overflowholder") {
      const marginBottom = 25;
      const target = $(targetSelector);
      if (isMobile()) {
        const parentHeight = sentinel.parentElement.getBoundingClientRect().height;
        target.css({ maxHeight: `${parentHeight + marginBottom}px` });
      } else {
        target.css({ maxHeight: `unset` });
      }
    }

    function injectSentinels(selector) {
      const identifier = Math.random().toString(36).slice(2, 7);
      $(selector).each(function () {
        const sentinel = $("<div/>").addClass("sentinel--detector").attr("identifier", identifier);
        $(this).append(sentinel);
      });

      return [...$(`.sentinel--detector[identifier="${identifier}"]`)];
    }

    function isFrontPage() {
      if (typeof __isFrontPage === "undefined") return false;
      return !!__isFrontPage;
    }

    function is_mobile() {
      var agents = [
        "android",
        "webos",
        "iphone",
        "ipad",
        "blackberry",
        "Android",
        "webos",
        "iPod",
        "iPhone",
        "iPad",
        "Blackberry",
        "BlackBerry",
      ];
      var ismobile = false;
      for (var i in agents) {
        if (navigator.userAgent.split(agents[i]).length > 1) {
          ismobile = true;
        }
      }
      return ismobile;
    }

    function initShowBizProSliders() {
      $(".products-thumbs").showbizpro({
        dragAndScroll: "on",
        visibleElementsArray: [3, 3, 3, 3],
        carousel: "on",
        entrySizeOffset: 0,
        allEntryAtOnce: "off",
      });

      $(".our-clients-cont").showbizpro({
        dragAndScroll: "off",
        visibleElementsArray: [5, 4, 3, 1],
        carousel: "off",
        entrySizeOffset: 0,
        allEntryAtOnce: "off",
      });

      setTimeout(() => {
        $(".recent-work").showbizpro({
          dragAndScroll: is_mobile() ? "on" : "off",
          visibleElementsArray: [4, 4, 3, 1],
          carousel: "off",
          entrySizeOffset: 0,
          allEntryAtOnce: "off",
        });

        $(".testimonials_wrap").showbizpro({
          dragAndScroll: "off",
          visibleElementsArray: [1, 1, 1, 1],
          carousel: "off",
          entrySizeOffset: 0,
          allEntryAtOnce: "off",
          closeOtherOverlays: "on",
        });

        $(".happy-clients").showbizpro({
          dragAndScroll: "off",
          visibleElementsArray: [1, 1, 1, 1],
          carousel: "off",
          entrySizeOffset: 0,
          allEntryAtOnce: "off",
        });

        $(".team").showbizpro({
          dragAndScroll: "off",
          visibleElementsArray: [3, 3, 3, 3],
          carousel: "off",
          entrySizeOffset: 0,
          allEntryAtOnce: "off",
        });

        const sentinels = injectSentinels(".pp_testimonials_block .testimonial");
        runSentinels(sentinels, onSentinelTrigger);

        if (isFrontPage()) {
          unifyHeight(".columns.sixteen.pp_portfolio_block");
          unifyHeight(".columns.sixteen.pp_blog_block ");
        }
      }, 0);
    }

    initShowBizProSliders();


    /*----------------------------------------------------*/
    /*	Hover Overlay
    /*----------------------------------------------------*/

    $(".media, li.product").hover(
      function () {
        $(this).find(".hovercover").stop().fadeTo(200, 1);
        $(this).find(".on-hover").stop().fadeTo(200, 1, "easeOutQuad");
        $(this).find(".hovericon").stop().animate({ top: "50%", opacity: 1 }, 250, "easeOutBack");
      },
      function () {
        $(this).find(".hovercover").stop().fadeTo(200, 0);
        $(this).find(".on-hover").stop().fadeTo(200, 0, "easeOutQuad");
        $(this).find(".hovericon").stop().animate({ top: "65%", opacity: 0 }, 150, "easeOutSine");
      }
    );

    /*----------------------------------------------------*/
    /*	Pricing table
    /*----------------------------------------------------*/

    $(".plan-currency").each(function () {
      var width = $(this).width();
      $(this).css({
        marginLeft: -width - 5,
      });
    });

    /*----------------------------------------------------*/
    /*	Tooltips
    /*----------------------------------------------------*/

    $(".tooltip.top").tipTip({
      defaultPosition: "top",
    });

    $(".tooltip.bottom").tipTip({
      defaultPosition: "bottom",
    });

    $(".tooltip.left").tipTip({
      defaultPosition: "left",
    });

    $(".tooltip.right").tipTip({
      defaultPosition: "right",
    });

    /*----------------------------------------------------*/
    /*	Isotope Portfolio Filter
    /*----------------------------------------------------*/

    let memoryPage = 1;

    function updatePagination(selector) {
      const totalPages = Math.ceil($(`#portfolio-wrapper > ${selector}`).length / __transferProjectsPerPage);
      const currentPage = Math.ceil(
        $(`#portfolio-wrapper > ${selector}[data-hidden="none"]`).length / __transferProjectsPerPage
      );
      $(".wp-pagenavi").empty();
      $(".wp-pagenavi").append(`<span class="pages">Page ${currentPage} of ${totalPages}</span>`);

      for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
          $(".wp-pagenavi").append(`<span class="current">${i}</span>`);
        } else {
          $(".wp-pagenavi").append(
            `<a class="page larger" title="Page ${i}" href="/en/portfolio/page/${i}" data-page="${i}">${i}</a>`
          );
        }
      }

      if (currentPage > 1) {
        $(".wp-pagenavi").prepend(
          `<a class="prevpostslink" rel="prev" aria-label="Previous Page" href="/en/portfolio/page/${
            currentPage - 1
          }" data-page="${currentPage - 1}">«</a>`
        );
      }

      if (currentPage < totalPages) {
        $(".wp-pagenavi").append(
          `<a class="nextpostslink" rel="next" aria-label="Next Page" href="/en/portfolio/page/${
            currentPage + 1
          }" data-page="${currentPage + 1}">»</a>`
        );
      }
    }

    function setFilter(selector) {
      $("#portfolio-wrapper").isotope({
        itemSelector: ".portfolio-item",
        layoutMode: "fitRows",
        filter: `${selector}[data-hidden="none"]`,
      });
    }

    function highlightSelectedFilter(selector) {
      $("#filters a.selected").removeClass("selected");
      $("#filters a[data-option-value='" + selector + "']").addClass("selected");
    }

    $(window).ready(function () {
      setFilter("*");
    });

    $("#filters a").click(function (evt) {
      evt.preventDefault();
      window.history.pushState(null, null, "/en/portfolio");
      const selector = $(this).attr("data-option-value");

      $('#portfolio-wrapper > .portfolio-item[data-hidden="full"]').each(function () {
        $(this).attr("data-hidden", "none");
      });

      const buf = $(`#portfolio-wrapper > .portfolio-item${selector}`);

      if (buf.length > __transferProjectsPerPage) {
        [...buf].slice(__transferProjectsPerPage).forEach((el) => {
          $(el).attr("data-hidden", "full");
        });
      }

      setFilter(selector);
      highlightSelectedFilter(selector);
      updatePagination(selector);
    });

    /*----------------------------------------------------*/
    /*	FlexSlider
    /*----------------------------------------------------*/
    var astrumfn = {
      flexinit: function () {
        $(".flexslider").flexslider({
          animation: astrum.flexanimationtype,
          controlNav: false,
          slideshowSpeed: astrum.flexslidespeed,
          animationSpeed: astrum.flexanimspeed,
          smoothHeight: true,
        });
      },
    };
    $(window).load(function () {
      astrumfn.flexinit();
    });

    $(".toggle-container").hide();
    $(".trigger").toggle(
      function () {
        $(this).addClass("active");
      },
      function () {
        $(this).removeClass("active");
      }
    );
    $(".trigger").click(function () {
      $(this).next(".toggle-container").slideToggle();
    });

    $(".trigger.opened").toggle(
      function () {
        $(this).removeClass("active");
      },
      function () {
        $(this).addClass("active");
      }
    );

    $(".trigger.opened").addClass("active").next(".toggle-container").show();

    /*----------------------------------------------------*/
    /*	Magnific Popup
    /*----------------------------------------------------*/

    $(".mfp-gallery").magnificPopup({
      type: "image",
      fixedContentPos: true,
      fixedBgPos: true,
      overflowY: "auto",
      closeBtnInside: true,
      preloader: true,
      removalDelay: 0,
      mainClass: "mfp-fade",
      gallery: { enabled: true },
      callbacks: {
        buildControls: function () {
          this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
        },
      },
    });

    $(".mfp-gallery2").magnificPopup({
      type: "image",
      fixedContentPos: true,
      fixedBgPos: true,
      overflowY: "auto",
      closeBtnInside: true,
      preloader: true,
      removalDelay: 0,
      mainClass: "mfp-fade",
      gallery: { enabled: true },
      callbacks: {
        buildControls: function () {
          this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
        },
      },
    });

    $(".mfp-image").magnificPopup({
      type: "image",
      closeOnContentClick: true,
      mainClass: "mfp-fade",
      image: {
        verticalFit: true,
      },
    });

    $(".mfp-youtube, .mfp-vimeo, .mfp-gmaps").magnificPopup({
      disableOn: 700,
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 0,
      preloader: false,
      fixedContentPos: false,
    });

    function adjustrevoarrows() {
      var leftarrow = $(".tp-bullets .tp-leftarrow"),
        rightarrow = $(".tp-bullets .tp-rightarrow"),
        rev_height = $(".slider").outerHeight(true),
        page_width = $(".container").width(),
        rev_height_parse = parseInt(rev_height);
      var toph = Math.floor((rev_height_parse - 95) / 2);
      rightarrow.css({
        bottom: toph,
        left: page_width / 2 - 59,
      });
      leftarrow.css({
        bottom: toph,
        right: page_width / 2 - 59,
      });
    }
    function hiderevoarrows() {
      var winWidth = $(window).width(),
        leftarrow = $(".tp-bullets .tp-leftarrow"),
        rightarrow = $(".tp-bullets .tp-rightarrow");
      if (winWidth < 768) {
        rightarrow.fadeOut();
        leftarrow.fadeOut();
      } else {
        rightarrow.fadeIn();
        leftarrow.fadeIn();
      }
    }
    setTimeout(function () {
      adjustrevoarrows();
      hiderevoarrows();
    }, 2000);
    $(window).resize(function () {
      adjustrevoarrows();
      hiderevoarrows();
    });

    // Responsive Tables
    $(".responsive-table").stacktable();
    $(".small-only input.input-text.qty.text").on("change", function () {
      var value = $(this).val();
      var name = $(this).attr("name");
      $(".large-only")
        .find(".quantity.buttons_added .qty[name*='" + name + "']")
        .val(value);
    });

    $(".small-only input.update-cart").on("click", function () {
      $(document.body).trigger("updated_cart_totals");
    });

    /*----------------------------------------------------*/
    /*	Skill Bars Animation
    /*----------------------------------------------------*/

    if ($("#skillzz").length !== 0) {
      var skillbar_active = false;
      $(".skill-bar-value").hide();

      if ($(window).scrollTop() === 0 && isScrolledIntoView($("#skillzz")) === true) {
        skillbarActive();
        skillbar_active = true;
      } else if (isScrolledIntoView($("#skillzz")) === true) {
        skillbarActive();
        skillbar_active = true;
      }
      $(window).bind("scroll", function () {
        if (skillbar_active === false && isScrolledIntoView($("#skillzz")) === true) {
          skillbarActive();
          skillbar_active = true;
        }
      });
    }

    function isScrolledIntoView(elem) {
      var docViewTop = $(window).scrollTop();
      var docViewBottom = docViewTop + $(window).height();
      var elemTop = $(elem).offset().top;
      var elemBottom = elemTop + $(elem).height();

      return elemBottom <= docViewBottom + $(elem).height() && elemTop >= docViewTop - $(elem).height();
    }

    function skillbarActive() {
      setTimeout(function () {
        $(".skill-bar-value").each(function () {
          $(this).data("origWidth", $(this)[0].style.width).css("width", "1%").show();
          $(this).animate(
            {
              width: $(this).data("origWidth"),
            },
            1200
          );
        });

        $(".skill-bar-value .dot").each(function () {
          var me = $(this);
          var perc = me.attr("data-percentage");
          var current_perc = 0;
          var progress = setInterval(function () {
            if (current_perc >= perc) {
              clearInterval(progress);
            } else {
              current_perc += 1;
              me.text(current_perc + "%");
            }
          }, 10);
        });
      }, 10);
    }
  });
})(this.jQuery);

jQuery(function ($) {
  $(document).on("wc_update_cart", function () {
    alert("updated");
    $(".plus").val("\uf067");
    $(".minus").val("\uf068");
  });
  $(document).on("click", ".plus, .minus", function () {
    var $qty = $(this).closest(".quantity").find(".qty"),
      currentVal = parseFloat($qty.val()),
      max = parseFloat($qty.attr("max")),
      min = parseFloat($qty.attr("min")),
      step = $qty.attr("step");

    if (!currentVal || currentVal === "" || currentVal === "NaN") currentVal = 0;
    if (max === "" || max === "NaN") max = "";
    if (min === "" || min === "NaN") min = 0;
    if (step === "any" || step === "" || step === undefined || parseFloat(step) === "NaN") step = 1;

    if ($(this).is(".plus")) {
      if (max && (max == currentVal || currentVal > max)) {
        $qty.val(max);
      } else {
        $qty.val(currentVal + parseFloat(step));
      }
    } else {
      if (min && (min == currentVal || currentVal < min)) {
        $qty.val(min);
      } else if (currentVal > 0) {
        $qty.val(currentVal - parseFloat(step));
      }
    }

    $qty.trigger("change");
  });
});

/**
 * stacktable.js
 * Author & copyright (c) 2012: John Polacek
 * Dual MIT & GPL license
 *
 * Page: http://johnpolacek.github.com/stacktable.js
 * Repo: https://github.com/johnpolacek/stacktable.js/
 *
 * jQuery plugin for stacking tables on small screens
 *
 */
(function ($) {
  $.fn.stacktable = function (options) {
    var $tables = this,
      defaults = { id: "stacktable small-only", hideOriginal: true },
      settings = $.extend({}, defaults, options);

    return $tables.each(function () {
      var $stacktable = $('<table class="' + settings.id + '"><tbody></tbody></table>');
      if (typeof settings.myClass !== undefined) $stacktable.addClass(settings.myClass);
      var markup = "";
      $table = $(this);
      $table.addClass("stacktable large-only");
      $topRow = $table.find("tr").eq(0);
      $table.find("tr").each(function (index, value) {
        if (index === 0) {
        } else {
          $(this)
            .find("td,th")
            .each(function (index, value) {
              if (index === 0) {
                markup +=
                  '<tr class="st-space"><td></td><td></td></tr><tr class="st-new-item"><td class="st-key"></td><td class="st-val st-head-row">' +
                  $(this).html() +
                  "</td></tr>";
              } else {
                if ($(this).html() !== "") {
                  markup += "<tr>";
                  if ($topRow.find("td,th").eq(index).html()) {
                    markup += '<td class="st-key">' + $topRow.find("td,th").eq(index).html() + "</td>";
                  } else {
                    markup += '<td class="st-key"></td>';
                  }
                  markup += '<td class="st-val">' + $(this).html() + "</td>";
                  markup += "</tr>";
                }
              }
            });
        }
      });
      $stacktable.append($(markup));
      $table.before($stacktable);
      if (!settings.hideOriginal) $table.show();
    });
  };
})(jQuery);

/* Accordion  */
jQuery(document).ready(function () {
  jQuery(".apq-trigger  a").click(function (e) {
    e.preventDefault();
  });

  jQuery(".apq-trigger").on("click", function () {
    jQuery(this).next(".toggle-container").slideToggle(500);
    jQuery(this).toggleClass("active");
  });

  function areaCoordinate() {
    var wpImg = jQuery("img.wp-image-9160");
    if (typeof wpImg !== "undefined") {
      var x = 7;
      var heightImg = wpImg.height();
      var widthImg = wpImg.width();
      areaArray = jQuery("map").attr("name", "somemap").children();

      if (Array.isArray(areaArray) && areaArray.length > 0) {
        for (var i = 0; i < x; i++) {
          areaArray[i].coords = [0, (i * heightImg) / x, widthImg, ((i + 1) * heightImg) / x];
        }
      }
    }
  }
  areaCoordinate();

  jQuery(window).resize(function () {
    areaCoordinate();
  });

  var newTop;
  if (jQuery(window).width() < 768) {
    newTop = 0;
  } else {
    newTop = 80;
  }

  jQuery(".on-scroll-class").click(function () {
    var hrefId = jQuery(this).attr("href");
    jQuery("html, body").animate({ scrollTop: jQuery(hrefId).offset().top - newTop }, 300);
  });
});

jQuery(document).ready(function () {
  function quadroAreaCoordinate() {
    var wpImg = jQuery(".wp-image-15950");
    if (typeof wpImg.height() !== "undefined") {
      var x = 3;
      var y = 3;
      var heightImg = wpImg.height();
      var widthImg = wpImg.width();
      area = jQuery("map").attr("name", "somemap").children();
      areaArray = [];
      for (var i = 0; i < x; i++) {
        for (var j = 0; j < y; j++) {
          areaArray[j + i * x] = [
            (j * widthImg) / y,
            (i * heightImg) / x,
            ((j + 1) * widthImg) / y,
            ((i + 1) * heightImg) / x,
          ];
        }
      }
      console.log(area);
      jQuery.each(areaArray, function (index, value) {
        area[index].coords = value;
      });
    }
  }
  quadroAreaCoordinate();
  jQuery(window).resize(function () {
    quadroAreaCoordinate();

  });
  unifyPortfolioHeight();
});

/*jQuery(function () {
  jQuery(".search-trigger").on("click", function () {
    jQuery("#menu-search #s").focus();
  });
});*/



function getHeightOfInnerElements(selector) {
  let maxHeight = 0;
  jQuery(selector).each(function () {
    let childrensHeight = 0;

    jQuery(this)
      .children()
      .each(function () {
        childrensHeight += jQuery(this).outerHeight(true);
      });

    if (maxHeight < childrensHeight) {
      maxHeight = childrensHeight;
    }
  });
  return maxHeight;
}

function unifyPortfolioHeight() {
  const maxHeight = getHeightOfInnerElements("#portfolio-wrapper .item-description");
  jQuery("#portfolio-wrapper .item-description").addClass("portfolio--unify");
  jQuery("#portfolio-wrapper .item-description").css({ height: `${maxHeight}px` });
}

window.addEventListener("resize", unifyPortfolioHeight);




/*******Mobile collapse button ******/

	jQuery(document).ready(function(){


		jQuery('.collapse-item-btn').on('click', function(e) {
			var thisLink = window.location.href;
			var dataClose = jQuery(this).attr('data-item-close');			
			if (dataClose == 'close'){
				jQuery(this).children('.collapse-i-btn').addClass('icon-angle-up').removeClass('icon-angle-down');
				jQuery(this).attr('data-item-close', 'open' );				
			} else {
				jQuery(this).children('.collapse-i-btn').addClass('icon-angle-down').removeClass('icon-angle-up');
				jQuery(this).attr('data-item-close', 'close');
				
			}
			jQuery(this).parent('a').next('.sub-menu').slideToggle(500);
			jQuery(this).parent().unbind('click');
			history.pushState('', document.title, thisLink);	

			return false;
		});
		
		
	});

/*******Mobile collapse button end ******/

  


jQuery(window).on('resize', function() {
	const navigator = jQuery('.wp-pagenavi');
    const containerWidth = navigator.width();
	const links = navigator.find('a.page');
    let totalWidth = 0;

    navigator.children().each(function() {
        totalWidth += jQuery(this).outerWidth(true);
    });

    if (totalWidth > containerWidth) {
        links.each(function(index) {
            if (!jQuery(this).hasClass('current')) {
                if (totalWidth > containerWidth) {
                    totalWidth -= jQuery(this).outerWidth(true);
                    jQuery(this).hide();
                }
            }
        });
		const ev = navigator.find('a.page:visible, .extend, .current');
		let prev = null;
		console.log(ev);
		ev.each(function(){
			const el = jQuery(this);
			const currentClass = el.attr('class');
			
			if(prev === currentClass){
				el.hide();
			}
			prev = currentClass;
		})
		
    } else {
        links.show();
		jQuery('.extend').show();
    }
}).resize(); 
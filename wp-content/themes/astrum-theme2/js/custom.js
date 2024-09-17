(function ($) {
  "use strict";
  $(document).ready(function () {
    // -------------------- Slick slider -------------

    const slides = $("div[data-slick-slider]");

    slides.each(function () {
      const slider = $(this);
      const controlsId = slider.attr("data-controls-id");
      const desktopSlidesCount = +slider.attr("data-desktop-count") || 4;
      const tabletSlidesCount = +slider.attr("data-tablet-count") || 3;
      const mobileSlidesCount = +slider.attr("data-mobile-count") || 1;
      const mobileLSlidesCount = +slider.attr("data-mobile-l-count") || 1;
      const isSwipe = slider.attr("data-swipe") !== undefined ? true : false;
      const isAdaptiveHeight = slider.attr("data-adaptive-height") !== undefined ? true : false;
      const isSyntaticMargin = slider.attr("data-syntatic-margin") !== undefined ? true : false;

      if (!controlsId || controlsId.trim() === "") {
        console.warn(`Slider ${slider.attr("class")} doesnt work cuz do not have data-controls-id attr`);
        return;
      }

      const leftControl = $(`div[data-slick-left][data-control-id="${controlsId}"]`);
      const rightControl = $(`div[data-slick-right][data-control-id="${controlsId}"]`);

      if (leftControl.length === 0 || rightControl.length === 0) {
        console.warn(`Slider ${slider.attr("class")} doesnt work cuz do not have one of his control`);
        return;
      }

      if (isSyntaticMargin) {
        function recalc(slides) {
          let maxHeight = 0;

          slides.each(function () {
            const currentSlide = $(this);
            const contentHeight = currentSlide.find(".modern-testimonials-block__testimonial-author-content").height();

            if (maxHeight < contentHeight) {
              maxHeight = contentHeight;
            }
          });

          slides.each(function () {
            const currentSlide = $(this);
            const fullHeight = maxHeight + 22; /* 22px is a gap between  */
            currentSlide.find(".modern-testimonials-block__testimonial-author").css({ height: `${maxHeight}px` });
            currentSlide
              .find(".modern-testimonials-block__testimonial-content")
              .css({ marginBottom: `${fullHeight}px` });
          });
        }

        slider.on("init", function (evt, slick) {
          recalc(slider.find("div[data-slick-slide]"));
        });

        slider.on("breakpoint", () => {
          recalc(slider.find("div[data-slick-slide]"));
        });
      }

      slider.slick({
        dots: false,
        infinite: false,
        adaptiveHeight: isAdaptiveHeight,
        swipe: isSwipe,
        speed: 250,
        slidesToShow: desktopSlidesCount,
        prevArrow: leftControl,
        nextArrow: rightControl,
        responsive: [
          {
            breakpoint: 960,
            settings: {
              slidesToShow: tabletSlidesCount,
              swipe: true,
            },
          },
          {
            breakpoint: 769,
            settings: {
              slidesToShow: mobileSlidesCount,
              swipe: true,
            },
          },
          {
            breakpoint: 425,
            settings: {
              slidesToShow: mobileLSlidesCount,
              swipe: true,
            },
          },
        ],
      });
    });

   
    // ========================
    // ====== Mobile menu =====
    // ========================
    var header = $("#header"),
      scrollPrev = 0;

    $(window).scroll(function () {
      var scrolled = $(window).scrollTop();
      const target = $(".sidebar-toc-wrapper");

      if (scrolled > 100 && scrolled > scrollPrev) {
        header.addClass("out");

        if (window.innerWidth < 1200) {
          if (target.length > 0 && target.is("[data-partical]") && target.is("[data-glued]")) {
            target.attr("data-oversize", "");
          }
          target.attr("data-oversize", "");
        } else {
          target.removeAttr("data-oversize");
        }
      } else {
        target.removeAttr("data-oversize");
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
        $(".search-trigger .menu-icon-remove").removeClass("menu-icon-remove").addClass("icon-search");
      } else {
        $(".menu-trigger, #logo").hide();
        menuSearch.show();
        $(".search-trigger .icon-search").removeClass("icon-search").addClass("menu-icon-remove");
        $("#menu-search #s").focus();
      }
    }

    $(".search-trigger").click(handleToggleSearch);

    // CLEAN ZONE <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

  

/* Accordion  */
var accordionBtn = document.querySelectorAll('.apq-trigger  a');
console.log('sss' + accordionBtn );
//if ( accordionBtn ) {
	accordionBtn.forEach(function(elem) {
		elem.addEventListener('click', function() {
				if ( elem.parentElement.classList.contains('active') ) {
					accordionClosed(elem.parentElement);			
					elem.nextElementSibling.style.height = 0;				
				} else {
					accordionOpen(elem.parentElement);
					elem.nextElementSibling.style.height = elem.nextElementSibling.scrollHeight+'px';
				}
			});
		});
//}


function accordionOpen(elem){
	console.log('open');
	elem.classList.add('active');
	
}

function accordionClosed(elem){
	console.log('closed');
	elem.classList.remove('active');	
}	  
	  
	  
jQuery(document).ready(function () {
  
 /*	jQuery(".apq-trigger  a").click(function (e) {
    e.preventDefault();
  });

  jQuery(".apq-trigger").on("click", function () {
    jQuery(this).next(".toggle-container").slideToggle(500);
    jQuery(this).toggleClass("active");
  });*/

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


jQuery(window)
  .on("resize", function () {
    const navigator = jQuery(".wp-pagenavi");
    const containerWidth = navigator.width();
    const links = navigator.find("a.page");
    let totalWidth = 0;

    navigator.children().each(function () {
      totalWidth += jQuery(this).outerWidth(true);
    });

    if (totalWidth > containerWidth) {
      links.each(function (index) {
        if (!jQuery(this).hasClass("current")) {
          if (totalWidth > containerWidth) {
            totalWidth -= jQuery(this).outerWidth(true);
            jQuery(this).hide();
          }
        }
      });
      const ev = navigator.find("a.page:visible, .extend, .current");
      let prev = null;
      console.log(ev);
      ev.each(function () {
        const el = jQuery(this);
        const currentClass = el.attr("class");

        if (prev === currentClass) {
          el.hide();
        }
        prev = currentClass;
      });
    } else {
      links.show();
      jQuery(".extend").show();
    }
  })
  .resize();

// YOUTUBE PREVIEW

function youtubePreview() {
  let youtubeApi = { loading: false, loaded: false };
  const { loading, loaded } = youtubeApi;

  function onYoutubeApiSuccessLoad() {
    youtubeApi.loaded = true;
    youtubeApi.loading = false;
  }

  function loadScript(url) {
    youtubeApi.loading = true;
    jQuery.ajax({ url: url, dataType: "script", success: onYoutubeApiSuccessLoad, async: true });
  }

  function startVideo(videoID, playerID) {
    if (!YT) return;

    if (YT.loaded === 1) {
      new YT.Player(playerID, {
        playerVars: {
          controls: 1,
          showinfo: 0,
          disablekb: 1,
          rel: 0,
          playsinline: 1,
        },
        videoId: videoID,
        events: { onReady: onPlayerReady },
      });
    } else {
      loadScript("https://www.youtube.com/iframe_api");
    }
  }

  jQuery(".yt-to-play").click(function (e) {
    e.preventDefault();
    const btn = jQuery(this);
    const videoID = btn.data("video");
    const playerID = btn.data("id");
    startVideo(videoID, playerID);
  });

  function onPlayerReady(evt) {
    const video = evt.target.g;
    evt.target.playVideo();
    jQuery(video).parent().find(".yt-to-play").remove();
  }
}

youtubePreview();

// END YOUTUBE PREVIEW

/*----------------------------------------------------*/
/*	NEW MENU
/*----------------------------------------------------*/

function openMenu() {
  let menuTrigger = document.querySelector(".menu-trigger");
  menuTrigger.querySelector(".icon-reorder").classList.remove("jt-icon-visible");
  menuTrigger.querySelector(".menu-icon-remove").classList.add("jt-icon-visible");
  menuTrigger.setAttribute("data-open-menu-mobile", "true");
  document.querySelector("body").classList.add("jt-menu-body-overflow");
  document.querySelector(".jt-mobile-menu-columns #responsive").classList.add("jt-mobile-menu-visible");
  document.querySelector(".jt-menu-background-overlay").classList.add("jt-menu-background-overlay-active");
  document.querySelector("#header").classList.add("jt-menu-fixed");
}

function closedMenu() {
  let menuTrigger = document.querySelector(".menu-trigger");
  menuTrigger.setAttribute("data-open-menu-mobile", "false");
  menuTrigger.querySelector(".icon-reorder").classList.add("jt-icon-visible");
  menuTrigger.querySelector(".menu-icon-remove").classList.remove("jt-icon-visible");
  document.querySelector(".jt-mobile-menu-columns #responsive").classList.remove("jt-mobile-menu-visible");
  document.querySelector(".jt-menu-background-overlay").classList.remove("jt-menu-background-overlay-active");
  document.querySelector("body").classList.remove("jt-menu-body-overflow");
  document.querySelector("#header").classList.remove("jt-menu-fixed");
}

document.querySelector(".menu-trigger").addEventListener("click", function (e) {
  let menuStatus = this.getAttribute("data-open-menu-mobile");
  e.preventDefault();
  if (menuStatus == "false") {
    openMenu();
  } else {
    closedMenu();
  }
});

document.addEventListener("click", function (e) {
  let menuStatus = document.querySelector(".menu-trigger").getAttribute("data-open-menu-mobile");
  const header = document.querySelector("#header");
  const withinBoundaries = e.composedPath().includes(header);

  if (menuStatus == "true") {
    if (!withinBoundaries) {
      closedMenu();
    }
  }
});

jQuery(document).ready(function () {
  jQuery(".collapse-item-btn").on("click", function (e) {
    //e.preventDefault;
    var thisLink = window.location.href;
    //console.log(thisLink);
    var dataClose = jQuery(this).attr("data-item-close");
    if (dataClose == "close") {
      console.log(123222);
      jQuery(this).children(".collapse-i-btn").addClass("icon-angle-up").removeClass("icon-angle-down");
      jQuery(this).attr("data-item-close", "open");
    } else {
      jQuery(this).children(".collapse-i-btn").addClass("icon-angle-down").removeClass("icon-angle-up");
      jQuery(this).attr("data-item-close", "close");
    }
    jQuery(this).parent("a").next(".sub-menu").slideToggle(500);
    jQuery(this).parent().unbind("click");
    history.pushState("", document.title, thisLink);

    return false;
  });
});

/*----------------------------------------------------*/
/*	NEW MENU END
/*----------------------------------------------------*/

/*----------------------------------------------------*/
/*	NEW STICKY MENU
/*----------------------------------------------------*/

function throttle(func, delay) {
  let timerId;
  let lastExecTime = 0;

  return function (...args) {
    const context = this;

    const currentTime = Date.now();

    if (currentTime - lastExecTime < delay) {
      clearTimeout(timerId);
      timerId = setTimeout(function () {
        lastExecTime = currentTime;
        func.apply(context, args);
      }, delay);
    } else {
      lastExecTime = currentTime;

      func.apply(context, args);
    }
  };
}

function scrollMenuJT() {
  let header = document.querySelector("#header");
  let headerContainer = document.querySelector("#header .container");
  if (window.pageYOffset >= 50) {
    header.classList.add("jt-header-scroll");
    headerContainer.classList.add("jt-header-scroll-container");
  } else {
    header.classList.remove("jt-header-scroll");
    headerContainer.classList.remove("jt-header-scroll-container");
  }
}

const handlePageScroll = throttle(scrollMenuJT, 50);

window.addEventListener("scroll", handlePageScroll);

/*----------------------------------------------------*/
/*	NEW STICKY MENU END
/*----------------------------------------------------*/

// Slick slider -----------
const tabletTrigger = 1024;

jQuery(".portfolio-item__hover-control").on("mouseenter", function (evt) {
  if (window.innerWidth > tabletTrigger) {
    const current = jQuery(this);
    current.addClass("portfolio-item--hovered");
  }
});

jQuery(".portfolio-item__hover-control").on("mouseleave", function () {
  if (window.innerWidth > tabletTrigger) {
    const current = jQuery(this);
    current.removeClass("portfolio-item--hovered");
  }
});

jQuery(".portfolio-block__mobile-icon-wrapper").click(function () {
  const current = jQuery(this);
  const control = current.closest(".portfolio-item__hover-control");

  if (control.hasClass("portfolio-item--hovered")) {
    control.removeClass("portfolio-item--hovered");
  } else {
    control.addClass("portfolio-item--hovered");
  }
});

jQuery(document).mouseup(function (e) {
  const hovered = jQuery(".portfolio-item--hovered");
  const control = jQuery(".portfolio-item__hover-control");

  if (control.has(e.target).length === 0) {
    hovered.removeClass("portfolio-item--hovered");
  }
});

// Slick slider End -----------

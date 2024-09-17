"use strict";

// Utils
registerPolicyCookieConsent();

function throttle(callback, delay) {
  let lastCallTime = 0;

  return function (...args) {
    const now = Date.now();

    if (now - lastCallTime >= delay) {
      callback.apply(this, args);
      lastCallTime = now;
    }
  };
}

function getCurrentScrollPosition() {
  return document.documentElement.scrollTop || document.body.scrollTop;
}

function isMobileWidth(width = 968) {
  const currentViewWidth = document.body.clientWidth;
  return currentViewWidth < width;
}

// Swiper Sliders --------------------------

function registerSwiperSlidersModule() {
  const slidersOnPage = [...document.querySelectorAll("[data-slider-container]")];
  slidersOnPage.forEach((slider) => {
    const rawSetup = slider.getAttribute("data-slider-setup");
    const { ...setup } = JSON.parse(rawSetup) || {};
    new Swiper(slider, setup);
  });
}

// Yandex metrika module -------------------

let YANDEXLoaded = !1;
function registerYandexMetrikaModule() {
  !1 === YANDEXLoaded &&
    ((YANDEXLoaded = !0),
    setTimeout(function () {
      var e,
        a,
        r,
        t,
        o = "script";
      (e = window),
        (a = document),
        (e.ym =
          e.ym ||
          function () {
            (e.ym.a = e.ym.a || []).push(arguments);
          }),
        (e.ym.l = +new Date()),
        (r = a.createElement(o)),
        (t = a.getElementsByTagName(o)[0]),
        (r.async = 1),
        (r.src = "https://mc.yandex.ru/metrika/tag.js"),
        t.parentNode.insertBefore(r, t),
        ym(97635475, "init", { clickmap: !0, trackLinks: !0, accurateTrackBounce: !0, webvisor: !0 });
    }, 0));
}

// Youtube module --------------------------

function registerYoutubeModule() {
  let youtubeApi = { loading: false, loaded: false };

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

/* notification module */

function registerNotificationModule() {
  const projects = document.querySelectorAll(".recent-project");
  projects.forEach((project) => {
    const observer = new IntersectionObserver((entries) => handleAttachInfo(entries, project, observer));
    observer.observe(project);
  });
}

function handleAttachInfo(entries, project, observer) {
  if (!entries[0].isIntersecting) return;

  const infoUnits = project.querySelectorAll(
    ".recent-project__slider-info-unit-title[data-title], .recent-project__slider-info-unit-value[data-value]"
  );

  infoUnits.forEach((infoUnit) => {
    if (infoUnit.dataset.title) {
      infoUnit.textContent = infoUnit.dataset.title;
      delete infoUnit.dataset.title;
    } else if (infoUnit.dataset.value) {
      infoUnit.textContent = infoUnit.dataset.value;
      delete infoUnit.dataset.value;
    }
  });

  observer.unobserve(project);
}

// Accordion module --------------------------

function registerAccordionModule() {
  const accordionLinks = document.querySelectorAll(".apq-trigger a, .question__header");
  accordionLinks.forEach((link) => {
    link.addEventListener("click", (evt) => {
      evt.preventDefault();
      if (link.parentElement.classList.contains("active")) {
        accordionClosed(link.parentElement);
      } else {
        accordionOpen(link.parentElement);
      }
    });
  });
}

function accordionOpen(link) {
  link.nextElementSibling.style.height = `${link.nextElementSibling.scrollHeight}px`;
  link.classList.add("active");
}

function accordionClosed(link) {
  link.nextElementSibling.style.height = 0;
  link.classList.remove("active");
}

// Header mobile navigation module --------------

function registerToggleMobileMenuModule() {
  const toggle = document.querySelector(".header__toggle-mobile-menu");

  toggle.addEventListener("click", (evt) => {
    const currentToggle = evt.currentTarget;
    const isOpened = currentToggle.getAttribute("data-opened") === "";

    if (isOpened) {
      closeMobileMenu();
    } else {
      openMobileMenu();
    }
  });

  let timer;
  document.querySelector(".header__search-field").addEventListener("focus", (evt) => {
    if (window.innerWidth > 460) return;
    clearTimeout(timer);
    document.querySelector(".header__contact-us").classList.add("header__contact-us--hidden");
  });

  document.querySelector(".header__search-field").addEventListener("blur", (evt) => {
    if (window.innerWidth > 460) return;
    timer = setTimeout(
      () => document.querySelector(".header__contact-us")?.classList.remove("header__contact-us--hidden"),
      300
    );
  });

  // Need on resize comeback button !

  jQuery(document).ready(function () {
    jQuery(`.header__nav li[data-marked-mobile-state="open"]`).each(function () {
      const current = jQuery(this);
      const arrow = current.find(".menu__item-link:first > .menu__arrow-down");
      arrow.attr("data-mobile-state", "open");
      if (current.find(".menu__sub-menu .menu__sub-menu").length > 0) {
        current.attr("data-mobile-state", "open");
      } else {
        current.attr("data-mobile-state", "open-flooded");
      }
      arrow.parent("a").next(".menu__sub-menu").slideDown(0);
      current.removeAttr("data-marked-mobile-state");
    });
  });

  jQuery(".menu__arrow-down").on("click", function () {
    if (jQuery(window).width() > 1150) return;
    const currentLink = window.location.href;
    const currentState = jQuery(this).attr("data-mobile-state");
    const closestListElement = jQuery(this).parent().parent(); // menu__item-preview || menu__item

    if (currentState == "close") {
      jQuery(this).attr("data-mobile-state", "open");
      if (closestListElement.find(".menu__sub-menu .menu__sub-menu").length > 0) {
        closestListElement.attr("data-mobile-state", "open");
      } else {
        closestListElement.attr("data-mobile-state", "open-flooded");
      }
      jQuery(this).parent("a").next(".menu__sub-menu").slideDown(300);
    } else {
      jQuery(this).attr("data-mobile-state", "close");
      closestListElement.removeAttr("data-mobile-state");
      jQuery(this).parent("a").next(".menu__sub-menu").slideUp(300);
    }

    jQuery(this).parent().unbind("click");
    history.pushState("", document.title, currentLink);

    return false;
  });
}

function closeMobileMenu() {
  const currentToggle = document.querySelector(".header__toggle-mobile-menu");
  const mobileHeader = document.querySelector(".header__nav");
  const overlays = document.querySelectorAll(".mobile-nav-overlay");

  currentToggle.removeAttribute("data-opened");
  mobileHeader.removeAttribute("data-opened");
  document.body.removeAttribute("data-mobile-overlay");

  overlays.forEach((overlay) => {
    overlay.removeAttribute("data-active", "");
    setTimeout(() => overlay.parentNode?.removeChild(overlay), 300);
  });
}

function openMobileMenu() {
  const currentToggle = document.querySelector(".header__toggle-mobile-menu");
  const mobileHeader = document.querySelector(".header__nav");

  currentToggle.setAttribute("data-opened", "");
  mobileHeader.setAttribute("data-opened", "");
  document.body.setAttribute("data-mobile-overlay", "");

  const overlay = document.createElement("div");
  overlay.className = "mobile-nav-overlay";
  document.body.appendChild(overlay);
  overlay.addEventListener("click", closeMobileMenu);
  setTimeout(() => overlay.setAttribute("data-active", ""), 0);
}

// Pagionation ------------
// jQuery(window)
//   .on("resize", function () {
//     const navigator = jQuery(".wp-pagenavi");
//     const containerWidth = navigator.width();
//     const links = navigator.find("a.page");
//     let totalWidth = 0;

//     navigator.children().each(function () {
//       totalWidth += jQuery(this).outerWidth(true);
//     });

//     if (totalWidth > containerWidth) {
//       links.each(function (index) {
//         if (!jQuery(this).hasClass("current")) {
//           if (totalWidth > containerWidth) {
//             totalWidth -= jQuery(this).outerWidth(true);
//             jQuery(this).hide();
//           }
//         }
//       });
//       const ev = navigator.find("a.page:visible, .extend, .current");
//       let prev = null;

//       ev.each(function () {
//         const el = jQuery(this);
//         const currentClass = el.attr("class");

//         if (prev === currentClass) {
//           el.hide();
//         }
//         prev = currentClass;
//       });
//     } else {
//       links.show();
//       jQuery(".extend").show();
//     }
//   })
//   .resize();

// Scroll module ----------------------

const moduleStore = {
  isModuleWorking: false,
  elements: {
    sections: jQuery(".headline, .form-headline"),
    nav: jQuery(".lwptoc_item"),
    header: jQuery(".header"),
  },
  setup: {
    onLoadScrollDuration: 50,
    scrollDuration: 800,
    closeDuration: 300,
    scrollOffset: 32,
    desktopHeaderHeight: 86,
    mobileHeaderHeight: 64,
  },
};

function smoothScrollOnLoad() {
  if (getCurrentScrollPosition() <= 125) {
    jQuery(".lwptoc_i .lwptoc_item a").eq(0).children("span").addClass("lwptoc_active");
  }
  window.addEventListener("load", () => scrollToChapterOnLoad(window.location.hash));
}

function handleScrollIntoChapter() {
  const id = jQuery(this).attr("href");
  if (!id.includes("#")) return;

  const [chapter] = jQuery(id);
  let isZip = false;
  if (isMobileWidth(968)) {
    isZip = true;
    scrollIntoChapter(chapter, calculateOffsetDependOnWidth());

    const lwptoc = jQuery(".lwptoc");
    if (lwptoc.length > 0) {
      if (lwptoc.hasClass("lwptoc-mobile--opened")) {
        lwptoc.addClass("lwptoc-mobile--transitionend").removeClass("lwptoc-mobile--opened");
        setTimeout(() => {
          lwptoc.removeClass("lwptoc-mobile--transitionend");
          jQuery(".lwptoc_toggle_label").find(".toc-open-icon")?.removeClass("toc-open-icon--size-m");
        }, 300); // Inherits logic from toc bundle
      }

      const transitionZipClass = isZip ? "toc-open-icon--size-m" : "";
      jQuery(".lwptoc_toggle_label")?.html(
        `<div class="toc-open-icon ${transitionZipClass}" style="width: 35px; height: 40px;"></div>`
      );
    }
  } else {
    scrollIntoChapter(chapter, calculateOffsetDependOnWidth());
  }

  history.pushState(null, null, id);
  return false;
}

function calculateSections() {
  const buffer = [];
  moduleStore.elements.sections.each(function () {
    const offsetFromTop =
      jQuery(this).offset().top - moduleStore.elements.header.height() - moduleStore.setup.scrollOffset - 8;
    const anchor = jQuery(this).children("span").attr("id");
    buffer.push({ anchor, offsetFromTop });
  });

  if (buffer.length === 0) return buffer;

  const sortedElements = buffer.sort((c, l) => l.offsetFromTop - c.offsetFromTop);
  sortedElements[sortedElements.length - 1].offsetFromTop = 0;

  return sortedElements;
}

function handleScroll() {
  const chapters = calculateSections();
  const currentPosition = jQuery(document).scrollTop();
  for (const chapter of chapters) {
    if (currentPosition >= chapter.offsetFromTop) {
      moduleStore.elements.nav.find("a").children("span").removeClass("lwptoc_active");
      moduleStore.elements.nav.find(`a[href="#${chapter.anchor}"]`).children("span").addClass("lwptoc_active");
      break;
    }
  }
}

function calculateOffsetDependOnWidth(currentPosition = 0, targetPosition = 0) {
  const isTablet = isMobileWidth(768);

  if (isTablet) {
    return moduleStore.setup.mobileHeaderHeight + moduleStore.setup.scrollOffset;
  } else {
    return moduleStore.setup.desktopHeaderHeight + moduleStore.setup.scrollOffset;
  }
}

function scrollToChapterOnLoad(hash) {
  const chapter = hash ? document.querySelector(hash) : null;
  if (!chapter) return;

  smoothScrollTo(
    chapter,
    moduleStore.setup.onLoadScrollDuration,
    calculateOffsetDependOnWidth(getCurrentScrollPosition(), chapter.getBoundingClientRect().top)
  );
}

function smoothScrollTo(element, duration, offset = 0, easing = "easeInOutQuad") {
  const easeFunctions = {
    linear: (t) => t,
    easeInQuad: (t) => t * t,
    easeOutQuad: (t) => t * (2 - t),
    easeInOutQuad: (t) => (t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t),
  };

  const easeFunction = easeFunctions[easing] || easeFunctions.easeInOutQuad;
  let targetPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
  let startPosition = window.pageYOffset;
  let distance = targetPosition - startPosition;
  let startTime = null;

  function scrollAnimation(currentTime) {
    if (startTime === null) startTime = currentTime;
    const elapsedTime = currentTime - startTime;
    const scrollProgress = Math.min(elapsedTime / duration, 1);
    const easeValue = easeFunction(scrollProgress);
    targetPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
    distance = targetPosition - startPosition;
    const currentScrollPosition = startPosition + distance * easeValue;
    window.scrollTo(0, currentScrollPosition);
    if (scrollProgress < 1) {
      window.requestAnimationFrame(scrollAnimation);
    } else {
      targetPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
      startPosition = window.pageYOffset;
      distance = targetPosition - startPosition;
      startTime = null;
    }
  }

  window.requestAnimationFrame(scrollAnimation);
}

function scrollIntoChapter(chapter, offset) {
  const targetPosition = chapter.getBoundingClientRect().top + window.pageYOffset - offset;
  const startPosition = window.pageYOffset;
  const distance = targetPosition - startPosition;

  const localDuration = Math.abs(distance) < 500 ? moduleStore.setup.closeDuration : moduleStore.setup.scrollDuration;
  smoothScrollTo(chapter, localDuration, offset);
}

function registerScrollModule() {
  moduleStore.isModuleWorking = true;
  calculateSections();
  smoothScrollOnLoad();
  jQuery(".lwptoc_itemWrap").on("click", "a", handleScrollIntoChapter);
  jQuery(document).on("scroll", throttle(handleScroll, 10));
}

// Policy cookie consent --------------------------

function registerPolicyCookieConsent() {
  const cookieConsentDuration = 31536000;
  const cookieAgreementDuration = 31536000;
  const COOKIE_AGREE = "privacy-policy-cookie-consent";
  const COOKIE_ACCEPT_ALL = "Cookies consent to all at once";

  function set(name, value, maxAge, useSameLax = false) {
    const sameLaxAttr = useSameLax ? "; samesite=lax" : "";
    const maxAgeAttr = maxAge ? `;max-age = ${maxAge}` : "";
    document.cookie = `${name}=${value}; secure${sameLaxAttr}${maxAgeAttr}; path=/`;
    return true;
  }

  function get(value) {
    const cookies = document.cookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      if (cookie.indexOf(`${value}=`) === 0) {
        return cookie.substring(value.length + 1);
      }
    }
    return null;
  }

  function showCookieBanner() {
    const cookieElement = document.getElementById("cookie-agreement-banner");
    const userAgent = navigator.userAgent.toLowerCase();

    // 	Inherit from jivosite
    if (/macintosh|iPod|iPhone|iPad|iPod|Android|Windows Phone/i.test(userAgent)) {
      document.body.setAttribute("data-cookie-agreement-banner-compact", "");
    }

    cookieElement.classList.remove("cookie-agreement-banner--hidden");
  }

  function hideCookieBanner(smooth = false) {
    const cookieElement = document.getElementById("cookie-agreement-banner");
    if (smooth) {
      cookieElement.classList.add("cookie-agreement-banner--confirm-smooth");
      setTimeout(() => cookieElement.classList.add("cookie-agreement-banner--hidden"), 150);
    } else {
      cookieElement.classList.add("cookie-agreement-banner--hidden");
    }
  }

  function acceptCookie() {
    set(COOKIE_AGREE, "true", cookieAgreementDuration, true);
    set(COOKIE_ACCEPT_ALL, "true", cookieConsentDuration, false);
    const event = new CustomEvent("cookie-all-accepted");
    window.dispatchEvent(event);
    hideCookieBanner(true);
  }

  const element = document.getElementById("confirm-cookie-button");
  if (!element) return;

  element.addEventListener("click", acceptCookie);
  const isCookieSet = get(COOKIE_AGREE);
  if (!isCookieSet) showCookieBanner();
}

// Toggle nav inner menu --------------------------

function registerToggleNavInnerMenu() {
  const dropdownMenus = [...document.querySelectorAll(".menu__item--dropdown")];
  dropdownMenus.forEach((dropdownItem) => {
    dropdownItem.addEventListener("mouseenter", handleOpenInnerMenu);
    dropdownItem.addEventListener("mouseleave", handleClearInnerMenu);
    dropdownItem.addEventListener("touchend", handleOpenInnerMenuForTouchDevices);
  });

  document.addEventListener("touchstart", handleCloseInnerMenuForTouchDevices);

  window.addEventListener("resize", () => {
    const hoveredSubMenuItems = document.querySelectorAll(".menu__sub-menu--hover");

    if (hoveredSubMenuItems.length > 0) {
      [...hoveredSubMenuItems].forEach((hoveredItem) => {
        clearHoverInnerMenu(hoveredItem);
      });
    }
  });
}

function handleCloseInnerMenuForTouchDevices(evt) {
  if (!evt.target.closest(".menu__item--dropdown")) {
    const hoveredSubMenuItems = document.querySelectorAll(".menu__sub-menu--hover");

    hoveredSubMenuItems.forEach((hoveredItem) => clearHoverInnerMenu(hoveredItem));
  }
}

function handleOpenInnerMenuForTouchDevices(evt) {
  if (!evt.currentTarget.matches(":hover")) return;
  const siblings = [...evt.currentTarget.parentElement.children].filter((sibling) => sibling !== evt.currentTarget);

  siblings.forEach((sibling) => {
    const subMenu = sibling.querySelector(".menu__sub-menu");

    if (subMenu) {
      subMenu.classList.remove("menu__sub-menu--hover");
    }
  });

  handleClearInnerMenu(evt);
  handleOpenInnerMenu(evt);
}

function handleOpenInnerMenu(evt) {
  if (window.innerWidth <= 1150) return;
  handleClearInnerMenu(evt);

  const innerMenu = evt.currentTarget.querySelector(".menu__sub-menu");
  const bottomOffset = 5;

  if (innerMenu) {
    innerMenu.classList.add("menu__sub-menu-calculate-pretend");

    const rect = innerMenu.getBoundingClientRect();

    const viewportHeight = window.innerHeight;
    const spaceBottom = viewportHeight - rect.bottom;
    const innerMenuOffset = spaceBottom - bottomOffset;
    if (innerMenuOffset < 0) {
      innerMenu.style.transform = `translateY(${innerMenuOffset}px)`;
    } else {
      innerMenu.style.transform = "";
    }

    innerMenu.classList.add("menu__sub-menu--hover");
    innerMenu.classList.remove("menu__sub-menu-calculate-pretend");
  }
}

function handleClearInnerMenu(evt) {
  if (window.innerWidth <= 1150) return;

  const innerMenu = evt.currentTarget.querySelector(".menu__sub-menu");
  clearHoverInnerMenu(innerMenu);
  innerMenu.style.transform = "";
}

function clearHoverInnerMenu(innerMenu) {
  if (!innerMenu) return;
  innerMenu.classList.remove("menu__sub-menu--hover");
}
// --------------

// Initialize modules
function initializeModules() {
  registerToggleNavInnerMenu();
  registerSwiperSlidersModule();
  registerNotificationModule();
  registerAccordionModule();
  registerYoutubeModule();
  registerToggleMobileMenuModule();
  registerScrollModule();

  document.referrer &&
    /^https?:\/\/([^\/]+\.)?(webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//.test(document.referrer) &&
    registerYandexMetrikaModule(),
    window.addEventListener("scroll", registerYandexMetrikaModule, { passive: !0 }),
    window.addEventListener("mousemove", registerYandexMetrikaModule);
}

initializeModules();

// LEGACY ZONE ---------------------------------------

let areaArray = [];
let area;

function quadroAreaCoordinate() {
  var wpImg = jQuery(".wp-image-15950");
  if (typeof wpImg.height() !== "undefined") {
    var x = 3;
    var y = 3;
    var heightImg = wpImg.height();
    var widthImg = wpImg.width();
    area = jQuery("map").attr("name", "somemap").children();
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

jQuery(document).ready(function () {
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

const elements = [...document.querySelectorAll("[data-bctip]")];

for (const el of elements) {
  const tip = document.createElement("div");
  tip.classList.add("tooltip");
  tip.textContent = el.getAttribute("data-bctip");
  const x = el.hasAttribute("tip-left") ? "calc(-100% - 5px)" : "16px";
  const y = el.hasAttribute("tip-top") ? "-100%" : "0";
  tip.style.transform = `translate(${x}, ${y})`;
  el.appendChild(tip);

  el.onpointermove = (e) => {
    const rect = tip.getBoundingClientRect();
    const rectWidth = rect.width + 16;
    const vWidth = window.innerWidth - rectWidth;
    const rectX = el.hasAttribute("tip-left") ? e.clientX - rectWidth : e.clientX + rectWidth;
    const minX = el.hasAttribute("tip-left") ? 0 : rectX;
    const maxX = el.hasAttribute("tip-left") ? vWidth : window.innerWidth;
    const x = rectX < minX ? rectWidth : rectX > maxX ? vWidth : e.clientX;
    tip.style.left = `${x}px`;
    tip.style.top = `${e.clientY}px`;
  };
}

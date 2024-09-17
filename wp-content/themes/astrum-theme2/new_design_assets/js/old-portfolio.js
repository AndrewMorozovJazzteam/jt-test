jQuery(document).ready(function ($) {
  function getHeightOfInnerElements(selector) {
    let maxHeight = 0;
    $(selector).each(function () {
      let childrensHeight = 0;

      $(this)
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
    $("#portfolio-wrapper .item-description").addClass("portfolio--unify");
    $("#portfolio-wrapper .item-description").css({ height: `${maxHeight}px` });
  }

  window.addEventListener("resize", unifyPortfolioHeight);
  unifyPortfolioHeight();



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
            `<a class="page larger" title="Page ${i}" href="/old-portfolio/page/${i}" data-page="${i}">${i}</a>`
          );
        }
      }

      if (currentPage > 1) {
        $(".wp-pagenavi").prepend(
          `<a class="prevpostslink" rel="prev" aria-label="Previous Page" href="/old-portfolio/page/${
            currentPage - 1
          }" data-page="${currentPage - 1}">«</a>`
        );
      }

      if (currentPage < totalPages) {
        $(".wp-pagenavi").append(
          `<a class="nextpostslink" rel="next" aria-label="Next Page" href="/old-portfolio/page/${
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
      window.history.pushState(null, null, "/old-portfolio");
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


});

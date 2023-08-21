jQuery(function ($) {
  // (function handleSliders() {
  //   const container = $(".list-carousel-container");
  //   container.map((i, el) => {
  //     const carousel = $(el).find(".list-carousel");
  //     const initialValue = $(el).find("#initial-value").val();
  //     console.log("initial ", initialValue);
  //     carousel.slick({
  //       // nextArrow: '<i class="fas fa-chevron-right slick-next"></i>',
  //       // prevArrow: '<i class="fab fa-facebook-f slick-prev">previous</i>',
  //       // prevArrow: '<button type="button" class="slick-prev">Previous</button>',
  //       prevArrow: '<i class="fas fa-arrow-circle-left slick-prev"></i>',
  //       autoplay: false,
  //       arrows: true,
  //       dots: false,
  //       slidesToShow: 3,
  //       centerMode: true,
  //       infinite: true,
  //       centerPadding: "0",
  //       lazyLoad: "ondemand",
  //       responsive: [
  //         {
  //           breakpoint: 768,
  //           settings: {
  //             slidesToShow: 1,
  //             slidesToScroll: 1,
  //             centerPadding: "80px",
  //           },
  //         },
  //       ],
  //     });
  //   });
  // })();
});

// For Ken Burns sliders
// const $slider = $(".sliders");
// const $slides = $(".sliders li");
// $(".sliders").on("init", function (slick) {
//   console.log("init");
//   window.setTimeout($slider.find("li").first().addClass("animate"), 2000);
// });
// $(".sliders").slick({
//   autoplay: true,
//   autoplaySpeed: 5000,
//   fade: true,
//   dots: true,
//   arrows: false,
// });
// $(".sliders").on(
//   "beforeChange",
//   function (event, slick, currentSlide, nextSlide) {
//     //   currentSlide.addClass("animate");
//     console.log("before change");
//     $slider
//       .find(`[data-slick-index="${currentSlide}"]`)
//       .addClass("animate");
//   }
// );
// $(".sliders").on("afterChange", function (event, slick, currentSlide) {
//   //   currentSlide.addClass("animate");
//   console.log("after change");
//   $slider
//     .find(`[data-slick-index="${currentSlide - 1}"]`)
//     .removeClass("animate");
// });

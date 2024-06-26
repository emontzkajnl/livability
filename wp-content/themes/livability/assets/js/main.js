(function ($) {
  $(window).load(function(){
    $('.livability-image-meta').show();
  });

  const bp24ScrollFixx = () => {
    const headHeight = $('.entry-header').height();
    const articleHeight = $('.bp24lparticle').height();
    const totalHeight = headHeight + articleHeight + 200;

    window.scroll({
      top: totalHeight,
      left: 0,
      behavior: "smooth",
    });
  }

  // desktop only
  $('.bp2l__tab-nav li').on('click', function(e){
    // console.log(e.target);
    const that = $(e.target);
    const items = $('.bp2l__tab-nav li');
    const tabID = that.data('tab');
    const tabs = $('.tab-content');
    const tabTarget = tabs.filter('#'+tabID);
    items.removeClass('active');
    that.addClass('active');
    tabs.hide();
    tabTarget.show();
  });

  // handheld only
  $('.bp2l__mobile-tab').on('click', function(e){
    const that = $(e.target).parent();
    const items = $('.bp2l__mobile-tab');
    const tabID = that.data('tab');
    const tabs = $('.tab-content');
    const tabTarget = tabs.filter('#'+tabID);
    items.removeClass('active');
    that.addClass('active');
    tabs.hide();
    tabTarget.show();
    $('html, body').animate({
      scrollTop: $(that).offset().top-100
    }, 500);
  });

  const searchPopupBtn = document.querySelector(".mobile-search-icon");
  const searchPopup = document.querySelector(".search-pop-up");
  const searchClose = document.querySelector(".close-search");

  
  searchPopupBtn.addEventListener("click", () => {
    // searchPopup.style.display = 'flex';
    searchPopup.classList.add('search-open');
    document.querySelector('.search-field').focus();
  });

  searchClose.addEventListener("click", () => {
    searchPopup.classList.remove('search-open');
  });

  // const top100_24btn = document.querySelectorAll('.open-top-100-24-map');
  // const top100_24closebtn = document.querySelector('.map-popup-close');
  // const top100_24_popup = document.querySelector('.top-100-popup-container');

  // if (top100_24btn.length && top100_24_popup.length && top100_24closebtn.length) {
  //   top100_24btn.forEach(btn => {
  //     btn.addEventListener("click", () => {
  //       top100_24_popup.classList.add('open-top-100-popup');
  //     });
  //   });
  // }   
  
  //   top100_24closebtn.addEventListener("click", () => {
  //     top100_24_popup.classList.remove('open-top-100-popup');
  //   });

  const top10024popup = $('.top-100-popup-container');
  $('.open-top-100-24-map').on('click', function(){
    console.log('clicksfsdf');
    top10024popup.addClass('open-top-100-popup');
  });
  $('.map-popup-close').on('click', function(){
    top10024popup.removeClass('open-top-100-popup');
  });

  $('.bp24__filters-container legend').on('click', function(e) {
    $(this).parent().toggleClass('open');
  });

  $('.text-shortener__button').on('click', function(){
    $('.text-shortener').removeClass('text-shortener');
    $('.text-shortener__container').remove();
  });





  // $('.mega-menu-wrap').on('click', function(e) {
  //   // console.log('event is ',e.target); //mega-toggle-on
  //   const theTarget = $(e.target).parent('.mega-menu-item');
  //   console.log('parent is ',theTarget.length);
  //   if (theTarget.length && theTarget.hasClass('mega-toggle-on')) {
  //     e.preventDefault();
  //    console.log(theTarget);
  //   //  theTarget.removeClass('mega-toggle-on');

  //   }
  // });

  // $('.mega-menu-link').on('click', function(e) {
  //   e.preventDefault();
  //   console.log('clicked');
  // });





  // const bp2lTabs = document.querySelectorAll('.bp2l__tab-nav li');
  // console.log(bp2lTabs);
  // bp2lTabs.forEach(tab => {tab.addEventListener('click', bptTab))});
  // bp2lTabs.addEventListener('click', 'bptlTab');
  // Array.from(bp2lTabs).forEach(tab => {
  //   tab.addEventListener('click', bptlTab(event));
  // })
  // function bptlTab(event) {
  //   console.log('bptlTab');
  //   const tabcontent = document.getElementsByClassName("tab-content");
  //   for (let i = 0; i < tabcontent.length; i++) {
  //       tabcontent[i].style.display = "none";
  //   }
  //   const tablinks = document.querySelectorAll('.bp2l__tab-nav li');
  //   for (let i = 0; i < tablinks.length; i++) {
  //       tablinks[i].className = tablinks[i].className.replace(" active", "");
  //   }
  //   document.getElementById(tab).style.display = "block";
  //   event.currentTarget.className += " active";
  // }
  const siteHeader = document.querySelector(".site-header");
  if (siteHeader) {
    const headroom = new Headroom(siteHeader);
    headroom.init();
  }

  $(".list-carousel").slick({
    prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
    nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
    autoplay: false,
    arrows: true,
    dots: false,
    slidesToShow: 3,
    centerMode: true,
    infinite: true,
    centerPadding: "0",
    // lazyLoad: "ondemand",
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          centerPadding: "80px",
        },
      },
    ],
  });
  // });
  // })();

  const initPwlSlick = function(){
    $(".pwl-slick").slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: true,
      prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
       nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          },
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          },
        },
      ],
    });
  }
  initPwlSlick();

  // const placeBrandStories = function() {
  //   $(".pwl-slick-place-brand-stories").slick({
  //     infinite: true,
  //     slidesToShow: 3,
  //     slidesToScroll: 1,
  //     arrows: true,
  //     prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
  //      nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
  //     responsive: [
  //       {
  //         breakpoint: 1200,
  //         settings: {
  //           slidesToShow: 2,
  //           slidesToScroll: 1
  //         },
  //       },
  //       {
  //         breakpoint: 962,
  //         settings: {
  //           slidesToShow: 1,
  //           slidesToScroll: 1
  //         },
  //       },
  //     ],
  //   });
  // }
  // placeBrandStories();

  $(".place-topics-2__button").on("click", function(){
    const button = $(this);
    const catObj = button.data("cat");
    const parent = button.parent();
    const container = button.parent().find('.place-topics-2__container');
    const catData = window[catObj];
    let currentPage = catData['current_page'];
    const maxPages = catData['max_pages'];
    
    const posts = JSON.parse(catData.posts);
    const nextPosts = posts.slice(currentPage*3, currentPage*3+3);
    const data = {
      action: "placeTopics2",
      posts: nextPosts,
    };
    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function (data) {
        console.log(data);
        container.append(data);
        if (currentPage == maxPages-1) {
          button.remove();
        } else {
          window[catObj].current_page++;
        }
      }
      
    });
  });


  $(".load-places").on("click", function () {
    const button = $(this);
    const blockId = button.data("topic-block");
    const data = {
      action: "loadmore",
      // query: window[blockId].posts,
      page: window[blockId].current_page,
      relationshipID: window[blockId].relationship_id,
      category: window[blockId].category,
    };
    let href = location.href;
    href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
    const pageView = href+'/'+data['category']+'/'+data['page'];
    if (typeof ga === "function") { 
      ga('send','pageview', pageView);
    }
    if (typeof PARSELY !== 'undefined') {
    PARSELY.beacon.trackPageView({
      url: pageView,
      urlref: href,
      js: 1
    });
    }
    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function (data) {
        if (data) {
          // console.log("data callback");
          // console.dir(data);
          window[blockId].current_page++;
          // button.before(data);
          button.prev("ul").append(data);
          if (window[blockId].current_page == window[blockId].max_page) {
            // console.log("no more posts");
            button.remove();
          }
        } else {
           button.remove();
        }
      },
    });
  });

  $(".load-masonry").on("click", function () {
    const button = $(this);
    const blockId = button.data("topic-block");
    const data = {
      action: "loadmasonry",
      // query: window[blockId].posts,
      current_page: window[blockId].current_page,
      categoryId: window[blockId].categoryId,
      offset: window[blockId].offset,
    };
    // console.log("page is ", window[blockId].current_page);
    // console.log('href is ',location.href);
    let href = location.href;
    href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
    const newPage = href+'/'+data['current_page'];
    
    if (typeof ga === "function") { 
      ga('send','pageview', newPage);
    }
    if (typeof PARSELY !== 'undefined') {
    PARSELY.beacon.trackPageView({
      url: newPage,
      urlref: href,
      js: 1
    });
    }
   

    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function (data) {
        if (data) {
          // console.log(data);
          window[blockId].current_page++;
          button.prev(".masonry-container").append(data);
        } else {
          button.remove();
        }
      },
    });
  });

  $(".load-bpm").on("click", function () {
    const button = $(this);
    const blockId = button.data("bpm-block");
    const data = {
      action: "loadbpm",
      // query: window[blockId].posts,
      current_page: window[blockId].current_page,
    };
    // console.log("data is ", data);
    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function (data) {
        if (data) {
          // console.log(data);
          window[blockId].current_page++;
          button.prev(".masonry-container").append(data);
        } else {
          button.remove();
        }
      },
    });
  });

  const ajaxOneHundred = () => {
    var obj = window.ohlObj;
    const data = {
      action: "loadonehundred",
      current_page: obj.current_page,
      // children: obj.children,
      parent: obj.parent
    };
    // console.dir(data);
    let href = location.href;
    href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
    const newPage = href+'/'+data['current_page'];
    if (typeof ga === "function") { 
      ga('send','pageview', newPage);
    }
    if (typeof PARSELY !== 'undefined') {
      PARSELY.beacon.trackPageView({
        url: newPage,
        urlref: href,
        js: 1
      });
    }
 
    

    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function (data) {
        if (data) {
          // console.log('data ',data);
          // var test = $(data).append('<p>Just testing</p>');
          // $('<p>Still testing</p>').appendTo(data);
          // console.dir(data);
          
          // var theString = `<div class="wp-block-jci-blocks-ad-area-three" id="ohlloop${obj.current_page}-2"></div>`;
          $(".onehundred-container").append(data);
          // $(".onehundred-container").append(theString);
          if (obj.current_page % 2 == 0) {
            cmWrapper.que.push(function () {
              cmWrapper.ads.define("ROS_BTF_970x250", `ohlloop${obj.current_page}-2`, function(name, slot){
                // console.log('name: ',name,' slot: ',slot.getSlotElementId());
                let adDiv = document.createElement("div");
                adDiv.id = slot.getSlotElementId();
                adDiv.classList.add("wp-block-jci-blocks-ad-area-three");
                // console.log('adDiv: ',adDiv);
                // $(".onehundred-container").append(adDiv);
                document.getElementsByClassName('onehundred-container')[0].appendChild(adDiv);
              });
              cmWrapper.ads.requestUnits();
            });
          }
          
          obj.current_page++;
         Waypoint.refreshAll();
          
        } else {
          console.log("no data ");
        }
      },
    });
  };

  const onehundredlist = $(".onehundred-container").waypoint({
    handler: function (direction) {
      // console.log('waypoint is running');
      ajaxOneHundred();
    },
    offset: "bottom-in-view",
  });

  // let logosrc = $('.custom-logo').attr('src');
  // let whitelogosrc = logosrc.replace('logo.svg', 'white-logo.svg');
  const logosrc = 'https://' + location.host + '/wp-content/themes/livability/assets/images/logo.svg';
  const whitelogosrc = 'https://' + location.host + '/wp-content/themes/livability/assets/images/white-logo.svg';
  if ($('.mega-hero').length) {
    $('.mega-hero').css('margin-top', '-130px');
    $('.custom-logo').attr('src', whitelogosrc);
    $('body').addClass('body-mega-hero');
    
  }

  const megaHeroFunction = function(direction) {
    const body = $('body');
    if (direction == 'up') {
      body.addClass('body-mega-hero');
      $('.custom-logo').attr('src', whitelogosrc);
    } else {
      body.removeClass('body-mega-hero');
      $('.custom-logo').attr('src', logosrc);
    }
    // console.log('direction is ',direction); 
  }


  const megaHero = $(".mega-hero").waypoint({
    // handler: megaHeroFunction(direction),
    handler: function(direction){
      megaHeroFunction(direction);
    },
    offset: function() {
      return -this.element.clientHeight
    }
  });

  if ($('body').hasClass('single-post')) {
    window.almOnChange = function (alm) {
      console.log("Ajax Load More is loading...");
      var post = alm.single_post_array;
      if (post !== undefined) {
        // console.log(post);
        var host = location.hostname+'/',
           url = host+post.slug,
                  urlref = host+alm.slug;
        // console.log('url is ',url);
        // console.log('urlref is ',urlref);
        
         PARSELY.beacon.trackPageView({
                  url: url,
                  urlref: urlref,
                  js: 1
              });
              return true;
      } else {
        console.warn('alm post is undefined');
      }
      // initiate sponsor carousel
      // console.log('alm is ',post);
      // initPwlSlick();
    };
  }

  const bps = document.querySelector(".site-main .bp-sponsor-container");
   if (typeof(bps) != 'undefined' && bps != null) {
    var waypointSponsor = new Waypoint({
      element: bps,
      handler: function (direction) {
        if (direction == 'up') {
          document.body.classList.remove("hide-bp-sponsor");
          // console.log("up");
        } else {
          document.body.classList.add("hide-bp-sponsor");
          // console.log("down");
        }
      },
      offset: 70
    })
   }

  $('.show-city-list').on('click', function() {
    const clb = $('.city-list-block');
    if (clb.hasClass('open')) {
      clb.removeClass('open');
      $(this).text('See Full List');
    } else {
      clb.addClass('open');
      $(this).text('Hide Full List');
    }
    
  });

  // $(window).on('onPlayerReady', function(event){
  //   console.log('player event in main js');
  // });


//  var heroVid = $('player');
//  $(window).on('resize load', function() {
//    var self = heroVid;
//    var container = self.parent();
//    self.css({
//     width: container.width() + "px",
//     height: container.width() * (9/16) + 'px',
//     position: 'absolute',
//     marginTop: -container.width() * (9/32) + 'px',
//     top: '50%'
//   });
//  });
 

  //  document.addEventListener('keyup', fetch);
  // document.querySelector('#keyword').addEventListener('keyup', fetch);
  // #keyword onkeyup fetch

  //  function fetch(){
  //   // console.log('event',e);
  //   // console.log($('#keyword').val());
  //   console.log(params.ajaxurl);
  //     jQuery.ajax({
  //         url: params.ajaxurl,
  //         type: "POST",
  //         data: { action: 'datafetch', keyword: jQuery('#keyword').val() },
  //         success: function(data) {
  //           console.log('data ',data);
  //             jQuery('#datafetch').html( data );
  //         }
  //     });
  // }


  window.almComplete = function(alm){
    console.log("Ajax Load More Complete");
    // console.dir(alm);
    // initPwlSlick();
    // console.dir(alm.content.innerHTML);
    // if (ScrollTrigger.length) {
      // let triggers = ScrollTrigger.getAll();
      // triggers.forEach( trigger => {      
        // trigger.kill();
        // trigger.enable();
      // });
    // }
    if (document.querySelectorAll('.pwl-slick')) {
      // initPwlSlick();
      let pwl = $('body').find('.pwl-slick').last();
      // console.log('has slick');
      // console.dir(pwl);
    
        pwl.slick({
          infinite: true,
          slidesToShow: 3,
          slidesToScroll: 1,
          arrows: true,
          prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
          nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              },
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              },
            },
          ],
        });
    } else {
      console.log('not slick');
    }
    if (alm.content.innerHTML.indexOf('horizontal-article-container') !== -1) {
      // console.log('has slider');
      horizontalSlides();
    } 

  

    // ScrollTrigger.refresh();
    // setTimeout(ScrollTrigger.refresh, 1000);
  };

  $('#widget-finder').on('submit', function(e) {
    // let that = $(this);
    // const val = e.target.value;
    e.preventDefault();
    const val = $('#widget-finder-input').val();
    // if (val.length > 5) {
      const data = {
        query:  val, 
        action: "findCity"
      }
      $.ajax({
        url: params.ajaxurl,
        data: data,
        type: "POST",
        dataType: "html",
        success: data => {
          if (data) {
            $('#widget-finder').after(data);
            console.log('data: ', data);
          }
        }

      });
    // }
  });



  // Ken Burns
  const kbContainer = $('#kb-slide-container');
  const kbReserve = $('#slide-reserve');

  if (kbContainer.length) {


    // kbContainer.appendChild(kbReserve.children[0]);
    // const metaContainer = $('.livability-image-meta');
    const metaContainer = document.querySelector('.livability-image-meta');
    const firstSlide = kbReserve.find('.kb-slide').first();
    firstSlide.prependTo(kbContainer);
    const firstByline = `<span>${firstSlide.data('byline')} / ${firstSlide.data('place')}</span>`;
    // const firstByline = '<span>Hello World</span>';
    console.log(metaContainer);
    // firstByline.appendTo(metaContainer);
    metaContainer.innerHTML = firstByline;
    // kbReserve.find('.kb-slide').first().prependTo(kbContainer);
    slidesInterval = setInterval(refreshSlide, 6000);

  function refreshSlide() {
    console.log('slide refreshed');
    let nextSlide = kbReserve.find('.kb-slide').first();
    if (nextSlide.data('byline').length || nextSlide.data('place').length) {
      let newByline = `<span>${nextSlide.data('byline')} / ${nextSlide.data('place')}</span>`;
      metaContainer.style.display = 'block';
      metaContainer.innerHTML = newByline;
    } else {
      metaContainer.style.display = 'none';
      metaContainer.innerHTML = '';
    }
    
    nextSlide.prependTo(kbContainer);
    kbContainer.find('.kb-slide').last().fadeOut(2000, function() {
      $(this).appendTo(kbReserve).show(0);
    });
  }

}

// local insight form: replace city state in span tags with correct city state
if (window.gform) {
  gform.addAction('gform_input_change', function(elem, formId, fieldId){
    console.log('change');
    if (formId == 10 && fieldId == 26) {
      console.log('form change event');
      let cityAndState = $('#input_10_26_chosen').find('.chosen-single span').text();
      // const labels = $('.gchoice').find('label');
      const choices = $('.gchoice');
      choices.each(function(index, el){
        // $(el).text(el.text().replace('CITY, STATE', cityAndState));
        // const label = $(el).find('label span');
        $(el).find('label span').text(cityAndState);
        // console.log('c and s ',cityAndState);
        // $(el).find('input').val($(el).find('label').text());
      });

      // console.dir(labels);
      
    }
  }, 10);
}

function getQueryStrings() { 
  var assoc  = {};
  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
  var queryString = location.search.substring(1); 
  var keyValues = queryString.split('&'); 

  for(var i in keyValues) { 
    var key = keyValues[i].split('=');
    if (key.length > 1) {
      assoc[decode(key[0])] = decode(key[1]);
    }
  } 

  return assoc; 
} 

//local insight form: use cityid query variable to set 
$(document).on('gform_post_render', function() {
  // 51356
  const queryString = getQueryStrings('cityid');
  $('#input_10_26').val("queryString"); 
  // $('#input_13_26').val("54594");
});

$(".bp23-category-btns").slick({
  prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
  nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
  autoplay: false,
  arrows: true,
  dots: false,
  slidesToShow: 9,
  centerMode: false,
  infinite: false,
  // centerPadding: "0",
  // lazyLoad: "ondemand",
  responsive: [
    {
      breakpoint: 200,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        // centerPadding: "80px",
      },
    },
      {
        breakpoint: 350,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          // centerPadding: "80px",
        },
    },
    {
      breakpoint: 500,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4,
        // centerPadding: "80px",
      },
  },
  {
    breakpoint: 750,
    settings: {
      slidesToShow: 5,
      slidesToScroll: 5,
      // centerPadding: "80px",
    },
},
{
  breakpoint: 900,
  settings: {
    slidesToShow: 6,
    slidesToScroll: 4,
    // centerPadding: "80px",
  },
},
{
  breakpoint: 1050,
  settings: {
    slidesToShow: 7,
    slidesToScroll: 3,
    // centerPadding: "80px",
  },
},
{
  breakpoint: 1200,
  settings: {
    slidesToShow: 8,
    slidesToScroll: 2,
    // centerPadding: "80px",
  },
},
{
  breakpoint: 1350,
  settings: {
    slidesToShow: 9,
    slidesToScroll: 1,
    // centerPadding: "80px",
  },
},
  ],
});

//best place landing pages, return current year from page template class
function getBpYear(){
  if ( $('body').hasClass('best_places-template-best_places_2023_landing_page') ) {
    return '2023';
  } else if ( $('body').hasClass('best_places-template-best_places_2024_landing_page') ) {
    return '2024';
  } else {
    return null;
  }
}

$('.bp23-category-btn').on('click', function(){
  const $this = $(this); 
  const year = getBpYear();
  const siblings = $this.siblings().find('div');
  if ( $this.hasClass('active')) {
    return;
  }
  window.params.bp23page = 1;
  $this.find('div').addClass('active');
  siblings.removeClass('active');
  // get data
  const cat = $this.find('div').data('cat');
  // ajax request
  const data = {
    action: "loadbp23",
    cat: cat,
    bp23filters: window.params.bp23filters,
    year
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      // console.log('data is ',data);
      if (year == '2023') {
        
        $('.bp23-results').html(data);
      } else if (year == '2024') {
        $('.bp24__results').html(data);
        
      }
      
      Waypoint.refreshAll();
    }
  }

  );
});
// $('input[type=radio][name=population], input[type=radio][name=region], input[type=radio][name=home_value]').on('change', function(e) {
//   // console.log('this: ',$(this).attr('name'));
//   console.log('attribute: ',e.target.getAttribute('name'));
//   const that = $(this);
//   const name = e.target.getAttribute('name');
//   window.params.bp24filters[name] = that.val();
// });
$('#region, #population, #home_value').on('change', function(e){
  
  // console.log('e ',e);
  const year = getBpYear();
  if (year == '2024') {
    window.params.bp23filters[e.target.parentElement.parentElement.id] = e.target.value;
  } else {
    window.params.bp23filters[e.target.id] = e.target.value;
  }
  window.params.bp23page = 1;
  if (e.target.value == '') {
    e.target.classList.remove("filtered");
  } else {
    e.target.classList.add("filtered");
  }
  const cat = $('.bp23-category-btn .active').data('cat');
  // console.log('cat data is ',cat);
  const data = {
    action: "loadbp23",
    cat: cat,
    bp23filters: window.params.bp23filters,
    year
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      if (year == '2023') {
        $('.bp23-results').html(data);
        Waypoint.refreshAll();
      } else if (year == '2024') {
        $('.bp24__results').html(data);
        bp24ScrollFixx();
        Waypoint.refreshAll();
       
      }
    }
  });
  
});


// console.dir(Object.values(window.params.bp23filters));
$('.reset-filter, .bp24__reset-btn').on('click', function() {
  const year = getBpYear();
  window.params.bp23page = 1;
  
  if (year == '2023') {
    const filters = $('#region, #population, #home_value');
    filters.val("").removeClass('filtered');
  } else {
    $('#allRegions, #allPopulation, #allhv').prop('checked', true);
    $('fieldset').removeClass('open');
  }
  

  window.params.bp23filters = {
    "region": "",
    "population": "",
    "home_value": ""
  }
  const cat = $('.bp23-category-btn .active').data('cat');
  const data = {
    action: "loadbp23",
    cat: cat,
    bp23filters: window.params.bp23filters,
    year
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      if (year == '2023') {
        $('.bp23-results').html(data);
      } else if (year == '2024') {
        $('.bp24__results').html(data);
        bp24ScrollFixx();
      }
      
      Waypoint.refreshAll();
    }
  });
});


const bp23Waypoint = $(".bp23-results, .bp24__results").waypoint({
  handler: function (direction) {
    loadbp23();
  },
  offset: "bottom-in-view",
});

function loadbp23() {
  const year = getBpYear();
  const data = {
    action: "loadMorebp23",
    page: window.params.bp23page,
    bp23filters: window.params.bp23filters,
    year
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      if (data) {
        window.params.bp23page++;
        const resultContainer = year == '2023' ? $('.bp23-results') : $('.bp24__results');
        resultContainer.append(data);
        if (year == '2023') {
        cmWrapper.que.push(function () {
          cmWrapper.ads.define("ROS_BTF_970x250", `ohlloop${window.params.bp23page}-2`, function(name, slot){
            // console.log('name: ',name,' slot: ',slot.getSlotElementId());
            let adDiv = document.createElement("div");
            adDiv.id = slot.getSlotElementId();
            adDiv.classList.add("wp-block-jci-blocks-ad-area-three");
            // console.log('adDiv: ',adDiv);
            // $(".onehundred-container").append(adDiv);
            resultContainer.appendChild(adDiv);
          });
          cmWrapper.ads.requestUnits();
        });
      } // end if year is 2023
        let href = location.href;
        href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
        let newPage = href+'/'+window.params.bp23page;
        if (typeof ga === "function") { 
          ga('send','pageview', newPage);
        }
        if (typeof PARSELY !== 'undefined') {
        PARSELY.beacon.trackPageView({
          url: newPage,
          urlref: href,
          js: 1
        });
      }
      }
      Waypoint.refreshAll();
    }
  });
}

$('.cat-detail').on('click', function(){
  $(this).toggleClass('open');
  $(this).find('p').toggleClass('open');
  $(this).siblings().removeClass('open');
  $(this).siblings().find('p').removeClass('open');
});

$('.lcd-btn').on('click', function() {
  $(this).toggleClass('opened');
  $('.livscore-category-details').toggleClass('hidden');
});

$('.bp23-announcement').show();


// $('.bp2l__tab-nav a').on('click', function(e){
//   console.log(e.target.hash);
// });

// run once on load

// get json object and loop through
// insert place into url and get returned coordinates
// run ajax function to add coordinates to metadata

// const jsonObj = [
//   {
//       "id": 120106,
//       "title": "Grand Chute, WI"
//   },
//   {
//       "id": 120082,
//       "title": "Fox Crossing, WI"
//   },
//   {
//       "id": 109208,
//       "title": "Rising Sun"
//   },
//   {
//       "id": 106188,
//       "title": "Bloomfield, IA"
//   },
//   {
//       "id": 102437,
//       "title": "Mount Vernon, IA"
//   },
//   {
//       "id": 101266,
//       "title": "Palisade, CO"
//   },
//   {
//       "id": 92797,
//       "title": "Sturbridge, MA"
//   },
//   {
//       "id": 90210,
//       "title": "Huxley, IA"
//   }
// ];


// jsonObj.forEach(element => {
//   let title = element['title'].replace(/"/g,'').replace(/ /g,'+');
//   let id = element['id'];
  // console.log('first id is '+id);
  // console.log(title);
  // $.ajax({
  //   url: `https://maps.googleapis.com/maps/api/geocode/json?address=${title}&key=AIzaSyAfO29tXYeeean_v42L4cnX_x0VH5EhnI0`,
  // }).then(function( data ){
    // console.log('ajax ran');
    // console.dir(data['results'][0]['geometry']['location']);
    // console.log('second id is '+id);
    // let placeInfo = {
    //   "id" : id, 
    //   "lat" : data['results'][0]['geometry']['location']['lat'],
    //   "lng" : data['results'][0]['geometry']['location']['lng'],
    // }
    // console.dir(placeInfo);
    // return {
      // $.ajax({
      //   url: params.ajaxurl,
      //   type: POST,
      //   data: placeInfo
      // }).done();
//     }
//   });
// });



$('li.mega-menu-item').on('open_panel', function() {
  console.log('Sub menu opened');
});

$('li.mega-menu-item').on('close_panel', function() {
console.log('Sub menu closed');
});

$('.close-all-panels').on('click', function(e) {
e.preventDefault();
$('.max-mega-menu').data('maxmegamenu').hideAllPanels();
});

$("li.mega-menu-item").on("open_panel", function() {
var current_menu = $(this).closest("ul.max-mega-menu");
$('ul.max-mega-menu').not(current_menu).each( function() {
  $(this).data('maxmegamenu').hideAllPanels();
});
});

// add active class to side that has id
// const sideNav = anchor => {
//   $('.place-side-nav li').each(function(n){
//     $(n).find('a').attr('id');
//     console.log('id is ',);
//   });
// }

//select all h2 and div. 
// const sideNav = $('.single-liv_place .entry-content div, .single-liv_place .entry-content h2').waypoint({
//   handler: {
//     function () {
//       console.log('id is ');
//     }
//   },
//   offset: "150px"
// });
//filter all that have ids
// create a function that adds active class on entry, removing class from siblings

// var testing = new Waypoint({
//   element: document.querySelectorAll('.single-liv_place div, .single-liv_place h2'),
//   handler: function(direction) {
//     let navItems = document.querySelectorAll('.place-side-nav li');
//     const theTarget = document.getElementById(this.element.id);
//     navItems.forEach(element => {
//       element.classList.remove('active');
//     });
//     document.

//     // console.log(this.element.id + 'sdfsdf hit');
//     // console.dir(this);
//   },
//   offset: '150px'
// });

const sideNavFunc = () => {
  const navItems = $('.place-side-nav li');
  $('.entry-content').find('div, h2').each( function(i,e){
    let id = $(e).attr('id');
    if (id) {
      let match = navItems.find('a[href$='+id+']');
      if (!match) {
        console.log('notta match');
        return;
      }
      let temp = new Waypoint({
        element: e,
        handler: function(direction) {
          // const theID = `#${this.element.id}`;
          navItems.removeClass('active');
          match.parent().addClass('active');
        },
        offset: '130px'
      });
    }
  });
}
if ( $('body').hasClass('single-liv_place')) {
  sideNavFunc();
} 

if ( !$('body').hasClass('single-post')) {
  console.log('byline function run');
  const images = $("img[class^='wp-image-']");
  images.each(function(i, e){
    let classes = e.className;
    let start = classes.indexOf('wp-image-');
    let str = classes.substring(start + 9);
    if (str.indexOf(' ') >= 0) {
      console.log('has space');
      str = str.substring(0, str.indexOf(' '));
    }
    const data = {
      action: "addImgByline",
      attachmentId: str
    }
    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "html",
      success: function(res) {
        $(e).wrap('<div class="img-container">'+res+'</div>');
        // $(res).insertAfter($(e));
      }
    })

  }); // end each
} // end if has class
  
})(jQuery);
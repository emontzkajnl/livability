(function ($) {
  $(window).load(function(){
    $('.livability-image-meta').show();
  });

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
    console.log('that ',that,' tab id ',tabID,' tabTarget ',tabTarget);
    items.removeClass('active');
    that.addClass('active');
    tabs.hide();
    tabTarget.show();
  });

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
      // prevArrow: '<svg class="slick-prev" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
      // nextArrow: '<svg class="slick-next" xmlns="http://www.w3.org/2000/svg" height="16" width="10" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
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
    if (typeof PARSELY.beacon !== 'undefined') {
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
    if (typeof PARSELY.beacon !== 'undefined') {
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
    // console.dir(window.ohlObj);
console.log('function is running');
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
    if (typeof PARSELY.beacon !== 'undefined') {
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

  let logosrc = $('.custom-logo').attr('src');
  let whitelogosrc = logosrc.replace('logo.svg', 'white-logo.svg');
  if ($('.mega-hero').length) {
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
        if (ListingObj) {
          ListingObj.articleid = post.id;
          ListingObj.pageslug = post.slug;
          updateListingObj(post.id)
        } else {
          console.warn('ListingObj not defined');
        }
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

  function updateListingObj(id) {
    const data = {
      id: id, 
      action: "updateLO"
    }
    $.ajax({
      url: params.ajaxurl,
      data: data,
      type: "POST",
      dataType: "JSON",
      success: data => {
        if (data) {
          window.ListingObj.place = data['place'];
          window.ListingObj.placeid = data['placeid'];
        } else {
          console.warn('did not get ajax data');
        }
      }
    })
  }

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
        slidesToScroll: 1,
        // centerPadding: "80px",
      },
    },
      {
        breakpoint: 350,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          // centerPadding: "80px",
        },
    },
    {
      breakpoint: 500,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        // centerPadding: "80px",
      },
  },
  {
    breakpoint: 750,
    settings: {
      slidesToShow: 5,
      slidesToScroll: 1,
      // centerPadding: "80px",
    },
},
{
  breakpoint: 900,
  settings: {
    slidesToShow: 6,
    slidesToScroll: 1,
    // centerPadding: "80px",
  },
},
{
  breakpoint: 1050,
  settings: {
    slidesToShow: 7,
    slidesToScroll: 1,
    // centerPadding: "80px",
  },
},
{
  breakpoint: 1200,
  settings: {
    slidesToShow: 8,
    slidesToScroll: 1,
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

$('.bp23-category-btn').on('click', function(){
  const $this = $(this); 
  
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
    bp23filters: window.params.bp23filters
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      // console.log('data is ',data);
      $('.bp23-results').html(data);
      Waypoint.refreshAll();
    }
  }

  );
});

$('#region, #population, #home_value').on('change', function(e){
  window.params.bp23filters[e.target.id] = e.target.value;
  window.params.bp23page = 1;
  if (e.target.value == '') {
    e.target.classList.remove("filtered");
  } else {
    e.target.classList.add("filtered");
  }
  console.log('e: ',e.target);
  const cat = $('.bp23-category-btn .active').data('cat');
  // console.log('cat data is ',cat);
  const data = {
    action: "loadbp23",
    cat: cat,
    bp23filters: window.params.bp23filters
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      // console.log('data is ',data);
      $('.bp23-results').html(data);
      Waypoint.refreshAll();
    }
  });
  
});
// console.dir(Object.values(window.params.bp23filters));
$('.reset-filter').on('click', function() {
  window.params.bp23page = 1;
  const filters = $('#region, #population, #home_value');
  filters.val("").removeClass('filtered');
  window.params.bp23filters = {
    "region": "",
    "population": "",
    "home_value": ""
  }
  const cat = $('.bp23-category-btn .active').data('cat');
  const data = {
    action: "loadbp23",
    cat: cat,
    bp23filters: window.params.bp23filters
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      // console.log('data is ',data);
      $('.bp23-results').html(data);
      Waypoint.refreshAll();
    }
  });

   
  


  
  


});

const bp23Waypoint = $(".bp23-results").waypoint({
  handler: function (direction) {
    // console.log('waypoint is running');
    loadbp23();
  },
  offset: "bottom-in-view",
});

function loadbp23() {
  const data = {
    action: "loadMorebp23",
    page: window.params.bp23page,
    bp23filters: window.params.bp23filters
  }
  $.ajax({
    url: params.ajaxurl,
    data: data,
    type: "POST",
    dataType: "html",
    success: function (data) {
      if (data) {
        window.params.bp23page++;
        $('.bp23-results').append(data);
        cmWrapper.que.push(function () {
          cmWrapper.ads.define("ROS_BTF_970x250", `ohlloop${window.params.bp23page}-2`, function(name, slot){
            // console.log('name: ',name,' slot: ',slot.getSlotElementId());
            let adDiv = document.createElement("div");
            adDiv.id = slot.getSlotElementId();
            adDiv.classList.add("wp-block-jci-blocks-ad-area-three");
            // console.log('adDiv: ',adDiv);
            // $(".onehundred-container").append(adDiv);
            document.getElementsByClassName('bp23-results')[0].appendChild(adDiv);
          });
          cmWrapper.ads.requestUnits();
        });
        let href = location.href;
        href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
        let newPage = href+'/'+window.params.bp23page;
        console.log('new page is ',newPage);
        if (typeof ga === "function") { 
          ga('send','pageview', newPage);
        }
        if (typeof PARSELY.beacon !== 'undefined') {
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





  
})(jQuery);

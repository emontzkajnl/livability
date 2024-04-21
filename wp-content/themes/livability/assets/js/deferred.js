(function ($) {
    function horizontalSlides() {
        if (document.getElementsByTagName('body')[0].classList.contains('handheld')) { return; }
        const horizontalSlides = $('.horizontal-article-container');
        if (horizontalSlides.length) {
          $('.full-width-off-white').css('minHeight', '250px');
          function setNavPosition() {
            const sld = $('.horizontal-slide').first().height();
            $('.horizontal-nav').css('top', sld + 50 + 'px');
          }
          setNavPosition();  // should also do this on resize
          gsap.registerPlugin(ScrollToPlugin, ScrollTrigger);
          let tween;
          // navigation function
          ScrollTrigger.refresh();
          let panelsContainer = $('.horizontal-article-container');
          $('.horizontal-nav a').on('click', function(e){
            e.preventDefault();
            const that = $(this);
            let targetElem = document.querySelector(e.target.getAttribute("href")),
            // let targetElem = $('#slide2');
            y = targetElem;
            
            if (targetElem) {
              let totalScroll = tween.scrollTrigger.end - tween.scrollTrigger.start,
              totalMovement = (panels.length -1) * targetElem.offsetWidth;
              y = Math.round(tween.scrollTrigger.start + (targetElem.offsetLeft / totalMovement) * totalScroll);
            }
            gsap.to(window, {
              scrollTo: {
                y: y,
                autoKill: false
              },
              duration: 1
            });
          });
    
          const panels = gsap.utils.toArray(".horizontal-article-container .horizontal-slide");
          let maxWidth = 0,
          currentSlide; 
          const slideWidthPercent = parseInt(100 / (panels.length - 1), 10).toFixed(2);
          function currentSlideActive(self ) {
            let progress = parseInt(self.progress, 10);
            let result = (progress / slideWidthPercent).toFixed();
            // console.log('result is '+ result);
            console.log('progress: ',progress);
            console.log('swp ',slideWidthPercent);
          }
          const getMaxWidth = () => {
            maxWidth = 0;
            panels.forEach((panel) => {
              maxWidth += panel.offsetWidth;
            });
          };
          getMaxWidth();
          ScrollTrigger.addEventListener("refreshInit", getMaxWidth);
          tween = gsap.to(panels, {
            // xPercent: -100 * ( panels.length - 1 ),
            x: () => `-${maxWidth - window.innerWidth}`,
            ease: "none",
            // onLeave: console.log('on leave'),
            scrollTrigger: {
              trigger: ".horizontal-article-container",
              pin: true,
              pinSpacing: true,
              start: "top 100px",
              // end: "+=1000",
              end: () => `+=${maxWidth}`,
              scrub: true,
              // anticipatePin: 1,
              // markers: true,
              onEnter: () => $('.horizontal-nav').addClass('show'),
              onEnterBack: () => $('.horizontal-nav').addClass('show'),
              onLeave: () => $('.horizontal-nav').removeClass('show'),
              onLeaveBack: () => $('.horizontal-nav').removeClass('show'),
              // onUpdate: self => currentSlideActive(self),
              onUpdate: self => {
                let progress = self.progress.toFixed(2);
                let newSlide = ((progress/slideWidthPercent) * 100).toFixed();
                if (newSlide != currentSlide) {
                  panels[newSlide].classList.add('active');
                  currentSlide = newSlide;
                }
              },
            },
          });
        } 
      }
      horizontalSlides();

})(jQuery);
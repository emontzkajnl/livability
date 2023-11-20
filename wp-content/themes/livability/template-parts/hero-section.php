<?php

$postId = get_the_ID();
if (have_rows('hero_section')):
    while (have_rows('hero_section')): the_row();
    $title = get_sub_field('hero_title');
    $text = get_sub_field('hero_subtitle');
    $hero_video = get_sub_field('hero_video');
    $has_hero_video = boolval($hero_video && !wp_is_mobile());
    $ken_burns = get_sub_field('ken_burns');
    

 if (!$has_hero_video):
    $thumbId = get_post_thumbnail_id();
    $img_byline = get_field('img_byline', $thumbId);
    $img_place_name = get_field('img_place_name', $thumbId);
 endif; 
 ?>

<div class="hero-section alignfull">
<?php if (!$ken_burns && !$has_hero_video) {
  echo get_the_post_thumbnail();
} ?>
<!-- <iframe id="yt-player" src="https://www.youtube.com/embed/14XxolEJloE?autoplay=1&modestbranding=1&controls=0&loop=1&showinfo=0&rel=0&enablejsapi=1&version=3&loop=0&playerapiid=ytplayer&allowfullscreen=true&wmode=transparent&iv_load_policy=3&html5=1&disablekb=true" frameborder="0"></iframe> -->
<?php if ($has_hero_video): ?>    
    <div class="video-wrap">
    <div id="player"></div>
    </div>
    

    <script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
        //   height: 'auto',
        //   width: '100%',
          videoId: '<?php echo $hero_video; ?>',
          playerVars: {
            'playsinline': 1,
            'loop'       : 1,
            'controls'   : 0,
            'fs'        : 0,
            'disablekb'  : 1,
            'modestbranding': 1,
            'playlist'   : '<?php echo $hero_video; ?>'
          },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }
      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.setVolume(0);
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
    //   var done = false;
      function onPlayerStateChange(event) {
      }
      function stopVideo() {
        player.stopVideo();
      }
    </script>

    <?php //echo 'the url is '.$hero_video; ?>

<?php endif; ?>

<?php if ( $ken_burns): 
 $kb_slides =  get_sub_field('kb_slides'); 
 
//  $initial_slide = $kb_slides[0]; ?>
 <div id="kb-slide-container" ></div>

 <div id="slide-reserve">
   <?php foreach ($kb_slides as $slide) { 
    $slideID = $slide['kb_slide']['ID']; 
    // echo 'slide id is '.$slideID; ?>
     <div 
        class="kb-slide <?php echo $slide['animation']; ?>" 
        data-byline="<?php echo get_field('img_byline', $slideID) ? strip_tags(get_field('img_byline', $slideID)) : ''; ?>" 
        data-place="<?php echo get_field('img_place_name', $slideID); ?>"
        style="background-image: url('<?php echo $slide['kb_slide']['url']; ?>'); ">
    </div>
   <?php } ?>
 </div>

<?php endif; ?>

<div class="container hero-flex">
<div class="hero-text">
   
  <?php the_title( '<h5>', '</h5>' ); ?>  
<?php if ($title) echo '<h2 class="h1">'.__($title, 'livability').'</h2>'; ?>
<?php if ($text) echo '<p>'.__($text, 'livability').'</p>'; ?>
</div>
<div class="hero-links">
<?php if (have_rows('links')): 
echo '<ul>';
    while (have_rows('links')): the_row(); 
    $link = get_sub_field('link'); ?>

  <li><a href="<?php echo esc_url( get_permalink( $link->ID )); ?>">
  <?php echo esc_html(get_the_title($link->ID)); ?>
  </a></li>
    <?php endwhile;
        echo '</ul>';
        endif; ?>

</div>
<?php //echo 'has hero video: '.$has_hero_video;
if ( !$has_hero_video ):
  if ($ken_burns) {
      echo '<div class="livability-image-meta"></div>';
    } else {
      echo '<div class="livability-image-meta">';
      echo $img_place_name ? $img_place_name : '' ;
      echo $img_place_name && $img_byline ? ' / ' : '';
      echo $img_byline ?  strip_tags($img_byline, "<a>") : '' ;
      echo '</div>';
  }
endif; ?>
</div>
</div> 
<?php //endif; // if !hide_hero ?>
<?php endwhile; else: ?>
    <div class="hero-section alignfull" >
    <div class="container">
        <div class="hero-text">
    <?php the_title( '<h5>', '</h5>' ); ?>  
    </div>
    </div>
</div>
<?php endif; ?>
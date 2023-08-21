// const { postcss } = require("autoprefixer");


const unRegisterBlocks = (...args) => {
    args.forEach(element => wp.blocks.unregisterBlockType(element));
}
wp.domReady(function() {
    wp.blocks.unregisterBlockType( 'core/search' );
    unRegisterBlocks(
        'core/archives',
        'core/calendar',
        'core/categories',
        'core/latest-comments',
        'core/latest-posts',
        'core/code',
        'core/nextpage',
        'core/preformatted',
        'core/pullquote',
        'core/verse',
        'core/cover',
        // 'core/gallery',
        // 'core/freeform',
        'core/media-text',
        'core/more',
        'core/social-links',
        'core/social-link',
        'core/tag-cloud',
        'core/site-tagline',
        'core/query-pagination',
        'core/query-pagination-next',
        'core/query-pagination-previous',
        'core/query-pagination-numbers',
        'core/loginout'
    );
 
    const postType = document.querySelector('form.metabox-base-form input#post_type').value;
    
    if (postType == 'liv_magazine') {
        unRegisterBlocks('acf/magazine-link','acf/full-width-magazine-link', 'jci-blocks/magazine-link');
    } else {
        unRegisterBlocks('jci-blocks/magazine-articles','jci-blocks/magazine');
    }
    if (postType != 'best_places') {
        unRegisterBlocks('jci-blocks/best-place-data', 'jci-blocks/onehundred-list','jci-blocks/onehundredslider','acf/best-places-carousel','jci-blocks/onehundredslider', 'jci-blocks/bp-sponsor');
    }
    if (postType != 'liv_place') {
        unRegisterBlocks('jci-blocks/quick-facts', 'jci-blocks/madlib', 'jci-blocks/city-301', 'jci-blocks/city-list');
    }
    // if (postType != 'post') {
    //     unRegisterBlocks('jci-blocks/author-block');
    // }
    // preformatted, code, social, archive, calendars, categories, comments, latest postcss, rss, 
    const types = wp.blocks.getBlockTypes();
    console.log('types: ',types);

});
  
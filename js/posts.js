jQuery(document).ready(function($) {
  // Add counter animation to numbers.
  let likeList = jQuery('div.flag-like');
  jQuery.each(likeList, function( key, value ) {
    if($(value).hasClass('action-flag')) {
      $(value).find('a.use-ajax').on('click', function(e) {
        console.log("key-flag", key);
        let el = $(value).parent().find('span');
        let currCount = parseInt(el.text());
        if (currCount >= 0) {
          el.text(currCount+1);
          currCount = '';
        }
      });
    }

    if($(value).hasClass('action-unflag')) {
      $(value).find('a.use-ajax').on('click', function(e) {
        let el = $(value).parent().find('span');
        let currCount = parseInt(el.text());
        if (currCount > 0) {
          el.text(currCount-1);
          currCount = '';
        }
      })
    }
  });
});

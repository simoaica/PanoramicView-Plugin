/*

Javascript file

*/
jQuery(document).ready(function() {
      jQuery(window).scroll(function(){
          if (jQuery(this).scrollTop() > 100) {
              jQuery('.sageata').fadeIn();
          } else {
              jQuery('.sageata').fadeOut();
          }
      });

      /**
       * On the click event of the link scroll back to the top of the page
       */
      jQuery('.sageata').on('click', function(){
          jQuery('html, body').animate({scrollTop : 0},800);
          return false;
      });
});

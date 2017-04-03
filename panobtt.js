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
       * On click event of the link scroll back to the top of the page
       */
      jQuery('.sageata').on('click', function(){
          jQuery('html, body').animate({scrollTop : 0},800);
          return false;
      });

      /**
       * On click event of the close button of Consent Cookie
       */
      jQuery('#cookie-span').on('click', function(){
          setCookie('cuchi', 'citit', 365);
          jQuery('#cookie-div').remove();
      });

      function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires;
        }
});

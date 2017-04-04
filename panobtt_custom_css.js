/*

Javascript file for custom css

*/
jQuery(document).ready(function($) {
  var updateCSS = function(){
      $("#custom_css").val( editor.getSession().getValue() );

      // Salveaza CustomCSS in fisierul customcss.txt

      var customache = editor.getSession().getValue();
      $.post( "../wp-content/plugins/panoramicview-plugin/diverse.php", { continut: customache })
                          .done(function( data ) {
                            // alert( "Data Loaded: " + data );
                          });

  }

  $("#save-panobtt-form").submit( updateCSS );


  /**
   * On click event of the button for Loading Custom CSS from file
   */
  jQuery('button#incarca-customCSS').on('click', function(e){
      e.preventDefault();
      var incarc = confirm('Esti sigur ca vrei sa incarci CustomCSS de pe harddisk? \n\nAcesta va suprascrie continutul pe care il ai!\n');
      if (incarc) {
        $.post( "../wp-content/plugins/panoramicview-plugin/diverse.php", { incarc: 'da' })
                            .done(function( data ) {
                              // alert( "Data Loaded: " + data );
                              if (data !== 'nasol') {
                                editor.$blockScrolling = Infinity;
                                editor.setValue( data );
                                fnDisplayAdminMessage('Custom CSS a fost incarcat cu succes din fisierul customcss.txt!', 'verde');
                              } else {
                                fnDisplayAdminMessage('Nu ai salvat nici un customCSS! - Nu am ce incarca :)', 'red');
                              }
                            });
                  }
  });

  function fnDisplayAdminMessage(adminMessage, adminMessageColor) {

                jQuery('#my-admin-message').after('<div class="error notice is-dismissible"><p>' + adminMessage + '</p><button id="my-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                jQuery("#my-dismiss-admin-message").click(function(event) {
                    event.preventDefault();
                    jQuery('.' + 'error').fadeTo(100, 0, function() {
                        jQuery('.' + 'error').slideUp(100, function() {
                            jQuery('.' + 'error').remove();
                        });
                    });
                });
                switch (adminMessageColor) {
                case 'yellow':
                    jQuery("div.error").css("border-left", "4px solid #ffba00");
                    break;
                case 'red':
                    jQuery("div.error").css("border-left", "4px solid #dd3d36");
                    break;
                default:
                    jQuery("div.error").css("border-left", "4px solid #46b450");
                }
            }
});

var editor = ace.edit("customCss");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/css");

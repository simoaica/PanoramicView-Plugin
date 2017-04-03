/*

Javascript file for custom css

*/
jQuery(document).ready(function($) {
  var updateCSS = function(){
      $("#custom_css").val( editor.getSession().getValue() );
  }

  $("#save-panobtt-form").submit( updateCSS );
});

var editor = ace.edit("customCss");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/css");

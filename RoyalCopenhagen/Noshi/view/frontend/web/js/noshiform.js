require([
    'jquery'
], function ($) {

  $(document).ready(function() {
    $('input#noshi_require_package_yes').change(function() {
         if (!$(this).is(':checked')) {
          return alert("Are you sure?");
        }
    });

    $('input#noshi_require_package_no').change(function() {
         if (!$(this).is(':checked')) {
          return alert("No?");
        }
    });

  });

});
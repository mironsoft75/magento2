require([
    'jquery'
], function ($) {

  $(document).ready(function() {
    var $purpose = $('select#purpose');
    var $ribbon = $('select#ribbon');
    var $use = $('select#use');
    var $inscription = $('select#inscription');

    var purposeValues = {
        "celebration": {
            "Marriage": "marriage",
            "Celebration": "celebration",
            "Baby gifts": "baby gifts",
            "Greeting": "greeting",
            "Disease recovery": "disease recovery"
        },
        "buddhist memorial": {
            "Condolence": "condolence"
        }
    };

    var inscriptionValues = {
        "marriage": {
            "Kotobuki": 'kotobuki',
            "Family celebration": "family celebration",
            "Holidays": "holidays"
        },
        "celebration": {
            "Holidays": "holidays",
            "Family celebration": "family celebration"
        },
        "baby gifts": {
            "Holidays": "holidays",
            "Family celebration": "family celebration"
        },
        "greeting": {
            "New Year's": "new years",
            "Hazime Ataru": "hazime ataru",
            "Year-end gift": "year-end gift",
            "Thank you gift": "thank you gift",
            "Light gift": "light gift"
        },
        "disease recovery": {
          "Kaiki family celebration": "kaiki family celebration",
          "Kaiki congratulation": "kaiki congratulation"
        },
        "condolence": {
          "Zhi": "zhi"
        }
    };

    $('input#noshi_require_package_no').click();

    $('input#noshi_require_package_no').click(function() {
      if($(this).is(':checked')) {
        disableField($purpose, true);
        disableField($ribbon, true);
      }
    });

    $('input#noshi_require_package_yes').click(function() {
        if($(this).is(':checked')) {
          disableField($purpose, false);
          disableField($ribbon, false);
        }
    });

    $ribbon.change(function() {

      if($(this).val() == 'works') {
        disableField($use, false);
        disableField($inscription, false);
      } else {
        disableField($use, true);
        disableField($inscription, true);
      }
    });

    $purpose.change(function() {
        $use.empty().append(function() {
            var output = '';

            $.each(purposeValues[$purpose.val()], function(key, value) {
                output += '<option value="' + value + '">' + key + '</option>';
            });
            return output;
        });
        $use.change();
    });

    $use.change(function() {

        $inscription.empty().append(function() {
            var output = '';

            $.each(inscriptionValues[$use.val()], function(key, value) {
                output += '<option value="' + value + '">' + key + '</option>';
            });
            return output;
        });
    }).change();

  });

  function disableField(elem, param) {
      elem.prop('disabled', param);
   };


});
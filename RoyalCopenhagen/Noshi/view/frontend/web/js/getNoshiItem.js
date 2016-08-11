define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('royal.getNoshiItem', {
        _create: function () {
            var self = this,
                path = 'noshi/multishipping/pdfView/?noshi_code=',
                baseUrl = self.options.baseUrl,
                serviceUrl = baseUrl + path;

            $('#preview-button').on('click', function () {
                //Read Noshi select
                var noshiCode = $('#noshi-code').val(),
                    reciverName1 = $('#reciver_name1').val(),
                    reciverName2 = $('#reciver_name2').val();

                //Get full path, eg: ?noshi_code=noshi-2&name1=456&name2=5678
                var  fullPath = serviceUrl + noshiCode + '&name1=' + reciverName1 + '&name2=' + reciverName2;
                window.open(fullPath);
               
            });
            $('#noshi-code').on('change', function () {
                var self = this;
                $('#noshi-name').val(self.options[self.selectedIndex].text);
            })
        }
    });
    return $.royal.getNoshiItem;
});
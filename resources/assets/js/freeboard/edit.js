require('../bootstrap');
require('trumbowyg');
require('trumbowyg/dist/langs/ko.min');
require('trumbowyg/dist/plugins/base64/trumbowyg.base64');

$.trumbowyg.svgPath = '/icons.svg';

$(document).ready(function() {
    $('#content').trumbowyg({
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'base64'],
                ico: 'insertImage'
            }
        },
        // Redefine the button pane
        btns: [
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em', 'del'],
            ['link'],
            ['image'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat']
        ],
        lang: 'ko',
        autogrow: true
    });

    $('.attachment-delete').click(function(e) {
        e.preventDefault();

        let url = $(this).attr('href');
        $.ajax({
            method: "DELETE",
            url: url
        })
        .done(function(data, textStatus) {
            location.reload(true);
        });
    });
});
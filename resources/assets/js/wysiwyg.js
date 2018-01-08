require('trumbowyg');
require('trumbowyg/dist/langs/ko.min');
require('trumbowyg/dist/plugins/base64/trumbowyg.base64');

$.trumbowyg.svgPath = '/icons.svg';

$(document).ready(function() {
  if ($('#article').length == 1) {
    $('#article').trumbowyg({
      btnsDef: {
        // Create a new dropdown
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
        ['unorderedList', 'orderedList']
      ],
      lang: 'ko',
      autogrow: true
    });
  }
});
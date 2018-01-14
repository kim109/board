require('trumbowyg')
require('trumbowyg/dist/langs/ko.min')
require('trumbowyg/dist/plugins/upload/trumbowyg.upload')
require('trumbowyg/dist/plugins/noembed/trumbowyg.noembed')

$.trumbowyg.svgPath = '/icons.svg'
jQuery.trumbowyg.langs.ko.upload = '"업로드'
jQuery.trumbowyg.langs.ko.file = '파일'
jQuery.trumbowyg.langs.ko.noembed = '비디오 링크'

$(document).ready(function() {
  if ($('#article').length == 1) {
    $('#article').trumbowyg({
      btnsDef: {
        // Create a new dropdown
        image: {
          dropdown: ['insertImage', 'upload'],
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
        ['noembed'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList']
      ],
      plugins: {
        upload: {
          serverPath: '/attachments',
          fileFieldName: 'file',
          data: [{name: 'type', value: 'artwork'}],
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          urlPropertyName: 'link'
        }
      },
      lang: 'ko',
      autogrow: true
    })
  }
});
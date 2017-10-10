require('../bootstrap');
require('../wysiwyg');
window.Dropzone = require('dropzone');
Dropzone.autoDiscover = false;

$(document).ready(function() {
    var myDropzone = new Dropzone("#attachment", {
        url: "/attachments",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFilesize: 2
    });

    myDropzone.on('success', (file, data) => {
        $("<input>", {
            type: "hidden",
            name: "attachments[]",
            class: "attachments",
            value: data.id
        }).appendTo($('form#fm'));
    });
});
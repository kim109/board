require('../bootstrap');
require('../wysiwyg');
require('dropzone');

$(document).ready(function() {

    var myDropzone = $("#attachment").dropzone({
        url: "/attachments",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    console.log(myDropzone);
    myDropzone.on("sending", function(file, xhr, formData) {
        // Will send the filesize along with the file as POST data.
        console.log('##');
      });

    myDropzone.on("success", function(file, data) {
        console.log(data);
        console.log(file);
        // $("<input>", {
        //     type: "hidden",
        //     name: "attachments[]",
        //     class: "attachments",
        //     value: data.id
        // })
    });
});
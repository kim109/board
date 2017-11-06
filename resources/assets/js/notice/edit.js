require('../bootstrap');
require('../wysiwyg');
window.Dropzone = require('dropzone');
Dropzone.autoDiscover = false;

$(document).ready(function() {
    let myDropzone = new Dropzone("#attachment", {
        url: "/attachments",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        params: {
            'type': 'notice',
            'article_id': article_id
        },
        maxFiles: 3,
        maxFilesize: 5,
        addRemoveLinks: true,
        dictRemoveFile: '삭제',
        init: function() {
            var self = this;
            attachments.forEach(function (attachment) {
                self.emit("addedfile", attachment);

                if (/^image/i.test(attachment.type)) {
                    self.emit("thumbnail", attachment, "/thumbnail/"+attachment._id+"?w=120&h=120");
                }

                self.emit("complete", attachment);
                self.files.push(attachment);
            });
        }
    });

    myDropzone.on('success', function (file, data) {
        file._id = data.id;
    });

    myDropzone.on('maxfilesexceeded', function (file) {
        alert ("최대 3개의 파일을 첨부할수 있습니다.");
        this.removeFile(file);
    });

    myDropzone.on('removedfile', function (file) {
        console.log(myDropzone);
        $.ajax({
            method: "DELETE",
            url: '/attachments/'+file._id
        });
    });
});
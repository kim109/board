require('../bootstrap');
require('../wysiwyg');
window.Dropzone = require('dropzone');
Dropzone.autoDiscover = false;

$(document).ready(function() {
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

    let myDropzone = new Dropzone("#attachment", {
        url: "/attachments",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        params: {
            'type': 'App\\Freeboard',
            'article_id': article_id
        },
        maxFiles: 3,
        maxFilesize: 5,
        addRemoveLinks: true,
        dictRemoveFile: '삭제',
        init: function() {
            var self = this;
            attachments.forEach(function (attachment) {
                if (/image\/*/i.test(attachment.mime)) {
                    self.emit("thumbnail", attachment, "/image/url");
                } else {
                    self.emit("addedfile", attachment);
                }
                self.emit("complete", attachment);
            });
        }
    });

    myDropzone.on('maxfilesexceeded', function (file) {
        alert ("최대 3개의 파일을 첨부할수 있습니다.");
        this.removeFile(file);
    });

    myDropzone.on('removedfile', function (file, data) {
        $('input[type=hidden][value='+file._id+']').remove();
        $.ajax({
            method: "DELETE",
            url: '/attachments/'+file._id
        });
    });
});
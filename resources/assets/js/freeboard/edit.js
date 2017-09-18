require('../bootstrap');

$(document).ready(function() {
    if ($('#content').length == 1) {
        CKEDITOR.replace('content');
    }

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
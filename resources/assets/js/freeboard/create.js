require('../bootstrap');

$(document).ready(function() {
    if ($('#content').length == 1) {
        CKEDITOR.replace('content');
    }
});
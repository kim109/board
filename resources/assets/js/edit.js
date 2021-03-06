require('./bootstrap')
require('./wysiwyg')

window.Dropzone = require('dropzone')
Dropzone.autoDiscover = false

$(document).ready(function() {
  let param = null;
  let path = window.location.pathname.split("/");
  if (path.length == 4 && path[3] == 'edit') {
    param = {
      'type': path[1],
      'article_id': path[2]
    }
  }

  let myDropzone = new Dropzone("#attachment", {
    url: "/attachments",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    params: param,
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
      })
    }
  })
  .on('success', (file, data) => {
    file._id = data.id
  })
  .on('removedfile', (file) => {
    axios.delete('/attachments/'+file._id)
  })
  .on('maxfilesexceeded', (file) => {
    alert ("최대 3개의 파일을 첨부할수 있습니다.")
    this.removeFile(file)
  })

  $('#fm').submit((event) => {
    return confirm('변경한 내용으로 저장하시겠습니까?')
  })
});
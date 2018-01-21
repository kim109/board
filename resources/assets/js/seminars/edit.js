require('../bootstrap')
require('../wysiwyg')

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

  // 썸네일
  new Dropzone("#thumbnail", {
    url: "/attachments",
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    params: param,
    acceptedFiles: "image/*",
    maxFiles: 1,
    maxFilesize: 5,
    addRemoveLinks: true,
    dictRemoveFile: '삭제',
    init: function() {
      this.emit("addedfile", thumbnail)

      if (/^image/i.test(thumbnail.type)) {
        this.emit("thumbnail", thumbnail, "/thumbnail/"+thumbnail._id+"?w=120&h=120")
      }

      this.emit("complete", thumbnail)
      this.files.push(thumbnail)
    }
  })
  .on('success', (file, data) => {
    file._id = data.id
    $("input[name=thumbnail_id]").val(data.id)
  })
  .on('removedfile', (file, data) => {
    $("input[name=thumbnail_id]").val('')
    axios.delete('/attachments/'+file._id)
  })
  .on('maxfilesexceeded', (file) => {
    alert ("최대 3개의 파일을 첨부할수 있습니다.")
    this.removeFile(file)
  })

  // 첨부파일
  new Dropzone("#attachment", {
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
    if ($("input[name=thumbnail_id]").val().length == 0) {
      alert('썸네일 이미지를 등록해주세요.')
      return false;
    }
    return confirm('변경한 내용으로 저장하시겠습니까?')
  })
});
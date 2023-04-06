const updateStatus = function (id) {
  $.ajax({
    type: 'POST',
    url: 'update-status.php',
    data: { updateId: id },
    dataType: 'html',
    success: function (data) {
      if (data == 1) {
        $('#statusBtn' + id).text('Approve')
      } else if (data == 0) {
        $('#statusBtn' + id).text('Disapprove')
      }
      // change status btn coloe
      if ($('#statusBtn' + id).hasClass('disapprove')) {
        $('#statusBtn' + id).removeClass('disapprove')
        $('#statusBtn' + id).addClass('approve')
      } else if ($('#statusBtn' + id).hasClass('approve')) {
        $('#statusBtn' + id).removeClass('approve')
        $('#statusBtn' + id).addClass('disapprove')
      }
    }
  })
}

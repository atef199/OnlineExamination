$(document).ready(function () {
    ajaxcsrf();

    $('#btncek').on('click', function () {
        var token = $('#token').val();
        var idtest = $(this).data('id');
        if (token === '') {
            Swal('Failed', 'Token must be filled', 'error');
        } else {
            var key = $('#exam_id').data('key');
            $.ajax({
                url: base_url + 'test/cektoken/',
                type: 'POST',
                data: {
                    exam_id: idtest,
                    token: token
                },
                cache: false,
                success: function (result) {
                    Swal({
                        "type": result.status ? "success" : "error",
                        "title": result.status ? "Successful" : "Failed",
                        "text": result.status ? "True Token" : "Incorrect Token"
                    }).then((data) => {
                        if(result.status){
                            location.href = base_url + 'test/?key=' + key;
                        }
                    });
                }
            });
        }
    });

    var time = $('.countdown');
    if (time.length) {
        countdown(time.data('time'));
    }
});
$(document).ready(function () {
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('#formtest input, #formtest select').on('change', function () {
        $(this).closest('.form-group').eq(0).removeClass('has-error');
        $(this).nextAll('.help-block').eq(0).text('');
    });

    $('#formtest').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Process...');

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').html('<i class="fa fa-save"></i> Save');

                if (data.status) {
                    Swal({
                        "title": "Success",
                        "type": "success",
                        "text": "Data saved successfully"
                    }).then(result => {
                        window.location = "master";
                    });
                } else {
                    if (data.errors) {
                        $.each(data.errors, function (key, val) {
                            $('[name="' + key + '"]').closest('.form-group').eq(0).addClass('has-error');
                            $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(val);
                            if (val === '') {
                                $('[name="' + key + '"]').closest('.form-group').eq(0).removeClass('has-error');
                                $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                            }
                        });
                    }
                }
            }
        });
    });
});
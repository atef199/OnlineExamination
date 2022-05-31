function load_level(id) {
    $('#level').find('option').not(':first').remove();

    $.ajax({
        url: base_url+'level/level_by_department/' + id,
        type: 'GET',
        success: function (data) {
            var option = [];
            for (let i = 0; i < data.length; i++) {
                option.push({
                    id: data[i].level_id,
                    text: data[i].level_name
                });
            }
            $('#level').select2({
                data: option
            })
        }
    });
}

$(document).ready(function () {

    ajaxcsrf();

    // Load level By department
    $('#department').on('change', function () {
        load_level($(this).val());
    });


    $('form#student input, form#student select').on('change', function () {
        $(this).closest('.form-group').removeClass('has-error has-success');
        $(this).nextAll('.help-block').eq(0).text('');
    });

    $('[name="jenis_kelamin"]').on('change', function () {
        $(this).parent().nextAll('.help-block').eq(0).text('');
    });

    $('form#student').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Wait...');

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').text('Save');
                if (data.status) {
                    Swal({
                        "title": "Success",
                        "text": "Data Saved Successfully",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'student';
                        }
                    });
                } else {
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(value);
                        $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
                        if (value == '') {
                            $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
                            $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
                        }
                    });
                }
            }
        });
    });
});
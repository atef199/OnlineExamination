function load_department() {
    $('#department').find('option').not(':first').remove();
    if (typeof sign_up !== 'undefined') {
            $.getJSON(base_url+'SignUp/load_department', function (data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].dep_id,
                text: data[i].dep_name
            });
        }
        $('#department').select2({
            data: option
        })
    });
    }
    else {
            $.getJSON(base_url+'department/load_department', function (data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].dep_id,
                text: data[i].dep_name
            });
        }
        $('#department').select2({
            data: option
        })
    });
    }

}

function load_level(id) {
    $('#level').find('option').not(':first').remove();
    if (typeof sign_up !== 'undefined') {

        $.getJSON(base_url+'SignUp/level_by_department/' + id, function (data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].level_id,
                text: data[i].level_name
            });
        }
        $('#level').select2({
            data: option
        });
    });
    }
    else {
        $.getJSON(base_url+'level/level_by_department/' + id, function (data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].level_id,
                text: data[i].level_name
            });
        }
        $('#level').select2({
            data: option
        });
    });
    }

}

$(document).ready(function () {

    ajaxcsrf();

    // Load department
    load_department();

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
                    if (typeof sign_up !== 'undefined') {
                    Swal({
                        "title": "Success",
                        "text": "Wating Admin approval! PID will be used as initial password.",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'student';
                        }
                    });
                    }
                else {
                        Swal({
                        "title": "Success",
                        "text": "Account Created Successfully",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'student';
                        }
                    });
                } 
                }
                else {
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
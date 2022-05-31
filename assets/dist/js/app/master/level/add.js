banyak = Number(banyak);
$(document).ready(function () {
    if (banyak < 1 || banyak > 50) {
        alert('Maksimum input 50');
        window.location = base_url+"level";
    }

    $('form#level input, form#level select').on('change', function () {
        $(this).closest('.form-group').removeClass('has-error');
        $(this).next().next().text('');
    });

    $('form#level').on('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var btn = $('#submit');
        btn.attr('disabled', 'disabled').text('Wait...');

        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            method: 'POST',
            success: function (data) {
                btn.removeAttr('disabled').text('Save');
                //console.log(data);
                if (data.status) {
                    Swal({
                        "title": "Success",
                        "text": "Data Saved Successfully",
                        "type": "success"
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = base_url+'level';
                        }
                    });
                } else {
                    var j;
                    for (let i = 0; i <= data.errors.length; i++) {
                        $.each(data.errors[i], function (key, val) {
                            j = $('[name="' + key + '"]');
                            j.closest('.form-group').addClass('has-error');
                            j.next().next().text(val);
                            if (val == '') {
                                j.closest('.form-group').removeClass('has-error');
                                j.next().next().text('');
                            }
                        });
                    }
                }
            }
        });
    });
});
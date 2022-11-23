$(function() {
    $('#send-message').click(function() {
        let message = '';

        $('#send-message').attr('disabled', true);

        $('#contact-form .required').each(function() {
            if ($(this).val().trim() === '') {
                if ($(this).not('.error')) {
                    $(this).addClass('error');

                    message += '<p>Поле "' + $("label[for='" + $(this).attr('id') + "']").text().trim() + '" обязательно для заполнения</p>';
                }
            } else if ($(this).attr("type") !== undefined && $(this).attr("type") == 'email') {
                if (!isEmail($(this).val().trim()) && $(this).not('.error')) {
                    $(this).addClass('error');
                    message += '<p>Поле "' + $("label[for='" + $(this).attr('id') + "']").text().trim() + '" должно содержать e-mail адрес</p>';
                }
            } else if ($(this).hasClass('error')) {
                $(this).removeClass('error');
            }
        });

        if (!$('#policy-agreements:checked').length) {
            $('#policy-agreements').addClass('error');
            message += 'Необходимо дать согласие на обработку персональных данных';
        } else  if ($('#policy-agreements').hasClass('error')) {
            $('#policy-agreements').removeClass('error');
        }

        if ($('#contact-form .error').length) {
            message = '<div class="error">' + message + '</div>';
        } else {
            let formData = $('#contact-form').serialize();
            $.ajax({
                type: 'POST',
                url: 'email.php',
                data: formData,
                datatype: 'json',
                async: false,
                success: function (response) {
                    $.each(response.message, function (i, msg) {
                        message += '<p>' + msg + '</p>';
                    });
                    if (response.success === true) {
                        message = '<div class="info">' + message + '</div>';
                        $('#contact-form').closest('form').find("input[type=text], input[type=email], textarea").val('');
                    } else {
                        message = '<div class="error">' + response + '</div>';
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    message = '<div class="error">' + thrownError + '</div>';
                }
              });

        }

        $('#send-message').removeAttr('disabled');
        $('#popup-content').append(message);
        $('#popup-wrapper').fadeIn(500);
    });

    $('#popup-wrapper').click(function(e) {
        if (!$(e.target).closest("#popup-content").length) {
            $('#popup-wrapper').fadeOut(500, function(){
                $('#popup-content :nth-child(n + 2)').remove();
            });
        }
    });
    $(document).on('click', '.popup-close', function() {
        $('#popup-wrapper').fadeOut(500, function(){
            $('#popup-content :nth-child(n + 2)').remove();
        });
    });
    $(document).on('click', '.popup-items p', function() {
        $('#popup-wrapper').fadeOut(500, function(){
            localStorage.selectedCity = $(this).data('key');
            $('#location span').html(localStorage.selectedCity);
            $('#popup-content :nth-child(n + 2)').remove();
        });
    });

    $(document).on('focus', '.error', function() {
        $(this).removeClass('error');
    });

    console.log('JQuery contact form is done!'); 
});

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
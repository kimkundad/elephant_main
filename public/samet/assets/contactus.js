function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(email) === false) {
        notificationEmail('Invalid email');
        $('#email').addClass('notificationAtInput');
        // $('#email').focus();
        return false

    } else {
        $('#email').removeClass('notificationAtInput');
        return true
    }

}

$(document).ready(function () {
    $('#contact input').focusout(function (e) {
        var values = $(this);
        var idInput = $(this).attr('id');

        if (idInput === 'email') {
            if (values.val() != '') {
                validateEmail(values.val())
            } else {
                values.addClass('notificationAtInput')
            }

        } else {
            if (values.val() != '') {
                values.removeClass('notificationAtInput')
            } else {
                values.addClass('notificationAtInput')
            }
            // $('#contact textarea').focusout(function (e) {
            //     var values = $(this);
            //     if (values.val() != '') {
            //         values.removeClass('notificationAtInput');
            //     } else {
            //         values.addClass('notificationAtInput')
            //     }
            // });
        }
    });
});



function Form_Validator() {
    var input = $('#contact input');
    // var textarea = $('#contact textarea');

    // if (textarea.val() === '') {
    //     textarea.addClass('notificationAtInput')
    // } else {
    //     textarea.removeClass('notificationAtInput')
    // }

    for (var i = 0; i < input.length; i++) {
        var name = input.eq(i);
        if (name.val() === '') {
            name.addClass('notificationAtInput')
        }

    }
    return Form_Validator2()
}


function Form_Validator2() {
    var result = true;
    var input = $('#contact input');

    for (var i = 0; i < input.length; i++) {
        var name = input.eq(i);
        if (name.val() === '') {
            var result = false;
            break;
        }

    }
    return result;
}

function closeNotificationEmail() {
    $('#notificationEmail').slideUp('fast')
}

function notificationEmail(message) {
    $('#notificationEmail').html('Please fill out ' + message);
    $('#notificationEmail').slideDown('fast');
    window.setTimeout(closeNotificationEmail, 5000);
}



const url = {
    host : 'https://cms.hoteliers.guru/all-teamweb/module/SendMail/App/View/re-check.php',
    localhost : 'App/View/re-check.php'
};

function checkContact(idHotel,name_hotel) {
    var checkForm = Form_Validator();
    if (checkForm === true) {
        if ($('#email').val() !== '') {
            validateEmail($('#email').val());
            if (validateEmail($('#email').val()) === true) {
                var codeHotel = idHotel;
                var name = $('[name="name"]').val();
                var email = $('[name="email"]').val();
                var phone = $('[name="phone"]').val();
                var country = $('[name="country"]').val();
                var subject = $('[name="subject"]').val();
                var message = $('[name="message"]').val();
                reCheck =  window.open(
                    url.host+'?hotel='+codeHotel+'&name_hotel='+name_hotel+'&name='+name+'&email='+email+'&phone='+phone+'&country='+country+'&subject='+subject+'&message='+message,
                    'popupWindow',
                    'width=570, height=700,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=no'
                );
            }
        }
    }

}

function buttonSubmit() {
    var checkForm = Form_Validator();
    if (checkForm === true) {
        if ($('#email').val() !== '') {
            validateEmail($('#email').val());
            if (validateEmail($('#email').val()) === true) {
                $('#contact').submit()
            }
        }
    }

}
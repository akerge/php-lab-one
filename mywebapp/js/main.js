$(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/mywebapp/application/registerHandler.php',
            data: $('form').serialize(),
            success: function (response) {
                console.log(response);
                alert('Registration was successful');
            }
        });
    });
});

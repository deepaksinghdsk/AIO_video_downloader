var send = $("#send");
send.empty();
send.html('<i class="fas fa-circle-notch fa-spin"></i>');
send.disabled = true;
grecaptcha.ready(function () {
    recaptcha_execute();
});
window.recaptcha_execute = recaptcha_execute;
function recaptcha_execute() {
    var send = $("#send");
    send.empty();
    send.html('<i class="fas fa-circle-notch fa-spin"></i>');
    send.disabled = true;
    grecaptcha.execute('%s', {action: 'homepage'}).then(function (token) {
        document.getElementById('token').value = token;
        send.html('<i class="fas fa-download"></i>');
        send.disabled = false;
    });
}
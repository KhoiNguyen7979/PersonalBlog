document.querySelector("form").addEventListener("submit", function(event) {
    var password = document.querySelector('input[name="password"]').value;
    var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
    if (password !== confirmPassword) {
        event.preventDefault();
        if (window.showToast) {
            showToast('Passwords do not match. Please try again!', 'error');
        } else {
            alert("Passwords do not match. Please try again!");
        }
    }
});
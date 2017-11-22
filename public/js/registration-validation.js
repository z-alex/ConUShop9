$(document).ready(function () {

    var inputs = document.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].oninput = function (event) {
            event.target.setCustomValidity('');
        };
    }

    // Apply client-side regex validation dynamically on the DOM objects.
    var firstName = document.getElementById("firstName");
    firstName.setAttribute("pattern", "^[A-z]*$");
    firstName.oninvalid = function (event) {
        event.target.setCustomValidity("First name should only contain letters ie:John.");
    };
    firstName.required = true;

    var lastName = document.getElementById("lastName");
    lastName.setAttribute("pattern", "^[A-z]*$"); //.*[A-z].* .... [A-z]+\d*
    lastName.oninvalid = function (event) {
        event.target.setCustomValidity("Last name should only contain letters ie:Doe");
    };
    lastName.required = true;

    var email = document.getElementById("email");
    email.setAttribute("pattern", "[^[^%$#!^&*()+]+@[a-zA-Z]*(.com|.ca)$");
    email.oninvalid = function (event) {
        event.target.setCustomValidity("Email must be valid ie:JohnDoe@hotmail.com");
    };
    email.required = true;

    var password = document.getElementById("password");
    password.setAttribute("pattern", "^\\S{6,16}");
    password.oninvalid = function (event) {
        event.target.setCustomValidity("Password must be of length between 6 to 16 and must not contain white spaces.");
    };
    password.required = true;

    var phone = document.getElementById("phone");
    phone.setAttribute("pattern", "^(\\d){3}-?(\\d){3}-?(\\d){4}$");
    phone.oninvalid = function (event) {
        event.target.setCustomValidity("Phone number must contain 10 digits or be in the form XXX-XXX-XXXX");
    };
    phone.required = true;

    var physicalAddress = document.getElementById("physicalAddress");
    physicalAddress.setAttribute("pattern", "^[A-z,0-9,',\\-,.,\\s]*$");
    physicalAddress.oninvalid = function (event) {
        event.target.setCustomValidity("Address must not contain symbols such as @#$%^*");
    };
    physicalAddress.required = true;


});

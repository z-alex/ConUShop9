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
    email.setAttribute("pattern", "[A-z,1-9]*@[A-z]*(.com|.ca)");
    email.oninvalid = function (event) {
        event.target.setCustomValidity("Email must be valid ie:JohnDoe@hotmail.com");
    };
    email.required = true;

    var password = document.getElementById("password");
    password.setAttribute("pattern", "^[A-z,1-9]*$");
    password.oninvalid = function (event) {
        event.target.setCustomValidity("Password must not contail special characters.");
    };
    password.required = true;

    var phone = document.getElementById("phone");
    phone.setAttribute("pattern", "^(\\d){3}-(\\d){3}-(\\d){4}$");
    phone.oninvalid = function (event) {
        event.target.setCustomValidity("Phone number must be of format XXX-XXX-XXXX");
    };
    phone.required = true;

    var physicalAddress = document.getElementById("physicalAddress");
    physicalAddress.setAttribute("pattern", "^[0-9]{1,5}\\s[A-z][A-z]*\\s[A-Z][0-9][A-Z]-[0-9][A-Z][0-9]$");
    physicalAddress.oninvalid = function (event) {
        event.target.setCustomValidity("Address must be of format ie: 111 Street H1Z-4C9");
    };
    physicalAddress.required = true;


});

$(document).ready(function () {
    $('#itemSelect').change(function () {
        var validation = [];

        // Store validation inside an associative array. Each key is the DOM input ID. Each
        // key maps to an array containing a Pattern at the 0th index, and the error message at the 1st index.
        validation["quantity"] = ["^[0-9]+$", "Please put in a quantity"];
        validation["brandName"] = ["^([A-z]+\\s*[A-z0-9]*)$", "Brand must have a valid name."];
        validation["dimension"] = ["^([1-9]+)\\s*x\\s*([1-9]+)\\s*x\\s*([1-9]+)$", "Dimension must be of the format width x height x depth"];
        validation["weight"] = ["^(\\d+)(\\.(\\d{1,2}))?$", "Weight must not contain more than 2 decimals."];
        validation["processorType"] = ["^(([A-z]+)(\\s*)([A-z1-9]*))$", "Processor must have a name."];
        validation["ramSize"] = ["^[1-9][0-9]*$", "RAM size must be greater than 0."];
        validation["cpuCores"] = ["^[1-9][0-9]*$", "CPU cores must be greater than 0."];
        validation["hdSize"] = ["^(\\d+)(\\.(\\d{1,2}))?$", "Hard drive size must be greater than 0."];
        validation["batteryInfo"] = ["^([1-9]+)$", "Battery hours greater than 0."];
        validation["modelNumber"] = ["^[A-Z0-9]{7,10}$", "Model Number must contain only upper-case letters, between 7 to 10 symbols."];
        validation["price"] = ["^(\\d+)(\\.(\\d{1,2}))?$", "Price must have positive value."];
        validation["displaySize"] = ["^(\\d+)(\\.(\\d{1,2}))?$", "Display Size must be positive value."];
        validation["os"] = ["^([A-z]+\\s*[A-z0-9]*)$", "Operating System (OS) must have a valid name."];


        for (let key in validation) {
            // Get the element from the DOM.
            var element = document.getElementById(key);

            // Make sure the input element exists on the page.
            if (element != null) {

                // Denote that this input is required.
                element.required = true;

                // Get the pattern from the dictionary.
                element.setAttribute("pattern", validation[key][0]);

                // Set the error message from the dictionary.
                element.oninvalid = function (event) {
                    event.target.setCustomValidity(validation[key][1]);
                }

                // Make sure we can re-try the validation when new input is entered.
                element.oninput = function (event) {
                    event.target.setCustomValidity('');
                }
            }
        }

        return;
    });
});
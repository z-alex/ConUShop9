$(document).ready(function () {

   var validation = [];

        // Store validation inside an associative array. Each key is the DOM input ID. Each
        // key maps to an array containing a Pattern at the 0th index, and the error message at the 1st index.
        validation["quantity"] = ["^([1-5][0-9]|[0-9])$", "The quantity must be a positive integer less than 51"];
        validation["brandName"] = ["^[_A-z0-9]*((-|\\s)*[_A-z0-9])*$", "Brand cannot contain symbols"];
        validation["dimension"] = ["^\\s*\\d+\\.?\\d*\\s*x\\s*\\d+\\.?\\d*\\s*x\\s*\\d+\\.?\\d*\\s*$", "Dimension must be of the format widthxheightxdepth"];
        validation["weight"] = ["^\\d+\\.?\\d*$", "Weight must be a positive number greater than 0"];
        validation["processorType"] = ["^[_A-z0-9]*((-|\\s)*[_A-z0-9])*$", "Cannot contain symbols"];
        validation["ramSize"] = ["^[1-9][0-9]*$", "RAM size must be a positive integer greater than 0"];
        validation["cpuCores"] = ["^[1-9][0-9]*$", "CPU cores must be greater than 0."];
        validation["hdSize"] = ["^[1-9][0-9]*$", "Hard drive size must be an integer greater than 0."];
        validation["batteryInfo"] = [".*\\S.*", "Battery hours greater than 0."];
        validation["modelNumber"] = ["^[a-zA-Z,0-9]+$", "Model Number not contain any spaces or symbols"];
        validation["price"] = ["^[1-9]\\d*\\.?\\d{0,2}$", "Price must have positive value with 2 decimals maximum"];
        validation["displaySize"] = ["^\\d+\\.?\\d*$", "Display Size must be positive value."];
        validation["os"] = ["^[_A-z0-9]*((-|\\s)*[_A-z0-9])*$", "Operating System (OS) cannot contain any symbols"];


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

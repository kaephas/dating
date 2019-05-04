/*
 * Kaephas Kain
 * 4-17-2019
 * profile.js
 *
 * Fills select box with all 50 states
 */
window.onload = function() {
  document.getElementById("state").innerHTML = states();
};

let stateNames = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware",
                    "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky",
                    "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi",
                    "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico",
                    "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania",
                    "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont",
                    "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];

function states() {
    let output = "";
    for (let i = 0; i < stateNames.length; i++) {
        output += "<option>";
        // default selection WASHINGTON = > move stickiness (and loop?) to php/f3
        // if(stateNames[i] === "Washington") {
        //     output += " selected>";
        // } else {
        //     output += ">";
        // }
        // all upper case to match example
        output += stateNames[i].toUpperCase() + "</option>";
    }
    return output;
}
window.onload = function() {
    document.getElementById('indoor').innerHTML = indoor();
    document.getElementById('outdoor').innerHTML = outdoor();
};

function indoor() {
    let indoorInterests = [
        "tv", "movies", "cooking", "board games", "puzzles", "reading", "playing cards", "video games"
    ];

    let output = "";
    indoorInterests.forEach(function(interest) {
        output += '<div class="col-md-3 col-sm-6">' +
            '<input type="checkbox" name="indoor[]" id="' + interest + '" value="' + interest + '">' +
            '<label for="' + interest + '" class="checkbox">' + interest + '</label>' +
            '</div>';
    });

    return output;
}

function outdoor() {
    let outdoorInterests = [
        "hiking", "biking", "swimming", "collecting", "walking", "climbing"
    ];

    let output = "";
    outdoorInterests.forEach(function(interest) {
        output += '<div class="col-md-3 col-sm-6">' +
            '<input type="checkbox" name="outdoor[]" id="' + interest + '" value="' + interest + '">' +
            '<label for="' + interest + '" class="checkbox">' + interest + '</label>' +
            '</div>';
    });

    return output;
}
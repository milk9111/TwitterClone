/**
 * On page load reset the values
 */
$(function() {
    $("#tweetInput").val("");
    $("#charsRemaining").text(140);
    getTweetsForUser("connor");
});


/**
 * This is called on every key up for the tweetInput field.
 */
function printCharsRemaining() {
    let statusLength = 140;
    let $tweetInput = $("#tweetInput");
    let $charsRemaining = $("#charsRemaining");
    if ($tweetInput.val() !== "") {
        statusLength = 140 - ($tweetInput.val().length);
    }
    $charsRemaining.text(statusLength);
}


function getTweetsForUser(username) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            // Typical action to be performed when the document is ready:
            $("#tweets").innerHTML = xhttp.responseText;
        }
    };
    xhttp.open("GET", "../php/gettweetsforuser.php?user="+username, true);
    xhttp.send();
}
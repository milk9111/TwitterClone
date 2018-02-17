/**
 * On page load reset the values
 */
$(function() {
    console.log('loading page');
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


/**
 * Retrieves all of the tweets for the given user and prints them to the
 * #tweets element list.
 *
 * @param username The String username used to get the tweets
 */
function getTweetsForUser(username) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            $("#tweets").html(xhttp.responseText);
        }
    };
    xhttp.open("GET", "../php/get-tweets.php?user="+username, true);
    xhttp.send();
}


function addNewTweetForUser(username) {
    let $tweetInput = $("#tweetInput");
    if ($tweetInput.val() === "") {
        alert ('Cannot submit an empty status');
        return;
    }
    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            getTweetsForUser(username);
        }
    };

    xhttp.open("GET", "../php/add-new-tweet.php?user="+username+"&text="+$tweetInput.val(), true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}
/**
 * On page load reset the values
 */
$(function() {
    $("#tweetInput").val("");
    $("#charsRemaining").text(140);

    if (document.getElementById("loading") != null) {
        document.getElementById("loading").style.display = "none";
    }

    let cookie = document.cookie;
    let params = cookie.split(";");
    let uname = params[0];

    let $uname = $("#uname");
    $uname.text(uname.substring(uname.indexOf("=")+1, uname.length));
    if (window.location.href.includes("feed.html")) {
        getTweetsForUser($uname.text());
    }
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
            $("#tweetInput").val("");
            $("#charsRemaining").text(140);
            document.getElementById("loading").style.display = "none";
        }
    };
    xhttp.open("GET", "../php/get-tweets.php?user="+username, true);
    xhttp.send();
    document.getElementById("loading").style.display = "inline-block";
}


/**
 * Adds the tweet to the given user's log of tweets.
 *
 * @param username The String username used to save the tweets
 */
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
            $("#submit").disabled = false;
        }
    };

    xhttp.open("GET", "../php/add-new-tweet.php?user="+username+"&text="+$tweetInput.val(), true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
    $("#submit").disabled = true;
}


/**
 * This function first verifies that neither of the sign in fields are empty.
 * After, it makes a POST call to the signin.php that will make sure the password
 * and username match.
 *
 * The response is a JSON object with a status. Status of 200 means the login was
 * successful and to move to the feed page, otherwise alert the user that one of
 * the fields is incorrect.
 */
function signin() {
    let username = $("#username").val();
    let password = $("#password").val();

    if (username === "" || password === "") {
        alert ("Username and Password cannot be empty!");
    } else if (username.includes(" ") || password.includes(" ")) {
        alert ("Username and Password cannot have spaces!");
    } else {
        let xhttp = new XMLHttpRequest();

        xhttp.open("POST", "./php/signin.php", true);
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let response = JSON.parse(xhttp.responseText);
                if (response['status'] === 200) {
                    window.location.href = './html/feed.html';
                    document.cookie="username="+username+";";
                } else {
                    alert("Username and/or Password are incorrect!");
                    document.getElementById("loading").style.display = 'none';
                    $("#submit").disabled = false;
                }
            }
        };
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("username="+username+"&password="+password);
        document.getElementById("loading").style.display = 'inline-block';
        $("#submit").disabled = true;
    }
}


/**
 * This does the registration for a new user. After verifying that the
 * input fields are correct, it will make a POST call to register.php
 * in order to first make sure the username is not already taken, then if it
 * is successful, to make a new user. It will return a status code corresponding
 * to the result of the action. 200 is good to go, anything in the 100's was
 * a failure.
 */
function register() {
    let username = $("#username").val();
    let password = $("#password").val();
    let passwordVerify = $("#passwordVerify").val();

    let errorMsg = verifyRegisterFields(username, password, passwordVerify);

    if (errorMsg === "") {
        let xhttp = new XMLHttpRequest();

        xhttp.open("POST", "../php/register.php", true);
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let response = JSON.parse(xhttp.responseText);
                if (response['status'] === 200) {
                    window.location.href = 'feed.html';
                    document.cookie="username="+username+";";
                } else {
                    if (response['status'] === 100) {
                        alert("Username already taken!");
                    } else if (response['status'] === 125) {
                        alert("Back end failed to query users");
                    } else {
                        alert("Back end failed to insert new user");
                    }
                    document.getElementById("loading").style.display = 'none';
                    $("#submit").disabled = false;
                }
            }
        };
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("username="+username+"&password="+password);
        document.getElementById("loading").style.display = 'inline-block';
        $("#submit").disabled = true;
    } else {
        alert (errorMsg);
    }
}


/**
 * Verifies that the fields follow the input standards. None of them can
 * be empty, they must be between 6 and 25 characters long, they cannot
 * have a space, and the two password fields must match.
 *
 * @param username
 * @param password
 * @param passwordVerify
 * @returns {string} The error message to print. If it's empty, then there
 * was no issues and the registration is good to go.
 */
function verifyRegisterFields (username, password, passwordVerify) {
    let errorMsg = "";
    if (username === "") {
        errorMsg += "Username cannot be empty\n";
    } else if (username.length < 6 || username.length > 25) {
        errorMsg += "Username must be between 6 and 25 characters\n";
    } else if (username.includes(" ")) {
        errorMsg += "Username cannot have a space\n";
    }

    if (password === "") {
        errorMsg += "Password cannot be empty\n";
    } else if (password.length < 6 || password.length > 25) {
        errorMsg += "Password must be between 6 and 25 characters\n";
    } else if (password.includes(" ")) {
        errorMsg += "Password cannot have a space\n";
    }

    if (passwordVerify === "") {
        errorMsg += "Password verify cannot be empty\n";
    } else if (passwordVerify.length < 6 || passwordVerify.length > 25) {
        errorMsg += "Password verify must be between 6 and 25 characters\n";
    } else if (passwordVerify !== password) {
        errorMsg += "Both Password fields must match\n";
    }

    return errorMsg;
}


/**
 * Signs the current user out by clearing the cookie and moving the window
 * back to the sign in page.
 */
function signOut() {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    window.location.href="../index.html";
}

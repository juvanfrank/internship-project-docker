$(document).ready(function () {
    let sessionToken = localStorage.getItem("session_token");

    if (!sessionToken) {
        window.location.href = "/login.html";
        return;
    }

    $.ajax({
        url: "/php/profile.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ session_token: sessionToken }),
        success: function (response) {
            let res;
            try {
                res = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) {
                alert("Invalid response from server");
                console.error("Parse error:", e, "Response:", response);
                return;
            }

            if (res.status === "success" && res.data) {
                $("#name").val(res.data.name);
                $("#email").val(res.data.email);
                $("#age").val(res.data.age);
                $("#dob").val(res.data.dob);
                $("#contact").val(res.data.contact);
            } else {
                alert(res.message || "User not found!");
                window.location.href = "/login.html";
            }
        },
        error: function (xhr, status, error) {
            alert("Server error loading profile!");
            console.error("AJAX Error:", status, error, "Response:", xhr.responseText);
        }
    });
});

function logoutUser() {
    let sessionToken = localStorage.getItem("session_token");
    
    // Call logout endpoint to delete session from Redis
    if (sessionToken) {
        $.ajax({
            url: "/php/logout.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ session_token: sessionToken }),
            success: function (response) {
                // Clear localStorage and redirect regardless of response
                localStorage.removeItem("session_token");
                localStorage.removeItem("user_email");
                window.location.href = "/login.html";
            },
            error: function () {
                // Clear localStorage and redirect even on error
                localStorage.removeItem("session_token");
                localStorage.removeItem("user_email");
                window.location.href = "/login.html";
            }
        });
    } else {
        // If no session token, just clear localStorage and redirect
        localStorage.removeItem("session_token");
        localStorage.removeItem("user_email");
        window.location.href = "/login.html";
    }
}

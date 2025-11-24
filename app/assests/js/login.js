function loginUser() {
    console.log("loginUser() called");
    
    let email = $("#email").val().trim();
    let password = $("#password").val().trim();

    if (!email || !password) {
        $("#msg").removeClass("alert-success").addClass("alert-danger").text("Both fields are required!").show();
        return;
    }

    // Clear previous messages
    $("#msg").hide().text("");
    
    // Disable button to prevent double submission
    $("button[onclick='loginUser()']").prop("disabled", true).text("Logging in...");

    $.ajax({
        url: "/php/login.php",
        type: "POST",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify({
            email: email,
            password: password
        }),
        success: function (response) {
            console.log("Login response:", response);
            
            // Re-enable button
            $("button[onclick='loginUser()']").prop("disabled", false).text("Login");
            
            if (response && response.status === "success") {
                console.log("Login successful, saving session token and redirecting...");
                // Save session token in localStorage
                if (response.session_token) {
                    localStorage.setItem("session_token", response.session_token);
                    localStorage.setItem("user_email", email);
                }
                
                $("#msg").removeClass("alert-danger").addClass("alert-success").text("Login successful! Redirecting...").show();
                setTimeout(function() {
                    window.location.href = "/profile.html";
                }, 500);
            } else {
                console.log("Login failed:", response ? response.message : "Unknown error");
                $("#msg").removeClass("alert-success").addClass("alert-danger").text(response ? (response.message || "Login failed") : "Unknown error occurred").show();
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.error("Response text:", xhr.responseText);
            
            // Re-enable button
            $("button[onclick='loginUser()']").prop("disabled", false).text("Login");
            
            let errorMsg = "Server error! Try again.";
            try {
                let errorResponse = JSON.parse(xhr.responseText);
                if (errorResponse && errorResponse.message) {
                    errorMsg = errorResponse.message;
                }
            } catch (e) {
                // If response is not JSON, use default message
            }
            $("#msg").removeClass("alert-success").addClass("alert-danger").text(errorMsg).show();
        }
    });
}

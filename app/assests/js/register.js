function registerUser() {
    console.log("registerUser() called");
    
    let name = $("#name").val().trim();
    let email = $("#email").val().trim();
    let password = $("#password").val().trim();
    let age = $("#age").val().trim();
    let dob = $("#dob").val().trim();
    let contact = $("#contact").val().trim();

    console.log("Form data:", { name, email, password, age, dob, contact });

    if (!name || !email || !password || !age || !dob || !contact) {
        $("#msg").removeClass("alert-success").addClass("alert-danger").text("All fields are required!").show();
        return;
    }

    // Clear previous messages
    $("#msg").hide().text("");
    
    // Disable button to prevent double submission
    $("button[onclick='registerUser()']").prop("disabled", true).text("Registering...");

    $.ajax({
        url: "/php/register.php",
        type: "POST",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify({
            name: name,
            email: email,
            password: password,
            age: age,
            dob: dob,
            contact: contact
        }),
        success: function (response) {
            console.log("Registration response:", response);
            console.log("Response type:", typeof response);
            console.log("Response status:", response ? response.status : "undefined");
            
            // Re-enable button
            $("button[onclick='registerUser()']").prop("disabled", false).text("Register");
            
            if (response && response.status === "success") {
                console.log("Registration successful, redirecting to login page...");
                $("#msg").removeClass("alert-danger").addClass("alert-success").text("Registration successful! Redirecting...").show();
                // Use setTimeout to ensure message is shown before redirect
                setTimeout(function() {
                    window.location.href = "/login.html";
                }, 1500);
            } else {
                console.log("Registration failed:", response ? response.message : "Unknown error");
                let errorMsg = response ? (response.message || "Registration failed") : "Unknown error occurred";
                $("#msg").removeClass("alert-success").addClass("alert-danger").html(errorMsg).show();
                
                // If email already exists, suggest logging in instead
                if (response && response.message && response.message.toLowerCase().includes("email already exists")) {
                    $("#msg").html(errorMsg + '<br><small><a href="/login.html">Click here to login instead</a></small>');
                    // Clear email field to make it easier to try a different email
                    $("#email").val("").focus();
                }
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.error("Response text:", xhr.responseText);
            console.error("Status code:", xhr.status);
            
            // Re-enable button
            $("button[onclick='registerUser()']").prop("disabled", false).text("Register");
            
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

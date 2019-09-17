<?php
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        // $cont_subject = trim($_POST["subject"]);
        $phone = trim($_POST["telephone"]);
        $gender = $_POST["gender"];
        $addressLine1 = trim($_POST["addressLine1"]);
        $addressLine2 = trim($_POST["addressLine2"]);
        $city = trim($_POST["city"]);
        $state = trim($_POST["state"]);
        $zip = trim($_POST["zip"]);
        $country = trim($_POST["country"]);

        // Check that data was sent to the mailer.
        if (empty($name) OR empty($phone) OR empty($gender) OR empty($addressLine1) OR empty($city) OR empty($state) OR empty($zip) OR empty($country) OR empty(!filter_var($email, FILTER_VALIDATE_EMAIL))) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "contact@specialforcesadventures.com";

        // Set the email subject.
        $subject = "New registration from $name";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Phone: $phone\n";
        $email_content .= "Gender: $gender\n";
        $email_content .= "Address: $addressLine1\n$addressLine2\n";
        $email_content .= "$city";
        $email_content .= " - ";
        $email_content .= "$zip\n";
        $email_content .= "$state";
        $email_content .= ", ";
        $email_content .= "$country\n";
      
        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your registration.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>

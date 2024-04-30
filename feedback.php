<?php
    // Initialize variables
    $name = $email = $type = $message = "";
    $errors = array();

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize input data
        $name = test_input($_POST["name"]);
        $email = test_input($_POST["email"]);
        $type = test_input($_POST["type"]);
        $message = test_input($_POST["message"]);

        // Check if name is empty
        if (empty($name)) {
            $errors[] = "Name is required";
        }

        // Check if email is empty or not valid
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Check if type is empty
        if (empty($type)) {
            $errors[] = "Feedback type is required";
        }

        // Check if message is empty
        if (empty($message)) {
            $errors[] = "Message is required";
        }

        // If there are no errors, proceed to save feedback to the database
        if (empty($errors)) {
            // Connect to your database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "feedback";

            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL statement to insert data into the database
            $stmt = $conn->prepare("INSERT INTO feedback (name, email, type, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $type, $message);

            // Execute the statement
            if ($stmt->execute()) {
                // Clear form fields
                $name = $email = $type = $message = "";
            } else {
                $errors[] = "Error occurred while saving feedback";
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
    }

    // Function to sanitize input data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
    <?php
    // Display errors if any
    if (!empty($errors)) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
    }

    // Display success message if feedback saved successfully
    if (isset($success_message)) {
        echo '<div class="success">';
        echo "<p>$success_message</p>";
        echo '</div>';
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="template.css" type="text/css" />
    <style>
        /* Add any additional styles here */
    </style>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="event-calendar.html">Event Calendar</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="community.php">Community</a></li>
            <li><a href="#" id="logoutBtn">Logout</a></li>
        </ul>
    </nav>
    <h1>Your Feedback, Our Strength</h1>
    <p>We would like your feedback to improve our website.</p>
</header>

<div class="feedback-container">
    <h2>Your Feedback</h2>
    <form id="feedbackForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label for="type">Feedback Type:</label>
            <select id="type" name="type" required>
                <option value="">Select Feedback Type</option>
                <option value="suggestion" <?php echo ($type == 'suggestion') ? 'selected' : ''; ?>>Suggestion</option>
                <option value="bug" <?php echo ($type == 'bug') ? 'selected' : ''; ?>>Bug Report</option>
                <option value="general" <?php echo ($type == 'general') ? 'selected' : ''; ?>>General Feedback</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required><?php echo $message; ?></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Submit Feedback">
        </div>
    </form>
</div>

<footer>
    <p>&copy; 2024 Calm Stride. All rights reserved.</p>
</footer>

<!-- JavaScript for logout button click -->
<script>
    document.getElementById('feedbackForm').addEventListener('submit', function(event) {
        // Disable the submit button to prevent multiple submissions
        document.querySelector('input[type="submit"]').disabled = true;
        
        var confirmation = confirm("Are you sure you want to submit the feedback?");
        if (!confirmation) {
            // Re-enable the submit button if user cancels the submission
            document.querySelector('input[type="submit"]').disabled = false;
            event.preventDefault(); // Prevent form submission
        }

    document.getElementById('logoutBtn').addEventListener('click', function() {
        var confirmation = confirm("Are you sure you want to logout?");
        if (confirmation) {
            // Redirect to Calm Stride page
            window.location.href = 'CalmStride.html';
        }
    });
</script>

</body>
</html>

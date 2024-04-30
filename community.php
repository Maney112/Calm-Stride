<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Assuming you don't have a password set for the root user
$database = "discussion"; // Update this with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $discussion = $_POST['discussion'];

    // Prepare and execute SQL statement to insert discussion into the database
    $stmt = $conn->prepare("INSERT INTO forum(discussion) VALUES (?)");
    $stmt->bind_param("s", $discussion);
    if ($stmt->execute()) {
        echo "<script>alert('Discussion saved successfully!');</script>";
    } else {
        echo "<script>alert('Error occurred while saving discussion.');</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Author: pariwarthan patel 
         Date: April 8, 2024 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Page</title>
    <link rel="stylesheet" href="template.css" type="text/css" />
    <style>
        /* Add custom styles for this page */
        .forum-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .forum-container h2 {
            text-align: center;
            color: #ae7724;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Community!</h1>
        <nav>
            <ul>
                <li><a href="event-calendar.html">Event Calendar</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="community.php">Community</a></li>
                <li><a href="#" id="logoutBtn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Discussion Forum -->
        <div class="forum-container">
            <h2>Discussion Forum</h2>
            <p>Welcome to our vibrant discussion forum where community members come together to share ideas, ask questions, and engage in meaningful conversations.</p>
            <button class="community-button" onclick="openDiscussionDialog()">Create Discussion</button>
            <button class="community-button" onclick="saveDiscussion()">Save Discussion</button>
            <button class="community-button" onclick="viewDiscussions()">View Discussions</button>
        </div>

        <!-- Social Group Links -->
        <div class="social-group-links">
            <h2>Resource Libraries</h2>
            <a href="https://www.youtube.com/watch?v=l-2hOKIrIyI">Procrastination? Listen to this!</a>
            <a href="https://openlibrary.org/">Frequently visit this to be more creative!</a>
            <a href="https://mentalhealth.openpathcollective.org/">Need more Help? Visit this!</a>
        </div>

        <!-- Community Images -->
        <div class="community-images">
            <img src="gathered.jpg" alt="Community Image 1">
            <img src="b.jpg" alt="Community Image 2">
            <img src="peace.jpg" alt="Community Image 3">
        </div>

        <!-- Discussion Dialog -->
        <div class="discussion-dialog" id="discussion-dialog">
            <form id="discussion-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" id="discussion-input" name="discussion" placeholder="Enter your discussion...">
                <button type="submit" name="submit">Save</button>
            </form>
        </div>

        <!-- Expert Advice -->
        <div class="container expert-advice">
            <h2>Expert Advice</h2>
            <p>Enhance your platform with forums, live chats, and Q&A sessions. Connect users to mental health professionals for expert advice and support.</p>
            <!-- Live Chat Button -->
            <a href="#" class="live-chat-button">Live Chat</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Calm Stride. All rights reserved.</p>
    </footer>
    
    <!-- JavaScript for Discussion Dialog -->
    <script>
        function openDiscussionDialog() {
            var dialog = document.getElementById('discussion-dialog');
            dialog.style.display = 'block';
        }

        function saveDiscussion() {
            var discussion = document.getElementById('discussion-input').value;
            if (discussion.trim() !== '') {
                var forumContainer = document.querySelector('.forum-container');
                var discussionElement = document.createElement('div');
                discussionElement.classList.add('discussion-item'); // Adds a class for styling
                discussionElement.innerHTML = '<p>' + discussion + '</p>' +
                    '<button class="like-button" onclick="likeDiscussion(this)">Like</button>' +
                    '<span class="like-count">0 likes</span>';
                forumContainer.appendChild(discussionElement);
            }
            // Close the dialog after saving
            var dialog = document.getElementById('discussion-dialog');
            dialog.style.display = 'none';
            // Clear the input field
            document.getElementById('discussion-input').value = '';
        }

        // Function to handle like button click
        function likeDiscussion(button) {
            var likeCount = button.nextElementSibling;
            var currentLikes = parseInt(likeCount.textContent);
            likeCount.textContent = (currentLikes + 1) + ' likes';
            // Disables the button after clicking
            button.disabled = true;
        }

        function viewDiscussions() {
            var forumContainer = document.querySelector('.forum-container');
            var discussions = forumContainer.querySelectorAll('p');
            var discussionsText = "";
            discussions.forEach(function(discussion) {
                discussionsText += discussion.textContent + "\n";
            });
            alert("Saved Discussions:\n" + discussionsText);
        }

        // Function to handle live chat button click
        document.querySelector('.live-chat-button').addEventListener('click', function() {
            // Implements live chat functionality 
            alert('Unlock our live chat feature by upgrading to our premium package for just $59.99 per year!');
        });
        // Function to handle logout button click
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

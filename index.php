<?php
session_start();
require_once 'database.php';
$mysqli = $connection;

// Check if the connection is successful
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit();
}

// Fetch conversations
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM conversations WHERE user1_id = '$user_id' OR user2_id = '$user_id'";
$result = mysqli_query($connection, $query);
$conversations = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Social Meet - Home</title>
</head>
<body>
	<h1>Welcome to Social Meet!</h1>

	<!-- Display a list of conversations -->
	<h2>Conversations</h2>
	<ul>
		<?php foreach ($conversations as $conversation): ?>
			<?php
				$other_user_id = ($conversation['user1_id'] == $user_id) ? $conversation['user2_id'] : $conversation['user1_id'];
				$other_user_query = "SELECT * FROM users WHERE id = '$other_user_id'";
				$other_user_result = mysqli_query($connection, $other_user_query);
				$other_user = mysqli_fetch_assoc($other_user_result);
			?>
			<li><a href="conversation.php?id=<?php echo $conversation['id']; ?>"><?php echo $other_user['name']; ?></a></li>
		<?php endforeach; ?>
	</ul>

	<!-- Display a form to start a new conversation -->
	<h2>Start a new conversation</h2>
	<form method="post" action="process_new_conversation.php">
		<label for="user2_id">Select a user to start a conversation with:</label>
		<select name="user2_id" id="user2_id">
			<?php
				$query = "SELECT * FROM users WHERE id != '$user_id'";
				$result = mysqli_query($connection, $query);
				$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
				foreach ($users as $user):
			?>
			<option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
			<?php endforeach; ?>
		</select>
		<br>
		<input type="submit" value="Start Conversation">
	</form>

	<!-- Button to go to profile page -->
	<form method="get" action="profile.php">
		<input type="submit" value="Go to my profile">
	</form>
	<form method="get" action="logout.php">
		<input type="submit" value="Logout">
	</form>
</body>
</html>

<?php mysqli_close($connection); ?>

<?php
$correct_password = '123456';
$new_hash = password_hash($correct_password, PASSWORD_DEFAULT);
echo "✅ NEW HASH for $correct_password:<br>";
echo "<strong>" . $new_hash . "</strong><br><br>";
echo "SQL to run:<br>";
echo "UPDATE users SET password = '$new_hash' WHERE username = 'admin';<br>";
echo "UPDATE users SET password = '$new_hash' WHERE username = 'user';";
?>

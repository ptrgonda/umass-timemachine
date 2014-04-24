<html>
<head>
<title>Authentication example</title>
</head>
<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<center>
    Welcome, <?php echo $_COOKIE["username"] ?>!<br><br>
    <form method="post" action="login.php">
        <input type="hidden" name="deletecookie" />
        <input type="submit" value="Logout" />
    </form>
</center>
</body>
</html>

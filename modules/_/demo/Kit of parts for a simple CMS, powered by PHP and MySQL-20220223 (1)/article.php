<?php
# A simple application to extract a single article from a database using mysqli and print it out.
# For ease of understanding, this script uses the mysqli connection methods described in
# Head First PHP & MySQL and not the PDO method described in Jon Duckett's PHP & MySQL.
# This application contains an example of input validation to prevent SQL injection.

# The value of $_GET, taken from the URL parameter is unsafe because it is user input. It has to be validated.
# Validate input: if id is set (has a value) and the data type is an integer, it seems to be OK, so assign
# the value to a variable ($article). If it doesn't pass this test, issue a 404 error, exit and print a message.
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $article=$_GET['id'];
}else{
	header('HTTP/1.0 404 Not Found');
	exit("<h1>Not Found</h1>\n<p>The submitted data is not valid.</p>");
}

# If we're still in the script, all is OK, so proceed.
# assign host, username, password and database name to variables
$host     = "localhost"; # usually "localhost" but may be different
$user     = "cabellah_demo"; # add your database username here
$password = "z1+##8a5[.~s"; # add your database user password here
$name     = "cabellah_demo"; # add your database name here

# connect to the database or stop the script and give an error message
$conn = mysqli_connect($host, $user, $password, $name) or die ("Cannot connect to database.");

# build the query and assign it to a variable
# This query uses a WHERE clause to select just one row, determined by the value of $article.
$query = "SELECT article_id, headline, article, author, novel, published FROM articles WHERE article_id = $article";

# run the query and assign the result to a variable or give an error message
$result = mysqli_query($conn, $query) or die ("Error querying database.");

# close the database connection
mysqli_close($conn);

# only one row in the result so don't need while
$row = mysqli_fetch_array($result);

# Check that id points to a real article - if $row doesn't have a value, we know there's a problem.
# if that article doesn't exist, issue a 404 error, exit and print a message.
if (!$row) {
	header('HTTP/1.0 404 Not Found');
	exit("<h1>Not Found</h1>\n<p>The requested article does not exist.</p>");
}

# If all the tests have been passed and we've reached this point, all is OK.
# assign each field to a variable
$article_id = $row['article_id'];
$headline = $row['headline'];
$article = $row['article'];
$author = $row['author'];
$novel = $row['novel'];
$date = $row['published'];
	
# convert mysql date to php timestamp
$timestamp = strtotime($date);
	
# format php timestamp
$display_date = date('jS F Y', $timestamp);
$display_time = date('g:ia', $timestamp);

# end this script and start building the page
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><? echo $headline; ?> | Strange Tales</title>
		<link rel="stylesheet" href="style/style.css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lato:ital@0;1&family=Oswald:wght@300;400&display=swap" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body>
		<header>
			<h1>Strange Tales</h1>
    		<p>A simple Content Management System using PHP and MySQL</p>
    	</header>
    	<main>
<?php
# print out the Strange Tales article
echo "            <article>\n";
echo "                <header>\n";
echo "                    <h2>$headline</h2>\n";
echo "                    <p><cite>An extract from the novel <b>$novel</b> by $author</cite></p>\n";
echo "                </header>\n";
echo "<!-- Start of article markup -->\n";
echo "$article\n";
echo "<!-- End of article markup -->\n";
echo "                <p><a href=\"index.php\"><span class=\"arrow\">&laquo;</span>Back to homepage</a></p>\n";
echo "                <footer>Published: $display_date at $display_time</footer>\n";
echo "            </article>\n";
?>
		</main>
		<footer>Written for MA Web Design &amp; Content Planning by David Watson</footer>
	</body>
</html>
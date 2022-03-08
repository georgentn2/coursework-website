<?php
# A simple application to extract articles from a database using mysqli and print them out.
# For ease of understanding, this script uses the mysqli connection methods described in
# Head First PHP & MySQL and not the object oriented methods described in PHP Solutions 2nd Ed.

# This script does not rely on user input, the data comes only from the database and can be
# considered safe. No testing is necessary.

# assign host, username, password and database name to variables
$host     = "localhost"; # usually "localhost" but may be different
$user     = "cabellah_demo"; # add your database username here
$password = "z1+##8a5[.~s"; # add your database user password here
$name     = "cabellah_demo"; # add your database name here

# connect to the database or stop the script and give an error message
$conn = mysqli_connect($host, $user, $password, $name) or die ("Cannot connect to database.");

# build the query and assign it to a variable
# This query selects 5 columns from the articles table and orders them by date (newest first).
# LIMIT 10 means select only the most recent 10 articles.
$query = "SELECT article_id, headline, intro, author, published FROM articles ORDER BY published DESC LIMIT 10";

# run the query and assign the result to a variable or give an error message
$result = mysqli_query($conn, $query) or die ("Error querying database.");

# close the database connection
mysqli_close($conn);
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Strange Tales | PHP and MySQL Example</title>
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
# step through each Strange Tales article, one at a time
while ($row = mysqli_fetch_array($result))
{
	# assign each field to a variable
	$article_id = $row['article_id'];
	$headline = $row['headline'];
	$intro = $row['intro'];
	$author = $row['author'];
	$date = $row['published'];
	
	# convert mysql date to php timestamp
	$timestamp = strtotime($date);
	
	# format php timestamp
	$display_date = date('jS F Y', $timestamp);
	$display_time = date('g:ia', $timestamp);
	
	# print out the article
	# we are adding a URL parameter to the link for article.php using the $article_id variable.
	echo "        <article> <!-- start of article card -->\n";
	echo "            <header>\n";
	echo "                <h2>$headline</h2>\n";
	echo "                <p>$display_date</p>\n";
	echo "            </header>\n";
	echo "<!-- Start of introductory text -->\n";
	echo "$intro\n";
	echo "<!-- End of introductory text -->\n";
	echo "            <p><a href=\"article.php?id=$article_id\">Read more<span class=\"arrow\">&raquo;</span></a></p>\n";
	echo "            <footer>Published: $display_time by $author</footer>\n";
	echo "        </article>\n\n";
}
?>
		</main>
		<footer>Written for MA Web Design &amp; Content Planning by David Watson</footer>
	</body>
</html>
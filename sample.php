<!doctype html>
<?php
include "customErrorHandler.php";
set_error_handler("customErrorHandler");
$cookie_name = "user";
$cookie_value = "value";
setcookie($cookie_name,$cookie_value,time()+3600);
session_start();
$session_exists = false;
if(isset($_SESSION['UserID'])) {
	$session_exists = true;
}
?>
<html>
	<head>
	<meta name="vewport" content="width=device-width, initial-scale=" >
	<link rel="stylesheet" type="text/css" href="CSStyle.css">
	</head>
	<body>
		<div class="maindiv"> 
		<div class="headdiv">
			<h1>
				FMH
			</h1>	
			<?php
					if($session_exists) {
						echo '<span>Hello, '.$_SESSION["FirstName"].'. <a href="logoff.php">Log Out</a></span>';
					}
				?>	
			<div class="hornavbar" id="hornavbarid"> <!-- The Horizontal Navigation bar--> 
				<div id="reveal-navpane">
					<hr>
					<hr>
					<hr>
				</div>
				<a href="#link">Home</a>
				<a href="#link">About Us</a>
				<a href="#link">Buy</a>
				<a href="#">Sell</a>
				<a href="#">Take a tour</a>
				<?php if(!$session_exists) 
				{
				echo '
				<a href="Profiles/signin.php">Sign In</a>
				<a href="Profiles/signup.php" style="float:right">Sign Up</a>
				';
				} else {
					
				}
				?>
			</div>			
		</div> 
		
		<div class="navpane floatmenu" id="navpaneid">
			<a href="#">Some random link</a>
			<a href="#">Some other link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">And another link</a>
			<a href="#">The last link</a>
		</div>
		
		<div class="maincontentpane floatmenu" id="maincontentpaneid">
			<!--The immediately following div should show the side menu when clicked.
					Otherwise, it's invisible by default until the screen size is less 
					than <600px> -->
			
			<div class="upper">
				
				<div class="contentdiv"> 
				
				<!--The main content container, whose sibling is the main side pane of 
				class sidepane. All the rest of the content goes in here, inside bordered
				and shadowed card-like div containers-->
				
				
				
<!--***************************************INSERT CONTENT BELOW THIS*************************-->


				
					<div class="content">
						<p style="text-align: center">
						<h3>MAIN CONTENT PANE</h3><br>
						This is a sample paragraph. Oh no! I lost my initial text, so I have to 
						type another sufficiently long line of text to analyse how it's going to 
						to affect the wrap bla bla bla. Turns out that wasn't long enough (That's
						 what she said. lol) but how about now?
						 <br>
						 <br>
						 </p>
						 <hr>
						 <p>
						 <h3>What is Lorem Ipsum?</h3>
						 <div>
						 		<img src="lorem.jpg" class="image_container" alt="logo">
						 </div>
							Lorem Ipsum is simply dummy text of the printing and <a href="#">typesetting<a> industry. 
							Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
							when an unknown printer took a galley of type and scrambled it to make a type 
							specimen book. 
							<p>
							It has survived not only five centuries, but also the leap into 
							electronic typesetting, remaining essentially unchanged. It was popularised in 
							the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
							and more recently with desktop publishing software like Aldus PageMaker 
							including versions of Lorem Ipsum.	
							</p>

							<hr>
							<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
									First Name: <input type="text" name="FirstName" value="<?php echo filter($_POST['FirstName']); ?>" required><font color="red">*</font><br>
									Last Name: <input type = "text" name="LastName" value="<?php echo $_POST['LastName']; ?>" required><br>
									DoB: <input type="number" name="dob" min=1900 max=2030 value=1992><br>
									<button onclick="submit" target="_self">Send</button>
									
								</form>	
								<?php
								if($_SERVER['REQUEST_METHOD']=="POST") {
									$fname=$lname=$Dob="";
									$fname_err=$lname_err=$Dob_err="";
									if(empty($_POST['FirstName'])){
										$fname_err="This field is required";
									}
									else { //pass the value and apply the 3 filters
										$fname=filter($_POST['FirstName']);
									}
									if(empty($_POST['LastName'])){
										$lname_err="This field is required";
									} 
									else { //filter
										$lname = filter($_POST['LastName']);
									}
									if(!empty($_POST['dob'])) {
										$Dob=filter($_POST['dob']);
									}
									echo "Hello, Mr. ".$fname." ".$lname."<br>";
									echo "You must be ".(2017-$Dob)." years old, huh?<br>";
								}
								function filter($value) {
									$value=trim($value);
									$value=stripslashes($value);
									$value=htmlspecialchars($value);
									return $value;
								}
								?>
							<hr>
							<form action="upload.php" method="post" enctype="multipart/form-data">
								Select a file to upload: <input type="file" name="upfile"><br>
								<input type="submit" name="submit">
							
							</form>
							<hr>
						</p> 
						<p>
						<h3>Where does it come from?</h3>
						Contrary to popular belief, Lorem Ipsum is not simply random text. 
						It has roots in a piece of classical Latin literature from 45 BC, 
						making it over 2000 years old. Richard McClintock, a Latin professor 
						at Hampden-Sydney College in Virginia, looked up one of the more obscure 
						Latin words, consectetur, from a Lorem Ipsum passage, and going through 
						the cites of the word in classical literature, discovered the undoubtable 
						source. 
						</p>
						<p>
						Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus
						 Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in
							45 BC. This book is a treatise on the theory of ethics, very popular during
							 the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
						<hr>
						</p>
						The standard chunk of Lorem Ipsum used since the 1500s is reproduced
						 below for those interested. Sections 1.10.32 and 1.10.33
						  from "de Finibus Bonorum et Malorum" by Cicero are also reproduced
						   in their exact original form, accompanied by English versions from
						    the 1914 translation by H. Rackham.
						</p>
						<br>
						<hr>
						The standard chunk of Lorem Ipsum used since the 1500s is reproduced
						 below for those interested. Sections 1.10.32 and 1.10.33
						  from "de Finibus Bonorum et Malorum" by Cicero are also reproduced
						   in their exact original form, accompanied by English versions from
						    the 1914 translation by H. Rackham.
						</p>
						<p>
						<h3>Where does it come from?</h3>
						Contrary to popular belief, Lorem Ipsum is not simply random text. 
						It has roots in a piece of classical Latin literature from 45 BC, 
						making it over 2000 years old. Richard McClintock, a Latin professor 
						at Hampden-Sydney College in Virginia, looked up one of the more obscure 
						Latin words, consectetur, from a Lorem Ipsum passage, and going through 
						the cites of the word in classical literature, discovered the undoubtable 
						source. 
						</p>
						<p>
						Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus
						 Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in
							45 BC. This book is a treatise on the theory of ethics, very popular during
							 the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
						<hr>
						</p>
					</div>
				</div>
				
<!--************************************INSERT CONTENT ABOVE THIS*******************************-->
				
				<div class="sidepane"> <!--The main side pane container, perhaps for ads and all that shit-->
				
				</div>
			</div>
			<div class="footermaindiv"> <!--The footer-->
				<p style="color: white">
				Copyright marketlink.com 2016-2017
				</p>
			</div>
		</div>
	</div>
	<script src="base-framework.js"></script>
	</body>
</html>

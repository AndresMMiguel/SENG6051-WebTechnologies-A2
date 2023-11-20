<!-- Copyright © 2023 Andres Moreno Miguel | c3465977@uon.edu.au | All rights reserved.
AUTHOR: Andres Moreno Miguel
STUDENT ID: c3465977
FILE NAME: submit.php
DATE: 14st October 2023 -->


<script type="text/javascript">
	function goBack(){
		window.history.back();
	}

	function congratulations(category, gameName){
		var url = "Games?category="+category+"#"+gameName;
		location.href = url;
		alert ("CONGRATULATIONS! Form submission success. Your game has been added.")
	}
</script>

<?php 
	// Apply GSADMIN env
	if (defined('GSADMIN')) {
		$GSADMIN = GSADMIN;
	} else {
		$GSADMIN = 'admin';
	}
    // Include common.php
    include($GSADMIN.'/inc/common.php');

	// Declare variables
	$userfirstname = $userlastname = $useremail = $userpreviousgames = $teachbefore = $gamename = $subject = $description = $gamecost = $category = $numplayers = $grade = $picture = $url = $duration = $difficulty = $addedtags = $addedreviews = $addedequipment = "";
	
	// DATA
	$userfirstname = checkinput($_POST["firstName"]);
	$userlastname = checkinput($_POST["lastName"]);
	$useremail = checkinput($_POST["email"]);
	$userpreviousgames = checkinput($_POST["numPreviousGames"]);
	$teachbefore = checkinput($_POST["taughtPast5Years"]);
	$gamename = checkinput($_POST["gameName"]);
	$subject = checkinput($_POST["subject"]);
	$description = checkinput($_POST["description"]);
	$gamecost = checkinput($_POST["gameCost"]);
	$category = checkinput($_POST["category"]);
	$numplayers = checkinput($_POST["numPlayers"]);
	$grade = checkinput($_POST["grade"]);
	$picture = $_FILES["picture"];
	$url = checkinput($_POST["url"]);
	$duration = checkinput($_POST["duration"]);
	$difficulty = checkinput($_POST["difficulty"]);
	$addedtags = json_decode($_POST["addedTags"]);
	$addedreviews = json_decode($_POST["addedReviews"]);
	$addedequipment = json_decode($_POST["addedEquipment"]);
	$agreement = checkinput($_POST["agreement"]);

	// 01. VALIDATE THE DATA
	$boolvalidform = false;
	
	// Check every required field is not empty
	$requiredfields = array($userfirstname, $userlastname, $useremail, $gamename, $subject, $description, $category, $numplayers, $grade, $picture, $addedequipment);
	foreach ($requiredfields as $field){
		if ($field == ""){
			$boolvalidform = false;
			break;
		}
		else{
			$boolvalidform = true;
		}
	}

	if($boolvalidform){
		// Check if every text input only contains letters and allowed characters
		$textfields = array($userfirstname, $userlastname, $gamename, $subject, $description);
		foreach ($textfields as $field){
			if (!preg_match("/^[a-zA-Z-' ]*$/", $field)){
				$boolvalidform = false;
				break;
			}
			else{
				$boolvalidform = true;
			}
		}

		if($boolvalidform){
			// Check if every number input only contains numbers
			$numericfields = array($userpreviousgames, $gamecost, $numplayers, $duration);
			$regexpNUMBERS = "/^[0-9]+$/";
			foreach ($numericfields as $field){
				if($field != ""){
					if(!preg_match($regexpNUMBERS, $field)){
						$boolvalidform = false;						
						break;
					}
					else{
						$boolvalidform = true;
					}
				}
			}

			if($boolvalidform){
				// Check if the e-mail address is valid
				if (!filter_var($useremail, FILTER_VALIDATE_EMAIL)){
					$boolvalidform = false;
				}
				else{
					$boolvalidform = true;
				}

				if($boolvalidform){
					// Check if the game URL is valid
					$regexpURL = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
					if ($url != "") {
						if (!preg_match($regexpURL, $url)){ $boolvalidform = false; }
						else{ $boolvalidform = true; }
					}

					if($boolvalidform){
						// Check if the user has agreed to the Terms and Conditions
						if ($agreement != "on") { $boolvalidform = false; }
						else{ $boolvalidform = true; }

						if($boolvalidform){
							// Check if the image is valid and upload it to the uploads directory
							$boolvalidform = uploadImage();
						}
						else{
							echo '<script type="text/javascript">alert("ERROR: The agreement to the Terms and Conditions is required to submit the form"); goBack();</script>';
						}
					}
					else{
						echo '<script type="text/javascript">alert("ERROR: The game URL should have the correct syntax"); goBack();</script>';
					}
				}
				else{
					echo '<script type="text/javascript">alert("ERROR: The e-mail should have the correct format"); goBack();</script>';
				}
			}
			else{
				echo '<script type="text/javascript">alert("ERROR: Numeric fields should contain only numbers"); goBack();</script>';
			}
		}
		else{
			echo '<script type="text/javascript">alert("ERROR: In text fields, special characters and numbers are not allowed"); goBack();</script>';
		}
	}
	else{
		echo '<script type="text/javascript">alert("ERROR:, All the required fields must be entered"); goBack();</script>';
	}


	// 02. XML CREATION (if needed) AND POPULATION
	if ($boolvalidform) {

		// Get the existing categories
		$existingcategories = getCategories();
		
		// Check if the category entered already exists
		$isnewcategory = true;
		foreach ($existingcategories as $existingcategory) {
			if ($category == $existingcategory){
				$isnewcategory = false;
				break;
			}	
		}
		
		// If the category entered is new, copy the template.xml file in the same directory and rename it after the new category
		if($isnewcategory){
			$sourcefile = GSDATAPAGESPATH."games/template.xml";
			$destinationfile = GSDATAPAGESPATH."games/".$category.".xml";
			copy($sourcefile, $destinationfile);

			// Confirm that the file exists
			if (file_exists($destinationfile)){
				// Load it, change all the "category" tags and declarations to the new category name and save it
				$categoryfile = file_get_contents($destinationfile);
				$categoryfile = str_replace("category", $category, $categoryfile);
				file_put_contents($destinationfile, $categoryfile);

				// Load it with SimpleXML
				$categoryfile = simplexml_load_file($destinationfile);

				// Remove the empty game of the template
				unset($categoryfile->game);

				// Populate it
				addGameToXML($categoryfile, $gamename, $description, $subject, $grade, $addedtags, $gamecost, $addedequipment, 
								$addedreviews, $picture, $url, $duration, $difficulty, $numplayers);

				// Save the updated XML document
				$categoryfile->asXML($destinationfile);

				// SUCCESS
				$gameHash = str_replace(' ', '', $gamename);
				echo '<script type="text/javascript">congratulations("'.$category.'", "'.$gameHash.'");</script>';
				
			}
		}
		else{ // If the category entered already existed, fill the XML file of such category
			$path = GSDATAPAGESPATH."games/".$category.".xml";
                
			if (file_exists($path)){ // Confirm that the file exists
				$categoryfile = simplexml_load_file($path); // Load the XML file of the category
				
				// Populate it with the form data
				addGameToXML($categoryfile, $gamename, $description, $subject, $grade, $addedtags, $gamecost, $addedequipment, 
								$addedreviews, $picture, $url, $duration, $difficulty, $numplayers);

				// Save the updated XML document
				$categoryfile->asXML($path);
				
				// SUCCESS
				$gameHash = str_replace(' ', '', $gamename);
				echo '<script type="text/javascript">congratulations("'.$category.'", "'.$gameHash.'");</script>';
			}
		}
	}

	// FUNCTIONS
    // Function to populate the XML file with the form data
    function addGameToXML($categoryfile, $gamename, $description, $subject, $grade, $addedtags, $gamecost, $addedequipment, 
                          $addedreviews, $picture, $url, $duration, $difficulty, $numplayers){
        $newgame = $categoryfile->addChild("game"); 
        $newgame->addChild("name", $gamename); // Game name
        $newgame->addChild("description", $description); // Description
        $newgame->addChild("subject", $subject); // Subject
        $newgame->addChild("grade", $grade); // Grade
        // Tags
        if($addedtags != ""){
            for ($x = 0; $x <= count($addedtags)-1; $x++) {
                $newgame->addChild("tag", $addedtags[$x]->Text);
            }
        }
        $newgame->addChild("cost", "AUD ".$gamecost); // Cost
        // Equipment
        for ($x = 0; $x <= count($addedequipment)-1; $x++) {
            $eq = $newgame->addChild("equipment");
            $eq->addChild("eqName", $addedequipment[$x]->Name);
            $eqcost = $eq->addChild("addCost", "AUD ".$addedequipment[$x]->Cost);
            $eqcost->addAttribute("costAttr", strtolower($addedequipment[$x]->Type));
        }
        // Review
        if ($addedreviews != ""){
            for ($x = 0; $x <= count($addedreviews)-1; $x++){
                $rev = $newgame->addChild("review", $addedreviews[$x]->Text);
                $rev->addAttribute("score", $addedreviews[$x]->Score);
            }
        }
        $newgame->addChild("picture", "/data/uploads/".$picture["name"]); // Picture
        $newgame->addChild("url", $url); // URL
        $newgame->addChild("duration", $duration."min"); // Duration
        $newgame->addChild("difficulty", $difficulty); // Difficulty
        $newgame->addChild("numPlayers", $numplayers); // Number of players
    }

	// Function to remove the blank spaces, strip slashes and convert special characters to HTML entities from the input entered 
	function checkinput($input){
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);

		return $input;
	}

    // Function to get all the already existing categories
	function getCategories(){
		$existingcategories = array();

		$categoriesFolder = GSDATAPAGESPATH."games/"; // Path where all the XML files of the game categories are
		$filesXML = glob($categoriesFolder."*.xml"); // Retrieve all the XML files inside that directory
		sort($filesXML); // Order the list alphabetically
				
		foreach ($filesXML as $filepath) {
			if(strpos($filepath, "template")==0){ // Ignore the template XML file
				$existingcategory = strtolower(basename($filepath, ".xml")); // Get the file name without file extension and in lowercase
				array_push($existingcategories, $existingcategory); // Add it to the array of existing categories
			}
		}

		return $existingcategories;
	}

	// Function to upload an image
	function uploadImage(){
		$path= GSDATAUPLOADPATH;
		$target_file = $path.basename($_FILES["picture"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is an actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["picture"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}
		
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
			echo "Sorry, only JPG, JPEG, PNG files are allowed.";
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			return false;		
		}
		else { // if everything is ok, try to upload file
			if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["picture"]["name"])). " has been uploaded.";
				return true;
			} else {
				echo "Sorry, there was an error uploading your file.";
				return false;
			}
		}
	}
?>



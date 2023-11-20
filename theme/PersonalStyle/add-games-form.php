<!-- Copyright © 2023 Andres Moreno Miguel | c3465977@uon.edu.au | All rights reserved.
AUTHOR: Andres Moreno Miguel
STUDENT ID: c3465977
FILE NAME: ad-games-form.php
DATE: 14st October 2023 -->

<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } 

    echo '<!DOCTYPE html>';
    echo '<html>';

    // HEAD
	include("head.php");

    // NAVIGATION BAR
    include("navbar.php");

	// PAGE CONTENT
    echo '<body id="'.get_page_slug().'">';
?>
<section class="content-header">
    <h1>Add your own game</h1>
    <p>Get ready to unleash your creativity and add all the games you know in just a few simple steps!</p>
</section>
<section class="content-body">
    <p>We invite you to take the reins of game creation by filling out the form below. Whether you're an educator, a parent, or a lifelong learner, this is your chance to shape the way we learn. Choose your subject, set the challenges, and tailor the content to suit your unique vision. With your input, we'll make learning fun and accessible to all.</p>
    <p style="text-align: center;">Don't miss this opportunity to start your adventure in education today - fill out the form and watch your learning ladder games come to life!</p>
    
    <div class="form-container">
        <form id="newGameForm" name="newGameForm" action="submit.php" method="POST" enctype="multipart/form-data">
            <h2 style="text-align: center;">New game</h2>
            <!-- USER INFORMATION -->
            <fieldset>
                <legend>Your personal information</legend>
                <!-- User name -->
                <div class="form-line">
                    <label for="firstName">First name:*</label>
                    <input id="firstName" name="firstName" placeholder="First name" type="text" required/>
                    <label for="lastName">Last name:*</label>
                    <input id="lastName" name="lastName" placeholder="Last name" type="text" required/>
                </div>
                <!-- User email -->
                <div class="form-line">
                    <label for="email">Email address:*</label>
                    <input id="email" name="email" placeholder="example@domain.com" type="email" required/>
                </div>
                <!-- User previous games added -->
                <div class="form-line">
                    <label for="numPreviousGames">How many games did you previously submitted?</label>
                    <input id="numPreviousGames" name="numPreviousGames" placeholder="5" type="number" />
                </div>
                <!-- User binary question -->
                <div class="form-line">Have you taught in the past 5 years?</div>
                <div>
                    <input id="yes" name="taughtPast5Years" value="YesTeacher" type="radio"/>
                    <label for="yes">Yes</label>
                </div>
                <div>
                    <input id="no" name="taughtPast5Years" value="NoTeacher" type="radio" checked/>
                    <label for="no">No</label>
                </div>
            </fieldset>
            <!-- GAME INFORMATION -->
            <fieldset>
                <legend>Game information</legend>
                <div class="form-line">
                    <!-- Name -->
                    <label for="gameName">Name:*</label>
                    <input id="gameName" name="gameName" placeholder="Game name" type="text" required/>
                    <!-- Subject -->
                    <label for="subject">Subject:*</label>
                    <input id="subject" name="subject" placeholder="Subject" type="text" required/>
                </div>
                <!-- Description -->
                <div class="form-line">
                    <label for="description">Description:*</label>
                </div>
                <div class="form-line">
                    <textarea id="description" name="description" placeholder="Describe the game" rows="5" required></textarea>
                </div>
                <div class="form-line">
                    <!-- Cost -->
                    <label for="gameCost">Cost:</label>
                    <input id="gameCost" name="gameCost" placeholder="Game cost" type="number" onchange="updateGameTotalCost()"/>
                    <!-- Category -->
                    <label for="category">Category:*</label>
                    <select id="category" name="category" onchange="categories(this.value)" required>
                        <?php
                            $categoriesFolder = GSDATAPAGESPATH."games/"; // Path where all the XML files of the game categories are
                            $filesXML = glob($categoriesFolder."*.xml"); // Retrieve all the XML files inside that directory
                            sort($filesXML); // Order the list alphabetically

                            // Populate the dropdown menu with the existing game categories:
                            foreach ($filesXML as $filepath) {
                                if(strpos($filepath, "template")==0){ // Ignore the template XML file
                                    $category = basename($filepath, ".xml"); // Get the file name without file extension
                                    echo '<option value="'.strtolower($category).'">'.ucfirst($category).'</option>';
                                }
                            }
                        ?>
                        <option value="newCategory">Add a new category</option>
                    </select>
                </div>
                <!-- Number of players -->
                <div class="form-line">
                    <label for="numPlayers">Number of players:*</label>
                    <input id="numPlayers" name="numPlayers" placeholder="7" type="number" required/>
                    <label for="grade">Grade level:*</label>
                    <input id="grade" name="grade" placeholder="Grade 5" type="text" required/>
                </div>
                <!-- Picture -->
                <div class="form-line">
                    <label for="picture">Game picture:*</label>
                    <input id="picture" name="picture" type="file" accept="image/png, image/jpg" style="border: none;" required/>
                </div>
                <!-- Url -->
                <div class="form-line">
                    <label for="url">Game URL:</label>
                    <input id="url" name="url" placeholder="https://www.example.com.au/game" type="url"/>
                </div>
                <div class="form-line">
                    <!-- Duration -->
                    <label for="duration">Duration (in minutes):</label>
                    <input id="duration" name="duration" placeholder="20" type="number"/>
                    <!-- Difficulty -->
                    <label for="difficulty">Difficulty:</label>
                    <select id="difficulty" name="difficulty">
                        <option value=""></option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <!-- Tags -->
                <div class="form-line">
                    <label for="tag">Tags:</label>
                    <input id="tag" name="tag" placeholder="Tag text" rows="3" type="text"/>
                    <button type="button" class ="form-inline-btn-add" onclick="addTag()">Add</button>
                </div>
                <ul id="addedTagsList" name="addedTagsList" class="form-list"></ul>
                <input id="addedTags" name="addedTags" hidden />
                <!-- Reviews -->
                <div class="form-line" style="justify-content: space-between;">
                    <div>
                        <label for="review">Write your review:</label>
                    </div>
                    <div>
                    <label for="reviewScore">Score:* &nbsp;</label>
                        0<input id="reviewScore" name="reviewScore" type="range" min="0" max="5" style="accent-color: #5A6E43;" required/>5
                    </div>
                </div>
                <div class="form-line" style="align-items: normal;">
                    <textarea id="review" name="review" placeholder="Awesome game! 5 out of 5 stars" rows="3"></textarea>
                    <button type="button" class="form-inline-btn-add" onclick="addReview()" style ="height: initial;">Add</button>
                </div>
                <ul id="addedReviewsList" name="addedReviewsList" class="form-list"></ul>
                <input id="addedReviews" name="addedReviews" hidden />
                <!-- Equipment -->
                <div class="form-line">
                    <p style="padding-bottom:0;">Equipment:*</p>
                </div>
                <div class="form-line" >
                    <label for="equipmentName">Name:*</label>
                    <input id="equipmentName" name="equipmentName" placeholder="Equipment name" type="text"/>
                    <label for="equipmentCost">Cost:*</label>
                    <input id="equipmentCost" name="equipmentCost" placeholder="Equipment cost" type="number"/>
                    <label for="costIncluded">Included*</label>
                    <input id="costIncluded" name="costIncluded" type="checkbox"/>
                    <button type="button" class="form-inline-btn-add" onclick="addEquipment()">Add</button>
                </div>
                <ul id="addedEquipmentList" name="addedEquipmentList" class="form-list"></ul>                
                <input id="addedEquipment" name="addedEquipment" hidden />
                <div id="eqTotalCost" hidden>0</div>
                <!-- Final price -->
                <div class="form-line" style="justify-content: center; color: red; margin-top:1em;">
                    <p style="margin-right: 1em;">GAME TOTAL PRICE:</p>
                    <p id="totalCost" style="font-size: 25px;">$0</p>
                    <p id="totalCostCategory" name ="totalCostCategory" style="margin-left: 1em;">Free</p>
                </div>
            </fieldset>
            <!-- Agreement checkbox -->
            <div style="margin-top: 1rem;">
                <input id="agreement" name="agreement" type="checkbox" required/>
                <label for="agreement">I confirm I have read, understood and agreed to the <a href="Terms">Terms &amp; Conditions</a></label>
            </div>
            <!-- BUTTONS -->
            <div class="form-buttons">
                <button type="submit" onclick="return validateReviewsAndPrice()">Submit</button>
                <button type="reset">Clear</button>
            </div>
        </form>
    </div>
</section>

<?php
    // FOOTER
	include("footer.php");
?>


<script type="text/javascript">
    
    function addItem (inputArrayName, object){
        // Get the actual array of the input
        var array =[];
        if(document.getElementById(inputArrayName).value != ""){
            array = JSON.parse(document.getElementById(inputArrayName).value);
        }

        // Add the object to the array
        array.push(object);
        
        // Convert the array into JSON in order to later pass it through the request
        document.getElementById(inputArrayName).value = JSON.stringify(array); 
    }

    function addEquipment() {
        // Get the actual total price of all the equipment items
        var totalCost = document.getElementById("eqTotalCost").innerHTML;      
        if (totalCost == ""){
            totalCost = 0;
        }
        else{
            totalCost = parseInt(totalCost);
        }

        if(document.newGameForm.equipmentName.value.trim() != ""){ // Check if the equipment name is not empty
            if(document.newGameForm.equipmentCost.value.trim() != ""){ // Check if the equipment cost is not empty

                var eqName = document.newGameForm.equipmentName.value.trim(); // Get the name of the equipment
                var eqCost = document.newGameForm.equipmentCost.value.trim(); // Get the cost of the equipment
                var addedEquipmentList = document.getElementById("addedEquipmentList"); // Get the list of the previously added equipment
                
                // Check if the cost of the equipment is included in the game price or it's additional
                var costAttr;
                if(document.newGameForm.costIncluded.checked){
                    costAttr = "Included";
                }
                else{
                    costAttr = "Additional";
                }

                // Create the equipment object and add it to the array input
                var equipment = {
                    Name: eqName,
                    Cost: eqCost,
                    Type: costAttr
                };
                addItem("addedEquipment", equipment);

                // Create an element in the list for the equipment
                var newEquipment = document.createElement("li");
                newEquipment.textContent = eqName.concat(": $", eqCost, " (", costAttr, ")");
                addedEquipmentList.appendChild(newEquipment);

                // Create the button to delete the equipment
                var removeEquipmentBtn = document.createElement("button");
                removeEquipmentBtn.innerHTML = " x ";
                removeEquipmentBtn.className = "form-inline-btn-remove";
                newEquipment.appendChild(removeEquipmentBtn);

                // If there's only one equipment, hide the remove button. If there's more than one, show all the remove buttons
                if(addedEquipmentList.getElementsByTagName("li").length == 1) {
                        removeEquipmentBtn.style.display = "none";
                }
                else{
                    var removeButtons = Array.from(addedEquipmentList.getElementsByTagName("button"));
                    removeButtons.forEach(button => {
                        button.style.display = "";
                    });
                }

                // Functionality when the remove button is clicked:
                removeEquipmentBtn.onclick = function(){
                    addedEquipmentList.removeChild(newEquipment); // Remove the equipment from the list of already added equipment
                    removeEquipmentFromInput("addedEquipment", equipment); // Remove the equipment from the array input

                    // Hide the remove button for the last child
                    if(addedEquipmentList.getElementsByTagName("li").length == 1) {
                        addedEquipmentList.getElementsByTagName("button")[0].style.display = "none";
                    }

                    // Recalculate the game total price
                    // (if the equipment cost was additional the game final price will decrease, whereas if it was included, the game final price won't change)
                    if(costAttr == "Additional"){
                        var newTotalCost = parseInt(document.getElementById("eqTotalCost").innerHTML) - parseInt(eqCost);
                        document.getElementById("eqTotalCost").innerHTML = newTotalCost;
                        updateGameTotalCost();
                    }
                }

                // Recalculate the game total price
                // (if the equipment cost is additional the price will increase, whereas if it's included, the price won't change)
                if(costAttr == "Additional"){
                    var newTotalCost = totalCost + parseInt(eqCost);
                    document.getElementById("eqTotalCost").innerHTML = newTotalCost;
                    updateGameTotalCost();
                }

                // Clear the text inputs for the next equipment.
                document.newGameForm.equipmentName.value = "";
                document.newGameForm.equipmentCost.value = "";
                document.newGameForm.costIncluded.checked = false;

            }
            else{
                alert("Please, provide a cost for the equipment");
            }
        }
        else{
            alert("Please, provide a name for the equipment");
        }
    }
    
    function addReview() {
        var addedReviewsList = document.getElementById("addedReviewsList"); // Get the list of the previously added reviews

        if(document.newGameForm.review.value.trim() != ""){ // Check if the review is not empty
            var reviewText = document.newGameForm.review.value.trim(); // Get the text of the review

            if(validateReview(reviewText)){ // Check if the review meets the mandatory phrase
                // Get the score
                var score = document.newGameForm.reviewScore.value;
                
                // Create the review object and add it to the array input
                var review = {
                    Text: reviewText,
                    Score: score
                };
                addItem("addedReviews", review);

                // Create an element in the list with the review text and the score
                var newReview = document.createElement("li");
                newReview.textContent = reviewText.concat(". SCORE: ", score);
                addedReviewsList.appendChild(newReview);

                // Create the button to delete the review
                var removeReviewBtn = document.createElement("button");
                removeReviewBtn.innerHTML = " x ";
                removeReviewBtn.className = "form-inline-btn-remove";
                newReview.appendChild(removeReviewBtn);

                //Functionality when the remove button is clicked
                removeReviewBtn .onclick = function(){
                    addedReviewsList.removeChild(newReview); // Remove the review from the list
                    removeItem("addedReviews", review); // Remove the review from the array input
                }

                // Clear the textarea for the next review.
                document.newGameForm.review.value = "";
            }
            else{
                alert("All reviews must contain the phrase \"x out of 5 stars\", where x is a number from 0 to 5 allowing a single decimal place.");
            }
        }
        else{
            alert("Your review cannot be empty.");
        }
    }

    function addTag() {
        var addedTagsList = document.getElementById("addedTagsList"); // Get the list of the previously added tags

        if(document.newGameForm.tag.value.trim() != ""){ // Check if the tag is not empty
            var tagText = document.newGameForm.tag.value.trim(); // Get the text of the tag

            // Create the tag object and add it to the array input
            var tag = {
                Text: tagText
            };
            addItem ("addedTags", tag);

            // Create an element in the list with the tag text
            var newTag = document.createElement("li");
            newTag.textContent = tagText;
            addedTagsList.appendChild(newTag);

            // Create the button to delete the tag
            var removeTagBtn = document.createElement("button");
            removeTagBtn.innerHTML = " x ";
            removeTagBtn.className = "form-inline-btn-remove";
            newTag.appendChild(removeTagBtn);

            // Functionality when the remove button is clicked
            removeTagBtn .onclick = function(){
                addedTagsList.removeChild(newTag); // Remove tag from the list
                removeItem("addedTags", tag); // Remove tag from the array input
            }

            // Clear the text input for the next tag.
            document.newGameForm.tag.value = "";
        }
        else{
            alert("Your tag cannot be empty.");
        }

    }

    function categories(selectedOption){
        var categoryMenu = document.newGameForm.category; // Get the dropdown menu of the categories
        var notValidStr = true;
        var specialCharsRegExp =/[`¡!@#$%^&*()_\-+=\[\]{};':"\\|,.<>\/¿?~ ]/;  // Regular expresion to check special characters

        if (selectedOption == "newCategory"){ 
            while(notValidStr){ // Pop up box prompting the new category name until it is valid
                var newCategory = prompt("Write the new category name:", ""); 
                if(specialCharsRegExp.test(newCategory)){
                    alert("The new category name cannot contain special characters.");
                }
                else{ // If it's valid add the new category to the dropdown menu
                    notValidStr = false;
                    var newOption = document.createElement("option");
                    newOption.text = newCategory;
                    newOption.value = newCategory.toLowerCase();
                    categoryMenu.add(newOption);
                    newOption.selected = true; // Select the option created
                }
            }
        }
    }
    
    function removeEquipmentFromInput (inputArrayName, equipment){
        // Get the actual array of the input
        var array =[];
        if(document.getElementById(inputArrayName).value != ""){
            array = JSON.parse(document.getElementById(inputArrayName).value);
        }

        // Remove the object from the array
        var index = array.findIndex(function(i) {
            return i.Name === equipment.Name;
        })
        array.splice(index, 1);
        
        // Convert the array again into JSON in order to later pass it through the request
        document.getElementById(inputArrayName).value = JSON.stringify(array);
    }

    function removeItem (inputArrayName, object){
        // Get the actual array of the input
        var array =[];
        if(document.getElementById(inputArrayName).value != ""){
            array = JSON.parse(document.getElementById(inputArrayName).value);
        }

        // Remove the object from the array
        var index = array.findIndex(function(i) {
            return i.Text === object.Text;
        })
        array.splice(index, 1);
        
        // Convert the array again into JSON in order to later pass it through the request
        document.getElementById(inputArrayName).value = JSON.stringify(array);
    }

    function updateGameTotalCost(){
        var gameCost;

        // Convert the value into an integer
        if(document.newGameForm.gameCost.value == ""){
            gameCost = 0;
        }
        else{
            gameCost = parseInt(document.newGameForm.gameCost.value);
        }

        // Game total cost = equipment total cost + game cost
        var totalCost = parseInt(document.getElementById("eqTotalCost").innerHTML) + gameCost;
        document.getElementById("totalCost").innerHTML = "$" + totalCost;

        // Change price category
        var priceCategory;
        if(totalCost == 0){
            priceCategory = "Free";
        }
        else if(totalCost > 0 && totalCost < 25){
            priceCategory = "Cheap";
        }
        else if(totalCost > 100){
            priceCategory = "Expensive";
        }
        else if(totalCost < 0){
            priceCategory = "Invalid";
        }
        else{            
            priceCategory = "Affordable";
        }
        document.getElementById("totalCostCategory").innerHTML = priceCategory;
    }

    function validatePrice(price){
        var priceNumber = price.replace("$", "");

        if (priceNumber < 0){
            return false;
        }
        else{
            return true;
        }
    }

    function validateReview(review){
        var regExp = /\b[0-5](\.[0-9])? out of 5 stars\b/; // Regular expresion to check if the review meets the mandatory phrase "x out of 5 stars" (x being a number from 0 to 5 allowing one single decimal)
        var boolValidReview = regExp.test(review);

        return boolValidReview;
    }

    function validateReviewsAndPrice(){
        var addedReviewsListList = document.getElementById("addedReviewsList"); // Get the list of added reviews

        // Check if there's any review added (if there is not, continue to price validation)
        if(addedReviewsListList.getElementsByTagName("li").length > 0 ){
            var addedReviewsList = document.getElementById("addedReviewsList").getElementsByTagName("li"); // Get each review

            for (let review of addedReviewsList) {
                var reviewText = review.childNodes[0].textContent; // Get the text of the review
                console.log(reviewText);
                var boolValidReview = validateReview(reviewText); // Validate the review

                // If invalid, show an error message to the user. If valid, continue to price validation
                if(!boolValidReview){
                    alert("ERROR: All reviews must contain the phrase \"x out of 5 stars\", where x is a number from 0 to 5 allowing a single decimal place.");
                    return false;
                }
            }
        }

        var gameFinalPrice = document.getElementById("totalCost").innerHTML; // Get the total cost of the game
        // Check if the price is negative
        var boolValidPrice = validatePrice (gameFinalPrice);
        if (!boolValidPrice){
            alert("ERROR: The final price of the game cannot be negative.");
            return false;
        }
        else{
            return true;
        }
    }
</script>


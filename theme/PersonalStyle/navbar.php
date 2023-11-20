<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } ?>

<nav class="navbar">
	<div class="left-aligned">
		<ul>
			<li><a href="<?php get_site_url(); ?>Index">Home</a></li>
			<li class="dropdown">Games
				<div class="dropdown-content">
					<?php
						
						$categoriesArray = getCategories();
						foreach ($categoriesArray as $category) {
							$href = '"Games?category='.$category.'"';
							echo '<a href='.$href.'>'.ucfirst($category).'</a>';
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
					?>
				</div>
			</li>
			<li><a href="<?php get_site_url(); ?>AddGame">Add game</a></li>
		</ul>
	</div>
	<div class="right-aligned">
		<ul>
			<li><a href="<?php get_site_url(); ?>About">About us</a></li>
			<li><a href="<?php get_site_url(); ?>Contact">Contact us</a></li>
			<li><a href="<?php get_site_url(); ?>Privacy">Privacy Policy</a></li>
			<li><a href="<?php get_site_url(); ?>Terms">Terms &amp; Conditions</a></li>
		</ul>
	</div>
</nav>
<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } ?>

<!DOCTYPE html>
<html>
	<!-- Head -->
	<?php include("head.php");?>

	<!-- Navigation bar -->
	<?php include("navbar.php");?>

	<!-- Page content -->
	<body id="<?php get_page_slug(); ?>">
		<div id="content">
			<h1><?php get_page_title(); ?></h1>
			<div id="page-content">
				<div class="page-text">
					<?php get_page_content(); ?>
				</div>
			</div>
		</div>
	</body>

	<!-- Footer -->
	<?php include("footer.php");?>
</html>
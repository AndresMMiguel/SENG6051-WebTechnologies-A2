<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); } ?>

<footer class="page-footer">
	<div class="footer-line">
		<ul>
			<li><a href="<?php get_site_url(); ?>Index">Home</a></li>
			<li><a href="<?php get_site_url(); ?>About">About us</a></li>
			<li><a href="<?php get_site_url(); ?>Contact">Contact us</a></li>
			<li><a href="<?php get_site_url(); ?>Privacy">Privacy Policy</a></li>
			<li><a href="<?php get_site_url(); ?>Terms">Terms & Conditions</a></li>
		</ul>
	</div>
	<div class="footer-line">
		<p id="pageMeta">
			Published on <span><?php get_page_date('F jS, Y'); ?></span>
		</p>
		<address>	
			<strong id="copyrightStatement">Copyright © 2023 Andres Moreno Miguel | c3465977 | c3465977@uon.edu.au | Master of Cyber Security | All rights reserved.</strong>
		</address>
	</div>
</footer>
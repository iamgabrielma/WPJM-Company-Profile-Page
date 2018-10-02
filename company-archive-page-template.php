<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="container">
	<div id="content" role="main">

		<?php the_post(); ?>
		<h1 class="entry-title"><?php /*the_title();*/ ?></h1>
		<h1 class="entry-title">Jobs by Company LTD 2 </h1>
		
		<?php get_search_form(); ?>
		
		<h2>COMPANY ARCHIVE PAGE TEMPLATE</h2>
		<ul>
			<?php wp_get_archives('type=postbypost'); ?>

		</ul>
		
		<h2>Archives by Subject:</h2>
		<ul>
			 <?php wp_list_categories(); ?>
		</ul>

	</div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
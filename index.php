<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php echo get_bloginfo('name'); ?></title>

<?php
wp_deregister_script( 'jquery' );
wp_enqueue_script( 'jquery', 'http://code.jquery.com/jquery-latest.js' );

// Add the effects
wp_enqueue_script( 'jquery-ui-effects-core', 'http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.core.js', array( 'jquery' ) );
wp_enqueue_script( 'jquery-ui-effects-slide', 'http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.slide.js', array( 'jquery', 'jquery-ui-effects-core' ) );

?>

<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>



<link href="<?php echo get_stylesheet_uri(); ?>" rel="stylesheet" type="text/css" />

<script>
	if(window.location.hash) {
		var hash = window.location.hash;
		var hashtourl = hash.substring(2)
		window.location.href = "/"+hashtourl;
	}
</script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<a class="shift" id="goarchive">&rarr;</a>
<a class="shift" id="gopost">&larr;</a>


<ul id="content">
	<li id="post">
		
		<div class="content">
			
			<?php the_post(); $do_not_duplicate = get_the_ID(); ?>
				
				<p id="hello">
					 <?php echo get_bloginfo('description'); ?> This blog post was published on <?php the_time('F j Y') ?>. <a href="<?php the_permalink(); ?>">&sect;</a> 
				</p>
			
			
				<h1><?php the_title(); ?></h1>
							
				<?php the_content(); ?>
			
		</div>
		
		<div id="footer">
			
			Using the <a href="http://tomcreighton.com/glider-theme">Glider</a> theme.
		</div>
												
	</li>
	
	<li id="archive">
		
		<div class="content">
			
			<ul>
				<?php 
				$my_query = new WP_Query( array( "nopaging"=>true ) ); 
				while ($my_query->have_posts()) : 
					$my_query->the_post();
				?>
					<li><h2><a rel="<? the_permalink(); ?>" id="<? the_id(); ?>" title="<?php echo( basename( get_permalink() ) ); ?>"><?php the_title(); ?></a></h2> <span><?php the_time('F j Y') ?></span></li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
			
		</div>
												
	</li>
	
</ul>



<script>
	$(document).ready(function () {
		// Cached DOM references
		var $goarchive = $('#goarchive'),
			$gopost = $('#gopost'),
			$archive = $('#archive'),
			$post = $('#post');

		function goarchive() {
			$goarchive.fadeOut(300);
			$post.hide('slide', {
				direction: 'left'
			}, 600, function () {
				$archive.scrollTop(0);
				$archive.show('slide', {
					direction: 'right'
				}, 600);
				$gopost.fadeIn(300);
			});
		};

		function gopost() {
			$gopost.fadeOut(300);
			$archive.hide('slide', {
				direction: 'right'
			}, 600, function () {
				$post.scrollTop(0);
				$post.show('slide', {
					direction: 'left'
				}, 600);
				$goarchive.fadeIn(300);
			});
		};

		function loadpost() {

			var perma = $(this).attr('rel'),
				postid = $(this).attr('id'),
				postitle = $(this).attr('title');

			$(this).parent().parent().addClass('loader');

			$post.load(perma + ' #post', function () {
				$gopost.fadeOut(300);
				$archive.hide('slide', {
					direction: 'right'
				}, 600, function () {
					$post.scrollTop(0);
					$goarchive.fadeIn(300);
					$post.show('slide', {
						direction: 'left'
					}, 600, function () {
						$('#' + postid).parent().parent().removeClass('loader');
						window.location.hash = '/' + postitle;
						if (typeof twttr != 'undefined') {
							twttr.widgets.load()
						}
					});
				});
			});
		}

		$goarchive.live('click', goarchive);

		$gopost.live('click', gopost);

		$archive.find('a').live('click', loadpost);

		
		/* arrow key navigation */
		
		$(document).keydown(function(ev) {
			if(ev.which === 39) {
				if ( $post.is(':visible') ) {
					goarchive();
				}				
				return false;
			}
			
			if(ev.which === 37) {
				if ( $archive.is(':visible') ) {
					gopost();
				}
				return false;
			}
		});
		
		
	});

</script>

<?php wp_footer(); ?>

</body>
</html>

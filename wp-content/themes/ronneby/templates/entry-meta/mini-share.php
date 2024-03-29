<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
	$data_title = get_the_title();
	$data_link = get_permalink();
?>
<?php
/*
<div class="share-cover">
    <div class="entry-share">
	    <ul class="entry-share-popup" data-directory="<?php echo get_template_directory_uri(); ?>">
		    <li>
			    <a class="entry-share-link-facebook" data-title="<?php echo esc_attr($data_title); ?>" data-url="<?php echo esc_url($data_link); ?>" href="#">
					<span class="box-name"><?php _e('Facebook', 'dfd'); ?></span>
			    </a>
		    </li>
		    <li>
			    <a class="entry-share-link-twitter" data-title="<?php echo esc_attr($data_title); ?>" data-url="<?php echo esc_url($data_link); ?>" href="#">
					<span class="box-name"><?php _e('Twitter', 'dfd'); ?></span>
			    </a>
		    </li>
		    <li>
			    <a class="entry-share-link-googleplus" data-title="<?php echo esc_attr($data_title); ?>" data-url="<?php echo esc_url($data_link); ?>" href="#">
					<span class="box-name"><?php _e('Google Plus', 'dfd'); ?></span>
			    </a>
		    </li>
		    <li>
				<a class="entry-share-link-linkedin" data-title="<?php echo esc_attr($data_title); ?>" data-url="<?php echo esc_url($data_link); ?>" href="#">
					<span class="box-name"><?php _e('LinkedIN', 'dfd'); ?></span>
				</a>
			</li>
	    </ul>
    </div>
</div>
*/
?>
<div class="share-cover" data-directory="<?php echo get_template_directory_uri(); ?>" data-url="<?php echo esc_url($data_link) ?>">
	<div class="entry-share">
		<ul class="rrssb-buttons entry-share">
			<li class="rrssb-facebook">
				<!--  Replace with your URL. For best results, make sure you page has the proper FB Open Graph tags in header:
					  https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content/ -->
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($data_link) ?>" class="popup entry-share-link-facebook">
					<span class="box-name"><?php _e('Facebook', 'dfd'); ?></span>
				</a>
			</li>
			<li class="rrssb-twitter">
				<!-- Replace href with your Meta and URL information  -->
				<a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr($data_link) ?>" class="popup entry-share-link-twitter">
					<span class="box-name"><?php _e('Twitter', 'dfd'); ?></span>
				</a>
			</li>
			<li class="rrssb-googleplus">
				<!-- Replace href with your meta and URL information.  -->
				<a href="https://plus.google.com/share?url=<?php echo esc_attr($data_link) ?>" class="popup entry-share-link-googleplus">
					<span class="box-name"><?php _e('Google Plus', 'dfd'); ?></span>
				</a>
			</li>
			<li class="rrssb-linkedin">
				<!-- Replace href with your meta and URL information -->
				<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_attr($data_link) ?>" class="popup entry-share-link-linkedin">
					<span class="box-name"><?php _e('LinkedIN', 'dfd'); ?></span>
				</a>
			</li>
		</ul>
	</div>
</div>
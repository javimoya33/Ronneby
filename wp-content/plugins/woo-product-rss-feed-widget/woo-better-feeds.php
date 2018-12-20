<?php

function getRssProductPrice() {
	global $post;
	$product = new WC_Product( $post->ID );
	$theproductprice = $product->price;
	return $theproductprice;
}

function wcbfeedAddImageToContent($content) {
	$wcbfeed_rss_image_size = get_option('wcbfeed_rss_image_size');
	global $post;
	if(get_option('wcbfeed_rss_export_image')==1) {
		if ( has_post_thumbnail( $post->ID ) ){
			$content = '' . get_the_post_thumbnail( $post->ID, $wcbfeed_rss_image_size, array( 'style' => 'float:left; margin:-50px 15px 15px 0;' ) ) . '' . $content;
		}
	}
	if(get_option('wcbfeed_rss_export_price')==1) {
	  $content = '<br><b><p style="color:green;">Price:' . getRssProductPrice() . '<br></p></b>' . $content;
	}
	return $content;
}


add_filter('the_excerpt_rss', 'wcbfeedAddImageToContent');
add_filter('the_content_feed', 'wcbfeedAddImageToContent');


function addImageUrlToCustomTag() {
	global $post;
	$theproductimage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'medium' );
	return $theproductimage[0];
}



add_action('rss_item','wcbfeedAddExtraItems');
add_action('rss2_item','wcbfeedAddExtraItems');

function wcbfeedAddExtraItems() { ?>

<?php if(get_option('wcbfeed_rss_export_price')==1 || get_option('wcbfeed_rss_export_image')==1) {?>
<product>
<?php if(get_option('wcbfeed_rss_export_price')==1) { ?>
	<price><?php print_r(getRssProductPrice());?></price>
<?php } ?>
<?php if(get_option('wcbfeed_rss_export_image')==1) { ?>
<image><?php echo addImageUrlToCustomTag();?></image>
<?php } ?>
</product>

<?php } }

include('wcbfeed.php');
?>
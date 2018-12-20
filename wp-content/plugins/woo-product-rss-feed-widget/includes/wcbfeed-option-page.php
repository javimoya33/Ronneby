<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><h3><?php _e('Configuration','woocommerce-better-feeds');?></h3></th>
        </tr>
    </thead>
    <tbody>
	    <tr>
		  <td>This Settings Page Adds product image and Price fields to your woocommerce Product RSS feed output, It's only needed on the source website that produces the product feed. <br/>If you are using the widget on target site only, you dont need to use this. To add the widget to your site visit your <a href="widgets.php"> admin Appearence > widgets page</a></td>
	    </tr>
		<tr>
        	<td>
				<form method="post" action="options.php">
				<?php settings_fields( 'wcbfeed-settings-group' ); ?>
				<table class="form-table noborder">

                	<tr valign="top">
                    	<th scope="row"><?php _e('Include Image to Feeds','woocommerce-better-feeds');?></th>
						<td>
							<input name="wcbfeed_rss_export_image" type="checkbox" value="1" <?php checked( '1', get_option( 'wcbfeed_rss_export_image' ) ); ?> />

						</td>
                	</tr>

                	<tr valign="top">
                    	<th scope="row"><?php _e('Include Price to Feeds','woocommerce-better-feeds');?></th>
						<td>
							<input name="wcbfeed_rss_export_price" type="checkbox" value="1" <?php checked( '1', get_option( 'wcbfeed_rss_export_price' ) ); ?> />						</td>
                	</tr>

                	<tr valign="top">
                    	<th scope="row"><?php _e('Choose Image Size','woocommerce-better-feeds');?></th>
						<td>
	                        <?php $image_sizes = get_intermediate_image_sizes(); ?>
	                        <select name="wcbfeed_rss_image_size">
	                          <?php foreach ($image_sizes as $size_name => $size_attrs): var_dump($size_attrs);?>
	                            <option value="<?php echo $size_attrs ?>" <?php echo $wcbfeed_rss_image_size == $size_attrs?'selected="selected"':''; ?>><?php echo ucwords(str_replace(array('-','_'),' ',$size_attrs)); ?></option>
	                          <?php endforeach; ?>
	                          <option value="full" <?php echo $wcbfeed_rss_image_size == 'full'?'selected="selected"':''; ?>>Full Size</option>
	                        </select>
                    	</td>
                	</tr>
					<tr valign="top">
                    	<th scope="row">&nbsp;</th>
						<td bordercolor="red">
                        	<input type="submit" name="submit-bpu" class="button-primary" value="<?php _e('Save Changes','woocommerce-better-feeds') ?>" />
                    	</td>
                	</tr>

            	</table>
        		</form>
        		<br />
			</td>
		</tr>
	</tbody>
</table>
<br/>
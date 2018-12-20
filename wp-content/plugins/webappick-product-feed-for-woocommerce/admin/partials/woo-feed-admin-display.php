<?php

/**
 * Feed Making View
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 */

$dropDown = new Woo_Feed_Dropdown();
$product = new Woo_Feed_Products();
$attributes=new Woo_Feed_Default_Attributes();
$product->load_attributes();
?>

<div class="wrap" id="Feed">
    <h2><?php echo _e('WooCommerce Product Feed', 'woo-feed'); ?></h2>
    <table class="widefat fixed">
        <tbody>
        <tr>
            <td align="center"><b><a target="_blank" style="color:#e96656;"
                                     href="https://webappick.com/plugin/woocommerce-product-feed-pro/">GET PREMIUM</a></b></td>
            <td align="center"><b><a target="_blank" style="color:#0073aa;"
                                     href="http://webappick.helpscoutdocs.com/article/17-woocommerce-product-feed">DOCUMENTATION</a></b></td>
            <td align="center"><b><a style="color:#ee264a;" target="_blank"
                                     href="https://www.youtube.com/channel/UCzy3G9pA3yVgo0YZc-yJmfw">VIDEOS</a></b>
            </td>
            <td align="center"><b><a target="_blank" style="color:#0DD41E;"
                                     href="https://webappick.com/support/">SUPPORT</a></b></td>
        </tr>
        </tbody>
    </table>
    <br/>

    <form action="" id="generateFeed" class="generateFeed" method="post">
        <table class="widefat fixed">
            <thead>
            <tr>
                <td colspan="2"><b><?php echo _e('Content Settings', 'woo-feed'); ?></b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="30%"><b><?php echo _e('Template', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td>
                    <select wftitle="Select a template" name="provider" id="provider"
                            class="generalInput wfmasterTooltip" required>
                        <?php echo $dropDown->merchantsDropdown(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b><?php echo _e('File Name', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td><input wftitle="Filename should be unique. Otherwise it will override the existing filename."
                           name="filename" type="text" class="generalInput wfmasterTooltip" required="required"/>
                </td>
            </tr>
            <tr>
                <td><b><?php echo _e('Feed Type', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td>
                    <select name="feedType" id="feedType" class="generalInput" required>
                        <option value=""></option>
                        <option value="xml">XML</option>
                        <option value="csv">CSV</option>
                        <option value="txt">TXT</option>
                    </select>
                </td>
            </tr>
            <tr class="itemWrapper" style="display: none;">
                <td><b><?php echo _e('Items Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td><input name="itemsWrapper" type="text" value="products" class="generalInput" required="required"/>
                </td>
            </tr>
            <tr class="itemWrapper" style="display: none;">
                <td><b><?php echo _e('Single Item Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td><input name="itemWrapper" type="text" value="product" class="generalInput" required="required"/>
                </td>
            </tr>
            <tr class="itemWrapper" >
                <td><b><?php echo _e('Extra Header', 'woo-feed'); ?> </b></td>
                <td>
                    <textarea name="extraHeader" id="" style="width: 100%" placeholder="Insert Extra Header value. Press enter at the end of each line." rows="3"></textarea>
                </td>
            </tr>
            <tr class="wf_csvtxt" style="display: none;">
                <td><b><?php echo _e('Delimiter', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td>
                    <select name="delimiter" id="delimiter" class="generalInput">
                        <option value=",">Comma</option>
                        <option value="tab">Tab</option>
                        <option value=":">Colon</option>
                        <option value=" ">Space</option>
                        <option value="|">Pipe</option>
                        <option value=";">Semi Colon</option>
                    </select>
                </td>
            </tr>
            <tr class="wf_csvtxt" style="display: none;">
                <td><b><?php echo _e('Enclosure', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
                <td>
                    <select name="enclosure" id="enclosure" class="generalInput">
                        <option value='double'>"</option>
                        <option value="single">'</option>
                        <option value=" ">None</option>
                    </select>
                </td>
            </tr>

            </tbody>
        </table>
        <br/><br/>

        <div id="providerPage">
<?php //include plugin_dir_path(__FILE__) . "bing/add-feed.php"; ?>
        </div>
    </form>
</div><!-- /wrap -->


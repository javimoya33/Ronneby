<?php
$dropDown = new Woo_Feed_Dropdown();
$product = new Woo_Feed_Products();
$product->load_attributes();
?>
<div class="wrap" id="Feed">
<h2><?php echo _e('WooCommerce Product Feed', 'woo-feed'); ?></h2>
<table class="widefat fixed">
    <tbody>
    <tr>
        <td align="center"><b><a target="_blank"
                                 href="http://webappick.helpscoutdocs.com/article/17-woocommerce-product-feed">Help
                    & Docs</a></b></td>
        <td align="center"><b><a style="color:#ee264a;" target="_blank"
                                 href="https://www.youtube.com/channel/UCzy3G9pA3yVgo0YZc-yJmfw">VIDEOS</a></b>
        </td>
        <td>Contact <b style="color:#2CC185;">support@webappick.com</b> for support.</td>
    </tr>
    </tbody>
</table>
<br/>

<form action="" name="feed" id="updatefeed"
      method="post">

<table class=" widefat fixed">
    <thead>
    <tr>
        <td colspan="2"><b><?php echo _e('Content Settings', 'woo-feed'); ?></b></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="30%"><b><?php echo _e('Template', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="provider" id="provider" class="generalInput">
                <?php echo $dropDown->merchantsDropdown(esc_attr($feedRules['provider'])); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b><?php echo _e('File Name', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="filename"
                   value="<?php echo isset($feedRules['filename']) ? esc_attr($feedRules['filename']) : ''; ?>"
                   type="text"
                   class="generalInput"/></td>
    </tr>
    <tr>
        <td><b><?php echo _e('Feed Type', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="feedType" id="feedType" class="generalInput">
                <option <?php echo ($feedRules['feedType'] == "xml") ? 'selected="selected"' : ''; ?> value="xml">
                    XML
                </option>
                <option <?php echo ($feedRules['feedType'] == "csv") ? 'selected="selected"' : ''; ?> value="csv">
                    CSV
                </option>
                <option <?php echo ($feedRules['feedType'] == "txt") ? 'selected="selected"' : ''; ?> value="txt">
                    TXT
                </option>
            </select>
        </td>
    </tr>
    <tr class="itemWrapper" <?php echo ($feedRules['feedType'] != "xml") ? 'style="display: none;"' : ''; ?> >
        <td><b><?php echo _e('Items Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="itemsWrapper" type="text"
                   value="<?php echo ($feedRules['feedType'] == "xml") && isset($feedRules['itemsWrapper']) ? esc_attr($feedRules['itemsWrapper']) : 'products'; ?>"
                   class="generalInput" required="required"/>
        </td>
    </tr>
    <tr class="itemWrapper" <?php echo ($feedRules['feedType'] != "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php echo _e('Single Item Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="itemWrapper" type="text"
                   value="<?php echo ($feedRules['feedType'] == "xml") && isset($feedRules['itemWrapper']) ? esc_attr($feedRules['itemWrapper']) : 'product'; ?>"
                   class="generalInput" required="required"/>
        </td>
    </tr>
    <tr class="itemWrapper" >
        <td><b><?php echo _e('Extra Header', 'woo-feed'); ?> </b></td>
        <td>
            <textarea name="extraHeader" id="" style="width: 100%" placeholder="Insert Extra Header value. Press enter at the end of each line."
                      rows="3"><?php echo isset($feedRules['extraHeader']) ? $feedRules['extraHeader'] : ''; ?></textarea>
        </td>
    </tr>
    <tr class="wf_csvtxt" <?php echo ($feedRules['feedType'] == "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php echo _e('Delimiter', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="delimiter" id="delimiter" class="generalInput">
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "," ? 'selected="selected"' : ''; ?>
                    value=",">Comma
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "tab" ? 'selected="selected"' : ''; ?>
                    value="tab">Tab
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == ":" ? 'selected="selected"' : ''; ?>
                    value=":">Colon
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == " " ? 'selected="selected"' : ''; ?>
                    value=" ">Space
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "|" ? 'selected="selected"' : ''; ?>
                    value="|">Pipe
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == ";" ? 'selected="selected"' : ''; ?>
                    value=";">Semi Colon
                </option>
            </select>
        </td>
    </tr>
    <tr class="wf_csvtxt" <?php echo ($feedRules['feedType'] == "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php echo _e('Enclosure', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="enclosure" id="enclosure" class="generalInput">
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == " " ? 'selected="selected"' : ''; ?>
                    value=" ">None
                </option>
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == "double" ? 'selected="selected"' : ''; ?>
                    value='double'>"
                </option>
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == "single" ? 'selected="selected"' : ''; ?>
                    value="single">'
                </option>
            </select>
        </td>
    </tr>
    </tbody>
</table>
<br/>

<ul class="wf_tabs">
<li>
    <input type="radio" name="wf_tabs" id="tab1" checked/>
    <label class="wf-tab-name" for="tab1">Feed Config</label>

    <div id="wf-tab-content1" class="wf-tab-content">
        <table class="table tree widefat fixed sorted_table mtable" width="100%" id="table-1">
            <thead>
            <tr>
                <th></th>
                <th><?php echo ucfirst($provider); ?> <?php echo _e('Attributes', 'woo-feed'); ?></th>
                <th><?php echo _e('Prefix', 'woo-feed'); ?></th>
                <th><?php echo _e('Type', 'woo-feed'); ?></th>
                <th><?php echo _e('Value', 'woo-feed'); ?></th>
                <th><?php echo _e('Suffix', 'woo-feed'); ?></th>
                <th><?php echo _e('Output Type', 'woo-feed'); ?></th>
                <th><?php echo _e('Output Limit', 'woo-feed'); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($feedRules['mattributes']) > 0) {
                $mAttributes = $feedRules['mattributes'];
                $wooAttributes = $feedRules['attributes'];
                $type = $feedRules['type'];
                $default = $feedRules['default'];
                $prefix = $feedRules['prefix'];
                $suffix = $feedRules['suffix'];
                $outputType = $feedRules['output_type'];
                $limit = $feedRules['limit'];
                $counter = 0;
                foreach ($mAttributes as $merchant => $mAttribute) {

                    ?>
                    <tr>
                        <td>
                            <i class="wf_sortedtable dashicons dashicons-menu"></i>
                        </td>
                        <td>
                            <input type="text" name="mattributes[]" value="<?php echo esc_attr($mAttribute); ?>"
                                   required
                                   class="wf_mattributes"/>
                        </td>
                        <td>
                            <input type="text" name="prefix[]" value="<?php echo esc_html($prefix[$merchant]); ?>"
                                   autocomplete="off"
                                   class="wf_ps"/>
                        </td>
                        <td>
                            <select name="type[]" id="" class="attr_type wfnoempty">
                                <option <?php echo ($type[$merchant] == "attribute") ? 'selected="selected"' : ''; ?>
                                    value="attribute"><?php echo _e('Attribute', 'woo-feed'); ?>
                                </option>
                                <option <?php echo ($type[$merchant] == "pattern") ? 'selected="selected"' : ''; ?>
                                    value="pattern"><?php echo _e('Pattern', 'woo-feed'); ?>
                                </option>
                            </select>

                        </td>
                        <td>
                            <select <?php echo ($type[$merchant] == "attribute") ? '' : 'style=" display: none;"'; ?>
                                name="attributes[]" id=""
                                class="wf_attr wf_attributes">
                                <?php echo $product->attributeDropdown(esc_attr($wooAttributes[$merchant])); ?>
                            </select>

                            <input <?php echo ($type[$merchant] == "pattern") ? '' : 'style=" display: none;"'; ?>
                                autocomplete="off"
                                class="wf_default wf_attributes" type="text" name="default[]"
                                value="<?php echo esc_attr($default[$merchant]); ?>"/>

                        </td>
                        <td>
                            <input type="text" name="suffix[]" value="<?php echo esc_html($suffix[$merchant]); ?>"
                                   autocomplete="off"
                                   class="wf_ps"/>
                        </td>

                        <td>
                            <select name="output_type[<?php echo $counter; ?>][]" id=""
                                    class="outputType wfnoempty" <?php echo (count($outputType[$counter]) > 1) ? 'multiple="multiple"' : ''; ?>>
                                <option <?php echo (in_array('1', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="1">Default
                                </option>
                                <option <?php echo (in_array('2', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="2">Strip Tags
                                </option>
                                <option <?php echo (in_array('3', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="3">UTF-8 Encode
                                </option>
                                <option <?php echo (in_array('4', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="4">htmlentities
                                </option>
                                <option <?php echo (in_array('5', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="5">Integer
                                </option>
                                <option <?php echo (in_array('6', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="6">Price
                                </option>
                                <option <?php echo (in_array('7', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="7">Remove Space
                                </option>
                                <option <?php echo (in_array('10', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="10">Remove ShortCodes
                                </option>
                                <option <?php echo (in_array('9', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="9">Remove Special Character
                                </option>
                                <option <?php echo (in_array('8', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="8">CDATA
                                </option>
                            </select>
                            <i class="dashicons dashicons-editor-expand expandType"></i>
                            <i style="display: none;"
                               class="dashicons dashicons-editor-contract contractType"></i>
                        </td>
                        <td>
                            <input type="text" name="limit[]" value="<?php echo esc_attr($limit[$merchant]); ?>"
                                   autocomplete="off"
                                   class="wf_ps"/>
                        </td>
                        <td>
                            <i class="delRow dashicons dashicons-trash"></i>
                        </td>
                    </tr>

                    <?php
                    $counter++;
                }
            }
            ?>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">
                    <button type="button" class="button-small button-primary" id="wf_newRow">
                        <?php echo _e('Add New Row', 'woo-feed'); ?>
                    </button>
                </td>
                <td colspan="6">

                </td>
            </tr>
            </tfoot>
        </table>
        <table class=" widefat fixed">
            <tr>
                <td align="left" class="">
                    <div class="makeFeedResponse"></div>
                    <div class="makeFeedComplete"></div>
                </td>
                <td align="right">
                    <button name="<?php echo isset($_GET['action']) ? esc_attr($_GET['action']) : ''; ?>"
                            type="submit" id="submit"
                            class="wfbtn updatefeed">
                        <?php echo _e('Update and Generate Feed', 'woo-feed'); ?>
                    </button>
                </td>
            </tr>
        </table>
    </div>
</li>
<li>
    <input type="radio" name="wf_tabs" id="tab3"/>
    <label class="wf-tab-name" for="tab3"><?php echo _e('FTP', 'woo-feed'); ?></label>

    <div id="wf-tab-content3" class="wf-tab-content">
        <table class="table widefat fixed mtable" width="100%">
            <tbody>
            <tr>
                <td><?php echo _e('Enabled', 'woo-feed'); ?></td>
                <td>
                    <select name="ftpenabled" id="">
                        <option <?php echo ($feedRules['ftpenabled'] == "0") ? 'selected="selected"' : ''; ?>
                            value="0"><?php echo _e('Disabled', 'woo-feed'); ?>
                        </option>
                        <option <?php echo ($feedRules['ftpenabled'] == "1") ? 'selected="selected"' : ''; ?>
                            value="1"><?php echo _e('Enabled', 'woo-feed'); ?>
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <td><?php echo _e('Host Name', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftphost']); ?>" name="ftphost"
                           autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php echo _e('User Name', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftpuser']); ?>" name="ftpuser"
                           autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php echo _e('Password', 'woo-feed'); ?></td>
                <td><input type="password" value="<?php echo esc_attr($feedRules['ftppassword']); ?>"
                           name="ftppassword" autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php echo _e('Path', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftppath']); ?>" name="ftppath"
                           autocomplete="off"/></td>
            </tr>

            </tbody>
        </table>
        <table class=" widefat fixed">
            <tr>
                <td align="left" class="">
                    <div class="makeFeedResponse"></div>
                    <div class="makeFeedComplete"></div>
                </td>
                <td align="right">
                    <button name="<?php echo isset($_GET['action']) ? esc_attr($_GET['action']) : ''; ?>"
                            type="submit" id="submit"
                            class="wfbtn updatefeed">
                        <?php echo _e('Update and Generate Feed', 'woo-feed'); ?>
                    </button>
                </td>
            </tr>
        </table>
    </div>
</li>

</ul>
</form>
</div>
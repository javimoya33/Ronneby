<?php

/**
 * A class definition responsible for processing and mapping product according to feed rules and make the feed
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class WF_Engine
{
    /**
     * This variable is responsible for mapping store attributes to merchant attribute
     *
     * @since   1.0.0
     * @var     array $mapping Map store attributes to merchant attribute
     * @access  private
     */
    private $mapping;

    /**
     * Store product information
     *
     * @since   1.0.0
     * @var     array $storeProducts
     * @access  public
     */
    private $storeProducts;

    /**
     * New product information
     *
     * @since   1.0.0
     * @var     array $products
     * @access  private
     */
    private $products;

    /**
     * Contain Feed Rules
     *
     * @since   1.0.0
     * @var     array $rules
     * @access  private
     */
    private $rules;

    public function __construct($Products, $rules)
    {
        $this->rules = $rules;
        $this->storeProducts = $Products;
    }

    public function stripInvalidXml($value) {
        $ret = "";
        $current="";
        if (empty($value)) {
            return $ret;
        }
        $length = strlen($value);
        for ($i=0; $i < $length; $i++) {
            $current = ord($value{$i});
            if (($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF))) {
                $ret .= chr($current);
            }
            else {
                $ret .= "";
            }
        }
        return $ret;
    }

    /**
     * Configure the feed according to the rules
     * @return array
     */
    public function mapProductsByRules()
    {
        $productClass = new Woo_Feed_Products();
        $attributes = $this->rules['attributes'];
        $prefix = $this->rules['prefix'];
        $suffix = $this->rules['suffix'];
        $outputType = $this->rules['output_type'];
        $limit = $this->rules['limit'];
        $merchantAttributes = $this->rules['mattributes'];
        $type = $this->rules['type'];
        $default = $this->rules['default'];
        $feedType = $this->rules['feedType'];

        $wf_attr = array();
        $wf_cattr = array();

        # Map Merchant Attributes and Woo Attributes
        $countAttr = 0;
        update_option('wpf_progress', 'Mapping Attributes');

        if (count($merchantAttributes)) {
            foreach ($merchantAttributes as $key => $attr) {
                if ($type[$key] == 'attribute') {
                    $this->mapping[$attr]['value'] = $attributes[$key];
                    $this->mapping[$attr]['suffix'] = $suffix[$key];
                    $this->mapping[$attr]['prefix'] = $prefix[$key];
                    $this->mapping[$attr]['type'] = $outputType[$key];
                    $this->mapping[$attr]['limit'] = $limit[$key];
                } else if ($type[$key] == 'pattern') {
                    $this->mapping[$attr]['value'] = "wf_pattern_$default[$key]";
                    $this->mapping[$attr]['suffix'] = $suffix[$key];
                    $this->mapping[$attr]['prefix'] = $prefix[$key];
                    $this->mapping[$attr]['type'] = $outputType[$key];
                    $this->mapping[$attr]['limit'] = $limit[$key];
                }
                $countAttr++;
            }
        }

        # Process Dynamic Attributes and Category Mapping
        if (count($this->mapping)) {
            foreach ($this->mapping as $mkey => $attr) {
                if (strpos($attr['value'], 'wf_attr_') !== false) {
                    $wf_attr[] = $attr['value'];
                }

                if (strpos($attr['value'], 'wf_cattr_') !== false) {
                    $wf_cattr[] = $attr['value'];
                }
            }

            # Init Woo Attributes, Custom Attributes and Taxonomies
            if (count($this->storeProducts)) {
                if (count($wf_attr) || count($wf_cattr)) {
                    foreach ($this->storeProducts as $key => $value) {
                        if ($value['type'] == 'variation') {
                            $id = $value['item_group_id'];
                        } else {
                            $id = $value['id'];
                        }

                        # Get Woo Attributes
                        if (count($wf_attr)) {
                            foreach ($wf_attr as $attr_key => $attr_value) {
                                $this->storeProducts[$key][$attr_value] = implode(',', wc_get_product_terms($id, str_replace("wf_attr_", "", $attr_value), array('fields' => 'names')));
                            }
                        }

                        # Get Custom Attributes
                        if (count($wf_cattr)) {
                            foreach ($wf_cattr as $cattr_key => $cattr_value) {
                                if ($cattr_value == 'wf_cattr_upc') {
                                    $get_upc = $productClass->getUPCForVariableProducts($value['id'], 'wf_cattr_upc');
                                    if (is_array($get_upc) && array_key_exists($value['id'], $get_upc)) {
                                        $this->storeProducts[$key][$cattr_value] = $get_upc[$value['item_group_id']];
                                    } else {
                                        $this->storeProducts[$key][$cattr_value] = $productClass->getAttributeValue($value['id'], str_replace("wf_cattr_", "", $cattr_value));
                                    }
                                } else {
                                    $this->storeProducts[$key][$cattr_value] = $productClass->getAttributeValue($value['id'], str_replace("wf_cattr_", "", $cattr_value));
                                }
                            }
                        }

                    }
                }
            }
        }

        # Make Product feed array according to mapping
        if (count($this->storeProducts)) {
            $totalProduct = count($this->storeProducts);
            $count = 1;
            foreach ($this->storeProducts as $key => $value) {
                $i = 0;
                update_option('wpf_progress', "Processed $count Products Out of $totalProduct");
                foreach ($this->mapping as $attr => $rules) {
                    if (array_key_exists($rules['value'], $value)) {
                        $output = $value[$rules['value']];
                        if (!empty($output)) {
                            if(count($rules['type'])){
                                foreach ($rules['type'] as $key22 => $value22) {
                                    # Format Output According to output type
                                    if ($value22 == 2) { # Strip Tags
                                        $output = strip_tags(html_entity_decode($output));
                                    } elseif ($value22 == 3) { # UTF-8 Encode
                                        $output = utf8_encode($output);
                                    } elseif ($value22 == 4) { # htmlentities
                                        $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
                                    } elseif ($value22 == 5) { # Integer
                                        $output = absint($output);
                                    } elseif ($value22 == 6) { # Price
                                        $output = number_format($output, 2, '.', '');
                                    } elseif ($value22 == 7) { # Delete Space
                                        $output = trim($output);
                                    } elseif ($value22 == 8) { # CDATA
                                        $output = '<![CDATA[' . $output . ']]>';
                                    }elseif ($value22 == 9) {
                                        $output=$this->stripInvalidXml($output);
                                        //$output = preg_replace( '/[^[:print:]]/',' ',$output);
                                    }else if($value22 == 10){
                                        $output=preg_replace("/\[[^]]+\]/","",$output);
                                    }
                                }
                            }

                            # Format Output According to output limit
                            if (!empty($rules['limit']) && is_numeric($rules['limit']) && strpos($output, "<![CDATA[") !== false) {
                                $output = str_replace(array("<![CDATA[", "]]>"), array("", ""), $output);
                                $output = substr($output, 0, $rules['limit']);
                                $output = '<![CDATA[' . $output . ']]>';
                            } elseif (!empty($rules['limit']) && is_numeric($rules['limit'])) {
                                $output = substr($output, 0, $rules['limit']);
                            }

                            # Prefix and Suffix Assign
                            if (strpos($output, "<![CDATA[") !== false) {
                                $output = str_replace(array("<![CDATA[", "]]>"), array("", ""), $output);
                                if(substr($output, 0, 4 ) === "http"){
                                    $output = $this->make_url_with_parameter($rules['prefix'],$output,$rules['suffix']);
                                }else{
                                    if($attr=='price'){
                                        $suffix=!empty($rules['suffix'])?trim($rules['suffix']):$rules['suffix'];
                                        $output = $rules['prefix'].$output." ".$suffix;
                                    }else{
                                        $output = $rules['prefix'].$output.$rules['suffix'];
                                    }
                                }
                                $output = '<![CDATA[' . $output . ']]>';
                            } else {
                                if(substr($output, 0, 4 ) === "http"){
                                    $output = $this->make_url_with_parameter($rules['prefix'],$output,$rules['suffix']);
                                }else{
                                    if($attr=='price'){
                                        $suffix=!empty($rules['suffix'])?trim($rules['suffix']):$rules['suffix'];
                                        $output = $rules['prefix'].$output." ".$suffix;
                                    }else{
                                        $output = $rules['prefix'].$output.$rules['suffix'];
                                    }
                                }
                            }
                        } elseif ($output=="0"){
                            $output="0";
                        } else{
                            $output="";
                        }

                        $attr = trim($attr);
                        $this->products[$key][$attr] = $output;
                    } else {

                        if (!empty($default[$i])) {
                            $output = str_replace("wf_pattern_", "", $rules['value']);
                            if (!empty($output)) {
                                # Format Output According to output type
                                if(count($rules['type'])){
                                    foreach ($rules['type'] as $key22 => $value22) {
                                        if ($value22 == 2) { # Strip Tags
                                            $output = strip_tags(html_entity_decode($output));
                                        } elseif ($value22 == 3) { # UTF-8 Encode
                                            $output = utf8_encode($output);
                                        } elseif ($value22 == 4) { # htmlentities
                                            $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
                                        } elseif ($value22 == 5) { # Integer
                                            $output = absint($output);
                                        } elseif ($value22 == 6) { # Price
                                            $output = number_format($output, 2, '.', '');
                                        } elseif ($value22 == 7) { # Delete Space
                                            $output = trim($output);
                                        } elseif ($value22 == 8) { # CDATA
                                            $output = '<![CDATA[' . $output . ']]>';
                                        }elseif ($value22 == 9) {
                                            $output=$this->stripInvalidXml($output);
                                            //$output = preg_replace( '/[^[:print:]]/',' ',$output);
                                        }else if($value22 == 10){
                                            $output=preg_replace("/\[[^]]+\]/","",$output);
                                        }
                                    }
                                }

                                # Format Output According to output limit
                                if (!empty($rules['limit']) && is_numeric($rules['limit']) && strpos($output, "<![CDATA[") !== false) {
                                    $output = str_replace(array("<![CDATA[", "]]>"), array("", ""), $output);
                                    $output = substr($output, 0, $rules['limit']);
                                    $output = '<![CDATA[' . $output . ']]>';
                                } elseif (!empty($rules['limit']) && is_numeric($rules['limit'])) {
                                    $output = substr($output, 0, $rules['limit']);
                                }

                                # Prefix and Suffix Assign
                                if (strpos($output, "<![CDATA[") !== false) {
                                    $output = str_replace(array("<![CDATA[", "]]>"), array("", ""), $output);
                                    if(substr($output, 0, 4 ) === "http"){
                                        $output = $this->make_url_with_parameter($rules['prefix'],$output,$rules['suffix']);
                                    }else{
                                        if($attr=='price'){
                                            $suffix=!empty($rules['suffix'])?trim($rules['suffix']):$rules['suffix'];
                                            $output = $rules['prefix'].$output." ".$suffix;
                                        }else{
                                            $output = $rules['prefix'].$output.$rules['suffix'];
                                        }
                                    }
                                    $output = '<![CDATA[' . $output . ']]>';
                                } else {
                                    if(substr($output, 0, 4 ) === "http"){
                                        $output = $this->make_url_with_parameter($rules['prefix'],$output,$rules['suffix']);
                                    }else{
                                        if($attr=='price'){
                                            $suffix=!empty($rules['suffix'])?trim($rules['suffix']):$rules['suffix'];
                                            $output = $rules['prefix'].$output." ".$suffix;
                                        }else{
                                            $output = $rules['prefix'].$output.$rules['suffix'];
                                        }
                                    }
                                }
                            } elseif ($output=="0"){
                                $output="0";
                            } else{
                                $output="";
                            }

                            $attr = trim($attr);
                            $this->products[$key][$attr] = $output;
                        } else {
                            $output = str_replace("wf_pattern_", "", $rules['value']);
                            if ($output=="0"){
                                $output="0";
                            } else{
                                $output="";
                            }
                            $attr = trim($attr);
                            $this->products[$key][$attr] = $output;
                        }
                    }
                    $i++;
                }
                $count++;
            }
        }

        # Reindex product array key
        if (count($this->products)) {
            update_option('wpf_progress', "Shorting Products");
            sleep(1);
            $array = array();
            $ij = 0;
            foreach ($this->products as $key => $item) {
                $array[$ij] = $item;
                unset($this->products[$key]);
                $ij++;
            }
            return $this->products = $array;
        }
        return $this->products;
    }

    /**
     * Make proper URL using parameters
     * @param $prefix
     * @param $output
     * @param $suffix
     * @return string
     */

    public function make_url_with_parameter($prefix='',$output='',$suffix='')
    {
        $getParam=explode('?',$output);
        $URLParam=array();
        if(isset($getParam[1])){
            $URLParam=$this->proper_parse_str($getParam[1]);
        }

        $EXTRAParam=array();
        if(!empty($suffix)){
            $EXTRAParam=$this->proper_parse_str($suffix);
        }

        $params=array_merge($URLParam,$EXTRAParam);
        if(!empty($params)){
            $params=http_build_query($params);
            $baseURL=isset($getParam)?$getParam[0]:$output;
            $output = $prefix.$baseURL."?".$params;
        }else{
            $output = $prefix.$output.$suffix;
        }

        return $output;
    }

    /**
     * Parse URL parameter
     * @param $str
     * @return array
     */
    public function proper_parse_str($str="") {

        # result array
        $arr = array();

        if(empty($str)){
            return $arr;
        }

        # split on outer delimiter
        $pairs = explode('&', $str);

        if(!empty($pairs)){

            # loop through each pair
            foreach ($pairs as $i) {
                # split into name and value
                list($name,$value) = explode('=', $i, 2);

                # if name already exists
                if( isset($arr[$name]) ) {
                    # stick multiple values into an array
                    if( is_array($arr[$name]) ) {
                        $arr[$name][] = $value;
                    }else {
                        $arr[$name] = array($arr[$name], $value);
                    }
                }
                # otherwise, simply stick it in a scalar
                else {
                    $arr[$name] = $value;
                }
            }
        }elseif (!empty($str)){
            list($name,$value) = explode('=', $str, 2);
            $arr[$name] = $value;
        }

        # return result array
        return $arr;
    }

    /**
     * Responsible to make XML feed header
     *
     * @param $wrapper
     * @param string $extraHeader
     *
     * @return string
     */
    public function get_feed_header($wrapper, $extraHeader = "")
    {
        $output = '<?xml version="1.0" encoding="UTF-8" ?>
<' . $wrapper . '>';
        $output .= "\n";
        if (!empty($extraHeader)) {
            $output .= $extraHeader;
            $output .= "\n";
        }

        return $output;
    }

    /**
     * Responsible to make XML feed body
     * @var array $items Product array
     * @return string
     */
    public function get_feed()
    {
        $feed = "";
        $itemsWrapper = $this->rules['itemsWrapper'];
        $itemWrapper = $this->rules['itemWrapper'];
        $extraheader = $this->rules['extraHeader'];
        $feed .= $this->get_feed_header($itemsWrapper, $extraheader);
        if (count($this->storeProducts)) {
            foreach ($this->storeProducts as $each => $products) {
                $feed .= "      <" . $itemWrapper . ">";
                foreach ($products as $key => $value) {
                    if (!empty($value)) {
                        $feed .= $value;
                    }
                }
                $feed .= "\n      </" . $itemWrapper . ">\n";
            }
            $feed .= $this->get_feed_footer($itemsWrapper);

            return $feed;
        }

        return false;
    }

    /**
     * Responsible to make XML feed footer
     * @var $wrapper
     * @return string
     */
    public function get_feed_footer($wrapper)
    {
        $footer = "  </$wrapper>";

        return $footer;
    }

    /**
     * Process string for CSV
     * @param $string
     * @return mixed|string
     */
    public function processStringForCSV($string){
        if(!empty($string)){
            $string = str_replace( "\n", ' ', $string );
            $string = str_replace( "\r", ' ', $string );
            $string =trim($string);
            return $string;
        }else{
         return "";
        }
    }

    /**
     * Process string for TXT
     * @param $string
     * @return mixed|string
     */
    public function processStringForTXT($string){
        if(!empty($string)){
            $string = html_entity_decode( $string, ENT_HTML401 | ENT_QUOTES ); // Convert any HTML entities
//            $string = iconv(
//                'UTF-8',
//                'ASCII//TRANSLIT',
//                $string
//            );

            if (stristr( $string,'"' ) ) {
                $string = str_replace( '"', '""', $string );
            }
            $string = str_replace( "\n", ' ', $string );
            $string = str_replace( "\r", ' ', $string );
            $string = str_replace( "\t", ' ', $string );
            $string = trim($string);

            return $string;
        }else{
            return "";
        }
    }


    /**
     * Responsible to make TXT feed
     * @return string
     */
    public function get_txt_feed()
    {
        if (count($this->storeProducts)) {

            if ($this->rules['delimiter'] == 'tab') {
                $delimiter = "\t";
            } else {
                $delimiter = $this->rules['delimiter'];
            }

            if (!empty($this->rules['enclosure'])) {
                $enclosure = $this->rules['enclosure'];
                if ($enclosure == 'double') {
                    $enclosure = '"';
                } elseif ($enclosure == 'single') {
                    $enclosure = "'";
                } else {
                    $enclosure = "";
                }
            } else {
                $enclosure = "";
            }


            if (count($this->storeProducts)) {
                if(!empty($this->rules['extraHeader'])){
                    $headers=explode("\n",$this->rules['extraHeader']);
                    foreach($headers as $header){
                        $header =trim(preg_replace('/\s+/', ' ', $header));
                        $feed[]=explode(',',$header);
                    }
                }
                $headers = array_keys($this->storeProducts[0]);
                $feed[] = $headers;
                foreach ($this->storeProducts as $no => $product) {
                    $row = array();
                    foreach ($headers as $key => $header) {
                        $row[] = isset($product[$header]) ? $this->processStringForTXT($product[$header]):"";
                    }
                    $feed[] = $row;
                }
                $str = "";
                foreach ($feed as $fields) {
                    $str .= $enclosure . implode("$enclosure$delimiter$enclosure", $fields) . $enclosure . "\n";
                }
                return $str;
            }
        }

        return false;
    }

    /**
     * Responsible to make CSV feed
     * @return string
     */
    public function get_csv_feed()
    {
        if (count($this->storeProducts)) {
            if(!empty($this->rules['extraHeader'])){
                $headers=explode("\n",$this->rules['extraHeader']);
                foreach($headers as $header){
                    $header =trim(preg_replace('/\s+/', ' ', $header));
                    if(!empty($header))
                    $feed[]=explode(',',$header);
                }
            }
            $headers = array_keys($this->storeProducts[0]);
            $feed[] = $headers;
            foreach ($this->storeProducts as $no => $product) {
                $row = array();
                foreach ($headers as $key => $header) {
                    $row[] = isset($product[$header])?$this->processStringForCSV($product[$header]): "";;
                }
                $feed[] = $row;
            }
            return $feed;
        }
        return false;
    }
}
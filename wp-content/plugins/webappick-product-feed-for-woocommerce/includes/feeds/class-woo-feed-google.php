<?php

/**
 * Class Google
 *
 * Responsible for processing and generating feed for Google.com
 *
 * @since 1.0.0
 * @package Google
 *
 */
class Woo_Feed_Google
{

    /**
     * This variable is responsible for holding all product attributes and their values
     *
     * @since   1.0.0
     * @var     array $products Contains all the product attributes to generate feed
     * @access  public
     */
    public $products;

    /**
     * This variable is responsible for holding feed configuration form values
     *
     * @since   1.0.0
     * @var     array $rules Contains feed configuration form values
     * @access  public
     */
    public $rules;

    /**
     * This variable is responsible for mapping store attributes to merchant attribute
     *
     * @since   1.0.0
     * @var     array $mapping Map store attributes to merchant attribute
     * @access  public
     */
    public $mapping;

    /**
     * This variable is responsible for generate error logs
     *
     * @since   1.0.0
     * @var     array $errors hold error logs
     * @access  public
     */
    public $errors;

    /**
     * This variable is responsible for generate error logs
     *
     * @since   1.0.0
     * @var     array $warnings hold warnings logs
     * @access  public
     */
    public $warnings;

    /**
     * This variable is responsible for making error number
     *
     * @since   1.0.0
     * @var     int $errorCounter Generate error number
     * @access  public
     */
    public $errorCounter;

    /**
     * Feed Wrapper text for enclosing each product information
     *
     * @since   1.0.0
     * @var     string $feedWrapper Feed Wrapper text
     * @access  public
     */
    public $feedWrapper = 'item';

    /**
     * Store product information
     *
     * @since   1.0.0
     * @var     array $storeProducts
     * @access  public
     */
    private $storeProducts;

    /**
     * Define the core functionality to generate feed.
     *
     * Set the feed rules. Map products according to the rules and Check required attributes
     * and their values according to merchant specification.
     * @var Woo_Generate_Feed $feedRule Contain Feed Configuration
     * @since    1.0.0
     */
    public function __construct($feedRule)
    {
        $products = new Woo_Feed_Products();
        $storeProducts = $products->woo_feed_get_visible_product($feedRule);
        $engine = new WF_Engine($storeProducts, $feedRule);
        $this->products = $engine->mapProductsByRules();
        $this->rules = $feedRule;
        if ($feedRule['feedType'] == 'xml') {
            $this->mapAttributeForXML();
        } else {
            $this->mapAttributeForCSVTXT();
        }
    }


    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        $engine = new WF_Engine($this->products, $this->rules);
        if ($this->rules['feedType'] == 'xml') {
            return $this->get_feed($this->products);
        } elseif ($this->rules['feedType'] == 'txt') {
            return $engine->get_txt_feed();
        } elseif ($this->rules['feedType'] == 'csv') {
            return $engine->get_csv_feed();
        }
        return false;
    }

    /**
     * Configure merchant attributes for XML feed
     */
    public function mapAttributeForXML()
    {

        $googleXMLAttribute = array(
            "id" => array("g:id", false),
            "title" => array("title", true),
            "description" => array("description", true),
            "link" => array("link", true),
            "mobile_link" => array("mobile_link", true),
            "product_type" => array("g:product_type", true),
            "current_category" => array("g:google_product_category", true),
            "image" => array("g:image_link", true),
            "images" => array("g:additional_image_link", false),
            "images_1" => array("g:additional_image_link_1", true),
            "images_2" => array("g:additional_image_link_2", true),
            "images_3" => array("g:additional_image_link_3", true),
            "images_4" => array("g:additional_image_link_4", true),
            "images_5" => array("g:additional_image_link_5", true),
            "images_6" => array("g:additional_image_link_6", true),
            "images_7" => array("g:additional_image_link_7", true),
            "images_8" => array("g:additional_image_link_8", true),
            "images_9" => array("g:additional_image_link_9", true),
            "images_10" => array("g:additional_image_link_10", true),
            "condition" => array("g:condition", false),
            "availability" => array("g:availability", false),
            "price" => array("g:price", true),
            "sale_price" => array("g:sale_price", true),
            "sale_price_effective_date" => array("g:sale_price_effective_date", true),
            "brand" => array("g:brand", true),
            "sku" => array("g:mpn", true),
            "upc" => array("g:gtin", true),
            "identifier_exists" => array("g:identifier_exists", true),
            "item_group_id" => array("g:item_group_id", false),
            "color" => array("g:color", true),
            "gender" => array("g:gender", true),
            "age_group" => array("g:age_group", true),
            "material" => array("g:material", true),
            "pattern" => array("g:pattern", true),
            "size" => array("g:size", true),
            "size_type" => array("g:size_type", true),
            "size_system" => array("g:size_system", true),
            "tax" => array("tax", true),
            "weight" => array("g:shipping_weight", false),
            "length" => array("g:shipping_length", false),
            "width" => array("g:shipping_width", false),
            "height" => array("g:shipping_height", false),
            "shipping_label" => array("g:shipping_label", false),
            "shipping_country" => array("g:shipping_country", false),
            "shipping_service" => array("g:shipping_service", false),
            "shipping_price" => array("g:shipping_price", false),
            "shipping_region" => array("g:shipping_region", false),
            "multipack" => array("g:multipack", true),
            "is_bundle" => array("g:is_bundle", true),
            "adult" => array("g:adult", true),
            "adwords_redirect" => array("g:adwords_redirect", true),
            "custom_label_0" => array("g:custom_label_0", true),
            "custom_label_1" => array("g:custom_label_1", true),
            "custom_label_2" => array("g:custom_label_2", true),
            "custom_label_3" => array("g:custom_label_3", true),
            "custom_label_4" => array("g:custom_label_4", true),
            "excluded_destination" => array("g:excluded_destination", true),
            "expiration_date" => array("g:expiration_date", true),
            "unit_pricing_measure" => array("g:unit_pricing_measure", true),
            "unit_pricing_base_measure" => array("g:unit_pricing_base_measure", true),
            "energy_efficiency_class" => array("g:energy_efficiency_class", true),
            "loyalty_points" => array("g:loyalty_points", true),
            "installment" => array("g:installment", true),
            "promotion_id" => array("g:promotion_id", true),
        );

        if (count($this->products)) {
            foreach ($this->products as $no => $product) {
                $this->identifier_status_add($no);
                foreach ($product as $key => $value) {
                    $this->mapAttribute($no, $key, $googleXMLAttribute[$key][0], $value, $googleXMLAttribute[$key][0]);
                }

                $this->process_google_shipping_attribute_for_xml($no);
            }
        }
    }

    /**
     * Configure merchant attributes for XML feed
     */
    public function mapAttributeForCSVTXT()
    {
        //Basic product information
        $googleCSVTXTAttribute = array(
            "id" => array("id", false),
            "title" => array("title", true),
            "description" => array("description", true),
            "link" => array("link", true),
            "mobile_link" => array("mobile_link", true),
            "product_type" => array("product type", true),
            "current_category" => array("google product category", true),
            "image" => array("image link", true),
            "images_1" => array("additional image link 1", true),
            "images_2" => array("additional image link 2", true),
            "images_3" => array("additional image link 3", true),
            "images_4" => array("additional image link 4", true),
            "images_5" => array("additional image link 5", true),
            "images_6" => array("additional image link 6", true),
            "images_7" => array("additional image link 7", true),
            "images_8" => array("additional image link 8", true),
            "images_9" => array("additional image link 9", true),
            "images_10" => array("additional image link 10", true),
            "condition" => array("condition", false),
            "availability" => array("availability", false),
            "price" => array("price", true),
            "sale_price" => array("sale price", true),
            "sale_price_effective_date" => array("sale price effective date", true),
            "brand" => array("brand", true),
            "sku" => array("mpn", true),
            "upc" => array("gtin", true),
            "identifier_exists" => array("identifier exists", true),
            "item_group_id" => array("item group id", false),
            "color" => array("color", true),
            "gender" => array("gender", true),
            "age_group" => array("age group", true),
            "material" => array("material", true),
            "pattern" => array("pattern", true),
            "size" => array("size", true),
            "size_type" => array("size type", true),
            "size_system" => array("size system", true),
            "tax" => array("tax", true),
            "weight" => array("shipping weight", false),
            "length" => array("shipping length", false),
            "width" => array("shipping width", false),
            "height" => array("shipping height", false),
            "shipping_label" => array("shipping label", false),
            "shipping_country" => array("shipping country", false),
            "shipping_service" => array("shipping service", false),
            "shipping_price" => array("shipping price", false),
            "shipping_region" => array("shipping region", false),
            "multipack" => array("multipack", true),
            "is_bundle" => array("is bundle", true),
            "adult" => array("adult", true),
            "adwords_redirect" => array("adwords redirect", true),
            "custom_label_0" => array("custom label 0", true),
            "custom_label_1" => array("custom label 1", true),
            "custom_label_2" => array("custom label 2", true),
            "custom_label_3" => array("custom label 3", true),
            "custom_label_4" => array("custom label 4", true),
            "excluded_destination" => array("excluded destination", true),
            "expiration_date" => array("expiration date", true),
            "unit_pricing_measure" => array("unit pricing measure", true),
            "unit_pricing_base_measure" => array("unit pricing base measure", true),
            "energy_efficiency_class" => array("energy efficiency class", true),
            "loyalty_points" => array("loyalty points", true),
            "installment" => array("installment", true),
            "promotion_id" => array("promotion id", true),
        );

        if (count($this->products)) {
            foreach ($this->products as $no => $product) {
                foreach ($product as $key => $value) {
                    $this->mapAttribute($no, $key, $googleCSVTXTAttribute[$key][0], $value, $googleCSVTXTAttribute[$key][0]);
                }
                $this->process_google_shipping_attribute_for_CSVTXT($no);
            }
        }
    }

    /**
     * Map to google attribute
     * @param $no
     * @param $from
     * @param $to
     * @param $value
     * @param bool $cdata
     * @return array
     */
    public function mapAttribute($no, $from, $to, $value, $cdata = false)
    {
        unset($this->products[$no][$from]);
        if($to=='g:color'){
            $value=str_replace(",","/",$value);
        }
        if ($this->rules['feedType'] == 'xml') {
            return $this->products[$no][$to] = $this->formatXMLLine($to, $value, $cdata);
        } else {
            return $this->products[$no][$to] = $value;
        }
    }

    public function identifier_status_add($no)
    {
        $identifier = array('brand', 'upc', 'sku', 'mpn', 'gtin');
        $product = $this->products[$no];

        if(!array_key_exists('g:identifier_exists',$product)) {
            if (count(array_intersect_key(array_flip($identifier), $product)) >= 2) {
                # Any 2 required keys exist!
                $countIdentifier=0;
                if(array_key_exists('brand',$product) && !empty($product['brand'])){
                    $countIdentifier++;
                }
                if(array_key_exists('upc',$product) && !empty($product['upc'])){
                    $countIdentifier++;
                }
                if(array_key_exists('sku',$product) && !empty($product['sku'])){
                    $countIdentifier++;
                }
                if(array_key_exists('mpn',$product) && !empty($product['mpn'])){
                    $countIdentifier++;
                }
                if(array_key_exists('gtin',$product) && !empty($product['gtin'])){
                    $countIdentifier++;
                }
                if($countIdentifier>=2){
                    $this->products[$no]["g:identifier_exists"] = $this->formatXMLLine("g:identifier_exists", "yes", $cdata = true);
                }else{
                    $this->products[$no]["g:identifier_exists"] = $this->formatXMLLine("g:identifier_exists", "no", $cdata = true);
                }
            } else {
                $this->products[$no]["g:identifier_exists"] = $this->formatXMLLine("g:identifier_exists", "no", $cdata = true);
            }
        }
    }


    /**
     * @param $no
     * @return bool|string
     */
    public
    function process_google_shipping_attribute_for_xml($no)
    {
        $shipping = array('g:shipping_country', 'g:shipping_service', 'g:shipping_price', 'g:shipping_region');
        $shippingAttr = array();
        $products = $this->products[$no];
        foreach ($products as $keyAttr => $valueAttr) {
            if (in_array($keyAttr, $shipping)) {
                array_push($shippingAttr, array($keyAttr => $valueAttr));
                unset($this->products[$no][$keyAttr]);
            }
        }
        if (count($shippingAttr)) {
            $str = "";
            foreach ($shippingAttr as $key => $attributes) {
                foreach ($attributes as $keyAttr => $valueAttr) {
                    $str .= str_replace("shipping_", "", $valueAttr);
                }
            }
            return $this->products[$no]['g:shipping'] = $this->formatXMLLine("g:shipping", $str, false);
        }
        return false;
    }

    /**
     * @param $no
     * @return bool|mixed
     */
    public
    function process_google_shipping_attribute_for_CSVTXT($no)
    {
        $shipping = array('shipping country', 'shipping service', 'shipping price', 'shipping region');
        $shippingAttr = array();
        $products = $this->products[$no];
        foreach ($products as $keyAttr => $valueAttr) {
            if (in_array($keyAttr, $shipping)) {
                array_push($shippingAttr, array($keyAttr => $valueAttr));
                unset($this->products[$no][$keyAttr]);
            }
        }
        if (count($shippingAttr)) {
            $str = "";
            foreach ($shippingAttr as $key => $attributes) {
                foreach ($attributes as $keyAttr => $valueAttr) {
                    $country = ($keyAttr == "shipping country") ? $str .= $valueAttr . ":" : "";
                    $service = ($keyAttr == "shipping service") ? $str .= $valueAttr . ":" : "";
                    $price = ($keyAttr == "shipping price") ? $str .= $valueAttr : "";
                    $region = ($keyAttr == "shipping region") ? $str .= $valueAttr . ":" : "";
                }
            }
            return $this->products[$no]['shipping(country:region:service:price)'] = str_replace(" : ",":", $str);
        }
        return false;
    }

    function formatXMLLine($attribute, $value, $cdata, $space = "")
    {
        //Make single XML  node
        if (!empty($value))
            $value = trim($value);
        if (gettype($value) == 'array')
            $value = json_encode($value);
        if (strpos($value, "<![CDATA[") === false && substr(trim($value), 0, 4) == "http") {
            $value = "<![CDATA[$value]]>";
        } elseif (strpos($value, "<![CDATA[") === false && $cdata === true && !empty($value)) {
            $value = "<![CDATA[$value]]>";
        } else if ($cdata) {
            if(!empty($value)){
                $value = "<![CDATA[$value]]>";
            }
        }

        if (substr($attribute, 0, 23) == 'g:additional_image_link' && !empty($value)) {
            $attribute = "g:additional_image_link";
        }

        return "
        $space<$attribute>$value</$attribute>";
    }



    public function get_feed_header()
    {
        $output = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0" xmlns:c="http://base.google.com/cns/1.0">
  <channel>
    <title>' . get_option('blogname') . '</title>
    <link><![CDATA['.site_url().']]></link>
    <description>'.get_option('blogdescription').'</description>';
        return $output;
    }

    public function get_feed($items)
    {
        $feed = "";
        $feed .= $this->get_feed_header();
        $feed .= "\n";
        if ($items) {
            foreach ($items as $item => $products) {
                $feed .= "      <" . $this->feedWrapper . ">";

                foreach ($products as $key => $value) {
                    if (!empty($value)){
                        $feed .= $value;
                    }
                }
                $feed .= "\n      </" . $this->feedWrapper . ">\n";
            }
            $feed .= $this->get_feed_footer();

            return $feed;
        }
        return false;
    }

    public function get_feed_footer()
    {
        $footer = "  </channel>
</rss>";
        return $footer;
    }

    public function short_products()
    {
        if ($this->products) {
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
     * Responsible to make CSV feed
     * @return string
     */
    public function get_csv_feed()
    {
        if ($this->products) {
            $headers = array_keys($this->products[0]);
            $feed[] = $headers;
            foreach ($this->products as $no => $product) {
                $row = array();
                foreach ($headers as $key => $header) {
                    if (strpos($header, "additional image link") !== false) {
                        $header = "additional image link";
                    }
                    $row[] = isset($product[$header]) ? $product[$header] : "";;
                }
                $feed[] = $row;
            }
            return $feed;
        }
        return false;
    }

}
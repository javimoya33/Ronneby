<?php

/**
 * Class Custom
 *
 * Responsible for processing and generating custom feed
 *
 * @since 1.0.0
 * @package Shopping
 *
 */
class Woo_Feed_Custom
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
     * @var     Custom $rules Contains feed configuration form values
     * @access  public
     */
    public $rules;


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
        }
    }


    /**
     * Prepare Feed For XML Output
     *
     */
    public function mapAttributeForXML()
    {
        $i = 0;
        $cdata = true;
        if ($this->products) {
            foreach ($this->products as $no => $product) {
                foreach ($product as $key => $value) {
                    $this->products[$no][$key] = $this->formatXMLLine($key, $value);
                }
                $i++;
            }
        }
    }

    /**
     * Format and Make the XML node for the Feed
     *
     * @param $attribute
     * @param $value
     * @param string $space
     * @return string
     */
    function formatXMLLine($attribute, $value, $space ="")
    {
        $attribute = str_replace(" ", "_", $attribute);
        //Make child node for XML
        if(!empty($value))
            $value=trim($value);
        if (strpos($value, "<![CDATA[") === false && substr(trim($value), 0, 4) == "http") {
            $value = "<![CDATA[$value]]>";
        } elseif (strpos($value, "<![CDATA[") === false && !is_numeric(trim($value)) && !empty($value)) {
            $value = "<![CDATA[$value]]>";
        }
        return "
        $space<$attribute>$value</$attribute>";
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
            return $engine->get_feed($this->products);
        } elseif ($this->rules['feedType'] == 'txt') {
            return $engine->get_txt_feed();
        } elseif ($this->rules['feedType'] == 'csv') {
            return $engine->get_csv_feed();
        }
        return false;
    }
}
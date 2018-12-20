<?php

/**
 * Class Amazon
 *
 * Responsible for processing and generating amazon feed
 *
 * @since 1.0.0
 * @package Shopping
 *
 */
class Woo_Feed_Amazon
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

    private $engine;

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
        $this->engine = new WF_Engine($storeProducts, $feedRule);
        $this->products = $this->engine->mapProductsByRules();
        $this->rules = $feedRule;
        $this->rules['feedType'] = 'txt';
    }


    /**
     * Responsible to make TXT feed
     * @return string
     */
    public function get_txt_feed()
    {
        if (count($this->products)) {

            $delimiter = "\t";
            $enclosure = "";
            $getHeader=new Woo_Feed_Default_Attributes();

            if (count($this->products)) {
                $provider= $this->rules['provider'];
                $firstRow=$provider."_first_row";
                $method=$provider."Attributes";

                $feed[]=$getHeader->$firstRow();
                $feed[] = array_values($getHeader->$method());
                $feed[] = array_keys($getHeader->$method());

                foreach ($this->products as $no => $product) {
                    $row = array();
                    foreach ($feed[2] as $key => $header) {
                        $row[] = isset($product[$header]) ? $this->engine->processStringForTXT($product[$header]):"";
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
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        return $this->get_txt_feed();
    }


}
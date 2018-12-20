<?php


class Nextag
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
        $storeProducts = $products->woo_feed_get_visible_product();
        $engine = new WF_Engine($storeProducts, $feedRule);
        $this->products = $engine->mapProductsByRules();
        $this->rules = $feedRule;
        if ($feedRule['feedType'] == 'xml') {
            $this->mapAttributeForXML();
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
            return $engine->get_feed($this->products);
        } elseif ($this->rules['feedType'] == 'txt') {
            return $engine->get_txt_feed();
        } elseif ($this->rules['feedType'] == 'csv') {
            return $engine->get_csv_feed();
        }
        return false;
    }

    public function mapAttributeForXML()
    {
        //Basic product information
        if (count($this->products)) {
            foreach ($this->products as $key => $values) {
                foreach ($values as $attr => $value) {
                    $this->products[$key][$attr] = $this->formatXMLLine($attr, $value, true, true, true);
                }
            }
        }
    }

    public function mapAttribute($from, $to, $cdata = false)
    {
        $i = 0;
        foreach ($this->products as $no => $product) {
            foreach ($product as $key => $value) {
                if ($key == $from) {
                    unset($this->products[$no][$from]);
                    if ($from == 'images') {
                        $this->products[$no][$to] = $value;
                    } else {
                        $this->products[$no][$to] = $this->formatXMLLine($to, $value);
                    }
                }
            }
            $i++;
        }
    }

    function formatXMLLine($attribute, $value, $space = '   ')
    {

        if (gettype($value) == 'array')
            $value = json_encode($value);


        return '
        ' . $space . '<' . $attribute . '>' . $value . '</' . $attribute . '>';
    }

}
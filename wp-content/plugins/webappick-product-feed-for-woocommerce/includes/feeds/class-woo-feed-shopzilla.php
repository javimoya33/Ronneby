<?php

/**
 * Class Shopzilla
 *
 * Responsible for processing and generating feed for Shopzilla.com
 *
 * @since 1.0.0
 * @package Shopzilla
 *
 */
class Shopzilla
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
     * @var     Shopzilla $rules Contains feed configuration form values
     * @access  public
     */
    public $rules;

    /**
     * This variable is responsible for mapping store attributes to merchant attribute
     *
     * @since   1.0.0
     * @var     Shopzilla $mapping Map store attributes to merchant attribute
     * @access  public
     */
    public $mapping;

    /**
     * This variable is responsible for generate error logs
     *
     * @since   1.0.0
     * @var     Shopzilla $errorLog Generate error logs
     * @access  public
     */
    public $errorLog;

    /**
     * This variable is responsible for making error number
     *
     * @since   1.0.0
     * @var     Shopzilla $errorCounter Generate error number
     * @access  public
     */
    public $errorCounter;

    /**
     * Feed Wrapper text for enclosing each product information
     *
     * @since   1.0.0
     * @var     Shopzilla $feedWrapper Feed Wrapper text
     * @access  public
     */
    private $feedWrapper;

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
        $this->feedWrapper = 'product';
        $this->errorCounter = 0;
        $this->rules = $feedRule;
        $this->mapProductsByRules();
        $this->formatRequiredField();
        $this->filterProductValues();
        if ($this->rules['feedType'] == 'xml') {
            $this->mapAttributeForXML();
        } else {
            $this->mapAttributeForCSVTEXT();
        }
    }

    /**
     * Configure merchant attributes for XML feed
     */
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

    /**
     * Configure merchant attributes for CSV and TXT feed
     */
    public function mapAttributeForCSVTEXT()
    {
        //Basic product information

        if (count($this->products)) {
            foreach ($this->products as $key => $values) {
                foreach ($values as $attr => $value) {
                    //Allow force strip HTML
                    $value = strip_tags(html_entity_decode($value));
                    if ($attr != 'Category') {
                        $value = utf8_encode($value);
                        $attr = utf8_encode($attr);

                        $value = htmlentities($value, ENT_QUOTES, 'UTF-8');

                        if (gettype($value) == 'array')
                            $value = json_encode($value);
                    }

                    $this->products[$key][$attr] = $value;
                }
            }
        }
    }

    /**
     * Check all the required attributes and make error message
     */
    public function formatRequiredField()
    {
        foreach ($this->products as $no => $product) {
            $upn = 0;
            if (array_key_exists('Unique ID', $product)) {
                $id = $product['Unique ID'];
            } else {
                $id = $product['Title'];
            }

            if (!array_key_exists('Unique ID', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Unique ID</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }


            if (!array_key_exists('Title', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Title</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Description', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Description</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Category', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Category</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Product URL', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Product URL</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Image URL', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Image URL</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Condition', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Condition</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Availability', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Availability</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Current price', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Current price</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }
        }
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if ($this->rules['feedType'] == 'xml') {
            return $this->get_feed($this->products);
        } elseif ($this->rules['feedType'] == 'txt') {
            return $this->get_txt_feed();
        } elseif ($this->rules['feedType'] == 'csv') {
            return $this->get_csv_feed();
        }
        return false;
    }

    /**
     * Check product's attribute value according to merchant specifications
     */
    public function filterProductValues()
    {
        $getProduct = new Woo_Feed_Products();
        $products = $this->products;

        foreach ($products as $no => $product) {
            if (array_key_exists('Unique ID', $product)) {
                $id = $product['Unique ID'];
            } else {
                $id = $product['Title'];
            }
//            echo "<pre>";
//            print_r($product);
            foreach ($product as $key => $value) {
                switch ($key) {
                    case "Unique ID":
                        if (strlen($value) > 100) {
                            $this->errorLog[$this->errorCounter] = "[<b>Unique ID</b>] is more that 100 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Title":
                        if (strlen($value) > 1000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Title</b>] is more that 1000 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Description":
                        if (strlen($value) > 3000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Description</b>] is more that 3000 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Category":
                        if (strlen($value) > 1000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Category</b>] is more that 1000 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Product URL":
                        if (strpos($value, 'http://') == false || strpos($value, 'https://') == false) {
                            $this->errorLog[$this->errorCounter] = "[<b>Product URL</b>] must start with http:// or https:// for $id.";
                            $this->errorCounter++;
                        }

                        if (strlen($value) > 2000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Product URL</b>] is more that 2000 character for $id.";
                            $this->errorCounter++;
                        }

                        break;
                    case "Image URL":
                        if (strpos($value, 'http://') == false || strpos($value, 'https://') == false) {
                            $this->errorLog[$this->errorCounter] = "[<b>Image URL</b>] must start with http:// or https:// for $id.";
                            $this->errorCounter++;
                        }

                        if (strlen($value) > 2000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Image URL</b>] is more that 2000 character for $id.";
                            $this->errorCounter++;
                        }

                        list($width, $height) = getimagesize($value);
                        if ($width < 450 || $height < 450) {
                            $this->errorLog[$this->errorCounter] = "<b>Image Width and Height</b> should be more that 450px for $id.";
                            $this->errorCounter++;
                        }

                        break;
                    case "Condition":
                        $condition = array("New", "Used", "Refurbished", "Open Box", "OEM");
                        if (!array_key_exists($value, $condition)) {
                            $this->errorLog[$this->errorCounter] = "[<b>Condition</b>] = $value is acceptable for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Availability":
                        $availability = array("In Stock", "Out of Stock", "Available for Order", "PreOrder");
                        if ($value == 'in stock') {
                            $this->products[$no][$key] = "In Stock";
                        } else {
                            $this->products[$no][$key] = "Out of Stock";
                        }

                        if (!array_key_exists($this->products[$no][$key], $availability)) {
                            $this->errorLog[$this->errorCounter] = "[<b>Availability</b>] = $value is acceptable for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Current price":
                        if (!is_numeric($value)) {
                            $this->errorLog[$this->errorCounter] = "[<b>Current price</b>] should be numeric for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Original Price":
                        if (!is_numeric($value)) {
                            $this->errorLog[$this->errorCounter] = "[<b>Original price</b>] should be numeric for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * Return Dynamic Category Mapping Values by Parent Product Id
     *
     * @param   string $mappingName Category Mapping Name
     * @param   int $parent Parent id of the product
     * @return mixed
     */
    public function get_category_mapping_value($mappingName, $parent)
    {
        $getValue = unserialize(get_option($mappingName));
        $mapp = array_reverse($getValue['cmapping'], true);
        $categories = get_the_terms($parent, 'product_cat');

        foreach ($categories as $key => $category) {
            if (!empty($mapp[$category->term_id]))
                return $mapp[$category->term_id];
        }
        return false;
    }

    /**
     * Configure the feed according to the rules
     * @return Shopping
     */
    public function mapProductsByRules()
    {
        /**
         * Get WooCommerce Products
         * @package Woo_Feed_Products
         */
        $products = new Woo_Feed_Products();

        /**
         * This variable contain selected Woo attributes from feed making form
         *
         * @since   1.0.0
         * @var     array $attributes contain selected Woo attributes from feed making form
         */
        $attributes = $this->rules['attributes'];
        /**
         * This variable contain selected Prefix Values from feed making form
         *
         * @since   1.0.0
         * @var     array $prefix Prefix Values from feed making form
         */
        $prefix = $this->rules['prefix'];
        /**
         * This variable contain selected Prefix Values from feed making form
         *
         * @since   1.0.0
         * @var     array $suffix Suffix Values from feed making form
         */
        $suffix = $this->rules['suffix'];
        /**
         * This variable contain selected Output Types from feed making form
         *
         * @since   1.0.0
         * @var     array $outputType Output Types from feed making form
         */
        $outputType = $this->rules['output_type'];

        /**
         * This variable contain selected Output Limit from feed making form
         *
         * @since   1.0.0
         * @var     array $limit Output Limit from feed making form
         */
        $limit = $this->rules['limit'];
        /**
         * This variable contain selected Merchant attributes from feed making form
         *
         * @since   1.0.0
         * @var     array $merchantAttributes contain selected Woo attributes from feed making form
         */
        $merchantAttributes = $this->rules['mattributes'];
        /**
         * This variable contain attribute types from feed making form
         *
         * @since   1.0.0
         * @var     array $type contain attribute types from feed making form
         */
        $type = $this->rules['type'];
        /**
         * This variable contain manual output of attribute from feed making form
         *
         * @since   1.0.0
         * @var     array $default contain manual output of attribute
         */
        $default = $this->rules['default'];

        /**
         * This variable contain feed type
         *
         * @since   1.0.0
         * @var     array $feedType contain feed type
         */
        $feedType = $this->rules['feedType'];

        // Map Merchant Attributes and Woo Attributes
        if (count($merchantAttributes)) {
            foreach ($merchantAttributes as $key => $attr) {
                if (!empty($attr) && !empty($attributes[$key])) {
                    if ($type[$key] == 'attribute') {
                        $this->mapping[$attr]['value'] = $attributes[$key];
                        $this->mapping[$attr]['suffix'] = $suffix[$key];
                        $this->mapping[$attr]['prefix'] = $prefix[$key];
                        $this->mapping[$attr]['type'] = $outputType[$key];
                        $this->mapping[$attr]['limit'] = $limit[$key];
                    }
                } else if (empty($attributes[$key])) {
                    if ($type[$key] == 'pattern') {
                        $this->mapping[$attr]['value'] = "wf_pattern_$default[$key]";
                        $this->mapping[$attr]['suffix'] = $suffix[$key];
                        $this->mapping[$attr]['prefix'] = $prefix[$key];
                        $this->mapping[$attr]['type'] = $outputType[$key];
                        $this->mapping[$attr]['limit'] = $limit[$key];
                    }
                }
            }
        }

        // Make Product feed array according to mapping
        foreach ($products->woo_feed_get_visible_product() as $key => $value) {
            $i = 0;
            foreach ($this->mapping as $attr => $rules) {
                if (array_key_exists($rules['value'], $value)) {

                    $output = $rules['prefix'] . $value[$rules['value']] . " " . $rules['suffix'];
                    // Format According to output type
                    if ($rules['type'] == 2) {
                        $output = strip_tags($output);
                    } elseif ($rules['type'] == 3) {
                        $output = absint($output);
                    }
                    // Format According to output limit
                    if (!empty($rules['limit']) && is_numeric($rules['limit'])) {
                        $output = substr($output, 0, $rules['limit']);
                    }
                    $attr = trim($attr);
                    $this->products[$key][$attr] = trim($output);
                } else {
                    if (!empty($default[$i])) {
                        $output = $rules['prefix'] . str_replace("wf_pattern_", "", $rules['value']) . " " . $rules['suffix'];
                        if ($rules['type'] == 2) {
                            $output = strip_tags($output);
                        } elseif ($rules['type'] == 3) {
                            $output = absint($output);
                        }
                        // Format According to output limit
                        if (!empty($rules['limit']) && is_numeric($rules['limit'])) {
                            $output = substr($output, 0, $rules['limit']);
                        }
                        $attr = trim($attr);
                        $this->products[$key][$attr] = trim($output);
                    }
                }
                $i++;
            }
        }


        return $this->products;
    }

    /**
     * Change the products old array key and set new
     *
     * @param string $from Attribute Before
     * @param string $to Attribute After
     * @param bool $cdata Enclose Feed value
     */
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
                        $this->products[$no][$to] = $this->formatXMLLine($to, $value, $cdata, true, true);
                    }

                }
            }
            $i++;
        }
    }

    /**
     * Format and Make the XML node for the Feed
     *
     * @param $attribute
     * @param $value
     * @param bool $cdata
     * @param bool $stripHTML
     * @param bool $utf8encode
     * @param string $space
     * @return string
     */
    function formatXMLLine($attribute, $value, $cdata = false, $stripHTML = true, $utf8encode = true, $space = '   ')
    {
        //Make single line for XML
        $c_leader = '';
        $c_footer = '';
        if ($cdata) {
            $c_leader = '<![CDATA[';
            $c_footer = ']]>';
        }
        //Allow force strip HTML
        if ($stripHTML)
            $value = strip_tags(html_entity_decode($value));


        if ($utf8encode || $utf8encode == 1) {
            $value = utf8_encode($value);
            $attribute = utf8_encode($attribute);
        }


        if (!$cdata)
            $value = htmlentities($value, ENT_QUOTES, 'UTF-8');

        if (gettype($value) == 'array')
            $value = json_encode($value);


        return '
        ' . $space . '<' . $attribute . '>' . $c_leader . $value . $c_footer . '</' . $attribute . '>';
    }

    /**
     * Responsible to change product array key
     *
     * @param $array
     * @param $old_key
     * @param $new_key
     * @return array
     */
    function change_key($array, $old_key, $new_key)
    {
        foreach ($this->products as $no => $product) {
            if (!array_key_exists($old_key, $product))
                return $array;

            $keys = array_keys($array);
            $keys[array_search($old_key, $keys)] = $new_key;
        }
        return array_combine($keys, $array);
    }

    /**
     * Responsible to make XML feed header
     * @return string
     */
    public function get_feed_header()
    {
        $output = '<?xml version="1.0" encoding="UTF-8" ?>
<products>';
        $output .= "\n";
        return $output;
    }

    /**
     * Responsible to make XML feed body
     * @var array $item Product array
     * @return string
     */
    public function get_feed($items)
    {
        $feed = "";
        $feed .= $this->get_feed_header();
        $feed .= "\n";
        foreach ($items as $item => $products) {
            $feed .= "      <" . $this->feedWrapper . ">";
            foreach ($products as $key => $value) {
                if (!empty($value))
                    $feed .= $value;
            }
            $feed .= "\n      </" . $this->feedWrapper . ">\n";
        }
        $feed .= $this->get_feed_footer();

        return $feed;
    }

    /**
     * Responsible to make XML feed footer
     * @return string
     */
    public function get_feed_footer()
    {
        $footer = "  </products>";
        return $footer;
    }

    /**
     * Responsible to make TXT feed
     * @return string
     */
    public function get_txt_feed()
    {
        if (count($this->products)) {
            $headers = array_keys($this->products[0]);
            $feed[] = $headers;
            foreach ($this->products as $no => $product) {
                $row = array();
                foreach ($headers as $key => $header) {
                    $row[] = $product[$header];
                }
                $feed[] = $row;
            }
            $str = "";
            foreach ($feed as $fields) {
                $str .= implode("\t", $fields) . "\n";
            }
            return $str;
        }
        return false;
    }

    /**
     * Responsible to make CSV feed
     * @return string
     */
    public function get_csv_feed()
    {
        if (count($this->products)) {
            $headers = array_keys($this->products[0]);
            $feed[] = $headers;
            foreach ($this->products as $no => $product) {
                $row = array();
                foreach ($headers as $key => $header) {
                    $row[] = $product[$header];
                }
                $feed[] = $row;
            }

            return $feed;
        }
        return false;
    }
}
<?php

/**
 * Class Shopping
 *
 * Responsible for processing and generating feed for Shopping.com
 *
 * @since 1.0.0
 * @package Shopping
 *
 */
class Shopping
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
     * @var     Shopping $rules Contains feed configuration form values
     * @access  public
     */
    public $rules;

    /**
     * This variable is responsible for mapping store attributes to merchant attribute
     *
     * @since   1.0.0
     * @var     Shopping $mapping Map store attributes to merchant attribute
     * @access  public
     */
    public $mapping;

    /**
     * This variable is responsible for generate error logs
     *
     * @since   1.0.0
     * @var     Shopping $errorLog Generate error logs
     * @access  public
     */
    public $errorLog;

    /**
     * This variable is responsible for making error number
     *
     * @since   1.0.0
     * @var     Shopping $errorCounter Generate error number
     * @access  public
     */
    public $errorCounter;

    /**
     * Feed Wrapper text for enclosing each product information
     *
     * @since   1.0.0
     * @var     Shopping $feedWrapper Feed Wrapper text
     * @access  public
     */
    private $feedWrapper = 'product';

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
        //Required Attributes
        $this->mapAttribute('Unique Merchant SKU', 'Merchant_SKU', true);
        $this->mapAttribute('Product Name', 'Product_Name', true);
        $this->mapAttribute('Product URL', 'Product_URL', true);
        $this->mapAttribute('Image URL', 'Image_URL', true);
        $this->mapAttribute('Current Price', 'Current_Price', true);
        $this->mapAttribute('Stock Availability', 'Stock_Availability', true);
        $this->mapAttribute('Condition', 'Condition', true);
        $this->mapAttribute('MPN', 'MPN', false);
        $this->mapAttribute('ISBN', 'ISBN', false);
        $this->mapAttribute('UPC', 'UPC', false);
        $this->mapAttribute('EAN', 'EAN', false);

        //Additional Attributes
        $this->mapAttribute('Shipping Rate', 'Shipping_Rate', false);
        $this->mapAttribute('Original Price', 'Original_Price', false);
        $this->mapAttribute('Coupon Code', 'Coupon_Code', false);
        $this->mapAttribute('Coupon Code Description', 'Coupon_Code_Description', true);
        $this->mapAttribute('Manufacturer', 'Manufacturer', true);
        $this->mapAttribute('Product Description', 'Product_Description', true);
        $this->mapAttribute('Product Type', 'Product_Type', true);
        $this->mapAttribute('Category', 'Category', true);
        $this->mapAttribute('Category ID', 'Category_ID', false);
        $this->mapAttribute('Parent SKU', 'Parent_SKU', true);
        $this->mapAttribute('Parent Name', 'Parent_Name', true);
        $this->mapAttribute('Top Seller Rank', 'Top_Seller_Rank', false);
        $this->mapAttribute('Estimated Ship Date', 'Estimated_Ship_Date', true);
        $this->mapAttribute('Gender', 'Gender', true);
        $this->mapAttribute('Color', 'Color', true);
        $this->mapAttribute('Material', 'Material', true);
        $this->mapAttribute('Size', 'Size', true);
        $this->mapAttribute('Size Unit of Measure', 'Size_Unit_of_Measure', true);
        $this->mapAttribute('Age Range', 'Age_Range', true);
        $this->mapAttribute('Stock Description', 'Stock_Description', true);
        $this->mapAttribute('Product Launch Date', 'Product_Launch_Date', false);
        $this->mapAttribute('Product Bullet Point 1', 'Product_Bullet_Point_1', false);
        $this->mapAttribute('Product Bullet Point 2', 'Product_Bullet_Point_2', false);
        $this->mapAttribute('Product Bullet Point 3', 'Product_Bullet_Point_3', false);
        $this->mapAttribute('Product Bullet Point 4', 'Product_Bullet_Point_4', false);
        $this->mapAttribute('Product Bullet Point 5', 'Product_Bullet_Point_5', false);
        $this->mapAttribute('Alternative Image URL 1', 'Alternative_Image_URL_1', false);
        $this->mapAttribute('Alternative Image URL 2', 'Alternative_Image_URL_2', false);
        $this->mapAttribute('Alternative Image URL 3', 'Alternative_Image_URL_3', false);
        $this->mapAttribute('Alternative Image URL 4', 'Alternative_Image_URL_4', false);
        $this->mapAttribute('Alternative Image URL 5', 'Alternative_Image_URL_5', false);
        $this->mapAttribute('Mobile URL', 'Mobile_URL', false);
        $this->mapAttribute('Related Products', 'Related_Products', false);
        $this->mapAttribute('Merchandising Type', 'Merchandising_Type', false);
        $this->mapAttribute('Zip Code', 'Zip_Code', false);
        $this->mapAttribute('Shipping Weight', 'Shipping_Weight', false);
        $this->mapAttribute('Format', 'Format', false);
        $this->mapAttribute('Unit Price', 'Unit_Price', false);
        $this->mapAttribute('Bundle', 'Bundle', false);
        $this->mapAttribute('Software Platform', 'Software_Platform', false);
        $this->mapAttribute('Watch Display Type', 'Watch_Display_Type', false);
        $this->mapAttribute('Custom', 'Custom', false);

    }

    /**
     * Configure merchant attributes for CSV and TXT feed
     */
    public function mapAttributeForCSVTEXT()
    {
        if (count($this->products)) {
            foreach ($this->products as $key => $values) {
                foreach ($values as $attr => $value) {
                    // Allow force strip HTML
                    $value = strip_tags(html_entity_decode($value));
                    // Encode value and attribute
                    $value = utf8_encode($value);
                    $attr = utf8_encode($attr);

                    $value = htmlentities($value, ENT_QUOTES, 'UTF-8');

                    if (gettype($value) == 'array')
                        $value = json_encode($value);

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
            if (array_key_exists('Unique Merchant SKU', $product)) {
                $id = $product['Unique Merchant SKU'];
            } else {
                $id = $product['Product Name'];
            }

            if (!array_key_exists('Product Name', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Product Name</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }


            if (!array_key_exists('Unique Merchant SKU', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Unique Merchant SKU</b>] Missing for <b>$id</b>.";
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

            if (!array_key_exists('Current Price', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Current Price</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Stock Availability', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Stock Availability</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('Condition', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>Condition</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('MPN', $product) && !array_key_exists('ISBN', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>MPN / ISBN</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }
            if (!array_key_exists('UPC', $product) && !array_key_exists('EAN', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>UPC / EAN</b>] Missing for <b>$id</b>.";
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
            if (array_key_exists('Unique Merchant SKU', $product)) {
                $id = $product['Unique Merchant SKU'];
            } else {
                $id = $product['Product Name'];
            }
//            echo "<pre>";
//            print_r($product);
            foreach ($product as $key => $value) {
                switch ($key) {
                    case "Product Name":
                        if (strlen($value) > 90) {
                            $this->errorLog[$this->errorCounter] = "<b>Product Name</b> is more that 90 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Image URL":
                        list($width, $height) = getimagesize($value);
                        if ($width < 150 || $height < 150) {
                            $this->errorLog[$this->errorCounter] = "<b>Image Width and Height</b> should be more that 150px for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Stock Availability":
                        if ($value == 'in stock') {
                            $this->products[$no][$key] = "YES";
                        } else if ($value == 'out of stock') {
                            $this->products[$no][$key] = "NO";
                        }
                        break;
                    case "Condition":
                        if ($value != "New") {
                            $this->errorLog[$this->errorCounter] = "<b>Condition</b> should be <b>New</b> for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "MPN":
                        if (strlen($value) != 13 || strlen($value) != 10) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>MPN</b>] should be 10 and 13 digit for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "ISBN":
                        if (strlen($value) != 13 || strlen($value) != 10) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>ISBN</b>] should be 10 and 13 digit for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "UPC":
                        if (strlen($value) != 12) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>UPC</b>] should be 12-digit Universal Product Codes for $id.";
                            $this->errorCounter++;
                        }
                        if (!is_numeric($value)) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>UPC</b>] should be Numeric for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "EAN":
                        if (strlen($value) != 13) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>EAN</b>] should be 13-digit EAN for $id.";
                            $this->errorCounter++;
                        }
                        if (!is_numeric($value)) {
                            $this->errorLog[$this->errorCounter] = "Product [<b>EAN</b>] should be Numeric for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Coupon Code":
                        if (strlen($value) > 20) {
                            $this->errorLog[$this->errorCounter] = "[<b>Coupon Code</b>] is more that 20 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Coupon Code Description":
                        if (strlen($value) > 150) {
                            $this->errorLog[$this->errorCounter] = "[<b>Coupon Code Description</b>] is more that 150 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Product Description":
                        if (strlen($value) > 4000) {
                            $this->errorLog[$this->errorCounter] = "[<b>Product Description</b>] is more that 150 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "Product Type":
                        $this->products[$no][$key] = str_replace(">", ",", $value);
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

                        // Format output type
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
     * @var array $items Product array
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
     * @var $products
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
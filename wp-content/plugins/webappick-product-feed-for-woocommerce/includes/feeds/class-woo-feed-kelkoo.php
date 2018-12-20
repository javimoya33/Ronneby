<?php


class Kelkoo
{

    public $products;

    public $rules;

    public $mapping;

    public $errorLog;
    public $errorCounter = 0;

    private $filteredProduct;
    private $feedWrapper = 'product';

    public function __construct($feedRule)
    {
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


    public function mapAttributeForCSVTEXT()
    {
        //Basic product information

        if (count($this->products)) {
            foreach ($this->products as $key => $values) {
                foreach ($values as $attr => $value) {
                    //Allow force strip HTML

                    $value = strip_tags(html_entity_decode($value));

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

    public function formatRequiredField()
    {
        foreach ($this->products as $no => $product) {
            $upn = 0;
            if (array_key_exists('title', $product)) {
                $id = $product['title'];
            } else {
                $id = "";
            }

            if (!array_key_exists('title', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>title</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }


            if (!array_key_exists('product-url', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>product-url</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('price', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>price</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }

            if (!array_key_exists('merchant-category', $product)) {
                $this->errorLog[$this->errorCounter] = "Required Attribute [<b>merchant-category</b>] Missing for <b>$id</b>.";
                $this->errorCounter++;
            }
        }
    }

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

    public function filterProductValues()
    {
        $getProduct = new Woo_Feed_Products();
        $products = $this->products;

        foreach ($products as $no => $product) {
            if (array_key_exists('title', $product)) {
                $id = $product['title'];
            } else {
                $id = "";
            }

            foreach ($product as $key => $value) {

                switch ($key) {
                    case "title":
                        if (strlen($value) > 80) {
                            $this->errorLog[$this->errorCounter] = "[<b>title</b>] is more that 80 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "product-url":
                        if (strpos($value, 'http://') == false || strpos($value, 'https://') == false) {
                            $this->errorLog[$this->errorCounter] = "[<b>product-url</b>] must start with http:// or https:// for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "description":
                        if (strlen($value) > 300) {
                            $this->errorLog[$this->errorCounter] = "[<b>description</b>] is more that 300 character for $id.";
                            $this->errorCounter++;
                        }
                        break;
                    case "image-url":
                        if (strpos($value, 'http://') == false) {
                            $this->errorLog[$this->errorCounter] = "[<b>image-url</b>] must start with http:// for $id.";
                            $this->errorCounter++;
                        }

                        list($width, $height) = getimagesize($value);
                        if (($width * $height) < 1920000) {
                            $this->errorLog[$this->errorCounter] = "<b>Image Pixel</b> should be less than 1920000px for $id.";
                            $this->errorCounter++;
                        }

                        break;
                    case "condition":
                        if ($value == 'new') {
                            $this->products[$no][$key] = "0";
                        } else if ($value == 'used') {
                            $this->products[$no][$key] = "1";
                        } else if ($value == 'refurbished') {
                            $this->products[$no][$key] = "2";
                        } else if ($value == 'open box') {
                            $this->products[$no][$key] = "3";
                        } else if ($value == 'returned') {
                            $this->products[$no][$key] = "4";
                        } else if ($value == 'damaged') {
                            $this->products[$no][$key] = "5";
                        } else if ($value == 'overstock') {
                            $this->products[$no][$key] = "6";
                        } else if ($value == 'liquidation') {
                            $this->products[$no][$key] = "7";
                        } else if ($value == 'unknown') {
                            $this->products[$no][$key] = "8";
                        } else if ($value == 'download') {
                            $this->products[$no][$key] = "9";
                        } else if ($value == 'offers for professional') {
                            $this->products[$no][$key] = "10";
                        }
                        break;
                    case "availability":
                        $conditions = array('In Stock', 'Yes', 'Any Number', 'Out of Stock');
                        if ($value == 'In Stock') {
                            $this->products[$no][$key] = "1";
                        } else if ($value == 'in stock') {
                            $this->products[$no][$key] = "1";
                        } else if ($value == 'Stock On Order') {
                            $this->products[$no][$key] = "2";
                        } else if ($value == 'Unknown, check site') {
                            $this->products[$no][$key] = "3";
                        } else if ($value == 'Preorder') {
                            $this->products[$no][$key] = "4";
                        } else if ($value == 'Available on Order') {
                            $this->products[$no][$key] = "5";
                        } else if ($value == 'Out of Stock') {
                            $this->products[$no][$key] = "6";
                        } else if ($value == 'out of stock') {
                            $this->products[$no][$key] = "6";
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function get_category_mapping_value($mappingName, $parent)
    {
        $getValue = unserialize(get_option($mappingName));
        $mapp = array_reverse($getValue['cmapping'], true);
        $categories = get_the_terms($parent, 'product_cat');

        foreach ($categories as $key => $category) {
            if (!empty($mapp[$category->term_id]))
                return $mapp[$category->term_id];
        }
    }


    public function mapProductsByRules()
    {
        $products = new Woo_Feed_Products(); //$this->woo_feed_get_visible_product();
        $attributes = $this->rules['attributes'];
        $prefix = $this->rules['prefix'];
        $suffix = $this->rules['suffix'];
        $outputType = $this->rules['output_type'];
        $limit = $this->rules['limit'];
        $merchantAttributes = $this->rules['mattributes'];
        $type = $this->rules['type'];
        $default = $this->rules['default'];
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

    public function get_feed_header()
    {
        $output = '<?xml version="1.0" encoding="UTF-8" ?>
<products>';
        $output .= "\n";
        return $output;
    }

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

    public function get_feed_footer()
    {
        $footer = "  </products>";
        return $footer;
    }

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
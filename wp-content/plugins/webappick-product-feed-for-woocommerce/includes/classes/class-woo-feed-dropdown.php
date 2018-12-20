<?php

/**
 * The file that defines the merchants attributes dropdown
 *
 * A class definition that includes attributes dropdown and functions used across the admin area.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */

class Woo_Feed_Dropdown
{

    /**
     * Dropdown of Merchant List
     *
     * @param string $selected
     * @return string
     */
    public function merchantsDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->merchants() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }


    public function amazon_clothingAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_clothingAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }

    public function amazon_jewelryAttributesDropdown($selected = "")
{
    $attributes = new Woo_Feed_Default_Attributes();
    $str = "<option></option>";
    foreach ($attributes->amazon_jewelryAttributes() as $key => $value) {
        if (substr($key, 0, 2) == "--") {
            $str .= "<optgroup label='$value'>";
        } elseif (substr($key, 0, 2) == "---") {
            $str .= "</optgroup>";
        } else {
            $sltd = "";
            if ($selected == $key)
                $sltd = 'selected="selected"';
            $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
        }

    }
    return $str;
}

    public function amazon_jewelry_frAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_jewelry_frAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }

    public function amazon_lightingAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_lightingAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_wirelessAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_wirelessAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_autoaccessoryAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_autoaccessoryAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_tiresandwheelsAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_tiresandwheelsAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_homeAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_homeAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_healthAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_healthAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_babyAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_babyAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_BookLoaderAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_BookLoaderAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_CameraAndPhotoAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_CameraAndPhotoAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_foodandbeveragesAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_foodandbeveragesAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_computersAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_computersAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_ConsumerElectronicsAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_ConsumerElectronicsAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_entertainmentcollectiblesAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_entertainmentcollectiblesAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_homeimprovementAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_homeimprovementAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_officeAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_officeAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_petsuppliesAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_petsuppliesAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }

    public function amazon_sportsmemorabiliaAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_sportsmemorabiliaAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_shoesAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_shoesAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_sportsAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_sportsAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_toysAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_toysAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_TradingCardsAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_TradingCardsAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }
    public function amazon_watchesAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazon_watchesAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value ." [".$key."]". "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of pricespy Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function pricespyAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->pricespyAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of pricespy Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function prisjaktAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->prisjaktAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Google Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function googleAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->googleAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Facebook Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function facebookAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->googleAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Facebook Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function shopbotAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->shopbotAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Amazon Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function amazonAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->amazonAttributes() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }
    /**
     * Dropdown of Pricegraber Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function priceGrabberAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->priceGrabberAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Nextag Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function nextagAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->nextagAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of kelkoo Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function kelkooAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->kelkooAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Shopzilla Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function shopzillaAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->shopzillaAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Shopping.com Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function shoppingAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->shoppingAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of Shopmania Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function shopmaniaAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->shopmaniaAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }


    /**
     * Dropdown of Bing.com Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function bingAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->bingAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of become.com Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function becomeAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->becomeAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }

    /**
     * Dropdown of connexity.com Attribute List
     *
     * @param string $selected
     * @return string
     */
    public function connexityAttributesDropdown($selected = "")
    {
        $attributes = new Woo_Feed_Default_Attributes();
        $str = "<option></option>";
        foreach ($attributes->becomeAttribute() as $key => $value) {
            if (substr($key, 0, 2) == "--") {
                $str .= "<optgroup label='$value'>";
            } elseif (substr($key, 0, 2) == "---") {
                $str .= "</optgroup>";
            } else {
                $sltd = "";
                if ($selected == $key)
                    $sltd = 'selected="selected"';
                $str .= "<option $sltd value='$key'>" . $value . "</option>";
            }

        }
        return $str;
    }
}

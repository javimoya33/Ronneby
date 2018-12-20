<?php

/**
 * This is used to save feed
 *
 * @since      1.0.0
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class Woo_Feed_Savefile
{

    /**
     * Check if the directory for feed file exist or not and make directory
     *
     * @param $path
     * @return bool
     */
    public function checkDir($path)
    {
        if (!file_exists($path)) {
            return wp_mkdir_p($path);
        }
        return true;
    }




    /**
     * Save CSV Feed file
     *
     * @param $path
     * @param $file
     * @param $content
     * @param $info
     * @return bool
     */
    public function saveCSVFile($path, $file, $content, $info)
    {
        if ($this->checkDir($path)) {
            $fp = fopen($file, "wb");

            if ($info['delimiter'] == 'tab') {
                $delimiter = "\t";
            } else {
                $delimiter = !empty($info['delimiter'])?trim($info['delimiter']):$info['delimiter'];
            }

            if (!empty($info['enclosure'])) {
                $enclosure = $info['enclosure'];
            } else {
                $enclosure = "";
            }

            $enclosure = $info['enclosure'];
            if (count($content)) {
                foreach ($content as $fields) {
                    if ($enclosure == "double")
                        fputcsv($fp,$fields,$delimiter,chr(34));
                    else if ($enclosure == "single")
                        fputcsv($fp,$fields,$delimiter,chr(39));
                    else{
                        fputs($fp,implode($fields,$delimiter)."\n");
                    }
                }
            }

            fclose($fp);
            update_option("WPF_DIRECTORY_PERMISSION_CHECK","");
            return true;
        } else {
            $upload_dir = wp_upload_dir();
            $user_dirname = $upload_dir['basedir'];
            update_option("WPF_DIRECTORY_PERMISSION_CHECK"," <b>Directory $user_dirname is not writable</b>");
            return false;
        }
    }

    /**
     * Save XML and TXT File
     *
     * @param $path
     * @param $file
     * @param $content
     * @return bool
     */
    public function saveFile($path, $file, $content)
    {
        if ($this->checkDir($path)) {
            $fp = fopen($file, "wb");
            fwrite($fp, $content);
            fclose($fp);
            update_option("WPF_DIRECTORY_PERMISSION_CHECK","");
            return true;
        } else {
            $upload_dir = wp_upload_dir();
            $user_dirname = $upload_dir['basedir'];
            update_option("WPF_DIRECTORY_PERMISSION_CHECK"," <b>Directory $user_dirname is not writable</b>");
            return false;
        }
    }
}

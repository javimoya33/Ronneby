<?php


/**
 * A class definition responsible for processing FTP Uploading
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Ohidul Islam <wahid@webappick.com>
 */
Class FTPClient
{
    private $connectionId;
    private $loginOk = false;
    private $messageArray = array();

    public function __construct()
    {

    }

    /**
     * Store Log Messages
     * @param $message
     */
    private function logMessage($message)
    {
        $this->messageArray[] = $message;
    }

    /**
     * Get Logs
     * @return array
     */
    public function getMessages()
    {
        return $this->messageArray;
    }

    /**
     * Connect to FTP Server
     *
     * @param $server
     * @param $ftpUser
     * @param $ftpPassword
     * @param bool $isPassive
     * @return bool
     */
    public function connect($server, $ftpUser, $ftpPassword, $isPassive = false)
    {

        // *** Set up basic connection
        $this->connectionId = ftp_connect($server);

        // *** Login with username and password
        $loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);

        // *** Sets passive mode on/off (default off)
        ftp_pasv($this->connectionId, $isPassive);

        // *** Check connection
        if ((!$this->connectionId) || (!$loginResult)) {
            $this->logMessage('FTP connection has failed!');
            $this->logMessage('Attempted to connect to ' . $server . ' for user ' . $ftpUser, true);
            return false;
        } else {
            $this->logMessage('Connected to ' . $server . ', for user ' . $ftpUser);
            $this->loginOk = true;
            return true;
        }
    }

    private function is_octal($i)
    {
        return decoct(octdec($i)) == $i;
    }

    /**
     * Give permission to file
     *
     * @param $permissions
     * @param $remote_filename
     * @return bool
     */
    public function chmod($permissions, $remote_filename)
    {
        if ($this->is_octal($permissions)) {
            $result = ftp_chmod($this->connectionId, $permissions, $remote_filename);
            if ($result) {
                $this->logMessage("File Permission Granted");
                return true;
            } else {
                $this->logMessage("File Permission Failed");
                return false;
            }
        } else {
            $this->logMessage("$permissions must be an octal number");
            return false;
        }
    }

    /** Make Directory
     * @param $directory
     * @return bool
     */
    public function makeDir($directory)
    {
        // *** If creating a directory is successful...
        if (ftp_mkdir($this->connectionId, $directory)) {

            $this->logMessage('Directory "' . $directory . '" created successfully');
            return true;

        } else {

            // *** ...Else, FAIL.
            $this->logMessage('Failed creating directory "' . $directory . '"');
            return false;
        }
    }

    /**
     * Upload files to FTP server
     * @param $fileFrom
     * @param $fileTo
     * @return bool
     */
    public function uploadFile($fileFrom, $fileTo)
    {
        // *** Set the transfer mode
        $asciiArray = array('txt', 'csv', 'xml');
        $extension = end(explode('.', $fileFrom));
        if (in_array($extension, $asciiArray)) {
            $mode = FTP_ASCII;
        } else {
            $mode = FTP_BINARY;
        }

        // *** Upload the file
        $upload = ftp_put($this->connectionId, $fileTo, $fileFrom, $mode);

        // *** Check upload status
        if (!$upload) {

            $this->logMessage('FTP upload has failed!');
            return false;

        } else {
            $this->logMessage('Uploaded "' . $fileFrom . '" as "' . $fileTo);
            return true;
        }
    }

}
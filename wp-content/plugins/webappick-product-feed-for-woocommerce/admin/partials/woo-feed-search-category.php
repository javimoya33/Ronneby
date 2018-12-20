<?php
/**
 * Auto suggest merchant category
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 * @author     Ohidul Islam <wahid@webappick.com>
 */
if (isset($_POST['q'])) {
    $searchfor = $_POST['q'];
    $provider = $_POST['provider'];
    if ($provider != 'custom') {
        $file = "$provider/categories.txt";
        $matches = array();

        $handle = @fopen($file, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                if (strpos($buffer, $searchfor) !== FALSE)
                    $matches[] = $buffer;
            }
            fclose($handle);
        }

        //show results:
        echo json_encode($matches);
    }
}
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Curler
 *
 * @author corey
 */
class Curler {
    public static function curl($endpoint, $ssl = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
?>

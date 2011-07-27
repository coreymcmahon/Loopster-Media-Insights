<?php

/**
 * Description of Curler
 *
 * @author corey
 */

/* TODO: Check whether curlPost (...,...,true) actually works */

class Curler {

    /**
     * Sends a GET CURL request to the specified address and returns the response text
     *
     * @param String $endpoint The URL to send the request to
     * @param boolean $ssl Whether or not SSL should be used
     * @return String
     */
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

    /**
     * Sends a POST CURL request to the specified address with the provided parameters in the body
     *
     * @param String $endpoint The URL to send the request to
     * @param array $params An array containing the parameters to send in the body of the POST request
     * @param boolean $ssl Whether or not to use SSL (not tested)
     * @return String 
     */
    public static function curlPost($endpoint, $params = array(), $ssl = false) {

        // Build the body of the request using the parameters
        $fields_string = "";
        foreach($params as $key=>$value)
        {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string,'&'); // Drop trailing & if there is one


        // Create the CURL handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_URL,$endpoint);

        // If SSL is required, set necessary fields
        if ($ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        // Add the POST body parameters
        curl_setopt($ch,CURLOPT_POST,count($params));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

        // Execute request and capture result
        $result = curl_exec($ch);

        // Close the CURL handle
        curl_close($ch);

        // Return the result from CURL request
        return $result;
    }
}
?>

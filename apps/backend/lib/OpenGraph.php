<?php
/**
 * Basic class to encapsulate the Facebook OpenGraph retrieval method(s)
 *
 * @author corey
 */
class OpenGraph {

    /**
     * Uses the Facebook OpenGraph to pull back the graph object associated with an ID. This can be either the vanity username or UID for a Facebook object.
     *
     * @param string $uid The ID of the OpenGraph object to retrieve
     * @param string $token The access token provided by the Open Graph API (only required for accessing protected OG objects, ie: Pages for alcohol brands)
     * @return object
     */
    public static function getOGObject($uid,$token = "") {
        $result = Curler::curl(sfConfig::get("app_opengraph_endpoint") . $uid, true);

        if (json_decode($result) == false && $token != "")
            $result = Curler::curl(sfConfig::get("app_opengraph_endpoint") . $uid . "?access_token=" . $token,true);

        return json_decode($result);
    }
}
?>

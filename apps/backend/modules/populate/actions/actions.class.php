<?php

/**
 * Actions for the 'populate' module.
 *
 * @package    insights
 * @subpackage populate
 * @author     Loopster Media
 */
class populateActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    /*$fancount = new FanCount();
    $fancount->setFacebookPageId("2");
    $fancount->setDate("2011-07-13 12:00:00");
    $fancount->setFancount(OpenGraph::getOGObject("HotelClub")->likes);
    $fancount->save();*/
  }

  /**
   * Update some number (provided as paramter 'num') of records that have not had a fancount update today
   *
   * @param sfWebRequest $request
   */
  public function executeUpdate(sfWebRequest $request)
  {
      $num = $request->getParameter("num");
      $pages = null;
      $access_token = TokensTable::getInstance()->getLatestToken();

      if ($num == "") 
      /* No value provided for parameter 'num' */
        $pages = FanCountTable::getDirtyPages();
      else
      /* Some value was provided for 'num' */
        $pages = FanCountTable::getDirtyPages($num);

      $now = date("Y-m-d") . " 12:00:00";
      
      foreach($pages as $page) {
          
          $count = OpenGraph::getOGObject($page->getName(), $access_token);
          $fancount = new FanCount();
          $fancount->setFacebookPage($page);
          
          $fancount->setFancount($count->likes);
          
          $fancount->setDate($now);
          $fancount->save();
          
      }


  }
}

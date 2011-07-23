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

  /**
   * This function interpolates data for a date if we 'forget' to run the update script. Just makes the data for the missed date the midpoint of the day before and after.
   *
   * TODO: this is messy and a hack, this should probably be refactored into some of the model code
   *
   * @param sfWebRequest $request
   */
  public function executeFix(sfWebRequest $request)
  {
      // Grab the parameters that should be provided in the query string
      $beforeDate = $request->getParameter("beforeDate");
      $duringDate = $request->getParameter("duringDate");
      $afterDate = $request->getParameter("afterDate");

      // Quickie function to see if a provided string matches the date format yyyy-mm-dd (ie: '2011-07-23')
      function isDate($str) {
          return preg_match('/^[0-9][0-9][0-9][0-9]\-[0-9][0-9]\-[0-9][0-9]$/', $str);
      }

      // Make sure all date values are provided
      if (isDate($beforeDate) && isDate($duringDate) && isDate($afterDate)) {

          // Lets get all of the Facebook Pages and create some arrays
          $pages = FacebookPageTable::getInstance()->findAll();
          $this->before = array(); // Let the view layer see these in case we want to display
          $this->after = array();
          $this->mid = array();

          // For each page, go through and grab the fancount value for the day before and the day after...
          foreach ($pages as $page) {
              $id = $page->getId(); // Local copy of the ID field for this page

              // Grab the 'before' date, stick it in the array with Page ID as the key
              $before = FanCountTable::getInstance()->createQuery()
                      ->select()
                      ->where("date = ?", ($beforeDate . " 12:00:00"))
                      ->andWhere("facebook_page_id = ?", $id)
                      ->execute();
              $this->before["$id"] = $before[0]->getFancount();

              // Grab the 'after' date, stick it in the array with Page ID as the key
              $after = FanCountTable::getInstance()->createQuery()
                      ->select()
                      ->where("date = ?", ($afterDate . " 12:00:00"))
                      ->andWhere("facebook_page_id = ?", $id)
                      ->execute();
              $this->after["$id"] = $after[0]->getFancount();
          }

          // Now let's go through and calculate the midpoints. Create the Fancounts but don't save until we can guarantee everything worked
          $fancounts = array();
          $i = 0;
          foreach ($this->before as $key => $val) {
              if (isset($this->after[$key])) {
                  $mid = $this->after[$key] - $val;
                  $fancounts[$i] = new FanCount();
                  $fancounts[$i]->setDate($duringDate . " 12:00:00");
                  $fancounts[$i]->setFacebookPageId($key);
                  $fancounts[$i]->setFancount(Floor($mid/2) + $val);
                  $i++;
              } else {
                  $this->getUser()->setFlash("message", "Could not get before and after dates for all pages.");
                  return sfView::ERROR;
              }
          }
          
          // Go through and commit the new values to the database
          foreach ($fancounts as $fc)
                  $fc->save();

      } else {
          // Some shit has gone DOWN... All required dates not supplied.
          $this->getUser()->setFlash("message", "All required date fields were not suppied.");
          return sfView::ERROR;
      }
      
      // Success!
      return sfView::SUCCESS;
  }
}

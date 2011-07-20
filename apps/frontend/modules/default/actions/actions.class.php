<?php

/**
 * default actions.
 *
 * @package    insights
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      // Find out the minimum date that we have a recorded Fancount value for
      $this->min_date = FanCountTable::getInstance()->getMinDate();
      $this->min_date_array = explode("-", $this->min_date);
      // ... likewise for 'max'
      $this->max_date = FanCountTable::getInstance()->getMaxDate();
      $this->max_date_array = explode("-", $this->max_date);

      // Set the default values for Graph form
      $this->form = new GraphForm( 
        array(
          "start_date" => $this->min_date,
          "end_date" => $this->max_date,
          "graph_type" => "line",
          "fans" => "total",
          "view_by" => "industry"
        )
      );

      // If this is a postback, do some stuff...
      if ($request->isMethod("post")) {
          // Bind parameter values to the form
          $this->form->bind($request->getParameter("graph"));

          $data = null;

          /* if ($this->form->isValid()) { // TODO: add validation in here */

          // Move bits and bytes around...
          if ( $this->form["view_by"]->getValue() == "pages" ) {
            // Show fan stats for specific pages...
            $page_ids = $this->form["brands"]->getValue();
            $pages = FacebookPageTable::getInstance()->createQuery()
                    ->select("*")
                    ->whereIn("id",$page_ids)
                    ->execute();
          } else {
            // Show fan stats for the 'top X' in specified industry
            $industry_id = $this->form["industry"]->getValue();
            $show = $this->form["show"]->getValue();
            $industryPages = FacebookPageTable::getFacebookPagesByIndustry($industry_id);
            $pages = FacebookPageTable::getTopFacebookPages($industryPages, $show);
          }

          // TODO: generate JavaScript to put the data below into the graph
          $fandata = FanCountTable::getFancountData($pages, $this->form["start_date"]->getValue() . " 00:00:00", $this->form["end_date"]->getValue() . " 23:59:99");
          $fancount = array();

          foreach ($fandata as $point) {
              $page = $point->getFacebookPage();
              $id = $page->getId();
              $date = $point->getDate();
              $count = $point->getFancount();
              if (!isset($fancount["" . $id])) {
                  $fancount["" . $id] = array();
                  $fancount["" . $id]["name"] = $page->getName();
                  $fancount["" . $id]["data"] = array();
              }
              $fancount["" . $id]["data"][] = array("date" => $date , "fancount" => $count);
          }

          $this->fancount = $fancount;
          $this->graph_type = $this->form["graph_type"]->getValue();
          /* } */
      }
  }
}

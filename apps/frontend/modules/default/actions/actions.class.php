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

          // Pull back the Facebook Pages we're going to show the fancount data for
          if ( $this->form["view_by"]->getValue() == "pages" ) {
            // If user wants to see fancount data for specific pages...
            $page_ids = $this->form["brands"]->getValue();
            $pages = FacebookPageTable::getInstance()->createQuery()
                    ->select("*")
                    ->whereIn("id",$page_ids)
                    ->execute();
          } else {
            // If user wants to see the fancount data for the top pages in a specific category...
            $industry_id = $this->form["industry"]->getValue();
            $show = $this->form["show"]->getValue(); // The number of top pages to show (ie: top 3, top 5, top 10...)
            $industryPages = FacebookPageTable::getFacebookPagesByIndustry($industry_id);
            $pages = FacebookPageTable::getTopFacebookPages($industryPages, $show);
          }

          // Pull back the fancount data from DB
          $fandata = FanCountTable::getFancountData($pages, $this->form["start_date"]->getValue() . " 00:00:00", $this->form["end_date"]->getValue() . " 23:59:99");
          $fancount = array();

          // Format the fancount data so that it can be rendered in JavaScript
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

          // Set-up the objects that will be visit by the view-layer code
          $this->fancount = $fancount;
          $this->graph_type = $this->form["graph_type"]->getValue();
          $this->fans = $this->form["fans"]->getValue();
      }
  }

 /**
  *
  *
  * @param sfRequest $request A request object
  */
  public function executeInclusionRequest(sfWebRequest $request)
  {
      $inclusion_request = new InclusionRequest();
      $inclusion_request->setUserId($this->getUser()->getGuardUser()->getId());
      $inclusion_request->setFacebookUrl($request->getParameter("page_url"));
      $this->renderText($inclusion_request->save());
      return sfView::NONE;
  }
}

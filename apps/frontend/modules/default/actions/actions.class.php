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
      $this->min_date = FanCountTable::getInstance()->getMinDate();
      $this->min_date_array = explode("-", $this->min_date);

      $this->max_date = FanCountTable::getInstance()->getMaxDate();
      $this->max_date_array = explode("-", $this->max_date);

      $this->form = new GraphForm( 
        array(
          "start_date" => $this->min_date,
          "end_date" => $this->max_date,
          "graph_type" => "line",
          "fans" => "total",
          "view_by" => "industry"
        )
      );

      if ($request->isMethod("post")) {
          $this->form->bind($request->getParameter("graph"));

          $this->form->isValid();

          /* if ($this->form->isValid()) { // TODO: add validation in here */
          
          if ( $this->form["view_by"]->getValue() == "pages" ) {
            $page_ids = $this->form["brands"]->getValue();
            foreach ($page_ids as $id) {
                $this->text .= $id ." ";
            }
          } else {
            $industry_id = $this->form["industry"]->getValue();
            $show = $this->form["show"]->getValue();
          }

          /* } */
      }
  }
}

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
      //$this->form = new GraphForm($defaults, $options, $CSRFSecret);
      $this->form = new GraphForm( array("graph_type" => "line", "fans" => "total","view_by" => "industry") );
  }
}

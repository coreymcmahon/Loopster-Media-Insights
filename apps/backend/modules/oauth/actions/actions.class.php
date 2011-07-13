<?php

/**
 * Actions for the 'OAuth' module.
 *
 * @package    insights
 * @subpackage oauth
 * @author     Loopster Media
 */
class OauthActions extends sfActions
{
 /**
  * Display the last generated token and give the user the ability to create a new one
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->latestToken = Doctrine_Core::getTable("Tokens")->getLatestToken();
  }

  /**
   * Save the access token provided as a parameter
   *
   * @param sfWebRequest $request 
   */
  public function executeSave(sfWebRequest $request)
  {
    $tokenString = $this->getRequest()->getParameter("token");
    $this->forward404If($tokenString == "");

    $token = new Tokens();
    $token->setToken($tokenString);
    $token->save();
  }
}

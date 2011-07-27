<?php

require_once dirname(__FILE__).'/../lib/BasesfGuardRegisterActions.class.php';

/**
 * sfGuardRegister actions.
 *
 * @package    guard
 * @subpackage sfGuardRegister
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z jwage $
 */
class sfGuardRegisterActions extends BasesfGuardRegisterActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->getUser()->setFlash('notice', 'You are already registered and signed in!');
      $this->redirect('@homepage');
    }

    $this->form = new sfGuardRegisterForm();
    $this->form->setWidget("register", new sfWidgetFormInputCheckbox(array('default' => true , 'label' => 'Sign-up to newsletter?'), array('title' => 'Check this box to receive up-to-date Facebook marketing news and articles', 'value' => '1')));
    $this->form->setValidator("register", new sfValidatorString(array("required" => false), array()));

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $event = new sfEvent($this, 'user.filter_register');
        $this->form = $this->dispatcher
          ->filter($event, $this->form)
          ->getReturnValue();

        $user = $this->form->save();
        $this->getUser()->signIn($user);

        /* Added below code to post details to MailChimp */
        if ($this->form->getValue("register") == "1") {
            $mailChimp = new MailChimp(
                    sfConfig::get("app_mailchimp_endpoint"),
                    sfConfig::get("app_mailchimp_u"),
                    sfConfig::get("app_mailchimp_id"),
                    sfConfig::get("app_mailchimp_fname_field"),
                    sfConfig::get("app_mailchimp_lname_field"),
                    sfConfig::get("app_mailchimp_email_field")
            );


            $mailChimp->register($this->form->getValue("first_name"), $this->form->getValue("last_name"), $this->form->getValue("email_address"));
            $details = "(" . $this->form->getValue("first_name") . ") (" . $this->form->getValue("last_name") . ") (" . $this->form->getValue("email_address") . ")";
            $this->getContext()->getLogger()->log("Just tried to sign-up user to newsletter " . $details, sfLogger::INFO);
        }
        /* End mailchimp code */

        $this->redirect('@homepage');
      }
    }
  }
}
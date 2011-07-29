<?php

class populateTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addArgument(
      'num', sfCommandArgument::OPTIONAL, 'The number of records to update','all'
    );

    $this->namespace        = '';
    $this->name             = 'populate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [populate|INFO] task is used to update the FanCount databsae from the command line
Call it with:

  [php symfony populate|INFO]
EOF;
  }

  /**
   * This is a task that can be run from the command line interface to update the FanCount database
   *
   * @param array $arguments
   * @param array $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    // Include the application configuration. This code was taken form the standard front controller format (path modified)
    require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');

    // Create the context for the response. This code is taken from the 'backend' front controller, although the ->dispatch() call has been removed and debug mode is set to 'true' (for some reason an exception is thrown for 'false')
    $configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'prod', true);
    $context = sfContext::createInstance($configuration);

    // Log the CRON user in. This action assumes there exists a user in the DB with username 'cron' and the 'manage-user' permission
    $cronUser = sfGuardUserTable::getInstance()->findBy("username", "cron")->getFirst();
    $context->getUser()->signIn($cronUser);
    
    if ($arguments["num"] == "all") {
        // Forward the request to the 'populate/update' action
        $context->getController()->forward("populate", "update");
        /*$context->dispatch(); // I don't think this is required when using $sfController->forward */

    } elseif (is_numeric($arguments["num"])) {
        // Set the 'num' parameter and then forward the request to the 'populate/update' action
        $context->getRequest()->getParameterHolder()->set("num",$arguments["num"]);
        $context->getController()->forward("populate", "update");

    } else {
        $this->log("Error: non-numeric parameter supplied");
    }

   }
}

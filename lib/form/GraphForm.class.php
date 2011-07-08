<?php

/**
 * Generates the graph for the 'logged-in' page of Loopster Insights
 *
 * @author corey_mcmahon
 */

class GraphForm extends sfForm {

    /**
     * Default set up method for the form
     */
    public function setup() {

        // Set up the input elements
        $this->setWidgets(
            array (
            "start_date" => new sfWidgetFormInput(),
            "end_date" => new sfWidgetFormInput (),
            "brands" => new sfWidgetFormDoctrineChoice( array("model"=>"FacebookPage","method"=>"getPageName","multiple"=>true)),
            "industry" => new sfWidgetFormDoctrineChoice( array("model"=>"Industry","method"=>"getName","multiple"=>false) ),
            "show" => new sfWidgetFormChoice(array("choices" => array("5" => "Top 5", "10" => "Top 10","15" => "Top 15") ) , array()),
            "graph" => new sfWidgetFormSelectRadio(array("choices" => array("line" => "Line" , "bar" => "Bar")), array()),
            "fans" => new sfWidgetFormSelectRadio(array("choices" => array("total" => "Total Fans" , "growth" => "Fan Growth")), array())
            )
        );

        // TODO: enter validation in here

        // Set the name / id naming convention
        $this->getWidgetSchema()->setNameFormat('graph[%s]');

        // Call the sfForm setup method
        parent::setup();
    }
}
?>

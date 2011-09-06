<?php

/**
 * Generates the graph for the 'logged-in' page of Loopster Insights
 *
 * @author corey_mcmahon
 */

class GraphForm extends BaseForm {

    public function configure() {
        // Set up the input elements
        $this->setWidgets(
            array (
            "start_date" => new sfWidgetFormInput(),
            "end_date" => new sfWidgetFormInput (),
            "view_by" => new sfWidgetFormSelectRadio(array("choices" => array("industry" => "Industry" , "pages" => "Individual Pages")), array()),
            "brands" => new sfWidgetFormDoctrineChoice( array("model"=>"FacebookPage","method"=>"getName","multiple"=>true)),
            "industry" => new sfWidgetFormDoctrineChoice( array("model"=>"Industry","method"=>"getName","multiple"=>false) ),
            "show" => new sfWidgetFormChoice(array("choices" => array("3" => "Top 3", "5" => "Top 5", "10" => "Top 10", "" => "All") ) , array()),
            //"graph_type" => new sfWidgetFormSelectRadio(array("choices" => array("line" => "Line" , "bar" => "Bar")), array()), /* commented out as only displaying line graphs */
            "graph_type" => new sfWidgetFormInputHidden( array( "default" => "line" ) ),
            "fans" => new sfWidgetFormSelectRadio(array("choices" => array("total" => "Total Fans" , "growth" => "Fan Growth")), array("title" => "Display which measurement on the graph" ))
            )
        );

        // TODO: enter validation in here

        // Set the name / id naming convention
        $this->getWidgetSchema()->setNameFormat('graph[%s]');

    }
}
?>

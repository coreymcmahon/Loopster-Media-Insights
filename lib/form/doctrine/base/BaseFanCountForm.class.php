<?php

/**
 * FanCount form base class.
 *
 * @method FanCount getObject() Returns the current form's model object
 *
 * @package    insights
 * @subpackage form
 * @author     Loopster Media
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFanCountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'facebook_page_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FacebookPage'), 'add_empty' => false)),
      'fancount'         => new sfWidgetFormInputText(),
      'date'             => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'facebook_page_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FacebookPage'))),
      'fancount'         => new sfValidatorInteger(),
      'date'             => new sfValidatorPass(),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('fan_count[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FanCount';
  }

}

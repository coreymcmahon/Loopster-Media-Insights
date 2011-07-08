<?php

/**
 * Message form base class.
 *
 * @method Message getObject() Returns the current form's model object
 *
 * @package    insights
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMessageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'fan_count_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FanCount'), 'add_empty' => true)),
      'facebook_page_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FacebookPage'), 'add_empty' => true)),
      'message'          => new sfWidgetFormTextarea(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'fan_count_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FanCount'), 'required' => false)),
      'facebook_page_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FacebookPage'), 'required' => false)),
      'message'          => new sfValidatorString(array('max_length' => 1023, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('message[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Message';
  }

}

<?php

/**
 * FacebookPage form base class.
 *
 * @method FacebookPage getObject() Returns the current form's model object
 *
 * @package    insights
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFacebookPageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'url'         => new sfWidgetFormTextarea(),
      'notes'       => new sfWidgetFormTextarea(),
      'industry_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Industry'), 'add_empty' => true)),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'url'         => new sfValidatorString(array('max_length' => 511)),
      'notes'       => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'industry_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Industry'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('facebook_page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FacebookPage';
  }

}

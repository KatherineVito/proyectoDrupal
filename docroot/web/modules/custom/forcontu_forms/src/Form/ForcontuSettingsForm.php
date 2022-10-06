<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBaseTrait;

class ForcontuSettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'forcontu_forms_admin_settings';
  }

  protected function getEditableConfigNames() {
    return [
      'forcontu_forms.settings'
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    //Constructor del formulario
    $config = $this->config('forcontu_forms.settings');

    //Listado con todos los tipos de contenido
    $types = node_type_get_names();

    $form['forcontu_forms_allowed_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types allowed'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('Select content types.'),
      '#required' => TRUE,
    ];

    $form['forcontu_forms_message'] = [
      '#type' => 'textarea',
      '#title' => t('Message'),
      '#cols' => 60,
      '#rows' => 5,
      '#default_value' => $config->get('message'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Validaciòn del formualrio
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    //Submit del formulario
    $allowed_types = array_filter($form_state->getValue('forcontu_forms_allowed_types'));

    sort($allowed_types);

    $this->config('forcontu_forms.settings')
      ->set('allowed_types', $allowed_types)
      ->set('message', $form_state->getValue('forcontu_forms_message'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}

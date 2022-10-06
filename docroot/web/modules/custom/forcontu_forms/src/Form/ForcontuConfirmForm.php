<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;

class ForcontuConfirmForm extends ConfirmFormBase {

  protected $database;
  protected $node;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('database')
    );
  }

  public function getFormId() {
    return 'forcontu_forms_confirm';
  }

  public function buildFrom(array $form, FormStateInterface $form_state, NodeInterface $node = NULL) {

    $this->node = $node;
    return parent::buildFrom($form, $form_state);
  }

  public function getQuestion() {
    return $this->t('Are you sure you want to delete node "%title" (%nid) from
    <em>forcontu_node_highlighted</em> table?', ['%title' => $this->node->getTitle(),
      '%nid' => $this->node->id()]);
  }

  public function getConfirmText() {
    return $this->t('Delete');
  }

  public function getCancelText() {
    return $this->t('Don\'t Delete');
  }

  public function getCancelUrl() {
    return new Url('<front>');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->database->delete('forcontu_node_highlighted')
      ->condition('nid', $this->node->id())
      ->execute();

    $this->messenger()->addMessage($this->t('The node has been removed'));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}

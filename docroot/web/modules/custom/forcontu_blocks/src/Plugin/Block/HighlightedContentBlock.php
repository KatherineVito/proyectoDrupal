<?php

namespace Drupal\forcontu_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Database\Connection;

/**
 *  Provides the HighlightedContent block.
 *
 *  @Block(
 *   id = "forcontu_blocks_highlighted_content_block",
 *   admin_label = @Translation("Highlighted Content")
 *  )
 */
class HighlightedContentBlock extends BlockBase implements ContainerFactoryPluginInterface{

  protected $database;
  protected $currentUser;

  public function __construct(array $configuration, $plugin_id,
                                    $plugin_definition, AccountInterface $current_user,
                              Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->database = $database;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('database')
    );
  }

  public function build() {
    $node_number = $this->configuration['node_number'];
    $block_message = $this->configuration['block_message'];

    // 1
    $build[] = [
      '#markup' => '<h3>' . $this->t($block_message) . '</h3>',
    ];

// 2a
    $result = $this->database->select('forcontu_node_highlighted', 'f')
      ->fields('f', ['nid'])
      ->condition('highlighted', 1)
      ->orderBy('nid', 'DESC')
      ->range(0, $node_number)
      ->execute();

// 2b
    $list = [];
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

// 2c
    foreach($result as $record) {
      $node = $node_storage->load($record->nid);
      $list[] = $node->toLink($node->getTitle())->toRenderable();
    }

    if (empty($list)) {
      $build[] = [
      '#markup' => '<h3>' . $this->t('No results found') . '</h3>',
       ];
    } else { // 2d
      $build[] = [
        '#theme' => 'item_list', '#items' => $list,
        '#cache' => ['max-age' => 0],
      ];
    }

    return $build;
  }

  public function blockForm($form, FormStateInterface $formState) {

    $form['forcontu_blocks_block_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Display message'),
      '#default_value' => $this->configuration['block_message'],
    ];

    $range = range(1, 10);

    $form['forcontu_blocks_node_number'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of nodes'),
      '#default_value' => $this->configuration['node_number'],
      '#options' => array_combine($range, $range),
    ];
    return $form;
  }

  public function blockValidate($form, FormStateInterface $form_state) {

    if (strlen($form_state->getValue('forcontu_blocks_block_message')) < 10) {
      $form_state->setErrorByName('forcontu_blocks_block_mssage',
      $this->t('The text must be at least 10 characters long'));
    }
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['block_message'] = $form_state->getValue('forcontu_blocks_block_message');
    $this->configuration['node_number'] = $form_state->getValue('forcontu_node_numer');
  }

  public function defaultConfiguration() {
    return [
      'block_message' => 'List of highlighted nodes',
      'node_number' => 5,
    ];
  }
}

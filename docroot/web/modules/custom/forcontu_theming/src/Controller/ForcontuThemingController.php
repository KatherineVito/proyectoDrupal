<?php

/**
 * @file
 * Contains \Drupal\forcontu_theming\Controller\ForcontuThemingController
 */

namespace Drupal\forcontu_theming\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class ForcontuThemingController extends ControllerBase {

  public function render() {
    //definicion del array $build

    //Example 1: markup
    $build['forcontu_theming_markup'] = [
      '#markup' => '<p>' . $this->t('Lorem ipsum dolor sit amet, consectetur adipiscing elit') . '</p>',
    ];

    //Example 2: table
    $header = ['Column 1', 'Column 2', 'Column 3'];
    $rows[] = ['A', 'B', 'C'];
    $rows[] = ['D', 'E', 'F'];

    $build['forcontu_theming_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    //Example 3: list
    $list = ['Item 1', 'Item 2', 'Item 3'];

    $build['forcontu_theming_list'] = [
      '#theme' => 'item_list',
      '#title' => $this->t('List of items'),
      '#list_type' => 'ol',
      '#items' => $list,
    ];

    //dropbutton
//    $build['dropbutton'] = [
//      '#type' => 'dropbutton',
//      '#links' => [
//        'view' => [
//          'title' => $this->t('View'),
//          'url' => Url::fromRoute('forcontu_theming.link_view'),
//        ],
//        'edit' => [
//          'title' => $this->t('Edit'),
//          'url' => Url::fromRoute('forcontu_theming.link_edit'),
//        ],
//        'delete' => [
//          'title' => $this->t('Delete'),
//          'url' => Url::fromRoute('forcontu_theming.link_delete'),
//        ],
//      ],
//    ];

//    $build['more_link'] = [
//      '#type' => 'more_link',
//      '#url' => Url::fromRoute('forcontu_theming.list')
//    ];

    $build['item_dimensions'] = [
      '#theme' => 'forcontu_theming_dimensions',
      '#attached' => [
        'library' => [
          'forcontu_theming/forcontu_theming.css',
        ],
      ],
      '#length' => 12,
      '#width' => 8,
      '#height' => 24,
    ];

    return $build;
  }
}

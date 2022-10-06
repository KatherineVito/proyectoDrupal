<?php

namespace Drupal\forcontu_theming\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element to display a Dimensions item.
 *
 * @RenderElemnt("forcontu_theming_dimension")
 */
class ForcontuThemingDimensions extends RenderElement {

  /**
   *  {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRenderForcontuThemingDimensions'],
      ],
      '#length' => NULL,
      '#width' => NULL,
      '#height' => NULL,
      '#units' => 'cm.',
      '#theme' => 'forcontu_theming_dimension',
    ];
  }

  /**
   * Element pre render cellback
   */
  public static function preRenderForcontuThemingDimensions($element) {
    return $element;
  }
}

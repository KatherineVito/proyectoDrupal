<?php

namespace Drupal\forcontu_plugins;

/**
 * Interface for all Fipsum type plugins.
 */
interface FipsumInterface {

  public function description();

  public function generate($length);
}

<?php

namespace Drupal\forcontu_test;

use Drupal\Core\Database\Connection;

/**
 * Twig extension.
 */
class ForcontuTestTwigExtension extends \Twig_Extension {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new ForcontuTestTwigExtension object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('foo', function ($argument = NULL) {
        return 'Foo: ' . $argument;
      }),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('bar', function ($text) {
        return str_replace('bar', 'BAR', $text);
      }),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getTests() {
    return [
      new \Twig_SimpleTest('color', function ($text) {
        return preg_match('/^#(?:[0-9a-f]{3}){1,2}$/i', $text);
      }),
    ];
  }

}

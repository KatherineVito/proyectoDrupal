<?php

namespace Drupal\forcontu_theming\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class ForcontuThemingTwigExtension extends AbstractExtension {

  public function getFunctions() {
    return [
      new TwigFunction('loripsum', [$this, 'loripsum']),
    ];
  }

  public function getFilters() {
    return [
      new TwigFilter('space_replace', [$this, 'spaceReplace']),
    ];
  }

  public function getName() {
    return 'forcontu_theming.twig.extension';
  }

  public function loripsum($length = 50) {
    return substr(file_get_contents('http://loripsum.net/api/long/plaintext'), 0,
        $length) . '.';
  }

  public function spaceReplace($string) {
    return str_replace(' ', '-', $string);
  }
}

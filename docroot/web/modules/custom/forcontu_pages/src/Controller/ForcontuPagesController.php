<?php

/**
 * @file
 * Contains \Drupal\forcontu_hello\Controller\ForcontuPagesController.
 */

namespace Drupal\forcontu_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\user\UserInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Controlador para devolver el contenido de las páginas definidas.
 */

class ForcontuPagesController extends ControllerBase {


  protected $currentUser;
  protected $dateFormatter;
  protected $routeMatch;

  public function __construct(AccountInterface $currentuser, DateFormatter
  $dateFormatter, RouteMatchInterface $routeMatch) {
    $this->currentUser = $currentuser;
    $this->dateFormatter = $dateFormatter;
    $this->routeMatch = $routeMatch;
  }
  public static function create(ContainerInterface $container) { return new static(
    $container->get('current_user'),
    $container->get('date.formatter'),
    $container->get('current_route_match')

  ); }

  public function simple() {
    return [
      '#markup' => '<p>' . $this->t('This is a simple page (with np arguments)') . '</p>',
    ];
  }

  public function calculator($num1, $num2) {

    //a) comprobamos que los valores facilitados sean numéricos y si no es así, lancemos una excepción
    if (!is_numeric($num1) || !is_numeric($num2)) {
      throw new BadRequestHttpException(t('No numeric arguments specified.'));
    }

    //b) los resultados se mostrarán en formato lista HTML (ul).
    //Cada elemento de la lista se añade a un array
    $list[] = $this->t("@num1 + @num2 = @sum",
                        ['@num1' => $num1,
                          '@num2' => $num2,
                          '@sum' => $num1 + $num2]);
    $list[] = $this->t("@num1 - @num2 = @difference",
                        ['@num1' => $num1,
                          '@num2' => $num2,
                          '@difference' => $num1 - $num2]);
    $list[] = $this->t("@num1 x @num2 = @product",
                        ['@num1' => $num1,
                          '@num2' => $num2,
                          '@product' => $num1 * $num2]);

    //c) Evita error de división por cero
    if ($num2 != 0)
      $list[] = $this->t("@num1 / @num2 = @division",
                        ['@num1' => $num1,
                          '@num2' => $num2,
                          '@division' => $num1 / $num2]);
    else
      $list[] = $this->t("@num1 / @num2 = undefined (division by zero)",
      array('@num1' => $num1, '@num2' => $num2));

    //d) Se transforma el array $list en una lista HTML (ul)
    $output['forcontu_pages_calculator'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Operations:'),
    ];

    //e) Se devuelve el array renderizable con la salida.
    return $output;
  }

  public function user(UserInterface $user) {
    //Podemos usar directamente el objeto $user
    $list[] = $this->t('Username: @username',
                        ['@username' => $user->getAccountName()]);
    $list[] = $this->t("Email: @email",
                        ['@email' => $user->getEmail()]);
    $list[] = $this->t("Roles: @roles",
                        ['@roles' => implode(', ', $user->getRoles())]);
    $list[] = $this->t("Last accessed time: @lastaccess",
      array(
        '@lastaccess' => \Drupal::service('date.formatter')->format($user->getLastAccessedTime(), 'short')));

    $output['forcontu_pages_user'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('User data'),
    ];

    return $output;
  }

  public function links() {

    //link to /admin/structure/blocks
    $url1 = Url::fromRoute('block.admin_display');
    $link1 = Link::fromTextAndUrl(t('Go to the Block administration page'), $url1);

    $list[] = $link1;
    $list[] = $this->t('This text contains a link to %enlace. Just
    convert it to String to use it into a text.', ['%enlace' => $link1->toString()]);

    //link to <front>
    $url3 = Url::fromRoute('<front>');
    $link3 = Link::fromTextAndUrl(t('Go to Front page'), $url3);

    $list[] = $link3;

    //link to /node/1
    $url4 = Url::fromRoute('entity.node.canonical', ['node' => 1]);
    $link4 = Link::fromTextAndUrl(t('Link to node/1'), $url4);

    $list[] = $link4;

    //link to /node/1/edit
    $url5 = Url::fromRoute('entity.node.edit_form', ['node' => 1]);
    $link5 = Link::fromTextAndUrl(t('Link to edit node/1'), $url5);

    $list[] = $link5;

    //link to https://www.forcontu.com
    $url6 = Url::fromUri('https://www.forcontu.com');
    $link6 = Link::fromTextAndUrl(t('Link to www.forcontu.com'), $url6);

    $list[] = $link6;

    //link to internal css file
    $url7 = Url::fromUri('internal:/core/themes/bartik/css/layout.css');
    $link7 = Link::fromTextAndUrl(t('Link to layout.css'), $url7);

    $list[] = $link7;

    //link to https://www.drupal.org with extra attributes
    $url8 = Url::fromUri('https:www.drupal.org');

    $link_options = [
      'attributes' => [
        'class' => [
          'external-link',
          'list'
        ],
        'target' => '_blank',
        'title' => 'Got to drupal.org',
      ],
    ];

    $url8->setOptions($link_options);

    $link8 = Link::fromTextAndUrl(t('Link to drupal.org'), $url8);

    $list[] = $link8;

    $output['forocntu_pages_link'] = [
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Example of links:'),
    ];

    return $output;
  }

  public function tab1() {
    $output = '<p>' . $this->t('This is the content of Tab 1') . '</p>';

    if($this->currentUser->hasPermission('administer nodes')){
      $output .= '</p>' . $this->t('This extra test is only displayed if the current user can administer nodes.') . '</p>';
    }

    return [
      '#markup' => $output,
    ];
  }

  public function tab2() {
    return [
      '#markup' => '<p>' . $this->t('This is the content of Tab 2') . '</p>',
    ];
  }

  public function tab3() {
    return [
      '#markup' => '<p>' . $this->t('This is the content of Tab 3') . '</p>',
    ];
  }

  public function tab3a() {
    return [
      '#markup' => '<p>' . $this->t('This is the content of Tab 3a') . '</p>',
    ];
  }

  public function tab3b() {
    return [
      '#markup' => '<p>' . $this->t('This is the content of Tab 3b') . '</p>',
    ];
  }

  public function extratab() {
    return [
      '#markup' => '<p>' . $this->t('This is an extra tab into Admin Content page') . '</p>',
    ];
  }

  public function action1() {
    return [
      '#markup' => '<p>' . $this->t('Add something link 1') . '</p>',
    ];
  }

  public function action2() {
    return [
      '#markup' => '<p>' . $this->t('Add something link 2') . '</p>',
    ];
  }

  public function contentTypeHelpPage() {

    return [
      '#markup' => '<p>' . $this->t('Content for route %route.', ['%route' =>
          $this->routeMatch->getRouteName()]) . '</p>',];
  }
}// cierre de class


//admin/config/regional/settings
//cambia el pais por us

//$config = \Drupal::service('config.factory')->getEditable('system.date');

//asigna un valor único
//$config->set('country.default', 'US');

//asigna un array
//$timezone = ['warn' => TRUE, 'default' => 1, 'configurable' => 1];
//$config->set('timezone.user', $timezone);


//deja vacio el pais
//$config = \Drupal::service('config.factory')->getEditable('system.date');
//$config->clear('country.default');


//$config->save();

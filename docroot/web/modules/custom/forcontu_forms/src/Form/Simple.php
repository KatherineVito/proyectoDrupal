<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Egulias\EmailValidator\EmailValidator;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Render\Element\PathElement;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Implements the Simple form controller.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class Simple extends FormBase {

  protected $database;
  protected $currentUser;
  protected $emailValidator;

  public function __construct(Connection $database, AccountInterface $current_user, EmailValidator $email_validator) {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->emailValidator = $email_validator;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('current_user'),
      $container->get('email.validator')
    );
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('The title must be at least 5 characters long.'),
      '#required' => TRUE,
    ];

//    $form['color'] = [
//      '#type' => 'select',
//      '#title' => $this->t('Color'),
//      '#options' => [
//        0 => $this->t('Black'),
//        1 => $this->t('Red'),
//        2 => $this->t('Blue'),
//        3 => $this->t('Green'),
//        4 => $this->t('Orange'),
//        5 => $this->t('White'),
//      ],
//      '#default_value' => 2,
//      '#description' => $this->t('Choose a color'),
//    ];

    $form['username'] = [
//      '#type' => 'textfield',
      '#type' => 'item',
      '#title' => $this->t('Username'),
//      '#description' => $this->t('Your username'),
//      '#default_value' => $this->currentUser->getAccountName(),
      '#markup' => $this->currentUser->getAccountName(),
//      '#disabled' => TRUE,
      '#required' => TRUE,
    ];

    $form['commnet'] = [
      '#markup' => $this->t('Item elemet <strong>to add HTML</strong> into a Form.'),
    ];

    $form['user_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#description' => $this->t('Your email'),
//      '#field_prefix' => '<div class="field_prefix">',
//      '#field_suffix' => '</div>',
//      '#prefix' => '<div class="prefix">',
//      '#suffix' => '</div>',
      '#attributes' => ['class' => ['highlighted', 'forcontu']],
      '#required' => TRUE,
    ];

    //elementos de agrupacion

    //fieldset
//    $form['personal_data'] = [
//      '#type' => 'fieldset',
//      '#title' => $this->t('Personal Data'),
//    ];


    //details
    // es un elemento agrupador plegable, se puede abrir y cerrar haciendo clic en su titulo
//    $form['personal_data'] = [
//      '#type' => 'details',
//      '#title' => $this->t('Personal Data'),
//      '#description' => $this->t('Lorem ipsum dolor sit amet, consectetur adipiscing elit'),
//      '#open' => TRUE,
//    ];


    //container
    //agrupa los elementos con una etiqueta <div>
//    $form['personal_data'] = [
//      '#type' => 'container',
//      '#title' => $this->t('Personal Data'),
//    ];


    //fieldgroup
    //es similar al fieldset


    //vertical_tabs
    //es un agrupador de agrupadores.
    $form['information'] = [
      '#type' => 'vertical_tabs',
      '#default_value' => 'access-data-group',
    ];

    $form['personal_data'] = [
      '#type' => 'details',
      '#title' => $this->t('Personal Data'),
      '#description' => $this->t('Lorem ipsum dolor sit amet, consectetur adipiscing elit'),
      'group' => 'information',
    ];

    $form['personal_data']['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
      '#size' => 40,
    ];

    $form['personal_data']['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#required' => TRUE,
      '#size' => 40,
    ];

    $form['access_data'] = [
      '#type' => 'details',
      '#title' => $this->t('Access Data'),
      '#description' => $this->t('Curabitur non semper diam. Mauris faucibus eu augue vel semper'),
      'group' => 'information',
      '#id' => 'access-data-group',
    ];

    $form['access_data']['user_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#required' => TRUE,
    ];

    $form['access_data']['password'] = [
      '#type' => 'password_confirm',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];
    //


    // tabla
    $form['members'] = [
      '#type' => 'table',
      '#caption' => $this->t('Members'),
      '#header' => [$this->t('ID'), $this->t('First name'),
        $this->t('Last name')],
    ];

    for ($i=1; $i<5; $i++) {
      $form['members'][$i]['id'] = [
        '#type' => 'number',
        '#title' => $this->t('ID'),
        '#title_display' => 'invisible',
        '#size' => 3,
      ];

      $form['members'][$i]['first_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First name'),
        '#title_display' => 'invisible',
        '#size' => 30,
      ];

      $form['members'][$i]['last_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last name'),
        '#title_display' => 'invisible',
        '#size' => 30,
      ];
    }

    ///

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
      '#size' => 40,
      '#maxlength' => 801,
      '#default_value' => $this->t('Your first name'),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#cols' => 60,
      '#rows' => 5,
    ];

//    $form['user_email'] = [
//      '#type' => 'email',
//      '#title' => $this->t('User email'),
//      '#description' => $this->t('Your email'),
//      '#required' => TRUE,
//      '#size' => 60,
//      '#maxlength' => 128,
//    ];

    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
    ];

//    $form['quantity'] = [
//      '#type' => 'number',
//      '#title' => $this->t('Quantity'),
//      '#description' => $this->t('Must be a multiple of 5'),
//      '#min' => 0,
//      '#max' => 100,
//      '#step' => 5,
//    ];

    $form['pass'] = [
//      '#type' => 'password',
      '#type' => 'password_confirm',
      '#title' => $this->t('Password'),
      '#maxlength' => 64,
      '#size' => 30,
    ];

    $form['tel_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Telephone number'),
      '#maxlength' => 64,
      '#size' => 30,
    ];

    $form['body'] = [
      '#type' => 'text_format',
      '#title' => 'Body',
      '#format' => 'full_html',
    ];

    $form['entity_id'] = [
//      '#type' => 'hidden',
      '#type' => 'value',
      '#value' => 1,
    ];

    $form['help'] = [
      '#title' => $this->t('Some help'),
      '#type' => 'item',
      '#markup' => $this->t('Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ultricies ligula sed magna dictum porta. Sed porttitor lectus nibh.')
    ];

    $form['months'] = [
      '#type' => 'select',
      '#title' => $this->t('Month'),
      '#default_value' => date('n'),
      '#options' => [
        1 => $this->t('January'),
        2 => $this->t('February'),
        3 => $this->t('March'),
        4 => $this->t('April'),
        5 => $this->t('May'),
        6 => $this->t('June'),
        7 => $this->t('July'),
        8 => $this->t('August'),
        9 => $this->t('September'),
        10 => $this->t('October'),
        11 => $this->t('November'),
        12 => $this->t('December'),
      ],
      '#description' => t('Select month'),
    ];

    $form['colors'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Colors'),
      '#default_value' => ['red', 'green'],
      '#options' => [
        'red' => $this->t('Red'),
        'green' => $this->t('Green'),
        'blue' => $this->t('Blue'),
        'yellow' => $this->t('Yellow'),
      ],
      '#description' => $this->t('Select your preferred colors.'),
    ];

    $form['day'] = [
      '#type' => 'radios',
      '#title' => $this->t('Day of the week'),
      '#options' => [
        1 => $this->t('Monday'),
        2 => $this->t('Tuesday'),
        3 => $this->t('Wednesday'),
        4 => $this->t('Thursday'),
        5 => $this->t('Friday'),
        6 => $this->t('Saturday'),
        7 => $this->t('Sunday'),
      ],
      '#description' => $this->t('Choose the day of the week'),
      '#default_value' => date('N'),
    ];

    $form['legal_notice'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Accept terms and conditions.'),
    ];

    $form['accept'] = [
      '#type' => 'radio',
      '#title' => $this->t('Accept the agreement'),
      '#default_value' => 'accept',
    ];

    $form['quantity'] = [
      '#type' => 'range',
      '#title' => $this->t('Quantity'),
      '#min' => 0,
      '#max' => 10,
      '#description' => $this->t('Value between 0 and 100'),
    ];

    $header = [
      'uid' => $this->t('User ID'),
      'first_name' => $this->t('First Name'),
      'last_name' => $this->t('Last Name'),
    ];

    $options = [
      1 => ['uid' => 1, 'first_name' => 'Fran', 'last_name' => 'Gil'],
      2 => ['uid' => 2, 'first_name' => 'Laura', 'last_name' => 'Fornie'],
      3 => ['uid' => 3, 'first_name' => 'Mark', 'last_name' => 'Gil'],
    ];

    $form['users'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#empty' => $this->t('No users found'),
    ];

    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date'),
      '#default_value' => '2021-01-13',
    ];

    $form['event'] = [
      '#type' => 'datelist',
      '#title' => $this->t('Event date'),
      '#date_part_order' => ['year', 'month', 'day', 'hour', 'minute', 'second'],
      '#date_year_range' => '2010:2025',
      '#date_increment' => 15,
      '#default_value' => new DrupalDateTime('2021-01-13 17:15:00')
    ];

    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Meeting date'),
      '#date_date_element' => 'date',
      '#date_time_element' => 'none',
      '#default_value' => new DrupalDateTime('2021-01-13'),
      '#date_year_range' => '2010:+3',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color'),
      '#default_value' => '#ffffff',
    ];

    $form['file_upload'] = [
      '#type' => 'file',
      '#title' => t('Upload a file'),
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#default_value' => 0,
      '#delta' => 10,
    ];

    $form['path'] = [
      '#type' => 'path',
      '#title' => $this->t('Enter a path'),
      '#convert_path' => PathElement::CONVERT_ROUTE,
    ];

    $form['element_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Element name'),
    ];

    $form['element_machine_name'] = [
      '#type' => 'machine_name',
      'description' => $this->t('A unique name for this item. It must only contain lowercase letters, numbers, and underscores.'),
      'machine_name' => [
        'source' => ['element_name'],
      ],
    ];

    $form['author'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
      '#title' => $this->t('Author'),
      '#description' => $this->t('Enter the username'),
    ];

    $form['langcode'] = [
      '#type' => 'language_select',
      '#title' => $this->t('Language'),
      '#languages' => LanguageInterface::STATE_ALL,
      '#default_value' => \Drupal::languageManager()->getCurrentLanguage()->getId(),
    ];

    $form['search'] = [
      '#type' => 'search',
      '#title' => $this->t('Search'),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit')
    ];

    $form['actions']['preview'] = [
      '#type' => 'button',
      '#value' => $this->t('Preview'),
    ];

    $form['actions']['submit_image'] = [
      '#type' => 'image_button',
      '#value' => $this->t('Image button'),
      '#name' => 'submit_image',
      '#src' => 'core/misc/icons/787878/cog.svg',
    ];

    return $form;
  }

  public function getFormId() {
    return 'forcontu_forms_simple';
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    $title = $form_state->getValue('title');
    if (strlen($title) < 5) {
      //Set an error for the form element with a key of "title2
      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long'));
    }

    $email = $form_state->getValue('user_email');
    if(!$this->emailValidator->isValid($email)) { $form_state->setErrorByName('user_email', $this->t('%email is not a valid email address.', ['%email' => $email]));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->database->insert('forcontu_forms_simple')
      ->fields([
        'title' => $form_state->getValue('title'),
        'color' => $form_state->getValue('color'),
        'username' => $form_state->getValue('username'),
        'email' => $form_state->getValue('user_email'),
        'uid' => $this->currentUser->id(),
        'ip' => \Drupal::request()->getClientIP(),
        'timestamp' => REQUEST_TIME,
      ])
      ->execute();
    $this->messenger()->addMessage($this->t('The form has been submitted correctly'));
    $this->logger('forcontu_forms')->notice('New Simple Form entry from user %username inserted: %title.',
      [
        '%username' => $form_state->getValue('username'),
        '%title' => $form_state->getValue('title'),
      ]);
    $form_state->setRedirect('forcontu_pages.simple');
  }

  public function access(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('forcontu form access ') &&
    $account->hasPermission('administer site configuration'));
  }
}

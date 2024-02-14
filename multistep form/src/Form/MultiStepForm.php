<?php

/**
 * @file
 * Contains Drupal\multi_step_form\Form\MultiStepForm.
 */

namespace Drupal\multi_step_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\Entity\File;

class MultiStepForm extends FormBase
{



  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID()
  {
    return 'multi_step_form';
  }

  /**
   *  STEP 1
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    if ($form_state->has('page') && $form_state->get('page') == 2) {
      return self::formPageTwo($form, $form_state);
    } else if ($form_state->has('page') && $form_state->get('page') == 3) {
      return self::formPageThree($form, $form_state);
    }

    $form = array(
      '#attributes' => array('enctype' => 'multipart/form-data'),
    );

    $form_state->set('page', 1);

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('<b>Page @page</b>', ['@page' => $form_state->get('page')]),
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#default_value' => $form_state->getValue('first_name', ''),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#default_value' => $form_state->getValue('last_name', ''),
    ];

    $form['resume'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Resume'),
      '#upload_location' => 'public://resumes',
      '#upload_validators' => [
        'file_validate_extensions' => ['pdf'],
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Next'),
      '#submit' => ['::submitPageOne'],
      '#validate' => ['::validatePageOne'],
    ];

    return $form;
  }

  public function validatePageOne(array &$form, FormStateInterface $form_state)
  {
    $first_name = $form_state->getValue('first_name');
    if (strlen($first_name) < 5) {
      $form_state->setErrorByName('first_name', $this->t('The first name must be at least 5 characters long.'));
    }
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitPageOne(array &$form, FormStateInterface $form_state)
  {
    $form_state
      ->set('page_values', [
        'first_name' => $form_state->getValue('first_name'),
        'last_name' => $form_state->getValue('last_name'),
      ])
      ->set('page', 2)
      ->setRebuild(TRUE);
  }

  /**
   * STEP 2
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @return array
   *   The render array defining the elements of the form.
   */
  public function formPageTwo(array &$form, FormStateInterface $form_state)
  {

    $form_state->set('page', 2);

    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('<b>Page @page</b>', ['@page' => $form_state->get('page')]),
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('email'),
      '#description' => $this->t('Please enter the email'),
      '#required' => TRUE,
      '#default_value' => $form_state->getValue('email', ''),
    ];

    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::pageTwoBack'],
      '#limit_validation_errors' => [],
    ];

    $form['actions']['next'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Next'),
      '#submit' => ['::submitPageTwo'],
      '#validate' => ['::validatePageTwo'],
    ];

    return $form;
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function pageTwoBack(array &$form, FormStateInterface $form_state)
  {
    $form_state
      ->setValues($form_state->get('page_values'))
      ->set('page', 1)
      ->setRebuild(TRUE);
  }

  public function validatePageTwo(array &$form, FormStateInterface $form_state)
  {
    $email = $form_state->getValue('email');
    if (!empty($email) && !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitPageTwo(array &$form, FormStateInterface $form_state)
  {

    $form_values = array_merge(
      $form_state->get('page_values'),
      ['email' => $form_state->getValue('email')]
    );

    $form_state
      ->set('page_values', $form_values)
      ->set('page', 3)
      ->setRebuild(TRUE);
  }

  /**
   *  STEP 3
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @return array
   *   The render array defining the elements of the form.
   */
  public function formPageThree(array &$form, FormStateInterface $form_state)
  {
    $form['description'] = [
      '#type' => 'item',
      '#title' => $this->t('<b>Page @page</b>', ['@page' => $form_state->get('page')]),
    ];

    $form['location'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Your location'),
      '#default_value' => $form_state->getValue('location', ''),
    );
    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::pageThreeBack'],
      '#limit_validation_errors' => [],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function pageThreeBack(array &$form, FormStateInterface $form_state)
  {
    $form_state
      ->setValues($form_state->get('page_values'))
      ->set('page', 2)
      ->setRebuild(TRUE);
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $form_values =  $form_state->get('page_values');
    $form_values['location'] = $form_state->getValue('location');
    //Handle file upload
    $form_file = $form_state->getValue('resume', 0);
    if (isset($form_file[0]) && !empty($form_file[0])) {
      $file = File::load($form_file[0]);
      $file->setPermanent();
      $file->save();
    }

    $rendered_message = Markup::create(
      "Submitted values are:<br/> 
      firstname:" . $form_values['first_name'] . " <br/>
      last_name:" . $form_values['last_name'] . " <br/>
      email:" . $form_values['email'] . " <br/>
      location:" . $form_values['location'] . " <br/"
    );
    $status_message = new TranslatableMarkup('@message', array('@message' => $rendered_message));
    \Drupal::messenger()->addStatus($status_message);
    \Drupal::messenger()->addMessage('Thank you');
  }
}

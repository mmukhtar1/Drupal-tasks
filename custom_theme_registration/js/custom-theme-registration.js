/**
 * @file
 * custom_theme_registration behaviors.
 */
(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.customThemeRegistration = {
    attach (context, settings) {
        //Adding dynamic placeholders via JS
        $("#edit-mail").attr("placeholder", "Email")
        $("#edit-name").attr("placeholder", "User name")
    }
  };

} (jQuery, Drupal, drupalSettings));

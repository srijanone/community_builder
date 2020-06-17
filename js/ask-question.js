(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.AskQuestion = {
    attach: function (context, settings) {
      let config = {
        '#edit-field-community' : {},
        '#edit-field-tags'  : {},
      };
      for (let selector in config) {
        $(selector).chosen(config[selector]);
      }
    }
  };
})(jQuery, Drupal, drupalSettings);

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

      $('#edit-submit').on('click', function(e){
        let community = $('#edit-field-community').val();
        let tags = $('#edit-field-tags').val();
        let desc = $("#cke_1_contents iframe").contents().find("body").text();
        let title = $('#edit-title-0-value').val();

        if (community === '_none'){
          e.preventDefault();
          if (!$('.comm-error').length) {
            $('.form-item-field-community').append("<span id='comm-error' class='error comm-error'> Please select community</span>");
          }
        }
        else {
          $( ".comm-error" ).remove();
        }

        if (tags.length === 0){
          e.preventDefault();
          if (!$('.tags-error').length) {
            $('.form-item-field-tags').append("<span id='tags-error' class='error tags-error'> Please select tags</span>");
          }
        }
        else {
          $( ".tags-error" ).remove();
        }

        if (desc.length === 0) {
          e.preventDefault();
          if (!$('.desc-error').length) {
            $('#edit-field-description-wrapper').append("<span id='desc-error' class='error desc-error'> Please select description</span>");
          }
        }
        else {
          $( ".desc-error" ).remove();
        }

        if (title.length === 0) {
          e.preventDefault();
          if (!$('.title-error').length) {
            $('#edit-title-wrapper').append("<span id='desc-error' class='error title-error'> Please add title</span>");
          }
        }
        else {
          $( ".title-error" ).remove();
        }
      })

    }
  };
})(jQuery, Drupal, drupalSettings);

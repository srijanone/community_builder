(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.AskQuestion = {
    attach: function (context, settings) {
      let currDate = new Date();
      let date = currDate.getDate();
      let month = currDate.getMonth();
      let currMonth = month+1;
      let year = currDate.getFullYear();
      let passRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{12,})");
      if (currMonth.toString().length < 2) {
        currMonth = '0' + currMonth.toString()
      }
      let finalDate = year + '-' + currMonth + '-' + date

      $('#edit-field-dob-0-value-date').attr('max', finalDate.toString())
      $('#edit-submit').on('click', function(e) {
        if ($('#edit-pass-pass1').val().length !== 0 &&
          !$('#edit-pass-pass1').val().match(passRegex) &&
          $('span.password-strength__text').text() !== 'Strong' &&
          $('span.password-strength__text').text() !== 'Fair'){
          e.preventDefault();
          if (!$('.pass-error').length) {
            $('.password-strength').append("<span id='pass-error' class='error pass-error'> Please choose strong password</span>")
          }
          else {
            $( ".pass-error" ).remove();
          }
        }
        else {
          $( ".pass-error" ).remove();
        }
      })
    }
  };
})(jQuery, Drupal, drupalSettings);

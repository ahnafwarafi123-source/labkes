// Premium Effects JS
(function($) {
  'use strict';

  // Initialize on document ready
  $(document).ready(function() {

    // Smooth scrolling for anchor links
    $('a[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top - 70
          }, 1000);
          return false;
        }
      }
    });

    // Add loading states to buttons
    $('button[type="submit"], .btn').on('click', function() {
      var $btn = $(this);
      if ($btn.hasClass('btn-loading')) return;

      // Beri jeda sangat kecil sebelum menonaktifkan tombol 
      // agar browser sempat mengirimkan (submit) form-nya.
      setTimeout(function() {
        $btn.addClass('btn-loading');
        if (!$btn.is('a')) {
          $btn.prop('disabled', true);
        }
      }, 50);

      // Re-enable after 3 seconds (fallback)
      setTimeout(function() {
        $btn.removeClass('btn-loading').prop('disabled', false);
      }, 3000);
    });

    // Auto-hide alerts after 5 seconds
    $('.alert').each(function() {
      var $alert = $(this);
      setTimeout(function() {
        $alert.fadeOut('slow');
      }, 5000);
    });

    // Enhanced form validation feedback
    $('form').on('submit', function(e) {
      var $form = $(this);
      var isValid = true;

      $form.find('input[required], select[required], textarea[required]').each(function() {
        var $field = $(this);
        if (!$field.val().trim()) {
          $field.addClass('is-invalid');
          isValid = false;
        } else {
          $field.removeClass('is-invalid');
        }
      });

      if (!isValid) {
        e.preventDefault();
        // Show error message
        if (!$('.form-error').length) {
          $form.prepend('<div class="alert alert-danger form-error">Mohon lengkapi semua field yang wajib diisi.</div>');
        }
      }
    });

    // Tooltip initialization
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Popover initialization
    $('[data-bs-toggle="popover"]').popover();

  });

})(jQuery);
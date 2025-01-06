   $(document).ready(function(){
      // Open login modal
      $(".login-modal a").click(function(){
         $("#loginModal").modal('show');
      });

      // Open register modal
      $(".cart-option a").click(function(){
         $("#registerModal").modal('show');
      });
   });
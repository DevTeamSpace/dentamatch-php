<script>
  $(function(){
     if ('URLSearchParams' in window) {
         var searchParams = new URLSearchParams(window.location.search);
         var code = searchParams.get('promo');
         if (code) {
           localStorage.setItem('code', code);
         }
     }
  });
</script>

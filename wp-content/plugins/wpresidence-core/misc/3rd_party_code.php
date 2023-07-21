<?php
function wpestate_core_add_to_footer(){
    $ga =esc_html(wpresidence_get_option('wp_estate_google_analytics_code', ''));
    if ($ga != '') { ?>

    <script>
        //<![CDATA[
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo esc_html($ga); ?>', '<?php     echo esc_html($_SERVER['SERVER_NAME']); ?>');
      ga('send', 'pageview');
    //]]>
    </script>


    <?php } 
}


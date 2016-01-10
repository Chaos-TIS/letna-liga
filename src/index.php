<?php
require_once(dirname(__FILE__)."/includes/functions.php");
page_head("Letná liga FLL");
page_nav();

if (!isset($_SESSION['loggedUser']))
    get_login_form();
else
    get_logout_button();
?>

        <div id="content">

            <script>
                $(document).ready(function(){
                    $("#results").load("includes/show_result_tables.php");
                });
            </script>

            <div class="translatable" data-trans="intro1"></div>

            <p id="results"><span  data-trans="table-loading"></span></p>

            <div class="translatable" data-trans="intro2"></div>

        </div>

<?php
page_footer();
?>
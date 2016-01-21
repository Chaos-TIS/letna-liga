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
                    $.ajax({
                        async: true,
                        type: "GET",
                        data: {<?php if (isset($_GET['year'])) echo 'year:'.$_GET['year'];?>},
                        url: "includes/show_result_tables.php"
                    }).done(function (data) {
                        $("#results").html(data);
                    })
                });
            </script>

            <p id="results"><span data-trans="table-loading"></span></p>

        </div>

<?php
page_footer();
?>
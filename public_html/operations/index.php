<?php
//Check user authentication
include $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
    
//Buffer larger content areas like the main page content
ob_start();
?>
<link href="/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="/js/datepicker.min.js"></script>
<script src="/js/i18n/datepicker.en.js"></script>
<script type="text/javascript">

    var clicked;
    function changeFilterCommands(theForm) {

        if (clicked == "cancel") {
            $("#cat_dashboard_main_panel").load("/user/cats/cat_dashboard_main.php");
            return false;
        }

        var formData = "unitid=" + theForm.elements.namedItem("unitid").value;
        
        if (theForm.elements.namedItem("username").value !== "") {
            formData += "&username=" + theForm.elements.namedItem("username").value;
        }
        if (theForm.elements.namedItem("description").value !== "") {
            formData += "&description=" + theForm.elements.namedItem("description").value;
        }
        if (theForm.elements.namedItem("mindate").value !== "") {
            formData += "&mindate=" + theForm.elements.namedItem("mindate").value;
        }
        if (theForm.elements.namedItem("maxdate").value !== "") {
            formData += "&maxdate=" + theForm.elements.namedItem("maxdate").value;
        }
        if (theForm.elements.namedItem("operation_type").value !== "all") {
            formData += "&operation_type=" + theForm.elements.namedItem("operation_type").value;
        }
        
        $.ajax({
            type: "POST",
            url: "/operations/handlers/get_command_logs_handler.php",
            data: formData,
            cache: false,
            success: function (res) {
                var res_arr = JSON.parse(res);
                if (res_arr.status === "error") {
                    $('#command_log_result').html("<div align='center'>That is embarrassing, there was an error in our server. Please, try again.</div>");
                } else if (res_arr.status === "notfound"){
                    $('#command_log_result').html("<div align='center'>We could not find any result matching your criteria.</div>");
                } else if (res_arr.status === 'success') {
                    $("#command_log_result").load("/operations/show_log_results.php",{msg: JSON.stringify(res_arr.msg)});
                } else {
                    // hopefully we never get here
                }
            }
        });
        return false;
    }
</script>

<div align="center" class="form-addupdate">
    <h1>
        Operation log
        <div class="closex" onclick="" />
    </h1>

    <form action="javascript:;" onsubmit="return changeFilterCommands(this);">
        <label>
            Buoy Unit Id
            <select name="unitid" id="unitid"  onchange="changeParameters();">
                <option value="300025010008540">PAP 2017 (300025010008540)</option>
            </select>
        </label>
        <label>
            Operation Type
            <select name="operation_type"> Command
                <option value="all">All</option>
                <option value="deployment">Deployment</option>
                <option value="test">Test</option>
                <option value="debug">Debug</option>
            </select>
        </label>
    
        <label>
            User name
            <input name="username" type="text" maxlength="100" size="25" value="" />
        </label>
        <label>
            Min Date (YYYY-MM-DD)
            <!--<input name="birthday" type="date" size="25" required />-->
            <input type='text' class='datepicker-here' data-language='en' name="mindate"/>
        </label>
        <label>
            Max Date (YYYY-MM-DD)
            <!--<input name="birthday" type="date" size="25" required />-->
            <input type='text' class='datepicker-here' data-language='en' name="maxdate"/>
        </label>
        <label>
            Description
            <input name="description" type="text" maxlength="100" size="25" value="" />
        </label>
        <div class="button-section">
            <input type="submit" onclick="clicked = 'update';" name = "update" value="Update" />
        </div>
    </form>
</div>

<br><br>
<div id="command_log_result"></div>
<br><br>
<?php
//Assign all Page Specific variables
$pagecontent = ob_get_contents();
ob_end_clean();
//  $pagetitle = "Welcome to FittyCat";
$pagetitle = "Operation log";
//        . '<link rel="stylesheet" href="/css/stylesheet_home.css" type="text/css" />';
//        . '<script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>';

//Apply the template
include($_SERVER['DOCUMENT_ROOT'] . "/master.php");
?>
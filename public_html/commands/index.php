<?php
//Check user authentication
include $_SERVER['DOCUMENT_ROOT'] . '/../server/accesscontrol.php';
    
//Buffer larger content areas like the main page content
ob_start();
?>
    <script type="text/javascript">
    
        $(document).ready(function () {
             document.getElementById("command").selectedIndex = 0;
             changeParameters();
         });
         
         function changeFilterCommands(evt) {
            
            var filter_name = 'option.' + evt.name;
            
            if (evt.checked) {
                $(filter_name).show();
            } else {
                $(filter_name).hide();
            }
            return false;
         }
         
         function changeParameters() {
             var x = document.getElementById("command");
             
             document.getElementById("huborbuoy").style.display = "none";
             document.getElementById("onoff").style.display = "none";
             document.getElementById("yesno").style.display = "none";
             document.getElementById("hourinterval").style.display = "none";
             document.getElementById("firsthour").style.display = "none";
             document.getElementById("firstminute").style.display = "none";
             document.getElementById("warmupminutes").style.display = "none";
             document.getElementById("minuteson").style.display = "none";
             document.getElementById("samplesmin").style.display = "none";
             document.getElementById("equilibriummin").style.display = "none";
             document.getElementById("avgseconds").style.display = "none";
             switch (x.value) {
                 // update search button when autoupdate/manual option was changed
                 case "DUP":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 4;
                     document.getElementById("firsthour_in").value = 0;
                     break;
                 case "SBD":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 3;
                     document.getElementById("firsthour_in").value = 1;
                     break;
                 case "IMM":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 0.5;
                     document.getElementById("firsthour_in").value = 0;
                     break;
                 case "IMD":
                     document.getElementById("yesno").style.display = "block";
                     
                     document.getElementById("yesno_in").value = "YES";
                     break;
                 case "RST":
                     break;
                 case "HUBPWR":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "GPS":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     document.getElementById("minuteson").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 0.5;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("firstminute_in").value = 4;
                     document.getElementById("minuteson_in").value = 10;
                     document.getElementById("avgseconds_in").value = 119;
                     break;
                 case "ACC":
                     document.getElementById("huborbuoy").style.display = "block";
                     
                     document.getElementById("huborbuoy_in").value = "BOTH";
                     break;
                 case "BTT":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     document.getElementById("minuteson").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 1;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("firstminute_in").value = 4;
                     document.getElementById("minuteson_in").value = 10;
                     document.getElementById("avgseconds_in").value = 119;
                     break;
                 case "KCO":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     document.getElementById("warmupminutes").style.display = "block";
                     document.getElementById("samplesmin").style.display = "block";
                     document.getElementById("equilibriummin").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 12;
                     document.getElementById("firsthour_in").value = 11;
                     document.getElementById("firstminute_in").value = 20;
                     document.getElementById("warmupminutes_in").value = 5;
                     document.getElementById("samplesmin_in").value = 17;
                     document.getElementById("equilibriummin_in").value = 15;
                     document.getElementById("avgseconds_in").value = 120;
                     break;
                 case "OCR":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     document.getElementById("minuteson").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 0.5;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("firstminute_in").value = 1;
                     document.getElementById("minuteson_in").value = 17;
                     document.getElementById("avgseconds_in").value = 29;
                     break;
                 case "OCO":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "MEL":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("minuteson").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 2;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("minuteson_in").value = 5;
                     document.getElementById("avgseconds_in").value = 27;
                     break;
                 case "BFT":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "ATT":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     document.getElementById("minuteson").style.display = "block";
                     document.getElementById("avgseconds").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 0;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("firstminute_in").value = 4;
                     document.getElementById("minuteson_in").value = 10;
                     document.getElementById("avgseconds_in").value = 119;
                     break;
                 case "GTD":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "HCO":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "FET":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "SUN":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "RHB":
                     break;
                 case "BUF":
                     document.getElementById("hourinterval").style.display = "block";
                     document.getElementById("firsthour").style.display = "block";
                     document.getElementById("firstminute").style.display = "block";
                     
                     document.getElementById("hourinterval_in").value = 0;
                     document.getElementById("firsthour_in").value = 0;
                     document.getElementById("firstminute_in").value = 4;
                     break;
                 case "PO4":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "SEA":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 case "WET":
                     document.getElementById("onoff").style.display = "block";
                     
                     document.getElementById("onoff_in").value = "ON";
                     break;
                 default:
                     break;
             }
             
             return false;
         }
         
         function create_command(theForm) {
            
            var x = document.getElementById("command");
            var command = x.value + "=";
             
            switch (x.value) {
                 // update search button when autoupdate/manual option was changed
                 case "DUP":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value; 
                     break;
                 case "SBD":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value; 
                     break;
                 case "IMM":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value; 
                     break;
                 case "IMD":
                     command += theForm.elements.namedItem("yesno").value; 
                     break;
                 case "RST":
                     command += "YES";
                     break;
                 case "HUBPWR":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "GPS":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "ACC":
                     if (theForm.elements.namedItem("huborbuoy") == "HUB"){
                        command += "2";
                     } else if (theForm.elements.namedItem("huborbuoy") == "BUOY"){
                        command += "1";
                     } else if (theForm.elements.namedItem("huborbuoy") == "NONE"){
                        command += "0";
                     } else  { // both
                        command += "3";
                     }
                     break;
                 case "BTT":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "KCO":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     command += "," + theForm.elements.namedItem("warmupminutes").value;
                     command += "," + theForm.elements.namedItem("equilibriummin").value;
                     command += "," + theForm.elements.namedItem("samplesmin").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "OCR":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "OCO":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "MEL":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "BFT":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "ATT":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("minuteson").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     command += "," + theForm.elements.namedItem("avgseconds").value;
                     break;
                 case "GTD":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "HCO":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "FET":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "SUN":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "RHB":
                     command += "RESET";
                     break;
                 case "BUF":
                     command += theForm.elements.namedItem("hourinterval").value; 
                     command += "," + theForm.elements.namedItem("firsthour").value;
                     command += "," + theForm.elements.namedItem("firstminute").value;
                     break;
                 case "PO4":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "SEA":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 case "WET":
                     command += theForm.elements.namedItem("onoff").value; 
                     break;
                 default:
                     break;
             }
            
            return command;
         }
         
         var clicked;
         function validateFormOnSubmit(theForm) {
     
             var docontinue = false;
             
             if (clicked == "cancel") {
                 return false;
             }
             
             var command = create_command(theForm);
             
             if (command === ''){
                 alert("Problem when creating command");
                 return false;
             }
             
             if (theForm.elements.namedItem("description").value === "" && theForm.elements.namedItem("operation_type").value != "debug") {
                alert("You need to enter a description to explain the reason for sending the command");
                return false;
             }
             
             var reason = "";
             var formData = "command=" + command;
             
             var unitid = theForm.elements.namedItem("unitid").value;
             formData += "&iridium_unit="+unitid;
             
             var optype = theForm.elements.namedItem("operation_type").value;
             formData += "&operation_type="+optype;
             
             var description = theForm.elements.namedItem("description").value;
             formData += "&description="+description;
             
             var x = document.getElementById("command");
             if (x.value === "RST") {
                docontinue = confirm("You are about to restart the observatory, are you sure you want to continue?");
                
                if (!docontinue)  {
                    alert("The command was not sent per user request");
                    return false;
                }
                docontinue = confirm("Really sure?");
                if (!docontinue)  {
                    alert("The command was not sent per user request");
                    return false;
                }
             } else if (x.value === "RHB" ) {
                docontinue = confirm("You are about to restart the data hub in the frame, are you sure you want to continue?");
                
                if (!docontinue)  {
                    alert("The command was not sent per user request");
                    return false;
                }
                docontinue = confirm("Really sure?");
                if (!docontinue)  {
                    alert("The command was not sent per user request");
                    return false;
                }
             } else {
                docontinue = confirm("You are about to send the folloing command: #CMD," + command +", are you sure you want to continue?");
                if (!docontinue)  {
                    alert("The command was not sent per user request");
                    return false;
                }
             }
            
             if (reason !== "") {
                 alert("Some fields need correction:\n" + reason);
             } else {
                 $.ajax({
                     type: "POST",
                     url: "handlers/send_command_handler.php",
                     data: formData,
                     cache: false,
                     success: function (res) {
                        res_arr = JSON.parse(res);
                         if (res_arr.status === 'success'){
                            alert("Command was sent successfully");
                         } else {
                            alert("There was a problem sending the command: "+res_arr.msg);
                         }
                     },
                     error: function (xhr, ajaxOptions, thrownError) {
                        alert("There was a problem sending command \n "+ xhr.status + "\n" +thrownError);
                     }
                 });
             }
             return false;
         }
         
    </script>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="lg-col-12 col-md-12 col-sm-12 col-xs-12">
                <h1>SBD command form</h1>
                <form action="javascript:;" onsubmit="return validateFormOnSubmit(this);">
                    
                        <div>
                            <p>Buoy Unit Id</p>
                            <select name="unitid" id="unitid"  onchange="changeParameters();">
                                <option value="300025010008540">PAP 2017 (300025010008540)</option>
                            </select>
                        </div>
                        <div id="operation_type">
                            <p>Operation Type</p>
                            <select name="operation_type"> Command
                                <option value="deployment">Deployment</option>
                                <option value="test">Test</option>
                                <option value="debug">Debug</option>
                            </select>
                        </div>
                        <input type="checkbox" name="opt_buoy" value='opt_buoy' onchange="return changeFilterCommands(this);" checked> Buoy
                        <input type="checkbox" name="opt_frame" value='opt_frame' onchange="return changeFilterCommands(this);" checked> Frame
                        <input type="checkbox" name="opt_iridium" value='opt_iridium' onchange="return changeFilterCommands(this);" checked> Iridium
                        <input type="checkbox" name="opt_behaviour" value='opt_behaviour' onchange="return changeFilterCommands(this);" checked> System
                        <input type="checkbox" name="opt_sensor" value='opt_sensor' onchange="return changeFilterCommands(this);" checked> Sensors
                        <input type="checkbox" name="opt_abort" value='opt_abort' onchange="return changeFilterCommands(this);" checked> Abort
                        <div>
                            <p>Command</p>
                            <select name="command" id="command" onchange="changeParameters()">
                                <option value="DUP" class="opt_iridium opt_buoy">Dial-Up data transfer (DUP)</option>
                                <option value="SBD" class="opt_iridium opt_buoy"> Email status report (SBD)</option>
                                <option value="HUBPWR" class="opt_behaviour opt_frame">Turn the hub power on or off (HUBPWR)</option>
                                <option value="BUF" class="opt_behaviour opt_frame">Change frequency of hub data sent to buoy (BUF)</option>
                                <option value="IMM" class="opt_inductive opt_buoy">Induction modem with sensors (IMM)</option>
                                <option value="IMD" class="opt_inductive opt_buoy">Activate inductive modem log diagnosis (IMD)</option>
                                <option value="GPS" class="opt_behaviour opt_buoy">Change GPS sample rate (GPS)</option>
                                <option value="ACC" class="opt_behaviour opt_frame">Turn on/off accelerometer data (ACC)</option>
                                <option value="BTT" class="opt_behaviour opt_buoy">Buoy attitude sample rate (BTT)</option>
                                <option value="KCO" class="opt_sensor opt_buoy opt_prooceanus">Keel CO2 sensor sample rate (KCO)</option>
                                <option value="OCR" class="opt_sensor opt_buoy opt_frame opt_ocr">Keel and frame OCR sample rate (OCR)</option>
                                <option value="OCO" class="opt_sensor opt_buoy opt_frame opt_ocr">Turn on or off OCR sensor's functionality for switching off at night (OCO)</option>
                                <option value="MEL" class="opt_sensor opt_buoy opt_ph">Change Senslab pH sensor sample rate (MEL)</option>
                                <option value="BFT" class="opt_sensor opt_buoy opt_ph">Turn SeaFET in buoy external power on/off (BFT)</option>
                                <option value="ATT" class="opt_behaviour opt_frame">Change attitude measurements sample rate of hub (ATT)</option>
                                <option value="GTD" class="opt_sensor opt_frame opt_prooceanus">Turn GTD in frame external power on/off (GTD)</option>
                                <option value="HCO" class="opt_sensor opt_frame opt_prooceanus">Turn CO2 in frame external power on/off (HCO)</option>
                                <option value="FET" class="opt_sensor opt_frame opt_ph">Turn SeaFET in frame external power on/off (FET)</option>
                                <option value="SUN" class="opt_sensor opt_frame opt_nitrate">Turn Nitrate sensor in frame external power on/off (SUN)</option>
                                <option value="PO4" class="opt_sensor opt_frame opt_phosphate">Turn Wetlabs Phosphage in frame external power on/off (PO4)</option>
                                <option value="SEA" class="opt_sensor opt_frame opt_ph opt_oxygen">Turn Seaguard in frame external power on/off (SEA)</option>
                                <option value="WET" class="opt_sensor opt_frame opt_fluorometer">Turn Wetlabs fluorometer in frame external power on/off (WET)</option>
                                <option value="RST" class="opt_abort opt_buoy">Reset the buoy (RST)</option>
                                <option value="RHB" class="opt_abort opt_frame">Resets Hub (RHB)</option>
                            </select>
                        </div>
                        <div id="huborbuoy">
                            <p>Unit</p>
                            <select name="huborbuoy" id="huborbuoy_in"> Command
                                <option value="HUB">Hub</option>
                                <option value="BUOY">Buoy</option>
                                <option value="BOTH">Both</option>
                                <option value="NONE">None</option>
                            </select>
                        </div>
                        <div id="onoff">
                            <p>Activate</p>
                            <select name="onoff" id="onoff_in"> Command
                                <option value="ON">On</option>
                                <option value="OFF">Off</option>
                            </select>
                        </div>
                        <div id="yesno">
                            <p>Switch</p>
                            <select name="yesno" id="yesno_in">
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div id="hourinterval">
                            <p>Hour interval</p>
                            <input name="hourinterval" id="hourinterval_in" type="number" min="0" max="12" value="1" step="0.5"/>
                        </div>
                        <div id="firsthour">
                            <p>Start hour</p>
                            <input name="firsthour" id="firsthour_in" type="number" min="0" max="23" value="1" />
                        </div>
                        <div id="firstminute">
                            <p>Start Minute</p>
                            <input name="firstminute" id="firstminute_in" type="number" min="0" max="59" value="10" />
                        </div>
                        <div id="warmupminutes">
                            <p>Warmup minutes</p>
                            <input name="warmupminutes" id="warmupminutes_in" type="number" min="0" max="120" value="19" />
                        </div>
                        <div id="minuteson">
                            <p>Minutes On</p>
                            <input name="minuteson" id="minuteson_in" type="number" min="0" max="59" value="19" />
                        </div>
                        <div id="samplesmin">
                            <p>Minutes On</p>
                            <input name="samplesmin" id="samplesmin_in" type="number" min="0" max="59" value="19" />
                        </div>
                        <div id="equilibriummin">
                            <p>Minutes to Equilibrate</p>
                            <input name="equilibriummin" id="equilibriummin_in" type="number" min="0" max="59" value="19" />
                        </div>
                        <div id="avgseconds">
                            <p>Seconds to Average</p>
                            <input name="avgseconds" id="avgseconds_in" type="number" min="0" max="200" value="19" />
                        </div>
                        <br><br>
                        <div id="description">
                            <p>Description</p>
                            <textarea name="description" id="description_in" cols="80" rows="6"></textarea>
                        </div>
                        <br><br>
                        <input type="submit" onclick="clicked = 'send';" name='sent' value="Send"/>
                        <br><br>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
//Assign all Page Specific variables
$pagecontent = ob_get_contents();
ob_end_clean();
//  $pagetitle = "Welcome to FittyCat";
$pagetitle = "SBD commands for PAP observatory";
//        . '<link rel="stylesheet" href="/css/stylesheet_home.css" type="text/css" />';
//        . '<script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>';

//Apply the template
include($_SERVER['DOCUMENT_ROOT'] . "/master.php");
?>
<?php
    
//Buffer larger content areas like the main page content
ob_start();
?>
    <section id="hero-section">
    <div class="container">
        <div class="row">
            <div class="lg-col-12 col-md-12 col-sm-12 col-xs-12">
                <div class="hero-section">	
                    <ul class="bxslider">
                        <li>
                            <span><img src="/resources/images/pap2016-team.jpg" alt="" /></span>
                            <div class="hero-caption">
                                <h3> This is an interface for controlling pap observatory.</h3>
                                <h3> Please, log in to start sending commands</h3>
                                <h2>Join, here </h2>
                                <a href="#" class="btn-css" data-toggle="modal" data-target="#signup-modal">Sign up</a>
                            </div>
                        </li>
                    </ul>
                </div> 
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view("asterisk_common/head");
        ?>
        <script src="<?php echo base_url("application/asterisk/asterisk_common/public/js/head.load.min.js")?>"></script>
    </head>

    <body>
        <?php
        $navigation_date = array("active" => "home");
        $this->load->view("asterisk_common/navigation", $navigation_date);
        ?>

        <header id="overview" class="jumbotron subhead">
            <div class="container">
                <h1 id="header_container">
                    Welcome!
                </h1>
            </div>
        </header>
        <div class="container">
            <div class="row" style="margin-top: 18px;" >
                <div class="span6 well">
                    <?php if (get_environment() == "PRD") { ?>
                        <h2>Environment: Production</h2>
                        <p><strong>Staging content is currently disabled in your browser.</strong> To preview the <a href="/" target="_blank">public site</a> in staging mode, please click on the button below.</p>
                        <p><a class="btn btn-large btn-primary" href="/asterisk/asterisk/environment/STG">Enable Staging</a></p>
                    <?php } else { ?>
                        <h2>Environment: Staging</h2>
                        <p><strong>Staging content is currently enabled in your browser.</strong> To view the <a href="/" target="_blank">public site</a> in production mode, please click on the button below.</p>
                        <p><a class="btn btn-large btn-primary" href="/asterisk/asterisk/environment/PRD">Disable Staging</a></p>
                    <?php } ?>
                </div>
                <div class="span6 well">
                    <?php if (get_now_value() == false) { ?>
                        <h2>Browser Time: Now</h2>
                        <p><strong>Your browser is currently set show content at the current time .</strong> To set the <a href="/" target="_blank">public site</a> to a specific time, please select the time and click on the button below.</p>
                        <p>
                            <form action="/asterisk/asterisk/now/" method="post">
                                <div class="control-group">
                                    <div class="controls input-append-custom" id="now_date_container">
                                        <input name="form[now_date]" type="text" class="input-large" id="now_date" value="" placeholder="MM/DD/YYYYY hh:mm:ss" data-format="MM/dd/yyyy hh:mm:ss"/>
                                        <span class="add-on">
                                            <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                            </i>
                                        </span>
                                    </div>
                                </div> 
                                <button class="btn btn-large btn-primary" href="/asterisk/asterisk/now/">Set Time</button>
                            </form>
                        </p>
                        <script>
                            loadCSS("application/asterisk/asterisk_common/public/css/bootstrap-datetimepicker.min.css");
                            head.js(
                                {jquery_datetimepicker: "application/asterisk/asterisk_common/public/js/bootstrap-datetimepicker.min.js"}
                            );
                            head.ready(function() {
                                $('#now_date_container').datetimepicker({
                                    language: 'en'
                                });
                            });
                        </script>
                    <?php } else { ?>
                        <h2>Time: <?php echo get_now()->format("F j, Y, g:i a"); ?></h2>
                        <p><strong>Staging content is currently enabled in your browser.</strong> To view the <a href="/" target="_blank">public site</a> in production mode, please click on the button below.</p>
                        <p>
                            <a class="btn btn-large btn-primary" href="/asterisk/asterisk/now/">Reset To Now</a>
                         </p>
                    <?php } ?>
                </div>
        </div>
    </div>

    <?php
    $this->load->view("asterisk_common/footer");
    ?>
    <!-- /container --> 

    <?php
    $this->load->view("asterisk_common/foot");
    ?>

</html>

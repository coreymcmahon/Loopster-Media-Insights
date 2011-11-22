<?php use_javascript("highcharts") ?>
<script type="text/javascript">
    $(document).ready(function () {

        var minDate = new Date(<?php echo $min_date_array[0]; ?>, <?php echo $min_date_array[1]; ?> - 1, <?php echo $min_date_array[2]; ?>);
        var maxDate = new Date(<?php echo $max_date_array[0]; ?>, <?php echo $max_date_array[1]; ?> - 1, <?php echo $max_date_array[2]; ?>);
        var dateFormat = 'yy-mm-dd';

        $("#graph_start_date").datepicker({
            dateFormat: dateFormat,
            minDate: minDate,
            maxDate: maxDate,
            onSelect: function(dateText, inst) {
               /* When a 'start' date is selected, make this the min value for the end-date selector */
                $("#graph_end_date").datepicker("option","minDate", $("#graph_start_date").datepicker("getDate"));
            }
        });
        $("#graph_end_date").datepicker({
            dateFormat: dateFormat,
            minDate: minDate,
            maxDate: maxDate,
            onSelect: function(dateText, inst) {
               /* When a 'end' date is selected, make this the max value for the start-date selector */
                $("#graph_start_date").datepicker("option","maxDate", $("#graph_end_date").datepicker("getDate"));
            }
        });
    });
</script>

<div class="main-form">
    <form action="<?php echo url_for('default/index') ?>" method="POST">
        <div class="button view_by">
            <a href="#" onclick="updateButtonState(this); return false;" class="criteria">Select Criteria</a>
            <a href="#" onclick="updateButtonState(this); return false;" class="pages">Select Pages</a>
        </div>
        <div class="fields">
            <div id="form-left">
                <div>
                    <div class="radio view_by"><?php echo $form['view_by']->render() ?></div>
                    <div class="radio industry">
                        <div class="ddl industry"><?php echo $form['industry']->renderRow() ?></div>
                        <div class="ddl show"><?php echo $form['show']->renderRow() ?></div>
                    </div>
                    <div class="radio pages">
                        <?php echo $form['brands']->renderRow() ?>
                    </div>
                </div>
            </div>
            <div id="form-center">
                <div>
                    <?php /*echo $form['graph_type']->renderRow() */ /* Commented out because only allowing 1 graph type */ ?>
                    <div>Graph display</div>
                    <?php echo $form['graph_type']->render() ?>
                    <?php echo $form['fans']->renderRow() ?>
                </div>
            </div>
            <div id="form-right">
                <div>
                    <table><?php echo $form['start_date']->renderRow() ?><br/>
                    <?php echo $form['end_date']->renderRow() ?></table>
                </div>
            </div>
        </div>
        <div style="display:none;"><?php if ($form->isCSRFProtected()) : ?><?php echo $form['_csrf_token']->render(); ?><?php endif; ?></div>

        <div class="update">
            <input type="submit" id="form_submit" value="Update" />
        </div>
    </form>

    

    <div id="graph-container">
        <div id="graph">&nbsp;</div>
    </div>

    <div id="inclusion-form">
        <div class="heading">Want us to track your Facebook Page?</div>
        <div class="subheading">Submit a request below to have your page included</div>
        <div class="input"><label>Facebook Page URL:</label> <input type="text" name="page_url" id="inclusion_page_url" /></div>
        <input type="hidden" name="user_id" value="<?php echo $sf_user->getGuardUser()->getId() ?>" id="inclusion_user_id" /><button id="inclusion_submit">Send</button>
    </div>
</div>

<script>
    $(document).ready(
        function () {
            $("#inclusion_submit").bind("click",function() {
                if ($("#inclusion_page_url").attr("value") != "") {
                    /* execute ajax callback */
                    $.ajax("default/inclusionRequest?page_url=" + $("#inclusion_page_url").attr("value"),{
                        success: function () {
                            alert("Thanks! Your inclusion request has been received.");
                            $("#inclusion_page_url").attr("value","");
                        },
                        error: function () {
                            alert("An error occured while trying to process your inclusion request.\n\nPlease try again later.");
                        }
                    });
                } else {
                    alert("Please provide a Facebook Page URL");
                }
            });
        }
    );
</script>
<script>
    /* Add the tooltips */
    $('#graph_start_date').tipsy({fallback:"Enter the start date for the data", gravity: "w" });
    $('#graph_end_date').tipsy({fallback:"Enter the end date for the data", gravity: "w"});
</script>
<script>
    var currentState = "<?php echo $form['view_by']->getValue() ?>";

    function updateButtonState(button) {
      var $button = $(button); // get a jQuery object for the button that was pressed
      $(".button.view_by a").removeClass("selected"); // remove the 'selected' class from all
      $button.addClass('selected'); // add the 'selected' class to the button that was pressed
      $button.hasClass("criteria") ? toggleCriteria() : togglePages();
    }

    function toggleCriteria() {
      /* get the jQuery object for the toggle button */
      var $button = $(".button.view_by a.criteria");
      $button.addClass("selected");
      /* hide / show the relevant content panel */
      $(".radio.industry").css("display","block")
      $(".radio.pages").css("display","none");
      /* update the state of the checkbox */
      $('input[value=industry]').attr('checked', true);
      $('input[value=pages]').attr('checked', false);
    }
    function togglePages() {
      /* get the jQuery object for the toggle button */
      var $button = $(".button.view_by a.pages");
      $button.addClass("selected");
      /* hide / show the relevant content panel */
      $(".radio.industry").css("display","none")
      $(".radio.pages").css("display","block");
      /* update the state of the checkbox */
      $('input[value=industry]').attr('checked', false);
      $('input[value=pages]').attr('checked', true);
    }

    $(function() {
        $(".radio.view_by").css("display","none");
        currentState == "industry" ? toggleCriteria() : togglePages();
    });
</script>
<script>
    var chart1;

    var data = [
        <?php foreach($fancount as $value): ?>
        {
            name: "<?php echo $value["name"]; ?>",
            data: [
            <?php foreach ($value["data"] as $point): ?>
                <?php $curr_date = explode("-", substr($point["date"],0,10)) ?>
                [ Date.UTC(<?php echo $curr_date[0] ."," . ($curr_date[1] - 1) . ",". $curr_date[2] ?>,0,0,0,0),<?php echo $point["fancount"]; ?> ],
            <?php endforeach; ?>
            ]
        },
        <?php endforeach; ?>
    ];

    function convertToGrowth(_data) {
        var ret = [];
        for (var i=0 ; i<_data.length ; i++) {
            ret[i] = { name: _data[i].name, data: [] };

            for (var x=0 ; x<_data[i]["data"].length ; x++) {
                if (x > 0) {
                    ret[i]["data"][x-1] = [ _data[i]["data"][x][0] , _data[i]["data"][x][1] - _data[i]["data"][x-1][1] ];
                }
            }
        }
        return ret;
    }
    var metric = "total";

    <?php if ($fans == "growth"): ?>
    data = convertToGrowth(data);
    metric = "growth";
    <?php endif; ?>

    $(document).ready( function () {
        chart1 = new Highcharts.Chart({
            chart: {
                renderTo: 'graph',
                type: 'line',
                zoomType: 'x'
            },
            title: {
                text: ''
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: (metric == 'growth' ? 'Fan growth' : 'Fans')
                }
            },
            credits: {
                enabled: false
            },
            series: data
        });
    });
</script>
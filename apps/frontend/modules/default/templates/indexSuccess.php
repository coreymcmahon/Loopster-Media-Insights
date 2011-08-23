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
<form action="<?php echo url_for('default/index') ?>" method="POST">
  
    <div id="form-dates">
        <?php echo $form['start_date']->renderRow() ?>
        <?php echo $form['end_date']->renderRow() ?>
    </div>
  
    <div id="graph-container">
        <div id="graph-image"><img src="<?php echo url_for("images/loopster-logo.png"); ?>"/></div>
        <div id="graph" style="width: 875px; height: 500px; margin-left: auto; margin-right: auto;">&nbsp;</div>
    </div>
    <script type="text/javascript">
    var data = [
    <?php foreach($fancount as $value): ?>
        {
            label: "<?php echo $value["name"]; ?>",
            data: [
            <?php foreach ($value["data"] as $point): ?>
                [ <?php echo strtotime($point["date"] . " UTC") * 1000; ?> , <?php echo $point["fancount"]; ?> ],
            <?php endforeach; ?>
            ],
            hoverable: true,
            clickable: true
        },
    <?php endforeach; ?>
    ];

    function convertToGrowth(_data) {
        var ret = [];
        for (var i=0 ; i<_data.length ; i++) {
            ret[i] = { label: _data[i].label, data: [] };

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
    </script>
    
    <div id="form-main">
        <div class="form-left">
            <div class="radio view_by">
                View By:
                <?php echo $form['view_by']->render() ?>
            </div>
            <div class="radio industry">
                <div class="ddl industry"><?php echo $form['industry']->renderRow() ?></div>
                <div class="ddl show"><?php echo $form['show']->renderRow() ?></div>
            </div>
            <div class="radio pages">
                <?php echo $form['brands']->renderRow() ?>
            </div>
        </div>
        <div class="form-right">
            <?php /*echo $form['graph_type']->renderRow() */ /* Commented out because only allowing 1 graph type */ ?>
            <?php echo $form['graph_type']->render() ?>
            <?php echo $form['fans']->renderRow() ?>
        </div>
    </div>
    <?php if ($form->isCSRFProtected()) : ?>
      <?php echo $form['_csrf_token']->render(); ?>
    <?php endif; ?>
    <input type="submit" id="form_submit" value="Update" />
</form>


    <script type="text/javascript">
    /* Add the tooltips */
    $('#graph_start_date').tipsy({fallback:"Enter the start date for the data", gravity: "w" });
    $('#graph_end_date').tipsy({fallback:"Enter the end date for the data", gravity: "w"});
    </script>

    <script>
    function viewByStatusChanged() {
        if ($("#graph_view_by_industry:checked").val()) {
            // Industry checked, hide brand selector
            $(".form-left .pages").css("display","none");
            $(".form-left .industry").css("display","block");
            $("label[for='graph_view_by_industry']").addClass("selected");
            $("label[for='graph_view_by_pages']").removeClass("selected");
        } else {
            // Individual Pages checked, hide industry selectors
            $(".form-left .pages").css("display","block");
            $(".form-left .industry").css("display","none");
            $("label[for='graph_view_by_industry']").removeClass("selected");
            $("label[for='graph_view_by_pages']").addClass("selected");
        }
    }

    $(document).ready(
        function () {
            $("#graph_view_by_industry").change(viewByStatusChanged);
            $("#graph_view_by_pages").change(viewByStatusChanged);
            $("#form-main .radio.view_by input").css("display","none");
            $("#form-main .industry").css("background-color","transparent");
            $("#form-main .pages").css("background-color","transparent");
            viewByStatusChanged();
        }
    );
    </script>

    <script>
        var chart1;


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
                        text: 'Fans'
                    }
                },
                credits: {
                    enabled: false
                },
                series: [
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
                ]
            });
        });
    </script>
<script type="text/javascript">
    $(document).ready(function () {
        /* TODO: insert start / end date range stuff in here */
        $("#graph_start_date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(<?php echo $min_date_array[0]; ?>, <?php echo $min_date_array[1]; ?> - 1, <?php echo $min_date_array[2]; ?>),
            maxDate: new Date(<?php echo $max_date_array[0]; ?>, <?php echo $max_date_array[1]; ?> - 1, <?php echo $max_date_array[2]; ?>),
            onSelect: function(dateText, inst) {
               /* When a 'start' date is selected, make this the min value for the end-date selector */
                $("#graph_end_date").datepicker("option","minDate", $("#graph_start_date").datepicker("getDate"));
            }
        });
        $("#graph_end_date").datepicker({
            dateFormat: 'yy-mm-dd',
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
  
    <div id="graph" style="width: 875px; height: 500px; margin-left: auto; margin-right: auto;">&nbsp;</div>
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
            <?php echo $form['graph_type']->renderRow() ?>
            <?php echo $form['fans']->renderRow() ?>
        </div>
    </div>
    <?php if ($form->isCSRFProtected()) : ?>
      <?php echo $form['_csrf_token']->render(); ?>
    <?php endif; ?>
    <input type="submit" id="form_submit" />
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
        $(function() {
        $.plot($("#graph"),
            data,

            {
                series: {
                    lines: { show: true },
                    points: { show: true }
                },
                xaxis: {
                    mode: "time",
                    timeformat: "%y/%m/%d"
                },
                yaxis: {

                },
                grid: {
                    hoverable: true, clickable: true
                }
            }
        );

        /* Get on mouse over tooltips working... */
        function formatDate(_date) {
            var date = new Date(parseInt(_date));
            return ((date.getFullYear()) + "-" +
                ((date.getMonth()+1) < 10 ? "0" + (date.getMonth()+1) : + (date.getMonth()+1) ) + "-" +
                (date.getDate() < 10 ? "0" + date.getDate() : + date.getDate()));
        }

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#fee',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $("#graph").bind("plothover", function (event, pos, item) {
            //$("#x").text(pos.x.toFixed(2));
            //$("#y").text(pos.y.toFixed(2));

            /*if ($("#enableTooltip:checked").length > 0) {*/
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(2);

                        showTooltip(item.pageX, item.pageY,
                                    item.series.label + " had " + parseInt(y) + " fans on " + formatDate(x));
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            //}
        });
    });
    </script>

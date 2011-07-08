<script type="text/javascript">
    $(document).ready(function () {
        /* TODO: insert start / end date range stuff in here */
        $("#graph_start_date").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: new Date(2011, 6 - 1, 1),
            maxDate: new Date(2011, 7 - 1, 1),
            onSelect: function(dateText, inst) {
               /* When a 'start' date is selected, make this the min value for the end-date selector */
                $("#graph_end_date").datepicker("option","minDate", $("#graph_start_date").datepicker("getDate"));
            }
        });
        $("#graph_end_date").datepicker({
            dateFormat: 'dd-mm-yy',
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
  
    <div id="graph">&nbsp;</div>
    
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
    
    <input type="submit" id="form_submit" />
    <?php /* TODO: add the code below to hide the submit button IF we go Ajaxy */ ?>
    <script>/* $(document).ready( function() { $("#form_submit").css("display","none"); } ); */</script>
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
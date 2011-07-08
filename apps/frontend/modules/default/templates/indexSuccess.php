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

<?php echo $form->renderFormTag('') ?>
      <table>
        <?php echo $form ?>
        <tr>
          <td colspan="2">
            <input type="submit" />
          </td>
        </tr>
      </table>
    </form>
    <script type="text/javascript">
    $('#graph_start_date').tipsy({fallback:"Enter the start date for the data", gravity: "w" });
    $('#graph_end_date').tipsy({fallback:"Enter the end date for the data", gravity: "w"});
    </script>
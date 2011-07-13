<div>
    <form method="POST" action="<?php echo url_for("populate/update"); ?>">
        <select name="num">
            <option selected="true">10</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="250">250</option>
            <option value="500">500</option>
            <option value="">All</option>
        </select>
        <input type="submit" value="Go" />
    </form>
</div>
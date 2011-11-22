<div>Success!</div>
<?php foreach ($before as $key=>$value): ?>
    <?php echo $key ?>: <?php echo $after[$key] ?> - <?php echo $before[$key] ?> = <?php echo $after[$key] - $before[$key] ?><br/><br/>
<?php endforeach; ?>
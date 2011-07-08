<?php $facebook_text = "Loopster Media on Facebook"; $twitter_text = "Loopster Media on Twitter"; $rss_text = "Follow Loopster Media via RSS"; ?>
<div id="sm-links">
 <a href="http://www.facebook.com/LoopsterMedia" class="facebook-link" title="<?php echo $facebook_text; ?>"><?php echo image_tag("facebook-small.png", array("title" => $facebook_text , "alt" => $facebook_text)) ?></a>
 <a href="http://twitter.com/LoopsterMedia" class="twitter-link" title="<?php echo $twitter_text; ?>"><?php echo image_tag("twitter-small.png", array("title" => $twitter_text , "alt" => $twitter_text)) ?></a>
 <a href="http://loopstermedia.com/feed/" class="rss-link" title="<?php echo $rss_text; ?>"><?php echo image_tag("rss-small.png", array("title" => $rss_text , "alt" => $rss_text)) ?></a>
</div>
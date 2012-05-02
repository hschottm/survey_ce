<?php if (count($this->answers)): ?>
<ol>
<?php foreach ($this->answers as $answer): ?>
<?php if (strlen($answer)): ?>
	<li><?php echo $answer; ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ol>
<?php endif; ?>
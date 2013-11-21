<table class="surveytable qid-<?php echo $objWidget->id; ?>">
<?php foreach ($this->surveypage as $objWidget): ?>
	<tr>
<?php if ($objWidget->showTitle): ?>
		<td class="titlecolumn">
			<?php echo $objWidget->title; ?><?php if ($objWidget->mandatory): ?><span class="mandatory">*</span><?php endif; ?>
			<?php if (strlen($objWidget->help)): ?><div class="help"><?php echo $objWidget->help; ?></div><?php endif; ?>
		</td>
<?php endif; ?>
		<td class="questioncolumn">
<?php if (strlen($objWidget->question)): ?>
			<div class="question"><?php echo $objWidget->question; ?></div>
<?php endif; ?>
			<div class="widget <?php echo $objWidget->class; ?>">
				<?php echo $objWidget->generateLabel(); ?> <?php echo $objWidget->generateWithError(); ?>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php if ($this->styleHorizontal): ?>
<?php if ($this->singleResponse): ?>
<table>
	<tr>
<?php $counter = 1; ?>
<?php foreach ($this->choices as $choice): ?>
		<td><label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars($choice); ?></label></td>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
		<td><label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo $this->otherTitle; ?></label> <input type="text" name="other_<?php echo $this->ctrl_name; ?>" class="text<?php echo $this->ctrl_class; ?>" <?php if (strlen($this->values["other"])): ?>value="<?php echo specialchars($this->values['other']); ?>" <?php endif; ?>/></td>
<?php endif; ?>
<?php $counter = 1; ?>
	</tr>
	<tr>
<?php foreach ($this->choices as $choice): ?>
		<td><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /></td>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
		<td><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /></td>
<?php endif; ?>
	</tr>
</table>
<?php elseif ($this->dichotomous): ?>
<table>
	<tr>
<?php for ($counter = 1; $counter <= 2; $counter++): ?>
		<td><label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars(($counter == 1) ? $this->lngYes : $this->lngNo); ?></label></td>
<?php endfor; ?>
	</tr>
	<tr>
<?php for ($counter = 1; $counter <= 2; $counter++): ?>
		<td><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /></td>
<?php endfor; ?>
	</tr>
</table>
<?php elseif ($this->multipleResponse): ?>
<table>
	<tr>
<?php $counter = 1; ?>
<?php $values = is_array($this->values["value"]) ? $this->values["value"] : array(); ?>
<?php foreach ($this->choices as $choice): ?>
		<td><label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars($choice); ?></label></td>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
		<td><label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo $this->otherTitle; ?></label> <input type="text" name="other_<?php echo $this->ctrl_name; ?>" class="text<?php echo $this->ctrl_class; ?>" <?php if (strlen($this->values["other"])): ?>value="<?php echo specialchars($this->values['other']); ?>" <?php endif; ?>/></td>
<?php endif; ?>
<?php $counter = 1; ?>
	</tr>
	<tr>
<?php foreach ($this->choices as $choice): ?>
		<td><input type="checkbox" name="<?php echo $this->ctrl_name; ?>[<?php echo $counter; ?>]" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="checkbox<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if (in_array($counter, $values)): ?> checked="checked"<?php endif; ?> /></td>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
		<td><input type="checkbox" name="<?php echo $this->ctrl_name; ?>[<?php echo $counter; ?>]" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="checkbox<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if (in_array($counter, $values)): ?> checked="checked"<?php endif; ?> /></td>
<?php endif; ?>
	</tr>
</table>
<?php endif; ?>
<?php elseif ($this->styleVertical): ?>
<?php if ($this->singleResponse): ?>
<?php $counter = 1; ?>
<?php foreach ($this->choices as $choice): ?>
<div><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /> <label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars($choice); ?></label></div>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
<div><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /> <label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo $this->otherTitle; ?></label> <input type="text" name="other_<?php echo $this->ctrl_name; ?>" class="text<?php echo $this->ctrl_class; ?>" <?php if (strlen($this->values["other"])): ?>value="<?php echo specialchars($this->values['other']); ?>" <?php endif; ?>/></div>
<?php endif; ?>
<?php elseif ($this->dichotomous): ?>
<?php for ($counter = 1; $counter <= 2; $counter++): ?>
<div><input type="radio" name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="radio<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> checked="checked"<?php endif; ?> /> <label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars(($counter == 1) ? $this->lngYes : $this->lngNo); ?></label></div>
<?php endfor; ?>
<?php elseif ($this->multipleResponse): ?>
<?php $values = is_array($this->values["value"]) ? $this->values["value"] : array(); ?>
<?php $counter = 1; ?>
<?php foreach ($this->choices as $choice): ?>
<div><input type="checkbox" name="<?php echo $this->ctrl_name; ?>[<?php echo $counter; ?>]" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="checkbox<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if (in_array($counter, $values)): ?> checked="checked"<?php endif; ?> /> <label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo specialchars($choice); ?></label></div>
<?php $counter++; ?>
<?php endforeach; ?>
<?php if ($this->blnOther): ?>
<div><input type="checkbox" name="<?php echo $this->ctrl_name; ?>[<?php echo $counter; ?>]" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="checkbox<?php echo $this->ctrl_class; ?>" value="<?php echo $counter; ?>"<?php if (in_array($counter, $values)): ?> checked="checked"<?php endif; ?> /> <label for="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>"><?php echo $this->otherTitle; ?></label> <input type="text" name="other_<?php echo $this->ctrl_name; ?>" class="text<?php echo $this->ctrl_class; ?>" <?php if (strlen($this->values["other"])): ?>value="<?php echo specialchars($this->values['other']); ?>" <?php endif; ?>/></div>
<?php endif; ?>
<?php endif; ?>
<?php elseif ($this->styleSelect): ?>
<?php if ($this->singleResponse): ?>
<div>
	<select name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="select<?php echo $this->ctrl_class; ?>">
		<option value="0"></option>
<?php $counter = 1; ?>
<?php foreach ($this->choices as $choice): ?>
		<option value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> selected="selected"<?php endif; ?>><?php echo specialchars($choice); ?></option>
<?php $counter++; ?>
<?php endforeach; ?>
	</select>
</div>
<?php elseif ($this->dichotomous): ?>
<div>
	<select name="<?php echo $this->ctrl_name; ?>" id="ctrl_<?php echo $this->ctrl_id; ?>_<?php echo $counter; ?>" class="select<?php echo $this->ctrl_class; ?>">
		<option value="0"></option>
<?php $counter = 1; ?>
<?php for ($counter = 1; $counter <= 2; $counter++): ?>
		<option value="<?php echo $counter; ?>"<?php if ($this->values['value'] == $counter): ?> selected="selected"<?php endif; ?>><?php echo specialchars(($counter == 1) ? $this->lngYes : $this->lngNo); ?></option>
<?php endfor; ?>
	</select>
</div>
<?php endif; ?>
<?php endif; ?>
</table>
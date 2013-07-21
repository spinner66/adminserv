<tr>
	<td class="key"><label for="Next<?php echo $data['id']; ?>"><?php echo Utils::t($data['name']); ?></label></td>
	<?php if ($data['gameInfos']['curr'] != null): ?>
		<td class="value">
			<input class="text width2" type="text" name="Curr<?php echo $data['id']; ?>" id="Curr<?php echo $data['id']; ?>" readonly="readonly" value="<?php if (isset($data['gameInfos']['curr'][$data['id']])): echo $data['gameInfos']['curr'][$data['id']]; endif; ?>" />
		</td>
	<?php endif; ?>
	<td class="value">
		<input class="text width2" type="<?php echo (isset($data['gameInfos']['next'][$data['id']]) && is_numeric($data['gameInfos']['next'][$data['id']])) ? 'number" min="0"' : 'text'; ?>" name="Next<?php echo $data['id']; ?>" id="Next<?php echo $data['id']; ?>" value="<?php if (isset($data['gameInfos']['next'][$data['id']])): echo $data['gameInfos']['next'][$data['id']]; endif; ?>" />
	</td>
	<td class="preview"></td>
</tr>
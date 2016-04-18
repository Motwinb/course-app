
<table id="search_block">
<tr>
<td>
<?php print drupal_render($form['keys']);?>
</td>
<td>
<?php print drupal_render($form['submit']);?>
</td>
</tr>
<tr>
<td>
<?php print drupal_render($form['search_in']);?>
</td>
<td>
<?php print drupal_render($form['search_options']);?>
</td>
</tr>
</table>
<?php print drupal_render($form);?>

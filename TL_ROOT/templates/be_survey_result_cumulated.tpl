<div id="tl_buttons">
	<a href="<?php echo $this->hrefExport; ?>" class="header_export" title="<?php echo $this->export; ?>" onclick="Backend.getScrollOffset();"><?php echo $this->export; ?></a> &nbsp; :: &nbsp;
	<a href="<?php echo $this->hrefBack; ?>" class="header_back" title="<?php echo $this->back; ?>" accesskey="b" onclick="Backend.getScrollOffset();"><?php echo $this->back; ?></a>
</div>

<div class="tl_listing_container">
<h2 class="sub_headline"><?php echo $this->heading; ?></h2>
<table cellpadding="0" cellspacing="0" class="tl_listing" summary="<?php echo $this->summary; ?>">
<?php foreach ($this->data as $data): ?>	
  <tr onmouseover="Theme.hoverRow(this, 1);" onmouseout="Theme.hoverRow(this, 0);">
    <td class="tl_file_list"><div class="questionheader"><span class="questionnumber"><?php echo $data["number"] ?>.</span> <span class="questiontitle"><?php echo $data["title"] ?></span> <span class="questiontype">[<?php echo $data["type"] ?>]</span></div><div class="statdata"><div><?php echo $this->lngAnswered ?>: <?php echo $data["answered"] ?></div><div><?php echo $this->lngSkipped; ?>: <?php echo $data["skipped"] ?></div></div></td>
    <td class="tl_file_list tl_right_nowrap"><a href="<?php echo $data["hrefdetails"] ?>" title="<?php echo $data["titledetails"] ?>"><img src="<?php echo $this->imgdetails; ?>" width="16" height="16" alt="<?php echo $data["titledetails"] ?>" /></a> </td>
  </tr>
<?php endforeach; ?>
</table>
</div>
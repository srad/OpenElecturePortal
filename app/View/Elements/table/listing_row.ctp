<tr id="list_<?php echo $listing['Listing']['id']; ?>" class="">
    <td class="center"><span class="icon-resize-vertical"></span></td>
    <td class="center"><span class="icon-arrow-left"></span></td>
    <td class="center"><span class="icon-arrow-right"></span></td>
    <td><input class="name" type="text" value="<?php echo $listing['Listing']['name']; ?>" /></td>
    <td><input class="code" type="text" value="<?php echo $listing['Listing']['code']; ?>" /></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Deaktiv.'); ?></label></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Dyn. expad.'); ?></label></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Aufst. Sort.'); ?></label></td>
    <td class="center"><button class="btn btn-mini"><?php echo __('Hinzuf.'); ?></button></td>
    <td class="center"><button class="btn btn-danger btn-mini"><?php echo __('Lösch.'); ?></button></td>
</tr>
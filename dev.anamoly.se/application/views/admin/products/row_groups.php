<tr id="row_<?php echo $count; ?>" class="row_<?php echo $count; ?>">
						<td><?php echo $dt->group_name_nl; ?></td>
                        <td><?php echo $dt->cat_name_nl; ?></td>
                        <td><?php echo $dt->sub_cat_name_nl; ?></td>
                        
                        <td>
                            <div class="btn-group">
                                <?php if(_is_admin()){ ?>
                                <a href="javascript:deleteTableRecord('<?php echo site_url($controller."/delete_map/"._encrypt_val($dt->product_map_id)); ?>','#group_list',<?php echo $count; ?>)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
								<?php } ?>
                            </div>
                        </td>
</tr>
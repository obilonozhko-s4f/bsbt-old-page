<?=$this->load->view('includes/admin/parts/simple_add_edit_entity', array('fields' => $fields, 'entity' => $entity, 'backUrl' => $backUrl))?>

<?
  /**
   * Print permission table.
   * @param $menuItems
   * @param $entity
   * @param string $parent
   */
  function print_permission_table($menuItems, $entity, $parent = null) {
    foreach ($menuItems as $key => $item) {
      if (is_array($item)) {
        if (!empty($item)) {
          print_permission_table($item, $entity, $key);
        }
      } else {
        print_permission_table_row($item, $entity, $parent);
      }
    }
  }

  /**
   * Print permission table row.
   * @param $item
   * @param $entity
   */
  function print_permission_table_row($item, $entity, $parent = null) {
    $prefix = isset($parent) ? lang('admin.menu.' . $parent . '.name') . "&nbsp;|&nbsp;" : "";
    print('<tr>
      <td class="action-inner">' . $prefix . lang('admin.menu.' . $item . '.name') . '</td>
      <td><input value="1" class="checkbox exclude" type="checkbox"' .(strstr($entity['permissions'], $item . "_view") !== false ? 'checked="checked"' : '') .' name="' . $item . '_view" /></td>
      <td><input value="1" class="checkbox exclude" type="checkbox"' .(strstr($entity['permissions'], $item . "_add") !== false ? 'checked="checked"' : '') .' name="' . $item . '_add" /></td>
      <td><input value="1" class="checkbox exclude" type="checkbox"' .(strstr($entity['permissions'], $item . "_edit") !== false ? 'checked="checked"' : '') .' name="' . $item . '_edit" /></td>
      <td><input value="1" class="checkbox exclude" type="checkbox"' .(strstr($entity['permissions'], $item . "_delete") !== false ? 'checked="checked"' : '') .' name="' . $item . '_delete" /></td>
    </tr>');
  }
?>

<?if (isset($entity['id'])) :?>
  <div class="content default-box">
    <h2 class="title"><?=$lang->line('admin.add_edit.' . $entityName . ".permission_form_title")?></h2>
      <div class="inner">
        <form id="permission-table" action="<?=site_url($permissionsProcessLink)?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?=$entity['id']?>" />
          <table class="permission-table">
            <tr>
              <td><?=lang('admin.entity')?></td>
              <td class="action"><?=lang('admin.permissions.view')?></td>
              <td class="action"><?=lang('admin.permissions.add')?></td>
              <td class="action"><?=lang('admin.permissions.edit')?></td>
              <td class="action"><?=lang('admin.permissions.delete')?></td>
            </tr>
						<?print_permission_table($menuItems, $entity)?>          
          </table>          
        
          <div class="group navform wat-cf" style="margin-top: 10px;">
            <button class="button" type="submit">
              <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_permissions')?>"/><?=lang('admin.save_permissions')?>
            </button>
          </div>              
        </form>
      </div>
  </div>
<?endif?>

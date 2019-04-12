<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'editors/multilevel_selection.tpl', 31, false),)), $this); ?>
<table <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "editors/editor_options.tpl", 'smarty_include_vars' => array('Editor' => $this->_tpl_vars['Editor'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> class="pgui-multilvevel-autocomplete">
<tbody>
<?php $_from = $this->_tpl_vars['Editor']->getLevels(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['Editors'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['Editors']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['LevelEditor']):
        $this->_foreach['Editors']['iteration']++;
?>
    <tr>
        <td><span><?php echo $this->_tpl_vars['LevelEditor']->getCaption(); ?>
</span></td>
        <td>
            <input
                type="hidden"
                data-id="<?php echo $this->_tpl_vars['FormId']; ?>
_<?php echo $this->_tpl_vars['LevelEditor']->GetName(); ?>
"
                data-placeholder="<?php echo $this->_tpl_vars['Captions']->GetMessageString('PleaseSelect'); ?>
"
                name="<?php echo $this->_tpl_vars['LevelEditor']->getName(); ?>
"
                data-minimal-input-length="<?php echo $this->_tpl_vars['Editor']->getMinimumInputLength(); ?>
"
                <?php if (! $this->_tpl_vars['Editor']->getEnabled()): ?>
                    disabled="disabled"
                <?php endif; ?>
                <?php if ($this->_tpl_vars['Editor']->getAllowClear()): ?>
                    data-allowClear="true"
                <?php endif; ?>
                <?php if ($this->_tpl_vars['Editor']->GetReadOnly()): ?>
                    readonly="readonly"
                <?php endif; ?>
                <?php if ($this->_tpl_vars['LevelEditor']->getParentEditor()): ?>
                    data-parent-autocomplete="<?php echo $this->_tpl_vars['FormId']; ?>
_<?php echo $this->_tpl_vars['LevelEditor']->getParentEditor(); ?>
"
                <?php endif; ?>
                data-url="<?php echo $this->_tpl_vars['LevelEditor']->getDataURL(); ?>
"
                <?php if (($this->_foreach['Editors']['iteration'] == $this->_foreach['Editors']['total'])): ?>
                    data-multileveledit-main="true"
                    <?php echo $this->_tpl_vars['Validators']['InputAttributes']; ?>

                <?php endif; ?>
                <?php if ($this->_tpl_vars['LevelEditor']->getFormatResult()): ?>
                    data-format-result="<?php echo ((is_array($_tmp=$this->_tpl_vars['LevelEditor']->getFormatResult())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
                <?php endif; ?>
                <?php if ($this->_tpl_vars['LevelEditor']->getFormatSelection()): ?>
                    data-format-selection="<?php echo ((is_array($_tmp=$this->_tpl_vars['LevelEditor']->getFormatSelection())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
                <?php endif; ?>
                value="<?php echo $this->_tpl_vars['LevelEditor']->getValue(); ?>
"
                data-init-text="<?php echo $this->_tpl_vars['LevelEditor']->getDisplayValue(); ?>
"
                <?php if (($this->_foreach['Editors']['iteration'] == $this->_foreach['Editors']['total'])): ?>
                    <?php echo $this->_tpl_vars['ViewData']['Validators']['InputAttributes']; ?>

                <?php endif; ?>
            />
        </td>
    </tr>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>
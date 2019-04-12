<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'to_json', 'charts/chart.tpl', 10, false),)), $this); ?>
<div data-id="<?php echo $this->_tpl_vars['chart']['id']; ?>
_<?php echo $this->_tpl_vars['uniqueId']; ?>
" class="pgui-chart pgui-chart-<?php echo $this->_tpl_vars['chart']['id']; ?>
" style="height: <?php echo $this->_tpl_vars['chart']['height']; ?>
px">
    <img class="pgui-chart-loading" src="components/assets/img/loading.gif">
</div>

<script type="text/javascript">
<?php echo '
    window[\'chartData_'; ?>
<?php echo $this->_tpl_vars['chart']['id']; ?>
_<?php echo $this->_tpl_vars['uniqueId']; ?>
<?php echo '\'] = {
        id: \''; ?>
<?php echo $this->_tpl_vars['chart']['id']; ?>
_<?php echo $this->_tpl_vars['uniqueId']; ?>
<?php echo '\',
        type: \''; ?>
<?php echo $this->_tpl_vars['type']; ?>
<?php echo '\',
        options: '; ?>
<?php echo smarty_function_to_json(array('value' => $this->_tpl_vars['chart']['options']), $this);?>
<?php echo ',
        data: '; ?>
<?php echo smarty_function_to_json(array('value' => $this->_tpl_vars['chart']['data']), $this);?>
<?php echo '
    };
'; ?>

</script>
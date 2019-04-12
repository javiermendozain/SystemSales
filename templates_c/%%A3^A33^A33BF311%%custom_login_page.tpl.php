<?php ob_start(); ?>
        <h1 style="text-align: center">Bienvenido a Smart-App!</h1>
     
    <?php echo $this->_tpl_vars['Renderer']->Render($this->_tpl_vars['LoginControl']); ?>

 
       <!--<h2>Login information</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>admin</td>
            <td>admin</td>
            <td>Can modify any record at any page and manage other users.</td>
        </tr>
        
			
		
        </tbody>
    </table>-->
	
    <?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('ContentBlock', ob_get_contents());ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/layout.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
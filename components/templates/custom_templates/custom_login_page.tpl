{capture assign="ContentBlock"}
    {* <CustomTemplate> *}
    <h1 style="text-align: center">Bienvenido a Smart-App!</h1>
    {* </CustomTemplate> *}
 
    {$Renderer->Render($LoginControl)}
 
    {* <CustomTemplate> *}
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
	
    {* </CustomTemplate> *}
{/capture}
{include file="common/layout.tpl"}
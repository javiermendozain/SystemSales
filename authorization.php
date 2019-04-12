<?php

require_once 'phpgen_settings.php';
require_once 'components/application.php';
require_once 'components/security/permission_set.php';
require_once 'components/security/tablebased_auth.php';
require_once 'components/security/grant_manager/user_grant_manager.php';
require_once 'components/security/grant_manager/composite_grant_manager.php';
require_once 'components/security/grant_manager/hard_coded_user_grant_manager.php';
require_once 'components/security/grant_manager/table_based_user_grant_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/pgsql_engine.php';

$grants = array();

$appGrants = array();

$dataSourceRecordPermissions = array('public.facturar_ventas' => new DataSourceRecordPermission('user_id', false, false, false, true, true, true));

$tableCaptions = array('public.deudas_memo' => 'Deudas Memo',
'public.facturar_ventas' => 'Facturar Ventas',
'public.facturar_ventas.public.detalle_factura_venta' => 'Facturar Ventas->Detalle Factura Venta',
'Consultar_Precio_producto' => 'Consultar Precio Producto',
'public.productos' => 'Productos',
'public.proveedor' => 'Proveedores',
'public.proveedor.public.registrar_compra' => 'Proveedores-> Compras',
'public.iva' => 'Iva',
'public.iva.public.productos' => 'Iva->Productos',
'public.registrar_compra' => 'Compra',
'public.registrar_compra.public.detalle_factura_compra' => 'Compra->Detalle Factura Compra',
'public.clientes' => 'Clientes',
'public.clientes.public.facturar_ventas' => 'Clientes->Facturas Ventas',
'public.unidades' => 'Unidades',
'public.descuentos' => 'Descuentos',
'public.descuentos.public.productos' => 'Descuentos->Productos',
'public.caracteristicas' => 'Marcas',
'venta_hoy' => 'Venta Hoy',
'all_facturas_ventas' => 'Ventas',
'utilidad_mensual' => 'Utilidad Mensual',
'utilidad_x_ano' => 'Utilidad X Año',
'inversion_utilidad' => 'Inversión y  Utilidad de Bodega',
'public.detalle_factura_venta' => 'Detalle Factura Venta',
'public.tipo_productos' => 'Tipo Productos',
'public.tipo_productos.public.productos' => 'Tipo Productos->Productos',
'Departamento_multiselec' => 'Departamento Multiselec',
'product_compra' => 'Product Compra',
'public.detalle_factura_compra' => 'Productos Factura Compra',
'producto_venta' => 'Producto Venta',
'user' => 'User',
'public.ciudad' => 'Ciudad',
'public.ciudad.public.clientes' => 'Ciudad->Clientes',
'public.ciudad.public.proveedor' => 'Ciudad->Proveedor',
'ciudad' => 'Ciudad',
'public.departamento' => 'Departamento',
'public.departamento.public.ciudad' => 'Departamento->Ciudad');

function CreateTableBasedGrantManager()
{
    global $tableCaptions;
    $usersTable = array('TableName' => 'public.phpgen_users', 'UserName' => 'user_name', 'UserId' => 'user_id', 'Password' => 'user_password');
    $userPermsTable = array('TableName' => 'public.phpgen_user_perms', 'UserId' => 'user_id', 'PageName' => 'page_name', 'Grant' => 'perm_name');

    $passwordHasher = HashUtils::CreateHasher('MD5');
    $connectionOptions = GetGlobalConnectionOptions();
    $tableBasedGrantManager = new TableBasedUserGrantManager(PgConnectionFactory::getInstance(), $connectionOptions,
        $usersTable, $userPermsTable, $tableCaptions, $passwordHasher, false);
    return $tableBasedGrantManager;
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;
    $hardCodedGrantManager = new HardCodedUserGrantManager($grants, $appGrants);
    $tableBasedGrantManager = CreateTableBasedGrantManager();
    $grantManager = new CompositeGrantManager();
    $grantManager->AddGrantManager($hardCodedGrantManager);
    if (!is_null($tableBasedGrantManager)) {
        $grantManager->AddGrantManager($tableBasedGrantManager);
        GetApplication()->SetUserManager($tableBasedGrantManager);
    }
    $userAuthorizationStrategy = new TableBasedUserAuthorization(new UserIdentitySessionStorage(GetIdentityCheckStrategy()), PgConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 'public.phpgen_users', 'user_name', 'user_id', $grantManager);
    GetApplication()->SetUserAuthorizationStrategy($userAuthorizationStrategy);

    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(
        new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}

function GetIdentityCheckStrategy()
{
    return new TableBasedIdentityCheckStrategy(PgConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 'public.phpgen_users', 'user_name', 'user_password', 'MD5');
}

function CanUserChangeOwnPassword()
{
    return true;
}

?>
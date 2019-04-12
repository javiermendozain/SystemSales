<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/pgsql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthorizationStrategy()->ApplyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    class public_productos_id_ivaNestedPage extends NestedFormPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('descripcion');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for id_iva field
            //
            $editor = new TextEdit('id_iva_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES [99.99]'));
            $editColumn = new CustomEditColumn(' Iva', 'id_iva', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descripcion field
            //
            $editor = new TextEdit('descripcion_edit');
            $editor->SetMaxLength(5);
            $editor->SetPlaceholder($this->RenderText('EJEMPLO: 16 %'));
            $editColumn = new CustomEditColumn('Descripci&oacute;n', 'descripcion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
            $column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
       static public function getNestedInsertHandlerName()
        {
            return get_class() . '_form_insert';
        }
    
        public function GetGridInsertHandler()
        {
            return self::getNestedInsertHandlerName();
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
    }
    
    class public_productos_iddescuentosNestedPage extends NestedFormPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for iddescuentos field
            //
            $editor = new TextEdit('iddescuentos_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES [99.99]'));
            $editColumn = new CustomEditColumn('Descuento', 'iddescuentos', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descpcion_descuento field
            //
            $editor = new TextEdit('descpcion_descuento_edit');
            $editor->SetMaxLength(10);
            $editor->SetPlaceholder($this->RenderText('EJEMPLO: 35 %'));
            $editColumn = new CustomEditColumn('Descpci&oacute;n', 'descpcion_descuento', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
            $column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
       static public function getNestedInsertHandlerName()
        {
            return get_class() . '_form_insert';
        }
    
        public function GetGridInsertHandler()
        {
            return self::getNestedInsertHandlerName();
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
    }
    
    class public_productos_idcaracterNestedPage extends NestedFormPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('marca');
            $this->dataset->AddField($field, false);
            $field = new StringField('modelo');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for marca field
            //
            $editor = new TextEdit('marca_edit');
            $editor->SetMaxLength(15);
            $editor->SetPlaceholder($this->RenderText('DE PRODUCTO'));
            $editColumn = new CustomEditColumn('Marca', 'marca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for modelo field
            //
            $editor = new TextEdit('modelo_edit');
            $editor->SetMaxLength(15);
            $editor->SetPlaceholder($this->RenderText('DE PRODUCTO'));
            $editColumn = new CustomEditColumn('Modelo', 'modelo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
            $column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
       static public function getNestedInsertHandlerName()
        {
            return get_class() . '_form_insert';
        }
    
        public function GetGridInsertHandler()
        {
            return self::getNestedInsertHandlerName();
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
    }
    
    class public_productos_idunidadNestedPage extends NestedFormPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('descripcion');
            $this->dataset->AddField($field, false);
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for descripcion field
            //
            $editor = new TextEdit('descripcion_edit');
            $editor->SetMaxLength(15);
            $editor->SetPlaceholder($this->RenderText('UNIDADES DE PRODUCTOS'));
            $editColumn = new CustomEditColumn('Descripci&oacute;n', 'descripcion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
    
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
            $column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
       static public function getNestedInsertHandlerName()
        {
            return get_class() . '_form_insert';
        }
    
        public function GetGridInsertHandler()
        {
            return self::getNestedInsertHandlerName();
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class public_productosPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new IntegerField('idproducto', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nombre');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('estado');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('stock');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('id_iva', 'public.iva', new IntegerField('id_iva'), new StringField('descripcion', 'id_iva_descripcion', 'id_iva_descripcion_public_iva'), 'id_iva_descripcion_public_iva');
            $this->dataset->AddLookupField('iddescuentos', 'public.descuentos', new IntegerField('iddescuentos'), new StringField('descpcion_descuento', 'iddescuentos_descpcion_descuento', 'iddescuentos_descpcion_descuento_public_descuentos'), 'iddescuentos_descpcion_descuento_public_descuentos');
            $this->dataset->AddLookupField('idcaracter', 'public.caracteristicas', new IntegerField('idcaracter', null, null, true), new StringField('marca', 'idcaracter_marca', 'idcaracter_marca_public_caracteristicas'), 'idcaracter_marca_public_caracteristicas');
            $this->dataset->AddLookupField('idtipo', 'public.tipo_productos', new IntegerField('idtipo', null, null, true), new StringField('descripcion', 'idtipo_descripcion', 'idtipo_descripcion_public_tipo_productos'), 'idtipo_descripcion_public_tipo_productos');
            $this->dataset->AddLookupField('idunidad', 'public.unidades', new IntegerField('idunidad', null, null, true), new StringField('descripcion', 'idunidad_descripcion', 'idunidad_descripcion_public_unidades'), 'idunidad_descripcion_public_unidades');
            $this->dataset->AddLookupField('idfactura_compra', 'public.registrar_compra', new IntegerField('idfactura_compra', null, null, true), new StringField('num_factura', 'idfactura_compra_num_factura', 'idfactura_compra_num_factura_public_registrar_compra'), 'idfactura_compra_num_factura_public_registrar_compra');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'idproducto', 'idproducto', $this->RenderText('Ref. del Productos')),
                new FilterColumn($this->dataset, 'nombre', 'nombre', $this->RenderText('Descripción')),
                new FilterColumn($this->dataset, 'stock', 'stock', $this->RenderText('Stock')),
                new FilterColumn($this->dataset, 'stock_minimo', 'stock_minimo', $this->RenderText('Stock Minimo')),
                new FilterColumn($this->dataset, 'precio_venta', 'precio_venta', $this->RenderText('Precio Venta')),
                new FilterColumn($this->dataset, 'precio_costo', 'precio_costo', $this->RenderText('Precio Costo')),
                new FilterColumn($this->dataset, 'id_iva', 'id_iva_descripcion', $this->RenderText('Iva')),
                new FilterColumn($this->dataset, 'iddescuentos', 'iddescuentos_descpcion_descuento', $this->RenderText('Descuento')),
                new FilterColumn($this->dataset, 'idcaracter', 'idcaracter_marca', $this->RenderText('Marca ')),
                new FilterColumn($this->dataset, 'idtipo', 'idtipo_descripcion', $this->RenderText('Tipo')),
                new FilterColumn($this->dataset, 'idunidad', 'idunidad_descripcion', $this->RenderText('Unidad')),
                new FilterColumn($this->dataset, 'estado_stock', 'estado_stock', $this->RenderText(' Stock Bodega')),
                new FilterColumn($this->dataset, 'idfactura_compra', 'idfactura_compra_num_factura', $this->RenderText('N° Factura Compra')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['nombre'])
                ->addColumn($columns['stock'])
                ->addColumn($columns['stock_minimo'])
                ->addColumn($columns['precio_venta'])
                ->addColumn($columns['precio_costo'])
                ->addColumn($columns['id_iva'])
                ->addColumn($columns['iddescuentos'])
                ->addColumn($columns['idcaracter'])
                ->addColumn($columns['idunidad'])
                ->addColumn($columns['estado_stock'])
                ->addColumn($columns['idfactura_compra'])
                ->addColumn($columns['estado']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('id_iva')
                ->setOptionsFor('iddescuentos')
                ->setOptionsFor('idcaracter')
                ->setOptionsFor('idunidad')
                ->setOptionsFor('idfactura_compra');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('idproducto_edit');
            
            $filterBuilder->addColumn(
                $columns['idproducto'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('nombre_edit');
            $main_editor->SetMaxLength(70);
            
            $filterBuilder->addColumn(
                $columns['nombre'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('stock_edit');
            $main_editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            
            $filterBuilder->addColumn(
                $columns['stock'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('stock_minimo_edit');
            $main_editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            
            $filterBuilder->addColumn(
                $columns['stock_minimo'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('precio_venta_edit');
            $main_editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            
            $filterBuilder->addColumn(
                $columns['precio_venta'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('precio_costo_edit');
            $main_editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            
            $filterBuilder->addColumn(
                $columns['precio_costo'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('id_iva_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_id_iva_descripcion_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_iva', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_id_iva_descripcion_search');
            
            $text_editor = new TextEdit('id_iva');
            
            $filterBuilder->addColumn(
                $columns['id_iva'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('iddescuentos_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_iddescuentos_descpcion_descuento_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('iddescuentos', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_iddescuentos_descpcion_descuento_search');
            
            $text_editor = new TextEdit('iddescuentos');
            
            $filterBuilder->addColumn(
                $columns['iddescuentos'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('idcaracter_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idcaracter_marca_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idcaracter', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idcaracter_marca_search');
            
            $text_editor = new TextEdit('idcaracter');
            
            $filterBuilder->addColumn(
                $columns['idcaracter'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('idunidad_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idunidad_descripcion_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idunidad', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idunidad_descripcion_search');
            
            $text_editor = new TextEdit('idunidad');
            
            $filterBuilder->addColumn(
                $columns['idunidad'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('estado_stock_edit');
            
            $filterBuilder->addColumn(
                $columns['estado_stock'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('idfactura_compra_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idfactura_compra_num_factura_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idfactura_compra', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idfactura_compra_num_factura_search');
            
            $text_editor = new TextEdit('idfactura_compra');
            
            $filterBuilder->addColumn(
                $columns['idfactura_compra'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('1'), $this->RenderText('ACTIVO'));
            $main_editor->addChoice($this->RenderText('2'), $this->RenderText('DESACTIVADO'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('estado');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $filterBuilder->addColumn(
                $columns['estado'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new AjaxOperation(OPERATION_VIEW,
                    $this->GetLocalizerCaptions()->GetMessageString('View'),
                    $this->GetLocalizerCaptions()->GetMessageString('View'), $this->dataset,
                    $this->GetModalGridViewHandler(), $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for idproducto field
            //
            $column = new TextViewColumn('idproducto', 'idproducto', 'Ref. del Productos', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for stock field
            //
            $column = new NumberViewColumn('stock', 'stock', 'Stock', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for stock_minimo field
            //
            $column = new NumberViewColumn('stock_minimo', 'stock_minimo', 'Stock Minimo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new CurrencyViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('id_iva', 'id_iva_descripcion', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descpcion_descuento field
            //
            $column = new TextViewColumn('iddescuentos', 'iddescuentos_descpcion_descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('idcaracter', 'idcaracter_marca', 'Marca ', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('MARCA Y/O MODELO'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idunidad', 'idunidad_descripcion', 'Unidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for estado_stock field
            //
            $column = new TextViewColumn('estado_stock', 'estado_stock', ' Stock Bodega', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('VERDE: SURTIDO  AZUL: AGOTANDOSE   ROJO:  BAJO EL STOCK MINIMO'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new TextViewColumn('idproducto', 'idproducto', 'Ref. del Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for stock field
            //
            $column = new NumberViewColumn('stock', 'stock', 'Stock', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for stock_minimo field
            //
            $column = new NumberViewColumn('stock_minimo', 'stock_minimo', 'Stock Minimo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new CurrencyViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('id_iva', 'id_iva_descripcion', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descpcion_descuento field
            //
            $column = new TextViewColumn('iddescuentos', 'iddescuentos_descpcion_descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('idcaracter', 'idcaracter_marca', 'Marca ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idunidad', 'idunidad_descripcion', 'Unidad', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado_stock field
            //
            $column = new TextViewColumn('estado_stock', 'estado_stock', ' Stock Bodega', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new TextEdit('idproducto_edit');
            $editColumn = new CustomEditColumn('Ref. del Productos', 'idproducto', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nombre field
            //
            $editor = new TextEdit('nombre_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Descripción', 'nombre', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for stock field
            //
            $editor = new TextEdit('stock_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Stock', 'stock', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for stock_minimo field
            //
            $editor = new TextEdit('stock_minimo_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Stock Minimo', 'stock_minimo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_venta field
            //
            $editor = new TextEdit('precio_venta_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Precio Costo', 'precio_costo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_iva field
            //
            $editor = new AutocompleteComboBox('id_iva_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Iva', 'id_iva', 'id_iva_descripcion', 'edit_id_iva_descripcion_search', $editor, $this->dataset, $lookupDataset, 'id_iva', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_id_ivaNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iddescuentos field
            //
            $editor = new AutocompleteComboBox('iddescuentos_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descpcion_descuento', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Descuento', 'iddescuentos', 'iddescuentos_descpcion_descuento', 'edit_iddescuentos_descpcion_descuento_search', $editor, $this->dataset, $lookupDataset, 'iddescuentos', 'descpcion_descuento', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_iddescuentosNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idcaracter field
            //
            $editor = new AutocompleteComboBox('idcaracter_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('marca');
            $lookupDataset->AddField($field, false);
            $field = new StringField('modelo');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('marca', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Marca ', 'idcaracter', 'idcaracter_marca', 'edit_idcaracter_marca_search', $editor, $this->dataset, $lookupDataset, 'idcaracter', 'marca', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_idcaracterNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idunidad field
            //
            $editor = new AutocompleteComboBox('idunidad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Unidad', 'idunidad', 'idunidad_descripcion', 'edit_idunidad_descripcion_search', $editor, $this->dataset, $lookupDataset, 'idunidad', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_idunidadNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idfactura_compra field
            //
            $editor = new AutocompleteComboBox('idfactura_compra_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('num_factura', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('N° Factura Compra', 'idfactura_compra', 'idfactura_compra_num_factura', 'edit_idfactura_compra_num_factura_search', $editor, $this->dataset, $lookupDataset, 'idfactura_compra', 'num_factura', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('ACTIVO'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('DESACTIVADO'));
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nombre field
            //
            $editor = new TextEdit('nombre_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Descripción', 'nombre', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for stock field
            //
            $editor = new TextEdit('stock_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Stock', 'stock', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for stock_minimo field
            //
            $editor = new TextEdit('stock_minimo_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Stock Minimo', 'stock_minimo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new NumberValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('NumberValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_venta field
            //
            $editor = new TextEdit('precio_venta_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Precio Venta', 'precio_venta', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Precio Costo', 'precio_costo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(12, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(0, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_iva field
            //
            $editor = new AutocompleteComboBox('id_iva_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Iva', 'id_iva', 'id_iva_descripcion', 'insert_id_iva_descripcion_search', $editor, $this->dataset, $lookupDataset, 'id_iva', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_id_ivaNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for iddescuentos field
            //
            $editor = new AutocompleteComboBox('iddescuentos_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descpcion_descuento', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Descuento', 'iddescuentos', 'iddescuentos_descpcion_descuento', '_iddescuentos_descpcion_descuento_search', $editor, $this->dataset, $lookupDataset, 'iddescuentos', 'descpcion_descuento', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_iddescuentosNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idcaracter field
            //
            $editor = new AutocompleteComboBox('idcaracter_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('marca');
            $lookupDataset->AddField($field, false);
            $field = new StringField('modelo');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('marca', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Marca ', 'idcaracter', 'idcaracter_marca', '_idcaracter_marca_search', $editor, $this->dataset, $lookupDataset, 'idcaracter', 'marca', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_idcaracterNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idunidad field
            //
            $editor = new AutocompleteComboBox('idunidad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Unidad', 'idunidad', 'idunidad_descripcion', '_idunidad_descripcion_search', $editor, $this->dataset, $lookupDataset, 'idunidad', 'descripcion', '');
            $editColumn->setNestedInsertFormLink(
                $this->GetHandlerLink(public_productos_idunidadNestedPage::getNestedInsertHandlerName())
            );
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idfactura_compra field
            //
            $editor = new AutocompleteComboBox('idfactura_compra_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('num_factura', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('N° Factura Compra', 'idfactura_compra', 'idfactura_compra_num_factura', '_idfactura_compra_num_factura_search', $editor, $this->dataset, $lookupDataset, 'idfactura_compra', 'num_factura', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('ACTIVO'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('DESACTIVADO'));
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new TextViewColumn('idproducto', 'idproducto', 'Ref. del Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for stock field
            //
            $column = new NumberViewColumn('stock', 'stock', 'Stock', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for stock_minimo field
            //
            $column = new NumberViewColumn('stock_minimo', 'stock_minimo', 'Stock Minimo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new CurrencyViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('id_iva', 'id_iva_descripcion', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for descpcion_descuento field
            //
            $column = new TextViewColumn('iddescuentos', 'iddescuentos_descpcion_descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('idcaracter', 'idcaracter_marca', 'Marca ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idunidad', 'idunidad_descripcion', 'Unidad', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for estado_stock field
            //
            $column = new TextViewColumn('estado_stock', 'estado_stock', ' Stock Bodega', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new TextViewColumn('idproducto', 'idproducto', 'Ref. del Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for stock field
            //
            $column = new NumberViewColumn('stock', 'stock', 'Stock', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for stock_minimo field
            //
            $column = new NumberViewColumn('stock_minimo', 'stock_minimo', 'Stock Minimo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new CurrencyViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('id_iva', 'id_iva_descripcion', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for descpcion_descuento field
            //
            $column = new TextViewColumn('iddescuentos', 'iddescuentos_descpcion_descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('idcaracter', 'idcaracter_marca', 'Marca ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idunidad', 'idunidad_descripcion', 'Unidad', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for estado_stock field
            //
            $column = new TextViewColumn('estado_stock', 'estado_stock', ' Stock Bodega', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for idproducto field
            //
            $column = new TextViewColumn('idproducto', 'idproducto', 'Ref. del Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('nombre', 'nombre', 'Descripción', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for stock field
            //
            $column = new NumberViewColumn('stock', 'stock', 'Stock', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for stock_minimo field
            //
            $column = new NumberViewColumn('stock_minimo', 'stock_minimo', 'Stock Minimo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta field
            //
            $column = new CurrencyViewColumn('precio_venta', 'precio_venta', 'Precio Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('id_iva', 'id_iva_descripcion', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for descpcion_descuento field
            //
            $column = new TextViewColumn('iddescuentos', 'iddescuentos_descpcion_descuento', 'Descuento', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for marca field
            //
            $column = new TextViewColumn('idcaracter', 'idcaracter_marca', 'Marca ', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idtipo', 'idtipo_descripcion', 'Tipo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for descripcion field
            //
            $column = new TextViewColumn('idunidad', 'idunidad_descripcion', 'Unidad', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado_stock field
            //
            $column = new TextViewColumn('estado_stock', 'estado_stock', ' Stock Bodega', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura Compra', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function GetEnableModalSingleRecordView() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $defaultSortedColumns = array();
            $defaultSortedColumns[] = new SortColumn('nombre', 'ASC');
            $result->setDefaultOrdering($defaultSortedColumns);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            $result->SetTotal('nombre', PredefinedAggregate::$Count);
            $result->SetTotal('precio_venta', PredefinedAggregate::$Sum);
            $result->SetTotal('precio_costo', PredefinedAggregate::$Sum);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_id_iva_descripcion_search', 'id_iva', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descpcion_descuento', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, '_iddescuentos_descpcion_descuento_search', 'iddescuentos', 'descpcion_descuento', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('marca');
            $lookupDataset->AddField($field, false);
            $field = new StringField('modelo');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('marca', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, '_idcaracter_marca_search', 'idcaracter', 'marca', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, '_idunidad_descripcion_search', 'idunidad', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('num_factura', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, '_idfactura_compra_num_factura_search', 'idfactura_compra', 'num_factura', null);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_id_iva_descripcion_search', 'id_iva', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descpcion_descuento', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_iddescuentos_descpcion_descuento_search', 'iddescuentos', 'descpcion_descuento', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('marca');
            $lookupDataset->AddField($field, false);
            $field = new StringField('modelo');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('marca', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idcaracter_marca_search', 'idcaracter', 'marca', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idunidad_descripcion_search', 'idunidad', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('num_factura', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idfactura_compra_num_factura_search', 'idfactura_compra', 'num_factura', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."iva"');
            $field = new IntegerField('id_iva');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_id_iva_descripcion_search', 'id_iva', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."descuentos"');
            $field = new IntegerField('iddescuentos');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descpcion_descuento');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descpcion_descuento', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_iddescuentos_descpcion_descuento_search', 'iddescuentos', 'descpcion_descuento', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."caracteristicas"');
            $field = new IntegerField('idcaracter', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('marca');
            $lookupDataset->AddField($field, false);
            $field = new StringField('modelo');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('marca', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idcaracter_marca_search', 'idcaracter', 'marca', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."unidades"');
            $field = new IntegerField('idunidad', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('descripcion');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('descripcion', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idunidad_descripcion_search', 'idunidad', 'descripcion', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $lookupDataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('num_factura', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idfactura_compra_num_factura_search', 'idfactura_compra', 'num_factura', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            
            
            
            
            
            
            
            new public_productos_id_ivaNestedPage($this, GetCurrentUserGrantForDataSource('public.iva'));
            new public_productos_iddescuentosNestedPage($this, GetCurrentUserGrantForDataSource('public.descuentos'));
            new public_productos_idcaracterNestedPage($this, GetCurrentUserGrantForDataSource('public.caracteristicas'));
            new public_productos_idunidadNestedPage($this, GetCurrentUserGrantForDataSource('public.unidades'));
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 1:
                      $customText=' ACTIVO';
            $handled=true;
                      break;
                case 2:
                   $customText=' DESACTIVADO';
            $handled=true;
                    break;
                }
            }
            if($fieldName=='estado_stock'){
            if($rowData['stock']>$rowData['stock_minimo']+5){
            $customText=' <img src="img/verde.png" width="20" height="20" />';
            $handled=true;
            
            }else if($rowData['stock']>=$rowData['stock_minimo']+1){
            $customText=' <img src="img/azul.png" width="20" height="20" />';
            $handled=true;
            
            }else if($rowData['stock'] <= $rowData['stock_minimo']){
            $customText=' <img src="img/rojo.png" width="20" height="20" />';
            $handled=true;
            
            }}
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            if($rowData['stock']<=0){
            $cancel=true;
            $message=' EL STOCK DEBE SER MAYOR A " 0 " ';
            $messageDisplayTime=5;
            }
            if($rowData['stock_minimo']<=0){
            $cancel=true;
            $message=' EL STOCK MINIMO DEBE SER MAYOR A " 0 " ';
            $messageDisplayTime=5;
            }
            if($rowData['precio_venta']<$rowData['precio_costo']){
            $cancel=true;
            $message=' NO PUEDE SER MENOR EL PRECIO DE VENTA QUE EL DE COSTO';
            $messageDisplayTime=5;
            }
            if($rowData['precio_venta']==0){
            $cancel=true;
            $message=' EL PRECIO DE VENTA NO PUEDE SER 0 ';
            $messageDisplayTime=5;
            }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            if($rowData['stock']<=0){
            $cancel=true;
            $message=' EL STOCK DEBE SER MAYOR A " 0 " ';
            $messageDisplayTime=5;
            }
            if($rowData['stock_minimo']<=0){
            $cancel=true;
            $message=' EL STOCK MINIMO DEBE SER MAYOR A " 0 " ';
            $messageDisplayTime=5;
            }
            if($rowData['precio_venta']<$rowData['precio_costo']){
            $cancel=true;
            $message=' NO PUEDE SER MENOR EL PRECIO DE VENTA QUE EL DE COSTO';
            $messageDisplayTime=5;
            }
            if($rowData['precio_venta']==0){
            $cancel=true;
            $message=' EL PRECIO DE VENTA NO PUEDE SER 0 ';
            $messageDisplayTime=5;
            }
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new public_productosPage("public_productos", "productos.php", GetCurrentUserGrantForDataSource("public.productos"), 'UTF-8');
        $Page->SetTitle('Productos');
        $Page->SetMenuLabel('Productos');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.productos"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

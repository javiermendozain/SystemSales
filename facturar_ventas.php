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

    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class public_facturar_ventas_public_detalle_factura_ventaPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."detalle_factura_venta"');
            $field = new IntegerField('iddetalle', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('cantidad');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_venta_unitario');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('valor_parcial');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idventa');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('descuento');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iva');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('aplicar_descuento');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('idventa', 'public.facturar_ventas', new IntegerField('idventa', null, null, true), new IntegerField('n_factura', 'idventa_n_factura', 'idventa_n_factura_public_facturar_ventas', true), 'idventa_n_factura_public_facturar_ventas');
            $this->dataset->AddLookupField('idproducto', '(SELECT 
              productos.idproducto, 
              concat(productos.idproducto,\' - \' ,
              to_char(productos.precio_venta,\'LFM9,999,999\')) as nombre
            FROM 
              public.productos, 
              public.descuentos, 
              public.unidades, 
                public.caracteristicas
            WHERE 
              descuentos.iddescuentos = productos.iddescuentos AND
              unidades.idunidad = productos.idunidad AND
                caracteristicas.idcaracter = productos.idcaracter AND
              productos.estado = 1)', new IntegerField('idproducto'), new StringField('nombre', 'idproducto_nombre', 'idproducto_nombre_producto_venta'), 'idproducto_nombre_producto_venta');
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
                new FilterColumn($this->dataset, 'iddetalle', 'iddetalle', $this->RenderText('Iddetalle')),
                new FilterColumn($this->dataset, 'idventa', 'idventa_n_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'idproducto', 'idproducto_nombre', $this->RenderText('Productos')),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', $this->RenderText('Cantidad')),
                new FilterColumn($this->dataset, 'precio_venta_unitario', 'precio_venta_unitario', $this->RenderText('Precio Venta Unitario')),
                new FilterColumn($this->dataset, 'valor_parcial', 'valor_parcial', $this->RenderText('Valor Parcial')),
                new FilterColumn($this->dataset, 'descuento', 'descuento', $this->RenderText('Descuento en Venta')),
                new FilterColumn($this->dataset, 'iva', 'iva', $this->RenderText('Iva')),
                new FilterColumn($this->dataset, 'aplicar_descuento', 'aplicar_descuento', $this->RenderText(' Descuento'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idventa'])
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['cantidad'])
                ->addColumn($columns['precio_venta_unitario'])
                ->addColumn($columns['valor_parcial'])
                ->addColumn($columns['descuento'])
                ->addColumn($columns['iva']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('idventa')
                ->setOptionsFor('idproducto');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new AutocompleteComboBox('idventa_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idventa_n_factura_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idventa', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idventa_n_factura_search');
            
            $filterBuilder->addColumn(
                $columns['idventa'],
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
            
            $main_editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idproducto_nombre_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idproducto', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idproducto_nombre_search');
            
            $text_editor = new TextEdit('idproducto');
            
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
            
            $main_editor = new TextEdit('cantidad_edit');
            $main_editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            
            $filterBuilder->addColumn(
                $columns['cantidad'],
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
            
            $main_editor = new TextEdit('precio_venta_unitario_edit');
            
            $filterBuilder->addColumn(
                $columns['precio_venta_unitario'],
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
            
            $main_editor = new TextEdit('valor_parcial_edit');
            
            $filterBuilder->addColumn(
                $columns['valor_parcial'],
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
            
            $main_editor = new TextEdit('descuento_edit');
            
            $filterBuilder->addColumn(
                $columns['descuento'],
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
            
            $main_editor = new TextEdit('iva_edit');
            
            $filterBuilder->addColumn(
                $columns['iva'],
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
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: red; background-color: yellow;');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: red; background-color: yellow;');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
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
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idventa field
            //
            $editor = new ComboBox('idventa_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."facturar_ventas"');
            $field = new StringField('clientes_nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idventa', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new DateTimeField('fecha_hora');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('tipo_de_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('abono');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('valor_total_pagar');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('n_factura', null, null, true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iva_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('descuento_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sub_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('printt');
            $lookupDataset->AddField($field, false);
            $field = new StringField('vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('n_factura', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'N° Factura', 
                'idventa', 
                $editor, 
                $this->dataset, 'idventa', 'n_factura', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idproducto field
            //
            $editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT 
              productos.idproducto, 
              concat(productos.idproducto,\' - \' ,
              to_char(productos.precio_venta,\'LFM9,999,999\')) as nombre
            FROM 
              public.productos, 
              public.descuentos, 
              public.unidades, 
                public.caracteristicas
            WHERE 
              descuentos.iddescuentos = productos.iddescuentos AND
              unidades.idunidad = productos.idunidad AND
                caracteristicas.idcaracter = productos.idcaracter AND
              productos.estado = 1';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'producto_venta');
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Productos', 'idproducto', 'idproducto_nombre', 'insert_idproducto_nombre_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'nombre', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editor->SetPlaceholder($this->RenderText('USE . PARA FRACCIONES'));
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('1'));
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
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Precio Venta Unitario', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for valor_parcial field
            //
            $editor = new TextEdit('valor_parcial_edit');
            $editColumn = new CustomEditColumn('Valor Parcial', 'valor_parcial', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento en Venta', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for iva field
            //
            $editor = new TextEdit('iva_edit');
            $editColumn = new CustomEditColumn('Iva', 'iva', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for aplicar_descuento field
            //
            $editor = new ComboBox('aplicar_descuento_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('APLICAR'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('NO_APLICAR'));
            $editColumn = new CustomEditColumn(' Descuento', 'aplicar_descuento', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('2'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: red; background-color: yellow;');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: red; background-color: yellow;');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for iddetalle field
            //
            $column = new TextViewColumn('iddetalle', 'iddetalle', 'Iddetalle', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Productos', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new CurrencyViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Precio Venta Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new CurrencyViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setInlineStyles('color: red; background-color: yellow;');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new CurrencyViewColumn('descuento', 'descuento', 'Descuento en Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('%');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva field
            //
            $column = new CurrencyViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('% ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new TextViewColumn('aplicar_descuento', 'aplicar_descuento', ' Descuento', $this->dataset);
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
        
        public function GetEnableModalGridInsert() { return true; }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
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
            $grid->SetInsertClientFormLoadedScript($this->RenderText('editors[\'iva\'].visible(false);
            editors[\'precio_venta_unitario\'].visible(false);
            editors[\'descuento\'].visible(false);
            editors[\'valor_parcial\'].visible(false);
            editors[\'idventa\'].visible(false);'));
        }
    
        protected function doRegisterHandlers() {
            $selectQuery = 'SELECT 
              productos.idproducto, 
              concat(productos.idproducto,\' - \' ,
              to_char(productos.precio_venta,\'LFM9,999,999\')) as nombre
            FROM 
              public.productos, 
              public.descuentos, 
              public.unidades, 
                public.caracteristicas
            WHERE 
              descuentos.iddescuentos = productos.iddescuentos AND
              unidades.idunidad = productos.idunidad AND
                caracteristicas.idcaracter = productos.idcaracter AND
              productos.estado = 1';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'producto_venta');
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idproducto_nombre_search', 'idproducto', 'nombre', null);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."facturar_ventas"');
            $field = new StringField('clientes_nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idventa', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new DateTimeField('fecha_hora');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('tipo_de_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('abono');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('valor_total_pagar');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('n_factura', null, null, true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iva_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('descuento_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('saldo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sub_total');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('printt');
            $lookupDataset->AddField($field, false);
            $field = new StringField('vendedor');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('n_factura', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idventa_n_factura_search', 'idventa', 'n_factura', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT 
              productos.idproducto, 
              concat(productos.idproducto,\' - \' ,
              to_char(productos.precio_venta,\'LFM9,999,999\')) as nombre
            FROM 
              public.productos, 
              public.descuentos, 
              public.unidades, 
                public.caracteristicas
            WHERE 
              descuentos.iddescuentos = productos.iddescuentos AND
              unidades.idunidad = productos.idunidad AND
                caracteristicas.idcaracter = productos.idcaracter AND
              productos.estado = 1';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'producto_venta');
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idproducto_nombre_search', 'idproducto', 'nombre', null);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
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
            /*  Pendiente para Obtener el valor de idventa-> N° Factura
            $estado=$this->GetConnection()->ExecScalarSQL('SELECT 
              facturar_ventas.estado
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.n_factura='.$rowData['idventa'].'');
            
            if($estado==2){
            $cancel=true;
            $message='ERROR: NO ESTA PERMITIDO ADICIONAR PRODUCTOS, SI LA FACTURA YA FUE GUARDADA ';
            $messageDisplayTime=5;   
            }
            */
            
            //------------------------------------------------------BIEN---------------------------------
            if($rowData['cantidad']==0){
            $cancel=true;
            $message=" LA CANTIDAD DEBE SER MAYOR A: 0 ";
            }
            
            include('conexion.php');
            
            $sql='SELECT 
             productos.precio_venta, 
              productos.iddescuentos,productos.stock
              ,productos.id_iva
            FROM 
              public.productos
              WHERE productos.idproducto='.$rowData['idproducto'].';';
               $rs=pg_query($conn,$sql);
               while($row=pg_fetch_row($rs)) {
               if($row[2]<$rowData['cantidad']){
                $cancel=true;
                $message=" LA CANTIDAD INGRESADA: ".$rowData['cantidad']."  ES MAYOR QUE EL STOCK: ".$row[2]; // number_format() quitar  .00
              }
                
               $rowData['precio_venta_unitario']=$row[0];
               $rowData['descuento']=$row[1];
               $rowData['valor_parcial']=$row[0]*$rowData['cantidad'];
               $rowData['iva']=$row[3];
              
               }
               if($rowData['aplicar_descuento']==2){
            $rowData['descuento']=0;
            }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            include('conexion.php');
            
            $sql='SELECT estado
              FROM public.facturar_ventas
              WHERE  facturar_ventas.idventa = '.$rowData['idventa'].';';
            
            $rs=pg_query($conn,$sql);
            while($row=pg_fetch_row($rs)) {
            if($row[0]==2){
            $cancel=true;
            $message='ERROR: NO ESTA PERMITIDO ELIMINAR PRODUCTOS, SI LA FACTURA YA FUE GUARDADA ';
            $messageDisplayTime=5;
            }
            }
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include('conexion.php');
            $precio=$rowData['precio_venta_unitario'];
            $cantidad=$rowData['cantidad'];
            $descuento=$rowData['descuento'];
            $idproducto=$rowData['idproducto'];
            $subtotal=$cantidad*$precio;
            
            
            $sq='
            SELECT 
              facturar_ventas.tipo_de_venta
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].';';
            $rs=pg_query($conn,$sq);
            while($row=pg_fetch_row($rs)) { 
            
            if($row[0]==1){
            $sql='UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta+'.$subtotal.'-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.$cantidad.'),iva_total=iva_total+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', 
               descuento_total=descuento_total+'.($cantidad*($precio*($descuento/100))).', 
                     saldo=0,abono=abono+'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).', sub_total=sub_total+'.$subtotal.'-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', valor_total_pagar=valor_total_pagar+'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'
             WHERE facturar_ventas.idventa='.$rowData['idventa'].';';
            
            }else{
            
            $sql='UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta+'.$subtotal.'-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.$cantidad.'),iva_total=iva_total+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', 
               descuento_total=descuento_total+'.($cantidad*($precio*($descuento/100))).', 
                     saldo=valor_total_pagar+'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'-(SELECT 
              facturar_ventas.abono
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].'), sub_total=sub_total+'.$subtotal.'-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', valor_total_pagar=valor_total_pagar+'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'
             WHERE facturar_ventas.idventa='.$rowData['idventa'].';';
            
            }
            $rs=pg_query($conn,$sql);
            
            
            
            }
            
            
            $sql='UPDATE public.productos
               SET  stock=stock-'.$rowData['cantidad'].'
             WHERE idproducto='.$rowData['idproducto'].';';
             $rs=pg_query($conn,$sql);
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include('conexion.php');
            $precio=$rowData['precio_venta_unitario'];
            $cantidad=$rowData['cantidad'];
            $descuento=$rowData['descuento'];
            $idproducto=$rowData['idproducto'];
            $subtotal=$cantidad*$precio;
            
            $sq='
            SELECT 
              facturar_ventas.tipo_de_venta
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].';';
            $rs=pg_query($conn,$sq);
            
            while($row=pg_fetch_row($rs)) { 
            
            if($row[0]==1){
            $sql='UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta-('.$subtotal.'-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.$cantidad.')),iva_total=iva_total-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', 
               descuento_total=descuento_total-'.($cantidad*($precio*($descuento/100))).', 
                     saldo=0,abono=abono-'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).', sub_total=sub_total-'.$subtotal.'+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', valor_total_pagar=valor_total_pagar-'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'
             WHERE facturar_ventas.idventa='.$rowData['idventa'].';';
            
            }else{
            
            $sql='UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta-('.$subtotal.'-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.$cantidad.')),iva_total=iva_total-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', 
               descuento_total=descuento_total-'.($cantidad*($precio*($descuento/100))).', 
                     saldo=valor_total_pagar-'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'-(SELECT 
              facturar_ventas.abono
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].'), sub_total=sub_total-'.$subtotal.'+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='.$idproducto.')*'.($cantidad)*($rowData['iva']/100).', valor_total_pagar=valor_total_pagar-'.$subtotal.'-'.($cantidad*($precio*($descuento/100))).'
             WHERE facturar_ventas.idventa='.$rowData['idventa'].';';
            
            }
            $rs=pg_query($conn,$sql);
            
            }
            
            
            // STOCK PRODUCTO
            $sql='UPDATE public.productos
               SET  stock=stock+'.$rowData['cantidad'].'
             WHERE idproducto='.$rowData['idproducto'].';';
             $rs=pg_query($conn,$sql);
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
    
    // OnBeforePageExecute event handler
    
    
    
    class public_facturar_ventasPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."facturar_ventas"');
            $field = new StringField('clientes_nit_cc');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idventa', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new DateTimeField('fecha_hora');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('tipo_de_venta');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('abono');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('valor_total_pagar');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('n_factura', null, null, true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iva_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('descuento_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('saldo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sub_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('estado');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('user_id');
            if (!$this->GetSecurityInfo()->HasAdminGrant())
              $field->SetReadOnly(true, GetApplication()->GetCurrentUserId());
            $this->dataset->AddField($field, false);
            $field = new IntegerField('printt');
            $this->dataset->AddField($field, false);
            $field = new StringField('vendedor');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('clientes_nit_cc', 'public.clientes', new StringField('nit_cc'), new StringField('nombre_completo', 'clientes_nit_cc_nombre_completo', 'clientes_nit_cc_nombre_completo_public_clientes'), 'clientes_nit_cc_nombre_completo_public_clientes');
            $this->dataset->AddLookupField('user_id', 'public.phpgen_users', new IntegerField('user_id'), new StringField('user_name', 'user_id_user_name', 'user_id_user_name_public_phpgen_users'), 'user_id_user_name_public_phpgen_users');
            $this->dataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), 'fecha_hora>=to_date(\'%CURRENT_DATE%\', \'dd-MM-yyyy\')'));
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
                new FilterColumn($this->dataset, 'n_factura', 'n_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'clientes_nit_cc', 'clientes_nit_cc_nombre_completo', $this->RenderText('Cliente')),
                new FilterColumn($this->dataset, 'idventa', 'idventa', $this->RenderText('Idventa')),
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha y Hora')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Tipo De Venta')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'descuento_total', 'descuento_total', $this->RenderText('Descuento Total')),
                new FilterColumn($this->dataset, 'iva_total', 'iva_total', $this->RenderText('Iva Total')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total Pagar')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_name', $this->RenderText('Vendedor')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado')),
                new FilterColumn($this->dataset, 'printt', 'printt', $this->RenderText('Imprim.')),
                new FilterColumn($this->dataset, 'vendedor', 'vendedor', $this->RenderText('Vendedor')),
                new FilterColumn($this->dataset, 'utilidad_en_venta', 'utilidad_en_venta', $this->RenderText('Utilidad En Venta'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['n_factura'])
                ->addColumn($columns['clientes_nit_cc'])
                ->addColumn($columns['fecha_hora'])
                ->addColumn($columns['tipo_de_venta'])
                ->addColumn($columns['abono'])
                ->addColumn($columns['sub_total'])
                ->addColumn($columns['descuento_total'])
                ->addColumn($columns['iva_total'])
                ->addColumn($columns['valor_total_pagar'])
                ->addColumn($columns['saldo']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('clientes_nit_cc')
                ->setOptionsFor('fecha_hora');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('n_factura_edit');
            
            $filterBuilder->addColumn(
                $columns['n_factura'],
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
            
            $main_editor = new AutocompleteComboBox('clientes_nit_cc_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_clientes_nit_cc_nombre_completo_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('clientes_nit_cc', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_clientes_nit_cc_nombre_completo_search');
            
            $text_editor = new TextEdit('clientes_nit_cc');
            
            $filterBuilder->addColumn(
                $columns['clientes_nit_cc'],
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
            
            $main_editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i a');
            
            $filterBuilder->addColumn(
                $columns['fecha_hora'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new ComboBox('tipo_de_venta');
            $main_editor->SetAllowNullValue(false);
            $main_editor->addChoice($this->RenderText('1'), $this->RenderText('DEBITO'));
            $main_editor->addChoice($this->RenderText('2'), $this->RenderText('CREDITO'));
            
            $multi_value_select_editor = new MultiValueSelect('tipo_de_venta');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $filterBuilder->addColumn(
                $columns['tipo_de_venta'],
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
            
            $main_editor = new TextEdit('abono_edit');
            $main_editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            
            $filterBuilder->addColumn(
                $columns['abono'],
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
            
            $main_editor = new TextEdit('sub_total_edit');
            
            $filterBuilder->addColumn(
                $columns['sub_total'],
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
            
            $main_editor = new TextEdit('descuento_total_edit');
            
            $filterBuilder->addColumn(
                $columns['descuento_total'],
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
            
            $main_editor = new TextEdit('iva_total_edit');
            
            $filterBuilder->addColumn(
                $columns['iva_total'],
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
            
            $main_editor = new TextEdit('valor_total_pagar_edit');
            
            $filterBuilder->addColumn(
                $columns['valor_total_pagar'],
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
            
            $main_editor = new TextEdit('saldo_edit');
            
            $filterBuilder->addColumn(
                $columns['saldo'],
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
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new AjaxOperation(OPERATION_EDIT,
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'),
                    $this->GetLocalizerCaptions()->GetMessageString('Edit'), $this->dataset,
                    $this->GetGridEditHandler(), $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.facturar_ventas.public.detalle_factura_venta')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_facturar_ventas_public_detalle_factura_venta detail
            //
            $column = new DetailColumn(array('idventa'), 'public.facturar_ventas.public.detalle_factura_venta', 'public_facturar_ventas_public_detalle_factura_venta_handler', $this->dataset, 'Detalle Factura Venta');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i a');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('SI EL TIPO DE VENTA FUE A CREDITO EL EXCEDENTE SERA EL SALDO PENDIENTE POR PAGAR  ***PENDIENTE****'));
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
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i a');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new AutocompleteComboBox('clientes_nit_cc_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Cliente', 'clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'edit_clientes_nit_cc_nombre_completo_search', $editor, $this->dataset, $lookupDataset, 'nit_cc', 'nombre_completo', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
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
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new AutocompleteComboBox('clientes_nit_cc_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Cliente', 'clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'insert_clientes_nit_cc_nombre_completo_search', $editor, $this->dataset, $lookupDataset, 'nit_cc', 'nombre_completo', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i a');
            $editColumn = new CustomEditColumn('Fecha y Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $editColumn->SetInsertDefaultValue($this->RenderText('%CURRENT_DATETIME%'));
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tipo_de_venta field
            //
            $editor = new RadioEdit('tipo_de_venta_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice($this->RenderText('1'), $this->RenderText('DEBITO'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('CREDITO'));
            $editColumn = new CustomEditColumn('Tipo De Venta', 'tipo_de_venta', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('1'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $  .  , )'));
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('0'));
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
            // Edit column for estado field
            //
            $editor = new TextEdit('estado_edit');
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i a');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i a');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('n_factura', 'n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('clientes_nit_cc', 'clientes_nit_cc_nombre_completo', 'Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for idventa field
            //
            $column = new TextViewColumn('idventa', 'idventa', 'Idventa', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y Hora', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d h:i a');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for abono field
            //
            $column = new CurrencyViewColumn('abono', 'abono', 'Abono', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator('.');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setBold(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'Vendedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for printt field
            //
            $column = new TextViewColumn('printt', 'printt', 'Imprim.', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for vendedor field
            //
            $column = new TextViewColumn('vendedor', 'vendedor', 'Vendedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
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
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        public function GetEnableModalGridEdit() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(false);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
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
            $result->SetTotal('valor_total_pagar', PredefinedAggregate::$Sum);
            $result->SetTotal('saldo', PredefinedAggregate::$Sum);
            
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
            $grid->SetInsertClientFormLoadedScript($this->RenderText('editors[\'estado\'].visible(false);
            editors[\'fecha_hora\'].visible(false);'));
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new public_facturar_ventas_public_detalle_factura_ventaPage('public_facturar_ventas_public_detalle_factura_venta', $this, array('idventa'), array('idventa'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.facturar_ventas.public.detalle_factura_venta'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.facturar_ventas.public.detalle_factura_venta'));
            $detailPage->SetTitle('Detalle Factura Venta');
            $detailPage->SetMenuLabel('Detalle Factura Venta');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_facturar_ventas_public_detalle_factura_venta_handler');
            $handler = new PageHTTPHandler('public_facturar_ventas_public_detalle_factura_venta_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_clientes_nit_cc_nombre_completo_search', 'nit_cc', 'nombre_completo', null);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_clientes_nit_cc_nombre_completo_search', 'nit_cc', 'nombre_completo', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('direccion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $lookupDataset->AddField($field, false);
            $field = new StringField('email');
            $lookupDataset->AddField($field, false);
            $field = new StringField('fax');
            $lookupDataset->AddField($field, false);
            $field = new StringField('observacion');
            $lookupDataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $lookupDataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_completo', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_clientes_nit_cc_nombre_completo_search', 'nit_cc', 'nombre_completo', null);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CREDITO';
            $handled=true;
                    break;
                
            }
            }
            
            if($fieldName=='estado'){
            switch ($fieldData) {
                case 1:
                if($rowData['saldo']==0){
                $customText = '<a href="guardar.php?lp='.$rowData['n_factura'].'"><img src="img/save.png" width="20" height="20"></a>';
                $handled=true;
                }else{
                  $customText = '<img src="img/save.png" width="20" height="20">';
                  $handled=true;
                }
                      break;
                case 2:
                $customText = '<img src="img/saved.png" width="20" height="20">';
            $handled=true;
                    break;
                
            }
            }
            
            
            
            if($fieldName=='printt'){
            if($rowData['estado']==2){
            $customText = '<a href="print.php?lp='.$rowData['n_factura'].'" target="_blank"><img src="img/print.png" width="20" height="20"></a>';
             $handled=true;
            }else{
            $customText = '<img src="img/print.png" width="20" height="20">';
             $handled=true;
            }
             
            }
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CREDITO';
            $handled=true;
                    break;
                
            }
            }
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' DEBITO';
            $handled=true;
                      break;
                case 2:
                   $customText=' CREDITO';
            $handled=true;
                    break;
                
            }
            }
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
            $fecha=date('Y-M-d h:i a');
            
            $rowData['fecha_hora']=$fecha;
            
            
            $rowData['estado']=1;
            
            
            
            //$rowData['user_id']=(%CURRENT_USER_ID%);
            
            if($rowData['tipo_de_venta']==1){
            $rowData['abono']=0;
            }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
            if($rowData['tipo_de_venta']==1){
            $rowData['abono']=$rowData['valor_total_pagar'];
            $rowData['saldo']=0;
            
            }else if ($rowData['tipo_de_venta']==2){
            
            include('conexion.php');
            
            $sql='SELECT 
              facturar_ventas.valor_total_pagar
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = '.$rowData['idventa'].';';
            $rs=pg_query($conn,$sql);
              while ($row=pg_fetch_row($rs)) { 
              if($rowData['abono']<=$row[0]){
                $rowData['saldo']=$row[0]-$rowData['abono'];
              }else{
              $cancel=true;
              $message='EL ABONO DEBE SER MENOR O IGUAL QUE EL VALOR TOTAL A PAGAR';
              $messageDisplayTime=15;
              }
              }
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
        $Page = new public_facturar_ventasPage("public_facturar_ventas", "facturar_ventas.php", GetCurrentUserGrantForDataSource("public.facturar_ventas"), 'UTF-8');
        $Page->SetTitle('Facturar Ventas');
        $Page->SetMenuLabel('Facturar Ventas');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.facturar_ventas"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

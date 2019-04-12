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
    
    
    
    class public_detalle_factura_ventaPage extends Page
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
            $this->dataset->AddLookupField('idproducto', 'public.productos', new IntegerField('idproducto', null, null, true), new StringField('nombre', 'idproducto_nombre', 'idproducto_nombre_public_productos'), 'idproducto_nombre_public_productos');
            $this->dataset->AddLookupField('idventa', 'public.facturar_ventas', new IntegerField('idventa', null, null, true), new IntegerField('n_factura', 'idventa_n_factura', 'idventa_n_factura_public_facturar_ventas', true), 'idventa_n_factura_public_facturar_ventas');
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
                new FilterColumn($this->dataset, 'idproducto', 'idproducto_nombre', $this->RenderText('Producto')),
                new FilterColumn($this->dataset, 'precio_venta_unitario', 'precio_venta_unitario', $this->RenderText('Valor Unitario')),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', $this->RenderText('Cantidad')),
                new FilterColumn($this->dataset, 'valor_parcial', 'valor_parcial', $this->RenderText('Valor Parcial')),
                new FilterColumn($this->dataset, 'descuento', 'descuento', $this->RenderText('Descuento x Unid')),
                new FilterColumn($this->dataset, 'idventa', 'idventa_n_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'iva', 'iva', $this->RenderText('Iva')),
                new FilterColumn($this->dataset, 'aplicar_descuento', 'aplicar_descuento', $this->RenderText('Aplicar Descuento'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['precio_venta_unitario'])
                ->addColumn($columns['cantidad'])
                ->addColumn($columns['valor_parcial'])
                ->addColumn($columns['descuento'])
                ->addColumn($columns['idventa'])
                ->addColumn($columns['iva'])
                ->addColumn($columns['aplicar_descuento']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('idproducto')
                ->setOptionsFor('idventa');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
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
            
            $main_editor = new TextEdit('cantidad_edit');
            
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
            
            $main_editor = new TextEdit('aplicar_descuento_edit');
            
            $filterBuilder->addColumn(
                $columns['aplicar_descuento'],
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
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
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
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
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
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new NumberViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Valor Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new NumberViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new NumberViewColumn('descuento', 'descuento', 'Descuento x Unid', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
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
            // View column for iva field
            //
            $column = new NumberViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new NumberViewColumn('aplicar_descuento', 'aplicar_descuento', 'Aplicar Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new NumberViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Valor Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new NumberViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new NumberViewColumn('descuento', 'descuento', 'Descuento x Unid', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva field
            //
            $column = new NumberViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new NumberViewColumn('aplicar_descuento', 'aplicar_descuento', 'Aplicar Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new ComboBox('idproducto_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new IntegerField('idproducto', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Producto', 
                'idproducto', 
                $editor, 
                $this->dataset, 'idproducto', 'nombre', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Valor Unitario', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor_parcial field
            //
            $editor = new TextEdit('valor_parcial_edit');
            $editColumn = new CustomEditColumn('Valor Parcial', 'valor_parcial', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento field
            //
            $editor = new TextEdit('descuento_edit');
            $editColumn = new CustomEditColumn('Descuento x Unid', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
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
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iva field
            //
            $editor = new TextEdit('iva_edit');
            $editColumn = new CustomEditColumn('Iva', 'iva', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for aplicar_descuento field
            //
            $editor = new TextEdit('aplicar_descuento_edit');
            $editColumn = new CustomEditColumn('Aplicar Descuento', 'aplicar_descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new ComboBox('idproducto_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new IntegerField('idproducto', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $editColumn = new LookUpEditColumn(
                'Producto', 
                'idproducto', 
                $editor, 
                $this->dataset, 'idproducto', 'nombre', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for precio_venta_unitario field
            //
            $editor = new TextEdit('precio_venta_unitario_edit');
            $editColumn = new CustomEditColumn('Valor Unitario', 'precio_venta_unitario', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for cantidad field
            //
            $editor = new TextEdit('cantidad_edit');
            $editColumn = new CustomEditColumn('Cantidad', 'cantidad', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('Descuento x Unid', 'descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
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
            $editor = new TextEdit('aplicar_descuento_edit');
            $editColumn = new CustomEditColumn('Aplicar Descuento', 'aplicar_descuento', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new NumberViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Valor Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new NumberViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new NumberViewColumn('descuento', 'descuento', 'Descuento x Unid', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva field
            //
            $column = new NumberViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new NumberViewColumn('aplicar_descuento', 'aplicar_descuento', 'Aplicar Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new NumberViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Valor Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new NumberViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new NumberViewColumn('descuento', 'descuento', 'Descuento x Unid', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for iva field
            //
            $column = new NumberViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new NumberViewColumn('aplicar_descuento', 'aplicar_descuento', 'Aplicar Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
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
            // View column for nombre field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_nombre', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_venta_unitario field
            //
            $column = new NumberViewColumn('precio_venta_unitario', 'precio_venta_unitario', 'Valor Unitario', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_parcial field
            //
            $column = new NumberViewColumn('valor_parcial', 'valor_parcial', 'Valor Parcial', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento field
            //
            $column = new NumberViewColumn('descuento', 'descuento', 'Descuento x Unid', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for n_factura field
            //
            $column = new TextViewColumn('idventa', 'idventa_n_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva field
            //
            $column = new NumberViewColumn('iva', 'iva', 'Iva', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for aplicar_descuento field
            //
            $column = new NumberViewColumn('aplicar_descuento', 'aplicar_descuento', 'Aplicar Descuento', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
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
    
        }
    
        protected function doRegisterHandlers() {
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."productos"');
            $field = new IntegerField('idproducto', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock_minimo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_venta');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('stock');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idcaracter');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('id_iva');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idtipo');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idunidad');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('iddescuentos');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('estado_stock');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idproducto_nombre_search', 'idproducto', 'nombre', null);
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
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
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
        $Page = new public_detalle_factura_ventaPage("public_detalle_factura_venta", "detalle_factura_venta.php", GetCurrentUserGrantForDataSource("public.detalle_factura_venta"), 'UTF-8');
        $Page->SetTitle('Detalle Factura Venta');
        $Page->SetMenuLabel('Detalle Factura Venta');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.detalle_factura_venta"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

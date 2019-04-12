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
    
    
    
    class public_clientes_public_facturar_ventasPage extends DetailPage
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
            $this->dataset->AddField($field, false);
            $field = new IntegerField('printt');
            $this->dataset->AddField($field, false);
            $field = new StringField('vendedor');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('clientes_nit_cc', 'public.clientes', new StringField('nit_cc'), new StringField('nombre_completo', 'clientes_nit_cc_nombre_completo', 'clientes_nit_cc_nombre_completo_public_clientes'), 'clientes_nit_cc_nombre_completo_public_clientes');
            $this->dataset->AddLookupField('user_id', 'public.phpgen_users', new IntegerField('user_id'), new StringField('user_name', 'user_id_user_name', 'user_id_user_name_public_phpgen_users'), 'user_id_user_name_public_phpgen_users');
            $this->dataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), 'tipo_de_venta=2'));
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
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha y  Hora')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Tipo De Venta')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total Pagar')),
                new FilterColumn($this->dataset, 'iva_total', 'iva_total', $this->RenderText('Iva Total')),
                new FilterColumn($this->dataset, 'descuento_total', 'descuento_total', $this->RenderText('Descuento Total')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado')),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_name', $this->RenderText('User Id')),
                new FilterColumn($this->dataset, 'printt', 'printt', $this->RenderText('Printt')),
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
                ->addColumn($columns['valor_total_pagar'])
                ->addColumn($columns['iva_total'])
                ->addColumn($columns['descuento_total'])
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
            
            $main_editor = new TextEdit('tipo_de_venta_edit');
            
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
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('abono_edit');
            
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
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
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
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
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
            $column->setNumberAfterDecimal(0);
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
            $column->setNumberAfterDecimal(0);
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
            $column->setNumberAfterDecimal(0);
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
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
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
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
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
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new TextEdit('n_factura_edit');
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new ComboBox('clientes_nit_cc_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
            $editColumn = new LookUpEditColumn(
                'Cliente', 
                'clientes_nit_cc', 
                $editor, 
                $this->dataset, 'nit_cc', 'nombre_completo', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i a');
            $editColumn = new CustomEditColumn('Fecha y  Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tipo_de_venta field
            //
            $editor = new TextEdit('tipo_de_venta_edit');
            $editColumn = new CustomEditColumn('Tipo De Venta', 'tipo_de_venta', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total Pagar', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iva_total field
            //
            $editor = new TextEdit('iva_total_edit');
            $editColumn = new CustomEditColumn('Iva Total', 'iva_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento_total field
            //
            $editor = new TextEdit('descuento_total_edit');
            $editColumn = new CustomEditColumn('Descuento Total', 'descuento_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for saldo field
            //
            $editor = new TextEdit('saldo_edit');
            $editColumn = new CustomEditColumn('Saldo', 'saldo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new TextEdit('n_factura_edit');
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for clientes_nit_cc field
            //
            $editor = new ComboBox('clientes_nit_cc_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
            $editColumn = new LookUpEditColumn(
                'Cliente', 
                'clientes_nit_cc', 
                $editor, 
                $this->dataset, 'nit_cc', 'nombre_completo', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new DateTimeEdit('fecha_hora_edit', false, 'Y-m-d h:i a');
            $editColumn = new CustomEditColumn('Fecha y  Hora', 'fecha_hora', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tipo_de_venta field
            //
            $editor = new TextEdit('tipo_de_venta_edit');
            $editColumn = new CustomEditColumn('Tipo De Venta', 'tipo_de_venta', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total Pagar', 'valor_total_pagar', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for iva_total field
            //
            $editor = new TextEdit('iva_total_edit');
            $editColumn = new CustomEditColumn('Iva Total', 'iva_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for descuento_total field
            //
            $editor = new TextEdit('descuento_total_edit');
            $editColumn = new CustomEditColumn('Descuento Total', 'descuento_total', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for saldo field
            //
            $editor = new TextEdit('saldo_edit');
            $editColumn = new CustomEditColumn('Saldo', 'saldo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(false && $this->GetSecurityInfo()->HasAddGrant());
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
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
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
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
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
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
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
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
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
            $column = new DateTimeViewColumn('fecha_hora', 'fecha_hora', 'Fecha y  Hora', $this->dataset);
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
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for valor_total_pagar field
            //
            $column = new CurrencyViewColumn('valor_total_pagar', 'valor_total_pagar', 'Valor Total Pagar', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for iva_total field
            //
            $column = new CurrencyViewColumn('iva_total', 'iva_total', 'Iva Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for descuento_total field
            //
            $column = new CurrencyViewColumn('descuento_total', 'descuento_total', 'Descuento Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddCompareColumn($column);
            
            //
            // View column for sub_total field
            //
            $column = new NumberViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new NumberViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddCompareColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('DetailGridpublic.clientes.public.facturar_ventas_user_id_user_name_handler_compare');
            $grid->AddCompareColumn($column);
            
            //
            // View column for printt field
            //
            $column = new NumberViewColumn('printt', 'printt', 'Printt', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
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
            $column = new NumberViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
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
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'User Id', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'DetailGridpublic.clientes.public.facturar_ventas_user_id_user_name_handler_compare', $column);
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
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 2:
                   $customText=' PENDIENTE DE PAGO';
            $handled=true;
                    break;
                }
            }
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='tipo_de_venta'){
            switch ($fieldData) {
                case 1:
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 2:
                   $customText=' PENDIENTE DE PAGO';
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
                      $customText=' CANCELADA';
            $handled=true;
                      break;
                case 2:
                   $customText=' PENDIENTE DE PAGO';
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
    
    // OnBeforePageExecute event handler
    
    
    
    class public_clientesPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."clientes"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('nombre_completo');
            $this->dataset->AddField($field, false);
            $field = new StringField('direccion');
            $this->dataset->AddField($field, false);
            $field = new StringField('tel_movil');
            $this->dataset->AddField($field, false);
            $field = new StringField('email');
            $this->dataset->AddField($field, false);
            $field = new StringField('fax');
            $this->dataset->AddField($field, false);
            $field = new StringField('observacion');
            $this->dataset->AddField($field, false);
            $field = new StringField('tel_fijo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sexo');
            $this->dataset->AddField($field, false);
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('idciudad', '(SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento)', new StringField('idciudad'), new StringField('ciudad', 'idciudad_ciudad', 'idciudad_ciudad_ciudad'), 'idciudad_ciudad_ciudad');
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
                new FilterColumn($this->dataset, 'nit_cc', 'nit_cc', $this->RenderText('NIT/CC')),
                new FilterColumn($this->dataset, 'nombre_completo', 'nombre_completo', $this->RenderText('Nombre Completo')),
                new FilterColumn($this->dataset, 'idciudad', 'idciudad_ciudad', $this->RenderText('')),
                new FilterColumn($this->dataset, 'sexo', 'sexo', $this->RenderText('Sexo')),
                new FilterColumn($this->dataset, 'direccion', 'direccion', $this->RenderText('Dirección')),
                new FilterColumn($this->dataset, 'tel_movil', 'tel_movil', $this->RenderText('Tel Movil')),
                new FilterColumn($this->dataset, 'email', 'email', $this->RenderText('Email')),
                new FilterColumn($this->dataset, 'fax', 'fax', $this->RenderText('Fax')),
                new FilterColumn($this->dataset, 'tel_fijo', 'tel_fijo', $this->RenderText('Tel Fijo')),
                new FilterColumn($this->dataset, 'observacion', 'observacion', $this->RenderText('Observacion'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['nit_cc'])
                ->addColumn($columns['nombre_completo'])
                ->addColumn($columns['idciudad'])
                ->addColumn($columns['sexo'])
                ->addColumn($columns['direccion'])
                ->addColumn($columns['tel_movil'])
                ->addColumn($columns['email'])
                ->addColumn($columns['fax'])
                ->addColumn($columns['tel_fijo'])
                ->addColumn($columns['observacion']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('idciudad');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('nit_cc_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['nit_cc'],
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
            
            $main_editor = new TextEdit('nombre_completo_edit');
            $main_editor->SetMaxLength(70);
            
            $filterBuilder->addColumn(
                $columns['nombre_completo'],
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
            
            $main_editor = new AutocompleteComboBox('idciudad_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idciudad_ciudad_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idciudad', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idciudad_ciudad_search');
            
            $text_editor = new TextEdit('idciudad');
            
            $filterBuilder->addColumn(
                $columns['idciudad'],
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
            
            $main_editor = new ComboBox('sexo_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $main_editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            $main_editor->SetAllowNullValue(false);
            
            $multi_value_select_editor = new MultiValueSelect('sexo');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $filterBuilder->addColumn(
                $columns['sexo'],
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
            
            $main_editor = new TextEdit('direccion_edit');
            $main_editor->SetMaxLength(50);
            
            $filterBuilder->addColumn(
                $columns['direccion'],
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
            
            $main_editor = new TextEdit('tel_movil_edit');
            $main_editor->SetMaxLength(10);
            
            $filterBuilder->addColumn(
                $columns['tel_movil'],
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
            
            $main_editor = new TextEdit('email_edit');
            $main_editor->SetMaxLength(50);
            
            $filterBuilder->addColumn(
                $columns['email'],
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
            
            $main_editor = new TextEdit('fax_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['fax'],
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
            
            $main_editor = new TextEdit('tel_fijo_edit');
            
            $filterBuilder->addColumn(
                $columns['tel_fijo'],
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
            
            $main_editor = new TextEdit('observacion_edit');
            $main_editor->SetMaxLength(100);
            
            $filterBuilder->addColumn(
                $columns['observacion'],
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
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_clientes_public_facturar_ventas detail
            //
            $column = new DetailColumn(array('nit_cc'), 'public.clientes.public.facturar_ventas', 'public_clientes_public_facturar_ventas_handler', $this->dataset, 'Facturas Ventas');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText('Departamento y Ciudad'));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Dirección', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_list');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Dirección', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_view');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for nombre_completo field
            //
            $editor = new TextEdit('nombre_completo_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Nombre Completo', 'nombre_completo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idciudad field
            //
            $editor = new MultiLevelComboBoxEditor('idciudad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            
            $selectQuery = 'SELECT 
              departamento.iddepartamento, 
              departamento.nombre
            FROM 
              public.departamento
            ORDER BY departamento.nombre';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset0 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Departamento_multiselec');
            $field = new StringField('iddepartamento');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'iddepartamento', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'iddepartamento', 'nombre', $this->RenderText('Departamento y Ciudad'), null);
            
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset1 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $dataset1->AddField($field, true);
            $field = new StringField('iddepartamento');
            $dataset1->AddField($field, false);
            $field = new StringField('ciudad');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idciudad', 'ciudad', new ForeignKeyInfo('iddepartamento', 'iddepartamento'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idciudad', 'ciudad', $this->RenderText(''), new ForeignKeyInfo('iddepartamento', 'iddepartamento'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idciudad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new ComboBox('sexo_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for direccion field
            //
            $editor = new TextEdit('direccion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Dirección', 'direccion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tel_movil field
            //
            $editor = new TextEdit('tel_movil_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Tel Movil', 'tel_movil', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fax field
            //
            $editor = new TextEdit('fax_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Fax', 'fax', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tel_fijo field
            //
            $editor = new TextEdit('tel_fijo_edit');
            $editColumn = new CustomEditColumn('Tel Fijo', 'tel_fijo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for observacion field
            //
            $editor = new TextEdit('observacion_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Observacion', 'observacion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nit_cc field
            //
            $editor = new TextEdit('nit_cc_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('NIT/CC', 'nit_cc', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nombre_completo field
            //
            $editor = new TextEdit('nombre_completo_edit');
            $editor->SetMaxLength(70);
            $editColumn = new CustomEditColumn('Nombre Completo', 'nombre_completo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for idciudad field
            //
            $editor = new MultiLevelComboBoxEditor('idciudad_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            
            $selectQuery = 'SELECT 
              departamento.iddepartamento, 
              departamento.nombre
            FROM 
              public.departamento
            ORDER BY departamento.nombre';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset0 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'Departamento_multiselec');
            $field = new StringField('iddepartamento');
            $dataset0->AddField($field, true);
            $field = new StringField('nombre');
            $dataset0->AddField($field, false);
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset0, 'iddepartamento', 'nombre', null, ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset0, 'iddepartamento', 'nombre', $this->RenderText('Departamento y Ciudad'), null);
            
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $dataset1 = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $dataset1->AddField($field, true);
            $field = new StringField('iddepartamento');
            $dataset1->AddField($field, false);
            $field = new StringField('ciudad');
            $dataset1->AddField($field, false);
            $dataset1->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            
            GetApplication()->RegisterHTTPHandler($editor->createHttpHandler($dataset1, 'idciudad', 'ciudad', new ForeignKeyInfo('iddepartamento', 'iddepartamento'), ArrayWrapper::createGetWrapper()));
            $level = $editor->addLevel($dataset1, 'idciudad', 'ciudad', $this->RenderText(''), new ForeignKeyInfo('iddepartamento', 'iddepartamento'));
            $editColumn = new MultiLevelLookupEditColumn('', 'idciudad', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new ComboBox('sexo_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('HOMBRE'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('MUJER'));
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for direccion field
            //
            $editor = new TextEdit('direccion_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Dirección', 'direccion', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tel_movil field
            //
            $editor = new TextEdit('tel_movil_edit');
            $editor->SetMaxLength(10);
            $editColumn = new CustomEditColumn('Tel Movil', 'tel_movil', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MaxLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MaxlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new MinLengthValidator(10, StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('MinlengthValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $validator = new DigitsValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('DigitsValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for email field
            //
            $editor = new TextEdit('email_edit');
            $editor->SetMaxLength(50);
            $editColumn = new CustomEditColumn('Email', 'email', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $validator = new EMailValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('EmailValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fax field
            //
            $editor = new TextEdit('fax_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('Fax', 'fax', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for tel_fijo field
            //
            $editor = new TextEdit('tel_fijo_edit');
            $editColumn = new CustomEditColumn('Tel Fijo', 'tel_fijo', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for observacion field
            //
            $editor = new TextEdit('observacion_edit');
            $editor->SetMaxLength(100);
            $editColumn = new CustomEditColumn('Observacion', 'observacion', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Dirección', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_print');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Dirección', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_export');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for nit_cc field
            //
            $column = new TextViewColumn('nit_cc', 'nit_cc', 'NIT/CC', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre_completo field
            //
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Completo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for ciudad field
            //
            $column = new TextViewColumn('idciudad', 'idciudad_ciudad', '', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for direccion field
            //
            $column = new TextViewColumn('direccion', 'direccion', 'Dirección', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for tel_movil field
            //
            $column = new TextViewColumn('tel_movil', 'tel_movil', 'Tel Movil', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for email field
            //
            $column = new TextViewColumn('email', 'email', 'Email', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fax field
            //
            $column = new TextViewColumn('fax', 'fax', 'Fax', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for tel_fijo field
            //
            $column = new TextViewColumn('tel_fijo', 'tel_fijo', 'Tel Fijo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $column->SetMaxLength(75);
            $column->SetFullTextWindowHandlerName('public_clientesGrid_observacion_handler_compare');
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
            $detailPage = new public_clientes_public_facturar_ventasPage('public_clientes_public_facturar_ventas', $this, array('clientes_nit_cc'), array('nit_cc'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.clientes.public.facturar_ventas'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.clientes.public.facturar_ventas'));
            $detailPage->SetTitle('Facturas Ventas');
            $detailPage->SetMenuLabel('Facturas Ventas');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_clientes_public_facturar_ventas_handler');
            $handler = new PageHTTPHandler('public_clientes_public_facturar_ventas_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_list', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_print', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_compare', $column);
            GetApplication()->RegisterHTTPHandler($handler);
            $selectQuery = 'SELECT 
              ciudad.idciudad , departamento.iddepartamento, 
              concat(ciudad.nombre_ciudad  ,\', \',departamento.nombre) as ciudad
              
            FROM 
              public.ciudad, 
              public.departamento
            WHERE 
              departamento.iddepartamento = ciudad.iddepartamento';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'ciudad');
            $field = new StringField('idciudad');
            $lookupDataset->AddField($field, true);
            $field = new StringField('iddepartamento');
            $lookupDataset->AddField($field, false);
            $field = new StringField('ciudad');
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('ciudad', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idciudad_ciudad_search', 'idciudad', 'ciudad', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            //
            // View column for observacion field
            //
            $column = new TextViewColumn('observacion', 'observacion', 'Observacion', $this->dataset);
            $column->SetOrderable(true);
            $handler = new ShowTextBlobHandler($this->dataset, $this, 'public_clientesGrid_observacion_handler_view', $column);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='sexo'){
            switch ($fieldData) {
                case 1:
                      $customText=' HOMBRE';
            $handled=true;
                      break;
                case 2:
                   $customText=' MUJER';
            $handled=true;
                    break;
                
            }
            }
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
            if($success){
            $newFullname=$rowData['nombre_completo'];
            $message="Cliente $newFullname Registrado Exitosamente.";
            $messageDisplayTime=3;
            }else {
               if(strpos($message,'nit_cc')!=false){
               $message=" El cliente con identificacion: ".$rowData['nit_cc'].", Ya existe en el Sistema!";
               
               }
            }
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
        $Page = new public_clientesPage("public_clientes", "clientes.php", GetCurrentUserGrantForDataSource("public.clientes"), 'UTF-8');
        $Page->SetTitle('Clientes');
        $Page->SetMenuLabel('Clientes');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.clientes"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

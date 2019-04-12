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
    
    
    
    class all_facturas_ventasPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $selectQuery = 'SELECT 
              clientes.nombre_completo, 
              facturar_ventas.n_factura, 
              facturar_ventas.valor_total_pagar, 
              facturar_ventas.abono, 
              facturar_ventas.tipo_de_venta, 
              to_char(facturar_ventas.fecha_hora, \'yyyy-MM-DD\') as fecha_hora, 
              facturar_ventas.iva_total, 
              facturar_ventas.descuento_total, 
              facturar_ventas.saldo, 
              facturar_ventas.sub_total, 
              facturar_ventas.user_id,
              facturar_ventas.utilidad_en_venta
            FROM 
              public.facturar_ventas, 
              public.clientes
            WHERE 
              clientes.nit_cc = facturar_ventas.clientes_nit_cc
              ORDER BY facturar_ventas.fecha_hora desc';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $this->dataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'all_facturas_ventas');
            $field = new StringField('nombre_completo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('n_factura');
            $this->dataset->AddField($field, true);
            $field = new IntegerField('valor_total_pagar');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('abono');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('tipo_de_venta');
            $this->dataset->AddField($field, false);
            $field = new StringField('fecha_hora');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('iva_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('descuento_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('saldo');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('sub_total');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('user_id');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('utilidad_en_venta');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('user_id', '(SELECT 
              phpgen_users.user_id, 
              phpgen_users.user_name
            FROM 
              public.phpgen_users)', new IntegerField('user_id'), new StringField('user_name', 'user_id_user_name', 'user_id_user_name_user'), 'user_id_user_name_user');
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
                new FilterColumn($this->dataset, 'nombre_completo', 'nombre_completo', $this->RenderText('Nombre Cliente')),
                new FilterColumn($this->dataset, 'tipo_de_venta', 'tipo_de_venta', $this->RenderText('Tipo De Venta')),
                new FilterColumn($this->dataset, 'fecha_hora', 'fecha_hora', $this->RenderText('Fecha Hora')),
                new FilterColumn($this->dataset, 'abono', 'abono', $this->RenderText('Abono')),
                new FilterColumn($this->dataset, 'sub_total', 'sub_total', $this->RenderText('Sub Total')),
                new FilterColumn($this->dataset, 'descuento_total', 'descuento_total', $this->RenderText('Descuento Total')),
                new FilterColumn($this->dataset, 'iva_total', 'iva_total', $this->RenderText('Iva Total')),
                new FilterColumn($this->dataset, 'valor_total_pagar', 'valor_total_pagar', $this->RenderText('Valor Total Pagar')),
                new FilterColumn($this->dataset, 'saldo', 'saldo', $this->RenderText('Saldo')),
                new FilterColumn($this->dataset, 'utilidad_en_venta', 'utilidad_en_venta', $this->RenderText('Utilidad En Venta')),
                new FilterColumn($this->dataset, 'user_id', 'user_id_user_name', $this->RenderText('Vendedor'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['n_factura'])
                ->addColumn($columns['nombre_completo'])
                ->addColumn($columns['tipo_de_venta'])
                ->addColumn($columns['fecha_hora'])
                ->addColumn($columns['abono'])
                ->addColumn($columns['sub_total'])
                ->addColumn($columns['descuento_total'])
                ->addColumn($columns['iva_total'])
                ->addColumn($columns['valor_total_pagar'])
                ->addColumn($columns['saldo'])
                ->addColumn($columns['utilidad_en_venta']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('user_id');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new SpinEdit('n_factura_edit');
            
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
            
            $main_editor = new TextEdit('nombre_completo_edit');
            
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
            
            $main_editor = new SpinEdit('tipo_de_venta_edit');
            
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
            
            $main_editor = new TextEdit('fecha_hora_edit');
            
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
            
            $main_editor = new TextEdit('utilidad_en_venta_edit');
            
            $filterBuilder->addColumn(
                $columns['utilidad_en_venta'],
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
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Cliente', $this->dataset);
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
            // View column for fecha_hora field
            //
            $column = new TextViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
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
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
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
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'Vendedor', $this->dataset);
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
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new TextViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
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
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for user_name field
            //
            $column = new TextViewColumn('user_id', 'user_id_user_name', 'Vendedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for n_factura field
            //
            $editor = new SpinEdit('n_factura_edit');
            $editColumn = new CustomEditColumn('N° Factura', 'n_factura', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nombre_completo field
            //
            $editor = new TextEdit('nombre_completo_edit');
            $editColumn = new CustomEditColumn('Nombre Cliente', 'nombre_completo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for tipo_de_venta field
            //
            $editor = new SpinEdit('tipo_de_venta_edit');
            $editColumn = new CustomEditColumn('Tipo De Venta', 'tipo_de_venta', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_hora field
            //
            $editor = new TextEdit('fecha_hora_edit');
            $editColumn = new CustomEditColumn('Fecha Hora', 'fecha_hora', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for abono field
            //
            $editor = new TextEdit('abono_edit');
            $editColumn = new CustomEditColumn('Abono', 'abono', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sub_total field
            //
            $editor = new TextEdit('sub_total_edit');
            $editColumn = new CustomEditColumn('Sub Total', 'sub_total', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for descuento_total field
            //
            $editor = new TextEdit('descuento_total_edit');
            $editColumn = new CustomEditColumn('Descuento Total', 'descuento_total', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for iva_total field
            //
            $editor = new TextEdit('iva_total_edit');
            $editColumn = new CustomEditColumn('Iva Total', 'iva_total', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for valor_total_pagar field
            //
            $editor = new TextEdit('valor_total_pagar_edit');
            $editColumn = new CustomEditColumn('Valor Total Pagar', 'valor_total_pagar', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for saldo field
            //
            $editor = new TextEdit('saldo_edit');
            $editColumn = new CustomEditColumn('Saldo', 'saldo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
    
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
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new TextViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
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
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddPrintColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
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
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new TextViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
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
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for saldo field
            //
            $column = new CurrencyViewColumn('saldo', 'saldo', 'Saldo', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $column->setCurrencySign('$ ');
            $grid->AddExportColumn($column);
            
            //
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
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
            $column = new TextViewColumn('nombre_completo', 'nombre_completo', 'Nombre Cliente', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for tipo_de_venta field
            //
            $column = new TextViewColumn('tipo_de_venta', 'tipo_de_venta', 'Tipo De Venta', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_hora field
            //
            $column = new TextViewColumn('fecha_hora', 'fecha_hora', 'Fecha Hora', $this->dataset);
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
            // View column for sub_total field
            //
            $column = new CurrencyViewColumn('sub_total', 'sub_total', 'Sub Total', $this->dataset);
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
            // View column for utilidad_en_venta field
            //
            $column = new CurrencyViewColumn('utilidad_en_venta', 'utilidad_en_venta', 'Utilidad En Venta', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
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
            $result->SetTotal('utilidad_en_venta', PredefinedAggregate::$Sum);
            
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
            $this->setPrintListAvailable(false);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(false);
            $this->setExportListAvailable(array());
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array());
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            
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
        $Page = new all_facturas_ventasPage("all_facturas_ventas", "all_facturas_ventas.php", GetCurrentUserGrantForDataSource("all_facturas_ventas"), 'UTF-8');
        $Page->SetTitle('All Facturas Ventas');
        $Page->SetMenuLabel('Ventas');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("all_facturas_ventas"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

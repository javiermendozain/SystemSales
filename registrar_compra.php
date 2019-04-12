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
    
    
    
    class public_registrar_compra_public_detalle_factura_compraPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."detalle_factura_compra"');
            $field = new IntegerField('iddetalle', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new IntegerField('cantidad');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idproducto', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('precio_costo');
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('idfactura_compra', 'public.registrar_compra', new IntegerField('idfactura_compra', null, null, true), new StringField('num_factura', 'idfactura_compra_num_factura', 'idfactura_compra_num_factura_public_registrar_compra'), 'idfactura_compra_num_factura_public_registrar_compra');
            $this->dataset->AddLookupField('idproducto', '(SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter)', new IntegerField('idproducto'), new StringField('name', 'idproducto_name', 'idproducto_name_product_compra'), 'idproducto_name_product_compra');
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
                new FilterColumn($this->dataset, 'idfactura_compra', 'idfactura_compra_num_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'idproducto', 'idproducto_name', $this->RenderText('Producto')),
                new FilterColumn($this->dataset, 'cantidad', 'cantidad', $this->RenderText('Cantidad')),
                new FilterColumn($this->dataset, 'precio_costo', 'precio_costo', $this->RenderText('Precio Costo'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['idfactura_compra'])
                ->addColumn($columns['idproducto'])
                ->addColumn($columns['cantidad'])
                ->addColumn($columns['precio_costo']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('idfactura_compra')
                ->setOptionsFor('idproducto');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
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
            
            $main_editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_idproducto_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('idproducto', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_idproducto_name_search');
            
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
            
            $main_editor = new TextEdit('precio_costo_edit');
            $main_editor->SetPlaceholder($this->RenderText('NO USE ( $ . , )'));
            
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
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_name', 'Producto', $this->dataset);
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
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
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
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_name', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
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
            // Edit column for idfactura_compra field
            //
            $editor = new ComboBox('idfactura_compra_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
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
            $editColumn = new LookUpEditColumn(
                'N° Factura', 
                'idfactura_compra', 
                $editor, 
                $this->dataset, 'idfactura_compra', 'num_factura', $lookupDataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for idproducto field
            //
            $editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'product_compra');
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Producto', 'idproducto', 'idproducto_name', 'edit_idproducto_name_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
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
            
            //
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $ . , )'));
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
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for idproducto field
            //
            $editor = new AutocompleteComboBox('idproducto_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $selectQuery = 'SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'product_compra');
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Producto', 'idproducto', 'idproducto_name', 'insert_idproducto_name_search', $editor, $this->dataset, $lookupDataset, 'idproducto', 'name', '');
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
            // Edit column for precio_costo field
            //
            $editor = new TextEdit('precio_costo_edit');
            $editor->SetPlaceholder($this->RenderText('NO USE ( $ . , )'));
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
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_name', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddPrintColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
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
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_name', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddExportColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
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
            // View column for iddetalle field
            //
            $column = new TextViewColumn('iddetalle', 'iddetalle', 'Iddetalle', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra_num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for name field
            //
            $column = new TextViewColumn('idproducto', 'idproducto_name', 'Producto', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for cantidad field
            //
            $column = new NumberViewColumn('cantidad', 'cantidad', 'Cantidad', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(2);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('.');
            $grid->AddCompareColumn($column);
            
            //
            // View column for precio_costo field
            //
            $column = new CurrencyViewColumn('precio_costo', 'precio_costo', 'Precio Costo', $this->dataset);
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
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        
        public function GetEnableModalGridInsert() { return true; }
        public function GetEnableModalSingleRecordView() { return true; }
        
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
            $selectQuery = 'SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'product_compra');
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_idproducto_name_search', 'idproducto', 'name', null);
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
            
            $selectQuery = 'SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'product_compra');
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_idproducto_name_search', 'idproducto', 'name', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $selectQuery = 'SELECT 
             CONCAT( productos.nombre, \', \',
              caracteristicas.marca) as name,
              productos.idproducto
            FROM 
              public.productos, 
              public.caracteristicas
            WHERE 
              caracteristicas.idcaracter = productos.idcaracter';
            $insertQuery = array();
            $updateQuery = array();
            $deleteQuery = array();
            $lookupDataset = new QueryDataset(
              PgConnectionFactory::getInstance(), 
              GetConnectionOptions(),
              $selectQuery, $insertQuery, $updateQuery, $deleteQuery, 'product_compra');
            $field = new StringField('name');
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('idproducto');
            $lookupDataset->AddField($field, true);
            $lookupDataset->setOrderByField('name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_idproducto_name_search', 'idproducto', 'name', null);
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
            if($rowData['cantidad']<=0){
            $cancel=true;
            $message='LA CANTIDAD INGRESADA, ESTA INCORECTA*';
            }
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include("conexion.php");
            
            $sql='UPDATE public.productos
               SET   precio_costo='.$rowData['precio_costo'].', stock=stock+'.$rowData['cantidad'].' 
             WHERE productos.idproducto='.$rowData['idproducto'].';';
              $rs=pg_query($conn,$sql);
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
            include("conexion.php");
            
            $sql='UPDATE public.productos
               SET   stock=stock-'.$rowData['cantidad'].'
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
    
    
    
    class public_registrar_compraPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."registrar_compra"');
            $field = new StringField('num_factura');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new IntegerField('estado');
            $this->dataset->AddField($field, false);
            $field = new StringField('id_proveedor');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateField('fecha_de_compra');
            $this->dataset->AddField($field, false);
            $field = new DateField('fecha_de_pago');
            $this->dataset->AddField($field, false);
            $field = new IntegerField('idfactura_compra', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $this->dataset->AddLookupField('id_proveedor', 'public.proveedor', new StringField('nit_cc'), new StringField('nombre_comercial', 'id_proveedor_nombre_comercial', 'id_proveedor_nombre_comercial_public_proveedor'), 'id_proveedor_nombre_comercial_public_proveedor');
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
                new FilterColumn($this->dataset, 'num_factura', 'num_factura', $this->RenderText('N° Factura')),
                new FilterColumn($this->dataset, 'estado', 'estado', $this->RenderText('Estado')),
                new FilterColumn($this->dataset, 'id_proveedor', 'id_proveedor_nombre_comercial', $this->RenderText('Proveedor')),
                new FilterColumn($this->dataset, 'fecha_de_compra', 'fecha_de_compra', $this->RenderText('Fecha De Compra')),
                new FilterColumn($this->dataset, 'fecha_de_pago', 'fecha_de_pago', $this->RenderText('Fecha Limite De Pago')),
                new FilterColumn($this->dataset, 'idfactura_compra', 'idfactura_compra', $this->RenderText('Idfactura Compra'))
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['num_factura'])
                ->addColumn($columns['estado'])
                ->addColumn($columns['id_proveedor'])
                ->addColumn($columns['fecha_de_compra'])
                ->addColumn($columns['fecha_de_pago']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('id_proveedor')
                ->setOptionsFor('fecha_de_compra')
                ->setOptionsFor('fecha_de_pago');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('num_factura_edit');
            $main_editor->SetMaxLength(15);
            
            $filterBuilder->addColumn(
                $columns['num_factura'],
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
            
            $main_editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $main_editor->addChoice($this->RenderText('1'), $this->RenderText('CANCELADA'));
            $main_editor->addChoice($this->RenderText('2'), $this->RenderText('PENDIENTE_PAGO'));
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
            
            $main_editor = new AutocompleteComboBox('id_proveedor_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_id_proveedor_nombre_comercial_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_proveedor', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_id_proveedor_nombre_comercial_search');
            
            $text_editor = new TextEdit('id_proveedor');
            
            $filterBuilder->addColumn(
                $columns['id_proveedor'],
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
            
            $main_editor = new DateTimeEdit('fecha_de_compra_edit', false, 'Y-m-d ');
            
            $filterBuilder->addColumn(
                $columns['fecha_de_compra'],
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
            
            $main_editor = new DateTimeEdit('fecha_de_pago_edit', false, 'Y-m-d');
            
            $filterBuilder->addColumn(
                $columns['fecha_de_pago'],
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
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserGrantForDataSource('public.registrar_compra.public.detalle_factura_compra')->HasViewGrant() && $withDetails)
            {
            //
            // View column for public_registrar_compra_public_detalle_factura_compra detail
            //
            $column = new DetailColumn(array('idfactura_compra'), 'public.registrar_compra.public.detalle_factura_compra', 'public_registrar_compra_public_detalle_factura_compra_handler', $this->dataset, 'Detalle Factura Compra');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura', $this->dataset);
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
            
            //
            // View column for nombre_comercial field
            //
            $column = new TextViewColumn('id_proveedor', 'id_proveedor_nombre_comercial', 'Proveedor', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_de_compra field
            //
            $column = new DateTimeViewColumn('fecha_de_compra', 'fecha_de_compra', 'Fecha De Compra', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fecha_de_pago field
            //
            $column = new DateTimeViewColumn('fecha_de_pago', 'fecha_de_pago', 'Fecha Limite De Pago', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription($this->RenderText(''));
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nombre_comercial field
            //
            $column = new TextViewColumn('id_proveedor', 'id_proveedor_nombre_comercial', 'Proveedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_de_compra field
            //
            $column = new DateTimeViewColumn('fecha_de_compra', 'fecha_de_compra', 'Fecha De Compra', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fecha_de_pago field
            //
            $column = new DateTimeViewColumn('fecha_de_pago', 'fecha_de_pago', 'Fecha Limite De Pago', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for estado field
            //
            $editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('CANCELADA'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('PENDIENTE_PAGO'));
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_proveedor field
            //
            $editor = new AutocompleteComboBox('id_proveedor_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."proveedor"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_comercial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('n_cuenta');
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
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_comercial', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Proveedor', 'id_proveedor', 'id_proveedor_nombre_comercial', 'edit_id_proveedor_nombre_comercial_search', $editor, $this->dataset, $lookupDataset, 'nit_cc', 'nombre_comercial', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_de_compra field
            //
            $editor = new DateTimeEdit('fecha_de_compra_edit', false, 'Y-m-d ');
            $editColumn = new CustomEditColumn('Fecha De Compra', 'fecha_de_compra', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fecha_de_pago field
            //
            $editor = new DateTimeEdit('fecha_de_pago_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Fecha Limite De Pago', 'fecha_de_pago', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for num_factura field
            //
            $editor = new TextEdit('num_factura_edit');
            $editor->SetMaxLength(15);
            $editColumn = new CustomEditColumn('N° Factura', 'num_factura', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for estado field
            //
            $editor = new ComboBox('estado_edit', $this->GetLocalizerCaptions()->GetMessageString('PleaseSelect'));
            $editor->addChoice($this->RenderText('1'), $this->RenderText('CANCELADA'));
            $editor->addChoice($this->RenderText('2'), $this->RenderText('PENDIENTE_PAGO'));
            $editColumn = new CustomEditColumn('Estado', 'estado', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_proveedor field
            //
            $editor = new AutocompleteComboBox('id_proveedor_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."proveedor"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_comercial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('n_cuenta');
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
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_comercial', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Proveedor', 'id_proveedor', 'id_proveedor_nombre_comercial', 'insert_id_proveedor_nombre_comercial_search', $editor, $this->dataset, $lookupDataset, 'nit_cc', 'nombre_comercial', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_de_compra field
            //
            $editor = new DateTimeEdit('fecha_de_compra_edit', false, 'Y-m-d ');
            $editColumn = new CustomEditColumn('Fecha De Compra', 'fecha_de_compra', $editor, $this->dataset);
            $editColumn->SetInsertDefaultValue($this->RenderText('%CURRENT_DATE%'));
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fecha_de_pago field
            //
            $editor = new DateTimeEdit('fecha_de_pago_edit', false, 'Y-m-d');
            $editColumn = new CustomEditColumn('Fecha Limite De Pago', 'fecha_de_pago', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nombre_comercial field
            //
            $column = new TextViewColumn('id_proveedor', 'id_proveedor_nombre_comercial', 'Proveedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_de_compra field
            //
            $column = new DateTimeViewColumn('fecha_de_compra', 'fecha_de_compra', 'Fecha De Compra', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fecha_de_pago field
            //
            $column = new DateTimeViewColumn('fecha_de_pago', 'fecha_de_pago', 'Fecha Limite De Pago', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nombre_comercial field
            //
            $column = new TextViewColumn('id_proveedor', 'id_proveedor_nombre_comercial', 'Proveedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_de_compra field
            //
            $column = new DateTimeViewColumn('fecha_de_compra', 'fecha_de_compra', 'Fecha De Compra', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fecha_de_pago field
            //
            $column = new DateTimeViewColumn('fecha_de_pago', 'fecha_de_pago', 'Fecha Limite De Pago', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for num_factura field
            //
            $column = new TextViewColumn('num_factura', 'num_factura', 'N° Factura', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for estado field
            //
            $column = new TextViewColumn('estado', 'estado', 'Estado', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nombre_comercial field
            //
            $column = new TextViewColumn('id_proveedor', 'id_proveedor_nombre_comercial', 'Proveedor', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_de_compra field
            //
            $column = new DateTimeViewColumn('fecha_de_compra', 'fecha_de_compra', 'Fecha De Compra', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fecha_de_pago field
            //
            $column = new DateTimeViewColumn('fecha_de_pago', 'fecha_de_pago', 'Fecha Limite De Pago', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for idfactura_compra field
            //
            $column = new TextViewColumn('idfactura_compra', 'idfactura_compra', 'Idfactura Compra', $this->dataset);
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
        
        public function GetEnableModalGridInsert() { return true; }
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
            $detailPage = new public_registrar_compra_public_detalle_factura_compraPage('public_registrar_compra_public_detalle_factura_compra', $this, array('idfactura_compra'), array('idfactura_compra'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserGrantForDataSource('public.registrar_compra.public.detalle_factura_compra'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('public.registrar_compra.public.detalle_factura_compra'));
            $detailPage->SetTitle('Detalle Factura Compra');
            $detailPage->SetMenuLabel('Detalle Factura Compra');
            $detailPage->SetHeader(GetPagesHeader());
            $detailPage->SetFooter(GetPagesFooter());
            $detailPage->SetHttpHandlerName('public_registrar_compra_public_detalle_factura_compra_handler');
            $handler = new PageHTTPHandler('public_registrar_compra_public_detalle_factura_compra_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."proveedor"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_comercial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('n_cuenta');
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
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_comercial', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_id_proveedor_nombre_comercial_search', 'nit_cc', 'nombre_comercial', null);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."proveedor"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_comercial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('n_cuenta');
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
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_comercial', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_id_proveedor_nombre_comercial_search', 'nit_cc', 'nombre_comercial', null);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                PgConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '"public"."proveedor"');
            $field = new StringField('nit_cc');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('nombre_comercial');
            $lookupDataset->AddField($field, false);
            $field = new StringField('n_cuenta');
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
            $field = new StringField('idciudad');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('nombre_comercial', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_id_proveedor_nombre_comercial_search', 'nit_cc', 'nombre_comercial', null);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
            if($fieldName=='estado'){
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
            if($rowData['estado']==1){
            $rowData['fecha_de_pago']=$rowData['fecha_de_compra'];
            }
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
        $Page = new public_registrar_compraPage("public_registrar_compra", "registrar_compra.php", GetCurrentUserGrantForDataSource("public.registrar_compra"), 'UTF-8');
        $Page->SetTitle('Registrar Compra');
        $Page->SetMenuLabel('Compra');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("public.registrar_compra"));
        GetApplication()->SetCanUserChangeOwnPassword(
            !function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	

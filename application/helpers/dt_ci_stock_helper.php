<?php
/**
 * Created by PhpStorm.
 * User: dt-user04
 * Date: 27-07-2018
 * Time: 15:55
 */

/**
 * @param $stockData
 * @return bool
 */
function stockInOut($stockData)
{
    $CI = &get_instance();
    $CI->load->model('Mdl_stock_balance');
    $CI->load->model('Mdl_item');

    $stockData['type']          = isset($stockData['type']) ? $stockData['type'] : "";
    $stockData['qty']           = isset($stockData['qty']) ? $stockData['qty'] : "";
    $stockData['rate']          = isset($stockData['rate']) ? $stockData['rate'] : "";
    $stockData['item_id']       = isset($stockData['item_id']) ? $stockData['item_id'] : "";
    $stockData['warehouse_id']  = isset($stockData['warehouse_id']) ? $stockData['warehouse_id'] : "";

    $stockLedgerData = [];
    $stockLedgerData['stock_ledger_id']        = '';
    $stockLedgerData['transaction_type']       = $stockData['type'];
    $stockLedgerData['transaction_type_id']    = isset($stockData['type_id']) ? $stockData['type_id']: "";
    $stockLedgerData['item_id']                = $stockData['item_id'];
    $stockLedgerData['serial_number_id']       = isset($stockData['serial_number_id']) ? $stockData['serial_number_id'] : "";
    $stockLedgerData['batch_id']               = isset($stockData['batch_id']) ? $stockData['batch_id'] : "";
    $stockLedgerData['warehouse_id']           =  $stockData['warehouse_id'];
    $stockLedgerData['fiscal_year_id']         = isset($stockData['fiscal_year_id']) ? $stockData['fiscal_year_id'] : "";
    $stockLedgerData['unit_id']                = isset($stockData['unit_id']) ? $stockData['unit_id'] : "";
    $stockLedgerData['actual_qty']             = $stockData['qty'];
    $total                                     = $stockData['qty'] * $stockData['rate'];
    $stockLedgerData['stock_value']            = $total;
    $stockLedgerData['stock_value_difference'] = 0.00;
    $stockLedgerData['is_cancelled']           = 0;
    $stockLedgerData['posting_date']           = date('Y-m-d');
    $stockLedgerData['posting_time']           = date('H:i:s');

    $transactionType = 'debit';

    $transactions = ['purchase_order' => 'credit', 'material_request' => 'debit', 'purchase_receipt' => 'credit', 'purchase_invoice' => 'credit',
                    'deliver_notes' => 'debit', 'sales_invoice' => 'debit', 'sales_order' => 'debit', 'sales_receipt' => 'debit'];

    if (array_key_exists($stockData['type'], $transactions)) {
        $transactionType = $transactions[$stockData['type']];
        $newStock = [];
        $currentStock = array();


        # get current stock
        $currentStock = $CI->Mdl_stock_balance->getStockBalanceByWarehouse($stockData['item_id'], $stockData['warehouse_id']);

        $currentStock['rate'] = $stockData['rate'];


        if (!isset($currentStock['stock_balance_id'])) {

            $currentStock = $CI->Mdl_item->getItemData($stockData['item_id']);
printArray($currentStock);

            if(!empty($currentStock)){
                if($currentStock['opening_stock'] == 0 && $currentStock['opening_stock'] >= 0 ){

                    $response = array(
                        'success' => false,
                        'msg'    => $currentStock['item_name'].'('.$currentStock['item_code'].') Stock Not Available'
                    );

                    return $response;
                    exit;
                }
            }
            $currentStock['actual_qty'] = $currentStock['opening_stock'];
            $currentStock['rate'] = $currentStock['standard_selling_rate'];
            $newStock['stock_balance_id'] = '';

        } else {

            $newStock['stock_balance_id'] = $currentStock['stock_balance_id'];
        }

        $stockLedgerData['valuation_rate'] = $currentStock['rate'];

        if ($transactionType == 'debit') {
            $newQty = $currentStock['actual_qty'] - $stockData['qty'];
            $stockLedgerData['outgoing_rate'] = $stockData['rate'];
            $stockLedgerData['qty_after_transaction'] = $newQty;

        } else {
            $newQty = $currentStock['actual_qty'] + $stockData['qty'];
            $stockLedgerData['incoming_rate'] = $stockData['rate'];
            $stockLedgerData['qty_after_transaction'] = $newQty;
        }


        #insert into stock ledger
        $CI->Mdl_stock_balance->insertUpdateRecord($stockLedgerData, 'stock_ledger_id', 'tbl_stock_ledger');



        #current stock insert/update
        $newStock = ['actual_qty' => $newQty, 'item_id' => $stockData['item_id'] ,'warehouse_id' => $stockData['warehouse_id'], 'stock_balance_id' =>  $newStock['stock_balance_id'], 'created_at' => date('Y-m-d H:i:s')];

        $CI->Mdl_stock_balance->insertUpdate($newStock, 'stock_balance_id', 'tbl_stock_balance');

        return true;
    } else {
        return false;
    }
}

/**
 * @param $moduleName
 * @param $moduleId
 * @param $dependentModule
 * @return mixed
 */
function checkDependency($moduleName, $moduleId, $dependentModule)
{
    # current instance
    $CI =& get_instance();

    # check dependency
    switch ($dependentModule) {
        # purchase order
        case  'PurchaseOrder':
            $CI->load->model('Mdl_purchase_order');
            $status = $CI->Mdl_purchase_order->checkDependency('tbl_purchase_order', $moduleName, $moduleId);
            break;
        # purchase receipt
        case  'PurchaseReceipt':
            $CI->load->model('Mdl_purchase_receipt');
            $status = $CI->Mdl_purchase_order->checkDependency('tbl_purchase_receipt', $moduleName, $moduleId);
            break;
        # purchase order
        case  'Quotation':
            $CI->load->model('Mdl_quotation');
            $status = $CI->Mdl_quotation->checkDependency('tbl_quotation', $moduleName, $moduleId);
            break;
        # purchase order
        case  'SalesOrder':
            $CI->load->model('Mdl_sales_order');
            $status = $CI->Mdl_sales_order->checkDependency('tbl_sales_order', $moduleName, $moduleId);
            break;
        default:
            break;
    }
    return $status;
}

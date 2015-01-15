<?php
/**
 * @Author G. M. Shaharia Azam <shaharia.azam@gmail.com>
 * @URI http://github.com/shahariaazam
 * @GitHub https://github.com/shahariaazam/openerp-php-connector
 *
 * OpenERP PHP Connector with PHP web application through XML-RPC. You can authenticated user from OpenERP
 * in your application and this library will help you to create records for OpenERP models, write new dataset
 * in OpenERP, Read records from the OpenERP model, Search records with all criteria from OpenERP.
 *
 * The idea came from to build startup company management system for my own Preview ICT Limited company.
 * And the coding structure, logic building idea came from another open source OpenERP PHP connector library
 * https://bitbucket.org/simbigo/openerp-api
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 OpenERP PHP Connector
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace OpenErp\Modules\Sales;


class Customer extends Sales
{
    private $allFieldListDefault = array(
        'display_name', 'name', 'email', 'title', 'company_id', 'street', 'zip', 'city',
        'property_account_position', 'property_stock_customer', 'property_product_pricelist', 'opt_out',
        'parent_id', 'last_reconciliation_date', 'fax', 'child_ids', 'unreconciled_aml_ids','property_delivery_carrier',
        'property_account_receivable', 'latest_followup_level_id', 'message_follower_ids', 'payment_next_action_date',
        'task_ids', 'event_ids', 'country_id', 'notification_email_send', 'debit', 'supplier', 'ref', 'picking_warn',
        'latest_followup_date', 'street2', 'payment_amount_due', 'active', 'claims_ids', 'property_product_pricelist_purchase',
        'credit', 'payment_next_action', 'payment_note', 'comment', 'sale_warn', 'purchase_warn', 'image', 'user_id',
        'event_registration_ids', 'type', 'website', 'picking_warn_msg', 'phone', 'payment_responsible_id', 'customer', 'state_id',
        'invoice_warn_msg', 'function', 'use_parent_address', 'sale_warn_msg', 'message_ids', 'invoice_warn', 'property_account_payable',
        'property_stock_supplier', 'is_company', 'bank_ids', 'section_id', 'property_supplier_payment_term', 'date', 'lang', 'credit_limit',
        'purchase_warn_msg', 'mobile', 'property_payment_term', 'category_id'
    );
    private $customFieldListDefault = array(
        'display_name', 'name', 'email', 'title', 'company_id', 'street', 'zip', 'city',
        'fax', 'country_id', 'street2', 'active', 'image', 'user_id',
        'type', 'website', 'phone','customer', 'state_id',
        'is_company','date', 'lang','mobile','category_id'
    );

    public function lists($ids = array(), $fields = array())
    {
        if(!is_array($ids) && !is_array($fields))
        {
            return array();
        }

        $resultRead = $this->erp->read('res.partner', $ids, $fields); // return array of records
        return $resultRead;
    }

    /**
     * @param $id
     * @param array $fields
     * @return null
     */
    public function read($model='res.partner',$id, $fields = array())
    {
        if(!isset($id))
        {
            return null;
        }

        if(is_array($fields) && !sizeof($fields) > 0)
        {
            $fields = $this->customFieldListDefault;
        }

        if($fields == 'all')
        {
            $fields = $this->allFieldListDefault;
        }

        $details = $this->erp->read($model, array($id), $fields);
        return $details[0];
    }

    /**
     * Suppose the return array has country_id => array[data]=> array[value] => [0 => '', 1 => 'Original Value']
     * In this situatio we can easily extract the exact data. I am lazy so I made this handy function.
     *
     * @param $key
     * @return mixed
     */
    private function getValueFromKey($key)
    {
        foreach($key['data']['value'][1] as $key => $value){
            return $value;
        }
    }

    public function create($model = 'res.partner', $data = array())
    {
        $create=$this->erp->create($model= 'res.partner', $data);
        return $create;
    }


    public function update($id, $data = array())
    {

    }
} 
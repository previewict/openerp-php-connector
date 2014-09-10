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

namespace OpenErp\Modules\HumanResource;

class User extends HumanResource
{

    private $listDefaultFields = array('property_account_position', 'ref_companies', 'alias_defaults', 'sale_order_count', 'sel_groups_3_42_43', 'contact_address', 'property_product_pricelist', 'message_summary', 'tz', 'opt_out', 'title', 'company_id', 'sel_groups_10', 'last_reconciliation_date', 'employee', 'sel_groups_30_31', 'fax', 'purchase_order_count', 'child_ids', 'company_ids', 'unreconciled_aml_ids', 'image_medium', 'property_delivery_carrier', 'property_account_receivable', 'payment_earliest_due_date', 'contract_ids', 'survey_id', 'street', 'task_ids', 'event_ids', 'alias_name', 'country_id', 'tz_offset', 'notification_email_send', 'supplier', 'email', 'street2', 'opportunity_ids', 'alias_model_id', 'active', 'display_name', 'signup_expiration', 'claims_ids', 'country', 'credit', 'payment_next_action', 'payment_amount_due', 'login', 'message_unread', 'payment_note', 'comment', 'groups_id', 'purchase_warn', 'color', 'user_id', 'zip', 'alias_force_thread_id', 'sel_groups_84', 'event_registration_ids', 'type', 'in_group_29', 'in_group_28', 'vat', 'in_group_21', 'in_group_20', 'in_group_23', 'in_group_22', 'in_group_24', 'phone', 'payment_responsible_id', 'customer', 'sale_order_ids', 'login_date', 'invoice_warn_msg', 'share', 'signup_type', 'id', 'new_password', 'message_ids', 'speaker', 'property_stock_supplier', 'is_company', 'bank_ids', 'default_section_id', 'date', 'purchase_warn_msg', 'sel_groups_47_48', 'category_id', 'user_email', 'ean13', 'signup_valid', 'alias_id', 'in_group_50', 'in_group_51', 'in_group_52', 'signup_url', 'in_group_55', 'in_group_56', 'parent_id', 'sel_groups_45_46', 'alias_domain', 'menu_id', 'user_ids', 'password', 'name', 'debit_limit', 'signup_token', 'latest_followup_level_id', 'payment_amount_overdue', 'commercial_partner_id', 'message_follower_ids', 'employee_ids', 'payment_next_action_date', 'partner_id', 'in_group_9', 'in_group_6', 'in_group_4', 'in_group_5', 'debit', 'ref', 'action_id', 'picking_warn', 'latest_followup_date', 'state', 'sel_groups_80_81', 'property_product_pricelist_purchase', 'alias_user_id', 'signature', 'sale_warn', 'image', 'invoice_ids', 'in_group_16', 'in_group_17', 'in_group_11', 'in_group_13', 'in_group_18', 'in_group_19', 'city', 'phonecall_ids', 'message_is_follower', 'sel_groups_82_83', 'in_group_86', 'in_group_85', 'opportunity_count', 'function', 'picking_warn_msg', 'latest_followup_level_id_without_lit', 'image_small', 'birthdate', 'purchase_order_ids', 'has_image', 'state_id', 'website', 'sel_groups_1_2', 'in_group_38', 'in_group_39', 'in_group_36', 'in_group_37', 'in_group_34', 'in_group_35', 'in_group_32', 'in_group_33', 'use_parent_address', 'sel_groups_25_26_27', 'meeting_ids', 'invoice_warn', 'property_account_payable', 'section_id', 'property_payment_term', 'in_group_49', 'property_supplier_payment_term', 'property_stock_customer', 'in_group_40', 'in_group_44', 'lang', 'credit_limit', 'meeting_count', 'sel_groups_7_12_8', 'mobile', 'sel_groups_53_54', 'sale_warn_msg');

    public $userDetails;

    public function lists($ids = array(), $fields = array())
    {
        if(!is_array($ids) && !is_array($fields))
        {
            return array();
        }

        $resultRead = $this->erp->read('res.users', $ids, $fields); // return array of records
        return $resultRead;
    }

    /**
     * @param $id
     * @param array $fields
     * @return null
     */
    public function read($id, $fields = array())
    {
        if(!isset($id))
        {
            return null;
        }

        if(!sizeof($fields) > 0)
        {
            $fields = $this->listDefaultFields;
        }

        $details = $this->erp->read('res.users', array($id), $fields);
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

    public function create($data = array())
    {

    }

    public function search()
    {

    }

    public function update($id, $data = array())
    {

    }
} 
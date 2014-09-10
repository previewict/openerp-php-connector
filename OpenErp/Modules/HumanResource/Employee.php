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

class Employee extends HumanResource
{

    protected $listDefaultFields = array('address_id', 'code', 'ssnid', 'coach_id', 'resource_id', 'color', 'leave_date_from', 'image', 'company_id', 'marital', 'uom_id', 'manager', 'attendance_access', 'current_leave_state', 'id', 'identification_id', 'children', 'evaluation_date', 'contract_id', 'city', 'time_efficiency', 'total_wage', 'user_id', 'job_id', 'work_phone', 'current_leave_id', 'country_id', 'journal_id', 'medic_exam', 'bank_account_id', 'parent_id', 'state', 'last_login', 'category_ids', 'vehicle', 'evaluation_plan_id', 'active', 'department_id', 'otherid', 'mobile_phone', 'last_sign', 'child_ids', 'slip_ids', 'birthday', 'leave_date_to', 'name_related', 'sinid', 'calendar_id', 'work_email', 'remaining_leaves', 'name', 'work_location', 'image_medium', 'contract_ids', 'product_id', 'gender', 'notes', 'image_small', 'address_home_id', 'resource_type', 'place_of_birth', 'login', 'passport_id', 'vehicle_distance');

    public function lists($ids = array(), $fields = array())
    {
        if(!is_array($ids) && !is_array($fields))
        {
            return array();
        }

        $resultRead = $this->erp->read('hr.employee', $ids, array('name')); // return array of records
        return $resultRead;
    }

    /**
     * Get details of a specific user
     *
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

        $details = $this->erp->read('hr.employee', array($id), $fields);
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

    public function search($criteria = array(), $offset = 0, $limit = 100)
    {
        if(!sizeof($criteria) > 0){
            $criteria= array('active', '=', true);
        }
        $employees = $this->erp->search('hr.employee', array($criteria), $offset, $limit); // return ID array

        if(sizeof($employees) > 0){
            return $employees;
        }

        return null;
    }

    public function update($id, $data = array())
    {

    }

    public function readByLoginId($loginId, $fields = array())
    {
        $employeeId = $this->search(array('login', '=', $loginId));
        return $this->read($employeeId[0], $fields);
    }
} 
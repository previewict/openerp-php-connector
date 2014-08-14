<?php
session_start();

require_once 'config.php';
require_once 'vendor/autoload.php';

use OpenErp\OpenErp;
$erp = new OpenERP('http://office.previewict.com', 'utf-8');
$result = $erp->login(DATABASE, USERNAME, PASSWORD); // return user id, if success

var_dump($result);

//create a new partner
$partner = ['name' => 'Foo', 'email' => 'foo@bar.com'];
$resultCreate = $erp->create('res.partner', $partner); // return record ID, if it created

var_dump($resultCreate);

$offset = 0; // default
$limit = 1000; // default
$criteria= [['name', '=', 'John'], ['email', '=', 'john@example.com']];
$resultSearch = $erp->search('res.partner', $criteria, $offset, $limit); // return ID array
var_dump($resultSearch);

$readColumns = ['name', 'email']; // default [] equal 'SELECT * ...'
$ids = [1, 2];
$resultRead = $erp->read('res.partner', $ids, $readColumns); // return array of records
var_dump($resultRead);
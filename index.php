<?php
session_start();

require_once 'config.php';
require_once 'vendor/autoload.php';
use Erp\Erp;

$oe = new Erp("http://office.previewict.com:8080", "openerp_idesk");

// login with $url and $dbname
$oe->login("administrator", "ltqpsmr7");

echo "Logged in (session id: " . $oe->session_id . ")";

// Query with direct object method which are mapped to json-rpc calls
$partners = $oe->read(array(
    'model' => 'res.partner',
    'fields' => array('id'),
));

var_dump($partners); die();

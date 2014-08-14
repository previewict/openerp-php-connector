![OpenErp Logo](https://sigrhe.dgae.mec.pt/openerp/static/images/openerp_small.png)

OpenERP PHP Connector
=====================

Connect your PHP driven web application with OpenERP through PHP XML RPC

#####Installation & Usage
To get started you need to install this library via **[Composer](http://getcomposer.org)**. If you have ready **composer.json** file then just add `"previewict/openerp-php-connector": "dev-master"` as a dependency. Or if you don't have any composer.json ready then copy/paste the below code and create your **composer.json** file.

```json
{
    "name": "YourProjectName",

    "description": "YourProjectDescription",

    "require": {
        "php": ">=5.3.0",
        "previewict/openerp-php-connector": "dev-master"
    }
}
```
Now just run `composer update` command and this **openerp php connector** library will be cloned into your **vendor** directory.


Now I am assuming that you already cloned this repository via composer.json or direct clone from GitHub. Now to use this you have to write down the following codes on your script.

```php
require_once 'vendor/autoload.php';

use OpenErp\Modules\Sales\Customer;

/**
* USERNAME = Your OpenERP username. i.e: admin
* PASSWORD = Your OpenERP password. i.e: 123456
* DATABASE = Your OpenERP database name. i.e: openerp_demo
* SERVER = Your OpenERP Server. i.e: http://yourOpenERPServer.com
*/
$sales = new Customer(USERNAME, PASSWORD, DATABASE, SERVER);
$result = $sales->getCustomer($customerID);   // a customer ID to get details from your OpenERP. i.e: 10
var_dump($result); die();
```

Now it will give you a PHP array. And now you can use the data however you want.

- [Submit Issues](https://github.com/previewict/openerp-php-connector/issues/new)
  Submit issues if you found anything wrong or face any problem to use this library
- [Instant Support](mailto:shaharia@previewict.com)
- [Documentation/Wiki](https://github.com/previewict/openerp-php-connector/wiki)

Please [Form](https://github.com/previewict/openerp-php-connector/fork) and send me Pull request if you added something useful for this. Happy PHP-ing!

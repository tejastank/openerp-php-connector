OpenERP/ODOO-PHP-Connector 
=========================

[Its support SSL enabled Odoo/OpenERP Instances too.]

XMLRPC Connecotor communicate with OpenERP Server 6 and 7, 8.0 version too.
One of tested and well worked PHP Connector. AGPL V3

We had been successful delived many mobile apps, eCommerce apps and many other business apps, by using this simplest, powerful connector. 

License: AGPL V3, No exception, by using this lib, you can keep private your code/modules.

Kindly contact us for integration & implementation, We are happy to serve you.

Powered By SnippetBucket.com
============================
Tejas Tank.

Email: snippetbucket@gmail.com or tejas.tank.mca@gmail.com

website: http://www.snippetbucket.com/


Example Code
=======

$instance = new OpenERP();

$x = $instance->login("demo", "demo", "demo", "http://demo.snippetbucket.com/xmlrpc/");

$fields = array('id','name','model');

$ids = range(1,2);

$partners = $instance->read($ids, $fields, "res.partner");

print_r($partners);


# Below example to execute workflow.
===========
example to use workflow,

$x = $rpc->login(....);

if($x){

     $rpc->workflow('sale.order', 'order_confirm',  $sale_order_id);
     $rpc->workflow('sale.order', 'manual_invoice',  $sale_order_id);
     .....
     // this way can execute any workflow or workflow signals...
}
// Similar button_click method also added.


Visit : www.SnippetBucket.com

Contact us for support
======================
http://www.snippetbucket.com/







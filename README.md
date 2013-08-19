openerp-php-connector
=====================

XMLRPC Connecotor communicate with OpenERP Server 6 and 7 version both.

-- One of tested and well worked PHP Connector.


Example
=======


$instance = new OpenERP();

$x = $instance->login("demo", "demo", "demo", "http://demo.snippetbucket.com/xmlrpc/");
$fields = array('id','name','model');
$ids = range(1,2);
$partners = $instance->read($ids, $fields, "res.partner");


print_r($partners);




Powered By SnippetBucket.com
============================
Tejas Tank
Email: tejas.tank.mca@gmail.com
website: http://www.snippetbucket.com/


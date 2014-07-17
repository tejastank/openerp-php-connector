openerp-php-connector
=====================

XMLRPC Connecotor communicate with OpenERP Server 6 and 7 version both.
One of tested and well worked PHP Connector. AGPL V3

License: AGPL V3, No exception, by using this lib, you can keep private your code/modules.


Example
=======

$instance = new OpenERP();

$x = $instance->login("demo", "demo", "demo", "http://demo.snippetbucket.com/xmlrpc/");

$fields = array('id','name','model');

$ids = range(1,2);

$partners = $instance->read($ids, $fields, "res.partner");

print_r($partners);

Contact us for support
======================
website: http://www.snippetbucket.com/



Powered By SnippetBucket.com
============================
Tejas Tank.

Email: tejas.tank.mca@gmail.com

website: http://www.snippetbucket.com/



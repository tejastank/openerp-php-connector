<?php
include_once('openerp.class.php');

print "<pre/>\nOpenERP PHP connector : It support version 5 and 6.0<br/>\n Author : Tejas Tank, Tejas.tank.mca@gmail.com\n";
print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n";

$rpc = new OpenERP();

$rpc->login("admin", "admin", "6_0_11531_all", "http://6_0_11531.runbot.openerp.com:9524/xmlrpc/");
//echo "user id : ", $x->uid;

//echo $x->create($values,"res.partner");
//echo "write at  " , $x->write(array(1,2,3,4,5,6) , $values , "res.partner"  );


$fields = array(
    'id','name','model'
);

$ids = range(0,100);
$partners = $rpc->read($ids, $fields, "ir.model");

$select = "<select name='oe_models' ONCHANGE='location = \"?model=\" +this.options[this.selectedIndex].value;'>";
foreach ($partners as $v){
   // print $v['name']."\n";
   // print_r($v) . "\n";
    $select .= "<option value='".$v['model']."' title='".$v['model']."'>".$v['name']."</option>";
} 
$select .= "</select>";
print $select;




//-------------------------------------

$model =  $_GET['model'];

echo $model;
$fields = array(
    'id','name',
);

$ids = range(0,100);
$partners = $rpc->read($ids, $fields, $model);

echo "\n\n";
foreach ($partners as $v){
    print $v['id']." ".$v['name']."\n";
//    print_r($v) . "\n";
} 


/////print $partners = $x->unlink(array(19), "res.partner");

?>

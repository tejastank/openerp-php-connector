<?php
/* OpenERP PHP connection script. Under GPL V3 , All Rights Are Reserverd , tejas.tank.mca@gmail.com
 *
 * @Author : Tejas L Tank.,             https://twitter.com/snippetbucket
 * @Email : tejas.tank.mca@gmail.com
 * @Country : India
 * @Date : 14 Feb 2011
 * @License : GPL V3
 * @Contact : www.facebook.com/tejaskumar.tank or www.linkedin.com/profile/view?id=48881854
 *
 *
 * OpenERP XML-RPC connections methods are db, common, object , report , wizard
 *
 *
 *
 *
 */
session_start();

include("xmlrpc-2.2.2/lib/xmlrpc.inc");

class OpenERP {

    public $server = "http://localhost:8069/xmlrpc/";
    public $database = "test";
    public $username = "admin"; /*     * * @userid = general name of user which require to login at openerp server */
    public $password = "a";/** @password = password require to login at openerp server * */
    public $uid = "";/**  @uid = once user succesful login then this will asign the user id */

    public function login($username, $password, $database, $server) {

        if ($server) $this->server = $server;
        if ($database) $this->database = $database;
        if ($username) $this->username = $username;
        if ($password) $this->password = $password;

        $sock = new xmlrpc_client($this->server . 'common');
        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($this->database, "string"));
        $msg->addParam(new xmlrpcval($this->username, "string"));
        $msg->addParam(new xmlrpcval($this->password, "string"));

        $resp = $sock->send($msg);
        if($resp->errno > 0 ){
            throw new Exception($resp->errstr);
        }

        //$val = $resp->value();
        //$id = $val->scalarval();
        $this->uid = $resp->value()->me['int'];
        if ( !$this->uid ) {
            throw new Exception( 'Unable to login' );
        }

        return $this->uid; //* userid of succesful login person *//
    }

    public function search($values, $model_name,$offset=0,$max=40, $order="id DESC") {
        $domains = array();
        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';

        $msg = new xmlrpcmsg('execute');

        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("search", "string"));/** method which u like to execute */

        foreach($values as $x){
            if(!empty($x)){
                    array_push( $domains,  new xmlrpcval( 
                                                        array(  new xmlrpcval($x[0], "string" ),
                                                                 new xmlrpcval( $x[1],"string" ),
                                                                 new xmlrpcval( $x[2], xmlrpc_get_type($x[2]) )
                                                              ),
                                                              "array"
                                                       )
                             );
            }
        }

        $msg->addParam(new xmlrpcval($domains, "array")); /* SEARCH DOMAIN */
        $msg->addParam(new xmlrpcval($offset, "int")); /* OFFSET, START FROM */
        $msg->addParam(new xmlrpcval($max, "int")); /* MAX RECORD LIMITS */
        $msg->addParam(new xmlrpcval($order, "string"));
        
        $resp = $client->send($msg);
        
        if ($resp->faultCode())
            return -1; /* if the record is not created  */
        else
            return $resp->value();  /* return new generated id of record */
    }

    public function searchread($values, $model_name, $fields=array(), $offset=0, $max=10, $order = "id DESC", $context=array()) {
        $domains = array();
        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("search", "string"));/** method which u like to execute */

        foreach($values as $x){
            if(!empty($x)){
                    array_push( $domains,  new xmlrpcval( 
                                                        array(  new xmlrpcval($x[0], "string" ),
                                                                 new xmlrpcval( $x[1],"string" ),
                                                                 new xmlrpcval( $x[2], xmlrpc_get_type($x[2]) )
                                                              ),
                                                              "array"
                                                       )
                             );
            }
        }
        $msg->addParam(new xmlrpcval($domains, "array")); /* SEARCH DOMAIN */
        $msg->addParam(new xmlrpcval($offset, "int")); /* OFFSET, START FROM */
        $msg->addParam(new xmlrpcval($max, "int")); /* MAX RECORD LIMITS */
        $msg->addParam(new xmlrpcval($order, "string"));
        
        $resp = $client->send($msg);

        if ($resp->faultCode())
            return -1; /* if the record is not created  */
        else
            return $this->read($resp->value(), $fields, $model_name, $context);  /* return new generated id of record */
    }


    public function create($values, $model_name) {

        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';
        //   ['execute','userid','password','module.name',{values....}]
        $nval = array();
        foreach($values as $k=>$v){
            $nval[$k] = new xmlrpcval( $v, xmlrpc_get_type($v) );
        }
         
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("create", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($nval, "struct"));/** parameters of the methods with values....  */
        
        $resp = $client->send($msg);
        
        if ($resp->faultCode())
            return -1; /* if the record is not created  */
        else
            return $resp->value();  /* return new generated id of record */
    }

    public function write($ids, $values, $model_name) {
        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';
        //   ['execute','userid','password','module.name',{values....}]

        $id_val = array();
        $count = 0;
        foreach ($ids as $id)
            $id_val[$count++] = new xmlrpcval($id, "int");
        $nval = array();
        foreach($values as $k=>$v){
            $nval[$k] = new xmlrpcval( $v, xmlrpc_get_type($v) );
        }

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("write", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($nval, "struct"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);
        
        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value();  /* return new generated id of record */
    }

    public function read($ids, $fields, $model_name, $context=array() ) {
        $client = new xmlrpc_client($this->server."object");
        //   ['execute','userid','password','module.name',{values....}]
        $client->return_type = 'phpvals';

        $id_val = array();
        $count = 0;
        foreach ($ids as $id)
            $id_val[$count++] = new xmlrpcval($id, "int");

        $fields_val = array();
        $count = 0;
        foreach ($fields as $field)
            $fields_val[$count++] = new xmlrpcval($field, "string");

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("read", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
#        $ctx = array();
#        foreach($context as $k=>$v){
#            $ctx[$k] = new xmlrpcval( xmlrpc_get_type($v) );
#        }
        if(!empty($context)){
            $msg->addParam(new xmlrpcval(array("lang" => new xmlrpcval("nl_NL", "string"),'pricelist'=>new xmlrpcval($context['pricelist'], xmlrpc_get_type($context['pricelist']) )) , "struct"));
        }

        $resp = $client->send($msg);
        ///print_r($resp);
        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value();
    }

    public function unlink($ids , $model_name) {
        
        $client = new xmlrpc_client("http://localhost:8069/xmlrpc/object");
      
        $client->return_type = 'phpvals';

        $id_val = array();
        $count = 0;
        foreach ($ids as $id)
            $id_val[$count++] = new xmlrpcval($id, "int");

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("unlink", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
//        $msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            //print_r( $resp->value() );
            return ( $resp->value() );
    }


    public function price_get($ids, $product_id, $qty, $partner_id) {
        $client = new xmlrpc_client($this->server."object");
        //   ['execute','userid','password','module.name',{values....}]
        $client->return_type = 'phpvals';

        $id_val = array();
        $count = 0;
        foreach ($ids as $id)
            $id_val[$count++] = new xmlrpcval($id, "int");

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval('product.pricelist', "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("price_get", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($product_id, "int"));
        $msg->addParam(new xmlrpcval($qty, xmlrpc_get_type($qty)  ));
        $msg->addParam(new xmlrpcval($partner_id, "int"));

        $resp = $client->send($msg);
        //print_r($resp);
        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value();
    }
    public function get_fields($model){
        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("fields_get", "string"));/** method which u like to execute */
        $resp = $client->send($msg);
        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value();
    }
    public function get_default_values($model){
        $values = $this->get_fields($model);

        $columns = array_keys($values);
        $array_temp = array();
        foreach($columns as $column){
            array_push($array_temp, new xmlrpcval($column,"string"));
        }
         
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->password, "string"));/** password */
        $msg->addParam(new xmlrpcval($model, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("default_get", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($array_temp, "array"));
        
        $resp = $client->send($msg);
        print_r($resp);
        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value();
    }
}

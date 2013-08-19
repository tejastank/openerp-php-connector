<?php
/* OpenERP PHP connection script. Under GPL V3 , All Rights Are Reserverd , tejas.tank.mca@gmail.com
 *
 * @Author : Tejas L Tank.
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
    public $uid = "";/**  @uid = once user succesful login then this will asign the user id */
    public $username = ""; /*     * * @userid = general name of user which require to login at openerp server */
    public $passwrod = "";/** @password = password require to login at openerp server * */

    public function login($username = "admin", $password="a", $database="test", $server="http://localhost:8069/xmlrpc/") {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->passwrod = $password;

        $sock = new xmlrpc_client($this->server . 'common');
        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($this->database, "string"));
        $msg->addParam(new xmlrpcval($this->username, "string"));
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));

        $resp = $sock->send($msg);
        if($resp->errno > 0 ){
            print "Error : ". $resp->errstr;
            return -1;
        }
        print_r($resp->value()->me['int']);
        //$val = $resp->value();
        //$id = $val->scalarval();
        $this->uid = $resp->value()->me['int'];
        if ( $resp->value()->me['int'] ) {
            return $resp->value()->me['int']; //* userid of succesful login person *//
        } else {
            return -1; //** if userid not exists , username or password wrong.. */
        }
    }

    public function create($values, $model_name) {
        $client = new xmlrpc_client("http://localhost:8069/xmlrpc/object");


        //   ['execute','userid','password','module.name',{values....}]
        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("create", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

        if ($resp->faultCode())
            return -1; /* if the record is not created  */
        else
            return $resp->value()->scalarval();  /* return new generated id of record */
    }

    public function write($ids, $values, $model_name) {
        $client = new xmlrpc_client("http://localhost:8069/xmlrpc/object");
        //   ['execute','userid','password','module.name',{values....}]

        $id_val = array();
        $count = 0;
        foreach ($ids as $id)
            $id_val[$count++] = new xmlrpcval($id, "int");



        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("write", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return $resp->value()->scalarval();  /* return new generated id of record */
    }

    public function read($ids, $fields, $model_name) {
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
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("read", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
//        print_r($msg);
        $resp = $client->send($msg);

//        print_r($resp);

        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            return ( $resp->value() );
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
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("unlink", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
//        $msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

        if ($resp->faultCode())
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        else
            print_r( $resp->value() );
            //return ( $resp->value() );
    }

}

?>

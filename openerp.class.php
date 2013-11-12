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
    public $certificate = FALSE;
    public $verify_host = 2;

    public function login($username = "admin", $password="a", $database="test", $server="http://localhost:8069/xmlrpc/", $certificate=FALSE, $verify_host=2) {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->passwrod = $password;
        $this->certificate = $certificate;
        $this->verify_host = $verify_host;

        $sock = new xmlrpc_client($this->server . 'common');
        if($this->certificate){
        	$sock->setSSLVerifyHost($this->verify_host);
        	$sock->setCaCertificate($this->certificate);
        }
        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($this->database, "string"));
        $msg->addParam(new xmlrpcval($this->username, "string"));
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));

        $resp = $sock->send($msg);
        if($resp->errno > 0 ){
            print "Error : ". $resp->errstr;
            return -1;
        }
        $this->uid = $resp->value()->me['int'];
        if ( $resp->value()->me['int'] ) {
            return $resp->value()->me['int']; //* userid of succesful login person *//
        } else {
            return -1; //** if userid not exists , username or password wrong.. */
        }
    }

    public function create($values, $model_name) {
        $client = new xmlrpc_client($this->server."object");
        if($this->certificate){
        	$client->setSSLVerifyHost($this->verify_host);
        	$client->setCaCertificate($this->certificate);
        }

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("create", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

    	if ($resp->faultCode()){
        	echo 'KO. Error: '.$resp->faultString();
        	return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        }
        else{
        	 
        	return ( $resp->value()->scalarval() );/* return new generated id of record */
        } 
    }

    public function write($ids, $values, $model_name) {
        $client = new xmlrpc_client($this->server."object");
    	if($this->certificate){
        	$client->setSSLVerifyHost($this->verify_host);
        	$client->setCaCertificate($this->certificate);
        }
    	if (is_array($ids)){
        	$id_val = array();
        	$count = 0;
        	foreach ($ids as $id)
        		$id_val[$count++] = new xmlrpcval($id, "int");
        }
        else{
        	$id_val = array(new xmlrpcval($ids, "int"));
        }



        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("write", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
        $msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);
        
        if ($resp->faultCode()){
        	echo 'KO. Error: '.$resp->faultString();
        	return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
        }
        else{
        	 
        	return ( $resp->value()->scalarval() );/* return new generated id of record */
        } 
    }

    public function read($ids, $fields, $model_name) {
        $client = new xmlrpc_client($this->server."object");
        $client->return_type = 'phpvals';
    	if($this->certificate){
        	$client->setSSLVerifyHost($this->verify_host);
        	$client->setCaCertificate($this->certificate);
        }
        if (is_array($ids)){
        	$id_val = array();
        	$count = 0;
        	foreach ($ids as $id)
        		$id_val[$count++] = new xmlrpcval($id, "int");
        }
        else{
        	$id_val = array(new xmlrpcval($ids, "int"));
        }

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
        $resp = $client->send($msg);


    	if ($resp->faultCode()){
    		echo 'KO. Error: '.$resp->faultString();
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
    	}
        else{
           
            return ( $resp->value() );
        }
    }

    public function unlink($ids , $model_name) {
        
       $client = new xmlrpc_client($this->server."object");
       if($this->certificate){
       	$client->setSSLVerifyHost($this->verify_host);
       	$client->setCaCertificate($this->certificate);
       }
        $client->return_type = 'phpvals';

    	if (is_array($ids)){
        	$id_val = array();
        	$count = 0;
        	foreach ($ids as $id)
        		$id_val[$count++] = new xmlrpcval($id, "int");
        }
        else{
        	$id_val = array(new xmlrpcval($ids, "int"));
        }

        $msg = new xmlrpcmsg('execute');
        $msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
        $msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
        $msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
        $msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
        $msg->addParam(new xmlrpcval("unlink", "string"));/** method which u like to execute */
        $msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
//        $msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
        $resp = $client->send($msg);

    	if ($resp->faultCode()){
    		echo 'KO. Error: '.$resp->faultString();
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
    	}
        else{
           
            return ( $resp->value() );
        }
    }
    
    /**
     * $client = xml-rpc handler
     * $model_name = name of the relation ex: res.partner
     * $attribute = name of the attribute ex:code
     * $operator = search term operator ex: ilike, =, !=
     * $key=search for
     */
    
    function search($model_name,$attribute=FALSE,$operator=FALSE,$key=array()) {
    	
    	$client = new xmlrpc_client($this->server."object");
    	$client->return_type = 'phpvals';
    	if($this->certificate){
    		$client->setSSLVerifyHost($this->verify_host);
    		$client->setCaCertificate($this->certificate);
    	}
    	if ($attribute){
	    	$key = array(
	    			new xmlrpcval(
	    					array(
	    						new xmlrpcval($attribute , "string"),
	    						new xmlrpcval($operator,"string"),
	    						new xmlrpcval($key,"string")
	    					),"array"
	    			),
	    	);
    	}
    	
    	$msg = new xmlrpcmsg('execute');
    	$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
    	$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
    	$msg->addParam(new xmlrpcval($this->passwrod, "string"));/** password */
    	$msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
    	$msg->addParam(new xmlrpcval("search", "string"));
    	$msg->addParam(new xmlrpcval($key, "array"));
    
    	$resp = $client->send($msg);
    	if ($resp->faultCode()){
    		echo 'KO. Error: '.$resp->faultString();
            return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
    	}
        else{
           
            return ( $resp->value() );
        }
    }

}

?>

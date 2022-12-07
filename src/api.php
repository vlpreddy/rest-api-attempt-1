<?php
class Api extends Rest{
    public $dbConn;
    public function __construct(){
        parent::__construct();
        $db = new DbConnect();
        $this->dbConn = $db->connect();
    }

    public function generateToken(){
        $email = $this->validateParameter('email',$this->param['email'], STRING);
        $pass = $this->validateParameter('pass',$this->param['pass'], STRING);
        try {
            $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email AND password = :pass");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!is_array($user)){
                $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect");
            }
            if($user['active'] == 0){
                $this->returnResponse(USER_NOT_ACTIVE, "User is not activated, please contact administrator");
            }
            //print_r($user);
            $payload = [
                'iat' => time(),
                'iss' => 'localhost',
                'exp' => time() +  (15*60),
                'userid'=> $user['id']
            ];
            $token = JWT::encode($payload, SECRETE_KEY);
            //echo $token;
            $data = ['token' => $token];
            $this->returnResponse(SUCCESS_RESPONSE,$data );
        } catch (Exception $e){
            $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
        }

    }

    public function addCustomer(){
        $name = $this->validateParameter('name',$this->param['name'], STRING, false);
        $email = $this->validateParameter('email',$this->param['email'], STRING, false);
        $addr = $this->validateParameter('addr',$this->param['addr'], STRING, false);
        $mobile = $this->validateParameter('mobile',$this->param['mobile'], STRING, false);
            $cust = new Customer();
            $cust->setName($name);
            $cust->setEmail($email);
            $cust->setAddress($addr);
            $cust->setMobile($mobile);
            $cust->setCreatedBy($payLoad->userid);
            $cust->setCreatedOn(date('Y-m-d'));
            if(!$cust->insert()){
                $message = "Failed to Insert";
            } else {
                $message = "Inserted Succesfully";
            }

            $this->returnResponse(SUCCESS_RESPONSE, $message);
            
    }

    public function getCustomerDetails(){
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
        $cust  = new Customer();
        $cust->setId($customerId);
        $customer = $cust->getCustomerDetailsById();
        if(!is_array($customer)){
            $this->returnResponse(SUCCESS_RESPONSE, ['message'=>'Customer Details are not exist in database']);
        }

        $response['customerId'] = $customer['id'];
        $response['CustomerName'] = $customer['name'];
        $response['email'] = $customer['email'];
        $response['mobile'] = $customer['mobile'];
        $response['address'] = $customer['address'];
        $response['created_by'] = $customer['created_user'];
        $response['LastUpdatedBy'] = $customer['updated_user'];
        $this->returnResponse(SUCCESS_RESPONSE, $response);

    }
}

?>
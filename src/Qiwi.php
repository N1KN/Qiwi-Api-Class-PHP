<?php

require_once(__DIR__."/Errors.php");

class Qiwi 
{
    private $_phone;
    private $_token;
    private $_url;
 
    function __construct($phone, $token) 
    {
        $this->_phone = (string)$phone;
        $this->_token = $token;
        $this->_url   = 'https://edge.qiwi.com/';
    }

    private function sendRequest($method, array $content=[], $post=false, $warnings=True) 
    {
        $ch = curl_init();
        if (true == $post) 
        {
            curl_setopt($ch, CURLOPT_URL, $this->_url . $method);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        } 

        else 
        {
            curl_setopt($ch, CURLOPT_URL, $this->_url . $method . '/?' . http_build_query($content));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->_token,
            'Host: edge.qiwi.com'
        ]); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);


        if (True == $warnings)
        {
            if (400 == $http_code)
            {
                throw new ArgumentError($result);
                
            }
            elseif (401 == $http_code)
            {
                throw new InvalidToken('Invalid token!');
            }
            elseif (403 == $http_code)
            {
                throw new NotHaveEnoughPermissions($result);
            }
            elseif (404 == $http_code)
            {
                throw new NoTransaction($result);
            }
        }

        return json_decode($result, 1);
    }

    public function getAccount(Array $params=[]) 
    {
        return $this->sendRequest('person-profile/v1/profile/current', $params);
    }


    public function getPaymentsHistory(Array $params = []) 
    {
        return $this->sendRequest('payment-history/v2/persons/' . $this->_phone . '/payments', $params);
    }


    public function getPaymentsStats(Array $params = []) 
    {
        return $this->sendRequest('payment-history/v2/persons/' . $this->_phone . '/payments/total', $params);
    }


    public function getTxn($txnId, Array $params = []) 
    {
        return $this->sendRequest('payment-history/v2/transactions/' . $txnId .'/', $params);
    }


    public function getCheck($txnId, Array $params =[]) 
    {
	   return $this->sendRequest('payment-history/v1/transactions/' . $txnId .'/cheque/file', $params);
    } 


    public function getBalance() 
    {
        return $this->sendRequest('funding-sources/v2/persons/' . $this->_phone . '/accounts');
    }


    public function getTax($providerId) 
    {
        return $this->sendRequest('sinap/providers/'. $providerId .'/form');
    } 


    public function sendMoneyToQiwi($recipient, $sum, $comment=null, $currency=643)
    {
        $path = 'sinap/api/v2/terms/99/payments';

        if (null == $comment)
        {
            $comment = '';
        }


        $params = [
            'id' => sprintf('%s', time() + 10 * 5),
            'sum' => [
                'amount'   => $sum,
                'currency' => (string)$currency
            ], 
            'paymentMethod' => [
                'type' => 'Account',
                'accountId' => '643'
            ],
            'comment' => $comment,
            'fields' => [
                'account' => (string)$recipient
            ]
        ];

        return $this->sendRequest($path, $params, 1);
    }


    public function sendMoneyToProvider($providerId, Array $params=[]) 
    {
        return $this->sendRequest('sinap/api/v2/terms/'. $providerId .'/payments', $params, 1);
    }


    public function sendMoneyToOther(Array $params=[]) 
    {
        return $this->sendRequest('sinap/api/v2/terms/1717/payments', $params, 1);
    }






    public function checkValidAccount($testRecipient, $testSum=1)
    {
        $response = $this->sendMoneyToQiwi($testRecipient, $testSum);
        
        if ("QWPRC-1021" == $response["code"]) // Ограничение на исходящие платежи
        {
            $response = false;
        }
        else
        {
            $response = true;
        }
    
        
        return  $response;
    }


    public function searchRefill($comment, $sum=0)
    {
        $history = $this->getPaymentsHistory()['data'];
        $response = null;

        foreach ($history as $key => $transaction) 
        {
            $transaction_comment = $transaction['comment'];
            $transaction_sum = (float)$transaction['total']['amount'];

            
            if ($comment == $transaction['comment'] and  $sum <= $transaction_sum)
            {
                $response = $transaction;
            }
            
        }

        return $response;
    }

    public function genPaymentLink($number=null, $sum=null, $comment=null)
    {
        $form = 99;

        $a = 'https://qiwi.com/payment/form/'.$form.'?extra%5B%27account%27%5D=';
        $b = $number.'&amountInteger='.$sum.'&amountFraction=0';
        $c = '&extra%5B%27comment%27%5D='.$comment.'&currency=643';
        $a = $a.$b.$c;


        if (null !== $number)
        {
            $a .= '&blocked[0]=account';
        }
        if (null !== $sum)
        {
            $a .= '&blocked[1]=sum';
        }
        if (null !== $comment)
        {
            $a .= '&blocked[2]=comment';
        }

        return $a;
    }


    public function genComment($lengt=10)
    {
        $comment = md5(uniqid(rand(),true));
        $comment = substr($comment, 0, $lengt);

        return $comment;
    }
}
?>

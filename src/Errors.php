<?php 
class ArgumentError extends Exception
{
  	public function __construct($message, $code=0, Exception $previous=null) 
  	{
    	parent::__construct($message, $code, $previous);
 	}


  	public function __toString() 
  	{
    	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  	}	
 

  	public function customFunction() 
  	{
    	echo $this->message;
  	}
}


class InvalidToken extends Exception
{
  	public function __construct($message, $code=0, Exception $previous=null) 
  	{
    	parent::__construct($message, $code, $previous);
 	}


  	public function __toString() 
  	{
    	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  	}	
 

  	public function customFunction() 
  	{
    	echo $this->message;
  	}
}


class NotHaveEnoughPermissions extends Exception
{
  	public function __construct($message, $code=0, Exception $previous=null) 
  	{
    	parent::__construct($message, $code, $previous);
 	}


  	public function __toString() 
  	{
    	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  	}	
 

  	public function customFunction() 
  	{
    	echo $this->message;
  	}
}


class NoTransaction extends Exception
{
  	public function __construct($message, $code=0, Exception $previous=null) 
  	{
    	parent::__construct($message, $code, $previous);
 	}


  	public function __toString() 
  	{
    	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  	}	
 

  	public function customFunction() 
  	{
    	echo $this->message;
  	}
}



?>
<?php

/**
 * Session Class.
 * Model for the Session Resource
 *
 * @author McFarlane.a
 */
class Session {
	/**
	 * The signature for the session.
	 * @access protected
	 * @var string
	 */
	protected $signature;
	
	/**
	 * The public key for the session.
	 * @access protected
	 * @var string
	 */
	protected $publicKey;
	
	/**
	 * holds true if session user is an admin
	 * @access protected
	 * @var boolean
	 */
	protected $isAdmin;
	
	/**
	 * holds true if session user is an admin
	 * @access protected
	 * @var boolean
	 */
	protected $user;
	
	/**
	 * Holds the error string for the session object.
	 * @var array 
	 */
	public $errors = array();
	
	/**
	 * 
	 */
	
	/**
	 * Constructor sets up {@link $username} and {@link $password}
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($publicKey, $signature, $request)
	{
		$this->publicKey = $publicKey;
		$this->signature = $signature;
		$this->request = $request;
        if($publicKey){
            $this->isAdmin = $this->isAdmin();
            $this->user = get_user($publicKey);
        }
	}
	
	public function verifySignature()
	{
		$sig = $this->createSignature();
		if($sig != $this->signature) {
			return false;
		}
		else{
			return true;
		}
	}
	
	private function createSignature()
	{
		$privateKey = sha1($this->user->salt);
		$requestString = $this->getRequestString();
		return hash_hmac("sha256", $requestString, $privateKey);
	}
	
	private function getRequestString()
	{
		if(empty($this->request)) {
			$request = json_decode("{}");
		}
		else{
			$request = array();
			$request = $this->request;
			ksort($request);
		}
		
		//do not escape special utf8 encoded chars
		$unescaped = preg_replace_callback('/\\\\u(\w{4})/', function ($matches) {
			return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
		}, json_encode($request));
		//do not escape slashes
		$unescaped = str_replace('\\/', '/', $unescaped);
		//both of the above code blocks can be solved by $val = json_encode($request, 256 | 64) with a server running >= PHP5.4
		return sha1($unescaped);
	}
	
	public function setHeader($responseCode)
	{
		header('Content-type: application/json');
		if($responseCode == 200) {
			header("HTTP/1.1 200 OK");
		}
		elseif($responseCode == 201){
			header("HTTP/1.1 201 Created");
		}
		elseif($responseCode == 401) {
			header("HTTP/1.1 401 Unauthorized");
		}
		elseif($responseCode == 500){
                    header("HTTP/1.1 500 ");
		}
		elseif($responseCode == 400){
                    header("HTTP/1.1 400 Bad Request");
		}
		elseif($responseCode == 404){
			header("HTTP/1.1 404 Not Found");
		}
	}
	
	public function isAdmin()
	{
		if(elgg_is_admin_user($this->publicKey)) {
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getPublicKey()
	{
		return $this->publicKey;
	}
	
	public function getIsAdmin()
	{
		return $this->isAdmin;
	}
	
	public function getLanguage()
	{
		return $this->user->language;
	}
	
	public function getProjectAdmin()
	{
		return $this->user->project_admin;
	}
        
    public function sendResponse($status ,$data)
    {
        header('Content-type: application/json');
        return json_encode(array('status'=>$status, 'data'=>$data), 32);
    }
    
    public function getUser()
    {
        return $this->user;
    }
}

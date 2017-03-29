<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/API.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/APIKey.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/include/category.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/include/books.class.php';
class WRAPI extends API
{
    protected $User;

    // Constructor verifies user key since lack of client context in server for api
    public function __construct($request, $origin) {
        parent::__construct($request);
        
        // Abstracted out for example
        $APIKey = new APIKey();

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) && !$APIKey->verifyUser($this->request['token'])) {
            throw new Exception('Invalid User Token');
        }

        $this->User = $APIKey->getUser();
    }

    // Category API
     protected function category() {
        if ($this->method == 'GET' || $this->method == 'POST') {
            $category = new Category();
            
            switch($this->verb) {
                case "list":
                    $result = $category->get_list();
                    break;
                case "details":
                    $result = $category->get_details($this->args);
                    break;
                case "books":
                    $result = $category->get_books($this->args);
                    break;
                default:
                    return "Action ".$this->verb." for category does not exist. Go to /api for more information about available APIs";
                    break;
            }
            return json_encode($result);
        } else {
            return "Only accepts GET or POST requests";
        }
     }
     
    // Books API
     protected function books() {
        if ($this->method == 'GET' || $this->method == 'POST') {
            
            $book = new Books();
            $result = $book->get_details($this->args);
            
            return json_encode($result);
        } else {
            return "Only accepts GET or POST requests";
        }
     }
 }
 
 ?>
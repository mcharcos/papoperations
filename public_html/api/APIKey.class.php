<?php

    // This class needs to be further ellaborated but for the purpose of the assignment
    // I skipp the API key part.
    class APIKey
    {
        
        // Current user
        protected $User = "";
        
        public function __construct() {
        }
        
        // Well, for simplicity, I will leave this as always true
        // But it will require to have the info in the DB
        public function verifyKey($key, $origin) {
            
            // Check if the key matches the origin
            return true;
        }
        
        // Verify the user token. Again, I skip this for simplicity
        public function verifyUser($token) {
            
            // Update with a dummy user
            $this->User = "World Reader";
            
            // Check the token for the user
            return true;
        }
        
        // get the user
        public function getUser(){
            return $this->User;
        }
    }
    
?>
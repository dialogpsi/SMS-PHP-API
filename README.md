#[Ideamart sms PHP API](http://www.ideamart.lk) 

---
This is the ideamart SMS PHP API, using the classes here you can recieve messages, send messages to a address, list of addressses or a broadcast message and log using the log class.

###Creating a Listener to recieve messages

    require 'SMSReceiver.php';  // Import the SMSReceiver Class
    
    // Create a object of the SMSReceiver and send the incomming request to be decoded
    
    $receiver = new SMSReceiver(file_get_contents('php://input'));
    
    $receiver->getMessage()     // Get the messsage recieved 
    $receiver->getAddress()     // Get the address from which message was sent
    $receiver->getVersion()     // Get the version of the incomming request
    $receiver->getEncoding()        // Get the encoding of the incomming request
    $receiver->getApplicationId()   // Get the appid which the request was sent to
    $receiver->getRequestId()       //  Get the uniqe requestID of the request

****

### Creating a sender to Send SMSs
*******
In simulation you can use any appid and password.
In production a appid and password will be provided when the app is be provisioned in Ideamart.

Production Server URLs

HTTP - [http://api.dialog.lk:8080/sms/send](http://api.dialog.lk:8080/sms/send)

HTTPS -  [https://api.dialog.lk/sms/send](https://api.dialog.lk/sms/send)


Simulator Server URL
HTTP -[http://localhost:7000/sms/send](http://localhost:7000/sms/send)

****



    require 'SMSSender.php';  // Import the SMSSender Class
    
    define('SERVER_URL', 'http://localhost:7000/sms/send'); // Set the Server URL
    define('APP_ID', 'appid');							  // Set the APPID
    define('APP_PASSWORD', 'pass');						 // Set the password

	// Create Sender intialze the object with the SeverURL , APPID and APP Password
    $sender = new SMSSender( SERVER_URL, APP_ID,  APP_PASSWORD); 


**To send a SMS to a user**

	$sender->sms( 'This message is send to one particlar no', ADDRESS)

**To send a SMS to number of users**

	$sender->sms( 'Same message to all the address send', array(ADDRESS1, ADDRESS2, ...))

**To broadcast a SMS to all the subcribers of the app**

	$sender->broadcast( 'This is a Broadcast Message')
   
   
   
   
   
   
   
    
    
    
    
    
    


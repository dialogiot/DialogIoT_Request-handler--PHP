# DialogIoT_Request-handler--PHP
Sample php code snippets to build apps to create,control & manage IoT devices on top of Dialog IoT platform

This will handle the API calls and token management. You just have to make your desired API calls using this SDK
Please refer https://portal.iot.ideamart.io/public/api_management for more info & available resources on API management

## Configuration
* Download the repository and extract it's contents to your preferred destination.
* Change config.json files properties based on your credential information Ex: username,password, X-secret.
* Edit the file "test.php" according to your needs.
* Upload the extracted files to your hosting space.
* Make **config.json** and **lib/data.json** writable.


## Use
Once config.json is updated, you may include `DialogIoTAPIHandler.php` to your php code and call `sendAPICall` method 

For example

```
include 'DialogIoTAPIHandler.php';
$auth = new DialogIoTAPIHandler();
$out = $auth->sendAPICall($url,RequestMethod::POST,$body);
```

## Parameters
### URL
 Base URL of Dialog IoT api management iot.dialog.lk/developer/api/applicationmgt

### Method
 its an HTTP method. you can use `RequestMethod` Enum for that. this accepts string as well such as "POST and "GET". RequestMethod enum contains

```
RequestMethod::POST
RequestMethod::GET
RequestMethod::DELETE
RequestMethod::PUT

```

### Body
this is a plain string that contains any payload. If you want to send an object, then please `json_encode` it.

```
$out = $auth->sendAPICall($url,RequestMethod::POST,json_encode($obj));

```


## Response
Result returns as array. 

### Success

```
 $result['status'] 
 $result['statusCode'] 
 $result['time']
 $result['header']
 $result['body']

```

#### status 
this contains 'OK' for success

#### Status Code
this contains http status code. eg : 200, 400 etc

#### Time
Time taken to complete the request

#### Headers
HTTP headers that are returned by the server

#### Body
body is retrieved as plain text. if you have an object, then you may use `json_decode` 

### Error
 this happens if connection fails or an error occurs other than the Authentication failures


```
 $result['status'] 
 $result['error'] 
```


#### status 
The string value "ERROR" is given for the Errors

#### error
this contain error description

 
### Exceptions
This returns two types of exceptions if any authentication errors

its
```
AuthenticationException
ConnectionException
```

### Example code
Please refer `test.php`

#### Special Credits
[ideaBiz Request handler by Malinda Prasad](https://github.com/ideabizlk/IdeaBiz-Request-Handler---PHP)



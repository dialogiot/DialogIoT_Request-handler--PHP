# DialogIoT_Request-handler--PHP
Sample php code snippets to build apps on top of Dialog IoT platform



#IdeaBiz PHP sample

This will handle the API call. and also it handle to token. If need,it will refresh existing token automatically . So you only need to make API call via this SDK

##Configuration
* Make **config.json** and **lib/data.json** writable
* Change config.json files properties based on your credential information


## Use
Once config.json is configured, you can include `DialogIoTAPIHandler.php` to your code. then call `sendAPICall` method 

For example

```
include 'DialogIoTAPIHandler.php';
$auth = new DialogIoTAPIHandler();
$out = $auth->sendAPICall($url,RequestMethod::POST,$body);
```

## Parameters
### URL
 complete URL of ideabiz api. Example for device management "https://iot.dialog.lk/developer/api/userdevicemgt/v1/devices"

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



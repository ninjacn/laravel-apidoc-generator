<!-- START_{{$parsedRoute['id']}} -->
@if($parsedRoute['title'] != '')## {{ $parsedRoute['title']}}
@else## {{$parsedRoute['uri']}}
@endif
@if($parsedRoute['description'])

{!! $parsedRoute['description'] !!}
@endif

> Example request:

```bash
curl -X {{$parsedRoute['methods'][0]}} "{{config('app.docs_url') ?: config('app.url')}}/{{$parsedRoute['uri']}}" \
-H 'cache-control: no-cache' \
-H 'token: {{$token}}' \
-H "Accept: application/json"@if(count($parsedRoute['parameters'])) \
@foreach($parsedRoute['parameters'] as $attribute => $parameter)
    -d "{{$attribute}}"="{{$parameter['value']}}" \
@endforeach
@endif

```

```php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "{{config('app.docs_url') ?: config('app.url')}}/{{$parsedRoute['uri']}}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
@if($parsedRoute['methods'][0] == 'POST')
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
@endif
  CURLOPT_CUSTOMREQUEST => "{{$parsedRoute['methods'][0]}}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "token: {{$token}}"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
```

```python
import requests

url = "{{config('app.docs_url') ?: config('app.url')}}/{{$parsedRoute['uri']}}"

headers = {
    'cache-control': "no-cache",
    'token': "{{$token}}"
    }

response = requests.request("$parsedRoute['methods'][0]", url, headers=headers)

print(response.text)
```

```golang
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io/ioutil"
)

func main() {

	url := "{{config('app.docs_url') ?: config('app.url')}}/{{$parsedRoute['uri']}}"

@if($parsedRoute['methods'][0] == 'POST')
	payload := strings.NewReader("------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;------WebKitFormBoundary7MA4YWxkTrZu0gW--")
	req, _ := http.NewRequest("{{$parsedRoute['methods'][0]}}", url, payload)
	req.Header.Add("content-type", "multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW")
@else
	req, _ := http.NewRequest("{{$parsedRoute['methods'][0]}}", url, nil)
@endif

	req.Header.Add("cache-control", "no-cache")
	req.Header.Add("token", "{{$token}}")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := ioutil.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "{{config('app.docs_url') ?: config('app.url')}}/{{$parsedRoute['uri']}}",
    "method": "{{$parsedRoute['methods'][0]}}",
    @if(count($parsedRoute['parameters']))
"data": {!! str_replace('    ','        ',json_encode(array_combine(array_keys($parsedRoute['parameters']), array_map(function($param){ return $param['value']; },$parsedRoute['parameters'])), JSON_PRETTY_PRINT)) !!},
    @endif
"headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

@if(in_array('GET',$parsedRoute['methods']) || isset($parsedRoute['showresponse']) && $parsedRoute['showresponse'])
> Example response:

```json
@if(is_object($parsedRoute['response']) || is_array($parsedRoute['response']))
{!! json_encode($parsedRoute['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@else
{!! json_encode(json_decode($parsedRoute['response']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@endif
```
@endif

### HTTP Request
@foreach($parsedRoute['methods'] as $method)
`{{$method}} {{$parsedRoute['uri']}}`

@endforeach
@if(count($parsedRoute['parameters']))
#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
@foreach($parsedRoute['parameters'] as $attribute => $parameter)
    {{$attribute}} | {{$parameter['type']}} | @if($parameter['required']) required @else optional @endif | {!! implode(' ',$parameter['description']) !!}
@endforeach
@endif

<!-- END_{{$parsedRoute['id']}} -->

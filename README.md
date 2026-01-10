# dl-cdn
Implementation of Gameloft's Droid6 CDN in php

THIS IS A W.I.P.

## I AM NOT AFFLIATED WITH OR ENDORSED BY GAMELOFT S.A.
## THIS REPOSITORY DOES NOT CONTAIN ANY GAME FILES OR CODE BY GAMELOFT S.A.

## Simple usage
Download the repo and put in a directory.

Edit the root variable in /config/nginx.conf to have your directory instead.

Edit products.json in /public/partners/androidmarket/ and change the urls

Open a terminal in /public and start the php server with this cmd :

`php-cgi.exe -b 0.0.0.0:9000`

Open a terminal in / and start nginx by running `nginx`.

## products.json formating
```json
{
  "1788": {
    "2.0.0": {
      "url": "http://192.168.1.19/cdn/Asphalt8_GAND_v200d.amz",
      "size": 1524755664,
      "release": 108031,
      "generic": "yes",
      "illegal": false
    }
  }
}
```
1788 is the product and 2.0.0 is the version.

`url`: URL of the file

`size`: Size of the file in bytes.

`release`: TODO: Document this.

`generic`: TODO: Document this.

`illegal`: Return error 451 to prevent download (ex : for DMCAs)

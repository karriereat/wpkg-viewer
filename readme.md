# wpkg-viewer

wpkg-viewer is a simple GUI for the windows package manager [wpkg](https://wpkg.org/).

## Requirements
See the Laravel docs:  [https://laravel.com/docs/5.4](https://laravel.com/docs/5.4) 

## Installation
```
$ git clone git@github.com:internetztube/wpkg-viewer.git
$ composer install
$ npm install
$ npm run production
```

## Configuration
You have to specify the path to the folder where the generated `.xml` files life. For that adjust path of the 
`WPKG_VIEWER_BASE_PATH` variable in the `.env` file.

```
...

WPKG_VIEWER_BASE_PATH=/path/to/xml/files
WPKG_VIEWER_PATH_TO_HOSTS_XML=hostxml/
```
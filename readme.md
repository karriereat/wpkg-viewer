<a href="https://www.karriere.at/" target="_blank"><img width="200" src="http://www.karriere.at/images/layout/katlogo.svg"></a>
<span>&nbsp;&nbsp;&nbsp;</span>
[![Code Style](https://styleci.io/repos/96221975/shield)](https://styleci.io/repos/96221975)

# wpkg-viewer

wpkg-viewer is a simple GUI for the windows package manager [wpkg](https://wpkg.org/).

## Requirements

See the Laravel docs:  [https://laravel.com/docs/5.4](https://laravel.com/docs/5.4) 

## Installation

```
$ git clone git@github.com:karriereat/wpkg-viewer.git
$ composer install
$ php artisan key:generate
$ npm install
$ npm run production
```

## Configuration

You have to specify the path to the folder where the generated `.xml` files life. For that adjust path of the 
`WPKG_VIEWER_BASE_PATH` variable in the `.env` file.

```
WPKG_VIEWER_BASE_PATH=/path/to/xml/files
WPKG_VIEWER_PATH_TO_HOSTS_XML=hostxml/
```

## License

Apache License 2.0 Please see [LICENSE](LICENSE) for more information.

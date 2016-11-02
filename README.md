## Photo Blog Application based on Laravel 5.3 and Angular 2.0

### Backend

#### Development Configuration

Apache 2.4, MySQL 5.7, Node.js 4.2, PHP 7.0, Laravel 5.3.

#### Installation

Run following command (within the `./backend` directory) to install application dependencies:

```
composer install
```

Create `./backend/.env` file from the example `./backend/.env.example`. Setup database connection credentials.

#### REST API Documentation

Run following commands to install development dependencies:

```
npm install
npm install -g gulp api-doc
```

Run following command (within the `./backend` directory) to generate REST API documentation:

```
gulp generate-api-doc
```

This command will create `./docs/api/dist` directory. Open the `./docs/api/dist/index.html` file in your favorite web browser to show the REST API documentation.
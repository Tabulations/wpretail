# WPRetail

Welcome to the WPRetail repository on GitHub. Here you can browse the source, look at open issues and use this template to bootstrap your next WPForms add-on development.

## Installation

### Requirements

WPRetail requires the following dependencies:

-   [Node.js](https://nodejs.org/)
-   [Composer](https://getcomposer.org/)


### Setup

To start using all the tools that come with WPRetail you need to install the necessary Node.js and Composer dependencies:

```sh
$ composer install
$ npm install
$ grunt assets
```

Without performing this step, if you try to activate the plugin it will simply show an admin notice for build dependencies.

### Available CLI commands

WPRetail comes packed with CLI commands tailored for WPForms add-on development:

-   `npm run build` - builds the code for production.
-   `composer makepot` : Generates a *.pot* file in the `languages/` directory.
-   `composer makepot-audit`: Generates a *.pot* file in the `languages/` directory and run audit.

Now you're ready to go! The next step is easy to say, but harder to do: make an awesome WPForms addon. :)

Good luck!

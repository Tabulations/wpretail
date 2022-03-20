# WPForms Starter

Welcome to the WPForms Starter repository on GitHub. Here you can browse the source, look at open issues and use this template to bootstrap your next WPForms add-on development.

## Installation

### Requirements

WPForms Starter requires the following dependencies:

-   [Node.js](https://nodejs.org/)
-   [Composer](https://getcomposer.org/)

### Quick Start

Click "Use this template" at the top of this page, or click [here](https://github.com/WPCanny/wpforms-starter/generate) to create your repo and clone locally to your `wp-content/plugins` directory. After that you'll need to do a five-step find and replace on the name in all the templates.

1. Search for `WPForms Starter` to capture the plugin name and replace with: `Plugin Name`.
2. Search for `WPForms\Starter` to capture the plugin namespace and replace with: `WPForms\Plugin_Name`.
3. Search for `WPForms\\Starter\\` to capture the autoload namespace and replace with: `WPForms\\Plugin_Name\\`.
4. Search for `wpforms-starter` to capture the plugin slug, textdomain, etc. and replace with: `plugin-name`.
5. Search for `WPFORMS_STARTER_` (in uppercase) to capture constants and replace with: `PLUGIN_NAME_`.

Then, rename the plugin main file `wpforms-starter.php` to use the plugin's slug. Next, update or delete this readme.

### Setup

To start using all the tools that come with WPForms Starter you need to install the necessary Node.js and Composer dependencies:

```sh
$ composer install
$ npm install
$ grunt assets
```

Without performing this step, if you try to activate the plugin it will simply show an admin notice for build dependencies.

### Available CLI commands

WPForms Starter comes packed with CLI commands tailored for WPForms add-on development:

-   `npm run build` - builds the code for production.
-   `composer makepot` : Generates a *.pot* file in the `languages/` directory.
-   `composer makepot-audit`: Generates a *.pot* file in the `languages/` directory and run audit.

Now you're ready to go! The next step is easy to say, but harder to do: make an awesome WPForms addon. :)

Good luck!

# Finance Dashboard

A WordPress plugin to manage invoices, receipts and clients (only for the Dutch market). This plugin adds an invoice, receipt and client custom post type and creates a 'finance dashboard' with income per month, quarter and year. You can enter your company details and start adding invoices, simply print them as PDF and send them to your customers. Visiting the dashboard will show you how well your company is doing.

## Templates

By default, 2 templates are added; One for the invoice and one for the receipt. You can add your own templates inside a template folder of your theme. Copy the template files from the plugin to your theme and start editing. Don't forget the print css for the pdf generation.

## Installation

Github Installation (you'll automatically get notified about new updates):

1. Install [Github Updater](https://github.com/afragen/github-updater)
2. Activate Github Updater
3. Go to Settings > Github Updater > Install Plugin
4. Enter the url of this repo (https://github.com/houke/finance-dashboard)
5. Click 'Install plugin'
6. Activate the plugin through the 'Plugins' menu in WordPress
7. Your Finance Dashboard is now ready

Manual Installation (you'll need to check this repo for new updates):

1. Download this plugin
2. Unzip and upload the folder to your '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Your Finance Dashboard is now ready

## Changelog

### 2.1

* Add 2018 files

### 2.0

* Complete rewrite/ restructure
* Add option to automatically increase invoice number

### 1.3

* Changes to back-end layout.
* Better overview of invoice items.
* Add support for private transfers (in/out). (These amounts are disregarded from the taxable profits, but affect your companies captial)

### 1.2

* Add client managemant.
* Connect client to invoices via search interface. This fixes reentering client details in recurring invoices.

### 1.1

* Quickly mark invoices as paid from the overview screen & from the Dashboard.

### 1.0

* First official release

## Screenshots

![Dashboard - Year](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/dashboard-1.png)

![Dashboard - Quarter](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/dashboard-2.png)

![Admin - settings](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/admin-1.png)

![Admin - income](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/admin-2.png)

![Admin - receipts](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/admin-3.png)

![Template - invoice](https://raw.githubusercontent.com/houke/finance-dashboard/master/screenshots/invoice.png)

_This is built for my girlfriend's company. Used the sprInvoice WordPress plugin as a starting point. Feel free to give it a try, but use at your own risk (always check if the numbers are correct, don't trust blindly on this plugin)_

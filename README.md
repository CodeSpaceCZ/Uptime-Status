<img src="./public/icon/success.webp" width="120px" alt="Uptime Status logo">

# Uptime Status

Modern and simple alternative public status page for **[Uptime Kuma](https://uptime.kuma.pet/)**.

## Overview

Uptime Status is your go-to solution for creating a stylish, lightning-fast, and straightforward public status page, designed to seamlessly complement Uptime Kuma. This status page offers a range of features, including:

- **Modern and Fast**: A sleek, up-to-date design that loads quickly for users.
- **Simple Configuration**: Easily customizable right from within Uptime Kuma's interface.
- **Global Status at a Glance**: Instantly view the overall status of all your services in the page header.
- **Grouping and Monitoring**: Organize your services into groups and closely monitor their performance.
- **Heartbeats and More**: Utilize various Uptime Kuma features to ensure comprehensive monitoring.
- **Multiple Backends and Failovers**: Uptime Status provides a way to setup multiple Uptime Kuma backends with automatic failover.
- **No JavaScript Required**: A JavaScript-free experience for a wider audience.

## How it works

Uptime Status fetches data from Uptime Kuma's public status page API. This way, you can share your status pages while keeping your Uptime Kuma instance secure within your private network.

## Requirements

To get Uptime Status up and running, you'll need the following:

- **Web Server**: Any web server will do (e.g., Apache2, Nginx, etc.).
- **PHP**: Version 7.4 or higher.
- **Composer**: This is essential for installing dependencies.

## Installation

Setting up Uptime Status is straightforward. Follow these steps to get started:

1. **Install Dependencies**:

	Use Composer to install Uptime Status and all the necessary dependencies, whether you're on your server or local device.

	```sh
	composer require codespace/uptime-status
	```

2. **Retrieve project's files**:

	Copy the `public` directory from `vendor/codespace/uptime-status` to the directory where you ran the `composer` command. You can also copy the config directory with `config.inc.php` template configuration file there.

	```sh
	cp -r vendor/codespace/uptime-status/public .
	cp -r vendor/codespace/uptime-status/config .
	```

3. **Copy the Files to Your Web Server**:

	Ensure that your web server's virtual host document root is configured to point to the application's `public` directory.

4. **Edit the Configuration File**:

	Don't forget to edit the `config.inc.php` file and set your Uptime Kuma's URL and status page.

Once you've completed these steps, you're all set to start using Uptime Status.

## Development

If you're interested in developing Uptime Status or testing it on your local device, make sure you have [PHP](https://php.net) and [Composer](https://getcomposer.org/) installed. Finally, you can simply clone this repository and run `composer run dev` to spin up a local PHP dev server.
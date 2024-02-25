<h1 style="text-align: center">PHP Firewalla API </h1>
<hr>
This API is meant to interact with Firewalla's MSP API. This version is meant to be run on a web server to dynamically 
update target lists or display specific information relative to your needs. 

My original intent for this project was to update my own Firewalla based on some Fail2Ban unauthorized attempts. Baseed 
on some user feedback, I've extended it to include other endpoints offered by Firewalla. 

I've included a few examples in the "examples" folder on how to use the classes and service.

<hr>

### Usage ###

This project uses the DotEnv class to increase security and keep API Keys and other passwords out of version control. 
You will need to execute the `composer install` to build and install the required classes this project uses. Make sure 
to copy the `.env.sample` file to `.env` in your root directory, or adjust the folder the `.env` file will be saved 
in the `__construct()` method of the `Firewalla\Service` class.
You must `require_once` the `Firewalla\Service` class which will also include the `Firewalla\Autoloader` for all the Files
that will be built/used by the Firewalla API classes.
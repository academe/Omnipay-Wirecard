[![GitHub license](https://img.shields.io/badge/license-GPL-blue.svg)](https://raw.githubusercontent.com/academe/OmniPay-Wirecard/master/LICENSE.md)
[![Packagist](https://img.shields.io/packagist/v/academe/omnipay-wirecard.svg?maxAge=2592000)](https://packagist.org/packages/academe/omnipay-wirecard)
[![GitHub issues](https://img.shields.io/github/issues/academe/OmniPay-Wirecard.svg)](https://github.com/academe/OmniPay-Wirecard/issues)
[![Travis](https://travis-ci.org/academe/Omnipay-Wirecard.svg?branch=master)](https://travis-ci.org/academe/Omnipay-Wirecard)
[![GitHub forks](https://img.shields.io/github/forks/academe/Omnipay-Wirecard.svg)](https://github.com/academe/Omnipay-Wirecard/network)

# Omnipay-Wirecard

Wirecard payment gateway driver for the Omnipay

## Gateways APIs Supported

Just Wirecard *Checkout Page* is supported for now,
which includes most *Checkout Page Backend* operations to support the front end.

This *Checkout Page* offers a payment page hosted by the gateway,
can be partially customised, and that can either be shown in an iframe
or navigated to as the top window.

## Why This Package

There are a few other Omnipay Wirecard drivers already,
[which you should explore](https://packagist.org/search/?q=omnipay-wirecard)
to see if any fit your needs.
This package was created with a number of prerequitits:

* It supports Omnipay 2.x following as many of the Omnipay standards/conventions
  as possible. This is to help integration into multi-gateway systems and wrappers
  with the least custom programming as possible.
* It does not use the Wirecard SDK. Though very complete in terms of functionaluty
  coverage, the SDK is locked onto HTTP clients that are not compatible with many
  sites using Omnipay 2.x
* It does not need an external serializer, that is an issue for some applications.

As Omnipay 3.x goes into beta, the intentiion is to branch this package to support
that version, but carry on maintaining the 2.x branch.


## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "academke/omnipay-wirecard": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

Or combine the two steps into one with composer on the path::

    $ composer require "academke/omnipay-wirecard: ~2.0"

## Wirecard Checkout Page

The *Wirecard Checkout Page* mode supports a remote checkout page that
the user is sent to. The user is returned to the merchant site with the
results, after completing their authorisation.
The same details and some additional details for the transaction are sent
to a backend notification handler. This allows the merchant site transaction
to be completed regardless of what happens on the front end.

The remote checkout page can be customised to an extent, and can run as
the full page or in an iframe. The page is responsive, so will adapt to
any iframe size set on the merchant site.

### Demo Mode and Test Mode

There are no separate endpoints for running tests. Instead, customer IDs
and secrets are published to trigger demo and test mode.

Demo mode does not involve the end merchant banks in any processng.
Test mode does involve the end merchant banks, so can involve 3D Secure
tests, but still no payments are taken.

**Demo** mode is invoked by using these details:

| Field | Value |
|:----- |:----- |
| customerId | D200001 |
| secret | B8AKTPWBRMNBV455FG6M2DANE99WU2 |
| shopId | *not used for the demo account* |
| toolkitPassword | jcv45z |

The `toolkitPassword` is only needed if you need to `capture` an authorisation
or `refund` a payment (also `void` and a few additional backend commands when
they are implemented).

The list of demo credit cards that 
[can be found](https://guides.wirecard.at/wcp:demo_mode).

**Test** mode is invoked by using these details for non-3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ |
| shopId | *not used for the demo account* |
| toolkitPassword | 2g4f9q2m |

**Test** mode is invoked by using these details for 3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F |
| shopId | 3D |
| toolkitPassword | 2g4f9q2m |

Test mode credentials and test cards
[can be found here](https://guides.wirecard.at/wcp:test_mode).

## The Checkout Page Gateway Class

This class is created qne configured like this:

```php
$gateway = Omnipay\Omnipay::create('Wirecard_CheckoutPage');

// This customer ID invokes demo mode. Try credit card MC: 9500000000000002
$gateway->setCustomerId('D200001');
$gateway->setSecret('B8AKTPWBRMNBV455FG6M2DANE99WU2');

// Because failureUrl and serviceUrl are gateway-specific, they can also be set
// as gateway configuration options:
$gateway->setFailureUrl('https://example.com/complete?status=failure');
$gateway->setServiceUrl('https://example.com/terms_of_service_and_contact');

// Most other gateway and API-specific parameters (i.e. those not recognised by
// the Omnipay core) can be set at the gateway or the message level.
```

These are the parameters that can be set when instantiating the Checkout Page gateway:

| Name | Type | Required | Notes |
| ---- | ---- | -------- | ----- |
| customerId | string | Yes | |
| shopId | string | No | |
| secret | string | Yes | |
| language | string | No | ISO two-letter; defaults to "en" |
| toolkitPassword | string | Yes for backend only | For server-to-server requests (void, capture, etc) |
| failureUrl | string | Yes for new transactions | URL |
| serviceUrl | string | Yes for new transactions | URL |
| --- | --- | --- | --- |
| noScriptInfoUrl | string | No | URL |
| windowName | string | No | |
| duplicateRequestCheck | boolean | No | Supplied value of whatever type will be cast to boolean |
| transactionIdentifier | string | No | |
| financialInstitution | string | No | |
| cssUrl | string | No | URL |
| --- | --- | --- | --- |
| displayText | string | No | |
| imageUrl | string | No | URL |
| backgroundColor | string | No | Hex value e.g. "ffcc00" |
| maxRetries | integer | No | |
| paymenttypeSortOrder | string | No | |

Documentation for these parameters can be found here: https://guides.wirecard.at/request_parameters

## purchase request

The purchase method returns an object to support a POST to the remote gateway form.
The POST can be a form, or a JavaScript object.
It can be invoked the user pressing a submit button or automatically using JavaScript.
It can target the top window or an iframe.

Here is a minimal example:

```php
$request = $gateway->purchase([
    ...normal purchase data (TODO: examples)...
    ...additional gateway-specific feature data (TODO: examples)...
    //
    // These three URLs are required to the gateway, but will be defaulted to the
    // returnUrl where they are not set.
    'returnUrl' => 'https://example.com/complete',
    //'cancelUrl' => 'https://example.com/complete?status=cancel', // User cancelled
    //'failureUrl' => 'https://example.com/complete?status=failure', // Failed to authorise
    //
    // These two URLs are required.
    'notifyUrl' => 'https://example.com/acceptNotification',
    'serviceUrl' => 'https://example.com/terms_of_service_and_contact',
    //
    'confirmMail' => 'shop.admin@example.com',
]);
$response = $request->send();

// Quick and dirty way to POST to the gateway, to get to the
// remote hosted payment form.
// This is ignoring error checking, as detailed in the Omnipay documentation.
echo $response->getRedirectResponse();
exit;
```

Alternatively put the data into a custom form that the user can submit:

```php
// This form could target an iframe.
echo '<form action="' . $response->getRedirectUrl() . '" method="POST" accept-charset="UTF-8">';

foreach($response->getRedirectData() as $name => $value) {
    echo '<input type="hidden" name="'.htmlspecialchars($name).'" value="'.htmlspecialchars($value).'" />';
}

echo '<button type="submit">Pay Now</button>';
echo "</form>";
```

## authorize request

While `payment` requests that the funds are automatically taken (usually at midnight of that day)
and `authorize` will leave the funds to be captured at a later date.
For most services you will have between 7 and 14 days to enact the capture.

By default, a Wirecard account will just support `authorize`.
You may need to request that the `purchase` option be enabled for your account.
It is known as "auto-deposit", and that is what you will need to ask for.

## capture request

To capture an authorisation in full, you will need the toolkit password.
This password gives you access to the backend API, which the capture uses.

So set up the gateway first. These details are used to access the test instance:

```php
$gateway->setCustomerId('D200411');
$gateway->setSecret('DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F');
$gateway->setShopId('3D'); // Or leave not set if using the non-3D Secure test cards.
$gateway->setToolkitPassword('2g4f9q2m');
```

You will need the original transaction reference from the `completeAuthorize`
response or the `acceptNotification` server response:

```php
$transactionReference = $complete_response->getTransactionReference();

// or

$transactionReference = $server_response->getTransactionReference();
```

Then send the request for the original full amount:

```php
    $request = $gateway->capture([
        'amount' => '1.00',
        'currency' => 'EUR',
        'orderNumber' => $transactionReference,
        // or
        'transactionReference' => $transactionReference,
    ]);
    $response = $request->send();

    // If successfully captured you will get this response:

    $response->isSuccessful(); // true

    // If not successful, details will be available:

    // Code and message from the gateway:
    $response->getCode();
    $response->getMessage();
    // Message from the remote financial merchant, if available:
    $response->getPaySysMessage();
```

If you wish to capture just a portion of the original authorisation,
then an `ItemBag` can be passed in here with details of just those items
being captured. That can include partial quantities of one or more items,
for example just 10 of the 20 cans of beans that have been authorised.

More details on how partial capture works will be added in due course.

## refund

This is set up and used exactly the same as for `capture`.

## completePurchase/completeAuthorize

This payment method will send the user off to the Wirecard site to authorise
a payment. The user will return with the result of the transaction, which
if parsed by the `completePurchase` object.

    $complete_purchase_request = $gateway->completePurchase();

The message will be signed to check for alteration enroute, so the gateway
needs to be given the `secret` when instantiating it.

Here `$complete_purchase_request` will contain all the data needed to parse the
result.

    // Checks the message is correctly signed.
    $complete_purchase_request->isValid();
    
    // Checks if the authorisation was successful.
    // If the fingerprint signing fails, then this will return false.
    $complete_purchase_request->isSuccessful();
    
    // Get the success or failure message.
    // Some messages are generated by the gateway, and some are filled
    // in by this driver.
    $complete_purchase_request->getMessage();
    
    // Checks if the authorisation was cancelled.
    $complete_purchase_request->isCancelled();
    
    // Get the raw data.
    $complete_purchase_request->getData();
    
    // Get the transaction ID.
    $complete_purchase_request->getTransactonId();
    
    // Get the transaction reference.
    $complete_purchase_request->getTransactonReference();

The merchant site will normally `send()` the `$complete_purchase_request`
to get the final response object. In this case, you will just get the same
object back - it acts as both request and response.

```php
$complete_purchase_response = $complete_purchase_request->send();
// $complete_purchase_response == $complete_purchase_request // true
```

## Notification ("confirm") Handler

The notification URL will be accessed by the following IPv4 addresses.
This driver does not look at the IP address.

* 195.93.244.97
* 185.60.56.35
* 185.60.56.36

The notification handler will send the same data as the front-end returns
to the merchant site with the user. It will include some additional
security-sensitive details that cannot be exposed to the user.

The notification handler does not need to respond to the notification
in any special way other than by returning a HTTP 200 code.
This driver leaves the merchant site to exit after processing the result.


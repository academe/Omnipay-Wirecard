[![GitHub license](https://img.shields.io/badge/license-GPL-blue.svg)](https://raw.githubusercontent.com/academe/OmniPay-Wirecard/master/LICENSE.md)
[![Packagist](https://img.shields.io/packagist/v/academe/omnipay-wirecard.svg?maxAge=2592000)](https://packagist.org/packages/academe/omnipay-wirecard)
[![GitHub issues](https://img.shields.io/github/issues/academe/OmniPay-Wirecard.svg)](https://github.com/academe/OmniPay-Wirecard/issues)
[![Travis](https://travis-ci.org/academe/Omnipay-Wirecard.svg?branch=master)](https://travis-ci.org/academe/Omnipay-Wirecard)
[![GitHub forks](https://img.shields.io/github/forks/academe/Omnipay-Wirecard.svg)](https://github.com/academe/Omnipay-Wirecard/network)

Table of Contents
=================

   * [Table of Contents](#table-of-contents)
   * [Omnipay-Wirecard](#omnipay-wirecard)
      * [Gateway APIs Supported](#gateway-apis-supported)
      * [Why This Package](#why-this-package)
      * [Installation](#installation)
   * [API Details](#api-details)
      * [Demo Mode and Test Mode](#demo-mode-and-test-mode)
      * [Wirecard Checkout Page](#wirecard-checkout-page)
         * [Page Demo Mode Credentials](#page-demo-mode-credentials)
         * [Page Test Mode Credentials](#page-test-mode-credentials)
         * [Initialise The Checkout Page Gateway](#initialise-the-checkout-page-gateway)
         * [Page Purchase Request](#page-purchase-request)
         * [Page Authorize Request](#page-authorize-request)
         * [Page Capture Request](#page-capture-request)
         * [Page Refund Request](#page-refund-request)
         * [Complete Purchase/Authorize](#complete-purchaseauthorize)
         * [Page Recur Authorize/Purchase Request](#page-recur-authorizepurchase-request)
         * [Notification Handler](#notification-handler)
      * [Wirecard Checkout Seamless](#wirecard-checkout-seamless)
         * [Seamless Demo Mode Credentials](#seamless-demo-mode-credentials)
         * [Seamless Test Mode Credentials](#seamless-test-mode-credentials)
         * [Initialise the Data Store](#initialise-the-data-store)
      * [Note About Order Numbers](#note-about-order-numbers)
      * [Extended ItemBag Items](#extended-itembag-items)
   * [Backend Features Implemented](#backend-features-implemented)

# Omnipay-Wirecard

Wirecard payment gateway driver for the Omnipay framework.

## Gateway APIs Supported

Both Wirecard *Checkout Page* and *Checkout Seamless* is supported.

The *Checkout Page* offers a payment page hosted by the gateway, that
can be partially customised, and that can either be shown in an iframe
or navigated to as the top window.

Wirecard *Checkout Seamless* allows a site to use its own form, but avoid
having the credit card details sent to the site by using AJAX to send them
directly to the gateway.

## Why This Package

There are a few other Omnipay Wirecard drivers already,
[which you should explore](https://packagist.org/search/?q=omnipay-wirecard)
to see if any fit your needs.
This package was created with a number of prerequitits:

* It supports Omnipay 2.x following as many of the Omnipay standards/conventions
  as possible. This is to help integration into multi-gateway systems and wrappers
  with the least custom programming as possible.
* It does not use the Wirecard SDK. Though very complete in terms of functionality
  coverage, the SDK is locked onto HTTP clients that are not compatible with many
  sites using Omnipay 2.x
* It does not need an external serializer, that is an issue for some applications.

As Omnipay 3.x goes into beta, the intention is to branch this package to support
3.x, but carry on maintaining the 2.x branch while it is still in active use.
The differences between the two should be very small and localised.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "academe/omnipay-wirecard": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

Or combine the two steps into one with composer on the path::

    $ composer require "academe/omnipay-wirecard: ~2.0"

# API Details

## Demo Mode and Test Mode

There are no separate endpoints for running tests. Instead, customer IDs
and secrets are published to trigger demo and test mode.

Demo mode does not involve the end merchant banks in any processng.
Test mode does involve the end merchant banks, so can involve 3D Secure
tests, but still no payments are taken.

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

### Page Demo Mode Credentials

Demo mode is invoked by using these details:

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

### Page Test Mode Credentials

Test mode is invoked by using these details for non-3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ |
| shopId | *not used for the demo account* |
| toolkitPassword | 2g4f9q2m |

Test mode is invoked by using these details for 3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F |
| shopId | 3D |
| toolkitPassword | 2g4f9q2m |

Test mode credentials and test cards
[can be found here](https://guides.wirecard.at/wcp:test_mode).

### Initialise The Checkout Page Gateway

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

### Page Purchase Request

The purchase method returns an object to support a POST to the remote gateway form.
The POST can be a form, or a JavaScript object.
It can be invoked by the user pressing a submit button or automatically using JavaScript.
It can target the top window or an iframe.

Here is a minimal example:

```php
$request = $gateway->purchase([
    'transactionId' => $transactionId, // merchant site generated ID
    'amount' => "9.00",
    'currency' => 'EUR',
    'invoiceId' => 'FOOOO',
    'description' => 'An order',
    'paymentType' => 'CCARD',
    'card' => $card, // billing and shipping details
    'items' => $items, // array or ItemBag of Omnipay\Common\Item or Omnipay\Wirecard\Extend\Item objects
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

### Page Authorize Request

While `payment` requests that the funds are automatically taken (usually at midnight of that day)
and `authorize` will leave the funds to be captured at a later date.
For most services you will have between 7 and 14 days to enact the capture.

By default, a Wirecard account will just support `authorize`.
You may need to request that the `purchase` option be enabled for your account.
It is known as "auto-deposit", and that is what you will need to ask for.

### Page Capture Request

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

### Page Refund Request

This is set up and used exactly the same as for `capture`.

### Complete Purchase/Authorize

This payment method will send the user off to the Wirecard site to authorise
a payment. The user will return with the result of the transaction, which
is parsed by the `completePurchase` object.

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
    
    // Checks if the authorisation was cancelled by the user.
    $complete_purchase_request->isCancelled();
    
    // Get the raw data.
    $complete_purchase_request->getData();
    
    // Get the transaction ID (generated by the merchant site).
    $complete_purchase_request->getTransactonId();
    
    // Get the transaction reference (generated by the gateway).
    $complete_purchase_request->getTransactonReference();

The merchant site will normally `send()` the `$complete_purchase_request`
to get the final response object. In this case, you will just get the same
object back - it acts as both request and response.

```php
$complete_purchase_response = $complete_purchase_request->send();
// $complete_purchase_response == $complete_purchase_request // true
```

### Page Recur Authorize/Purchase Request

A new authorisation or purchase can be created from an existing order.

```php
// or $gateway->recurAuthorize([...])
$request = $gateway->recurPurchase([
    'amount' => 3.10,
    'currency' => 'GBP',
    'description' => 'A recuring payment',
    'sourceOrderNumber' => $originalTransactionReference,
]);

$response = $request->send();

// The order reference is needed to capture the pament if just authorizing.
$new_order_number = $response->getOrderReference();
```

This is a backend operation, though takes many parameters that are otherwise
only available to the front end authorise or purchase, for example billing and
shipping details.
See the
[Wirecard documentation](https://guides.wirecard.at/back-end_operations:transaction-based:recurpayment)
for details on other parameters that can be used and are available in the response.

### Notification Handler

The back-channel notification handler is know as the "confirm" request in
the Wirecard documentation.

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

## Wirecard Checkout Seamless

The *Wirecard Checkout Seamless* gateway is designed to keep the customer on
the merchant site.
It works like this:

* A temporary data store is initialised on the remote gateway. The merchant site
  is given a token to represent this storage, called the `storageId`.
  This single-used data store will last for 30 minutes or until it is used.
* A custom form is provided on the front end that captures authorisation details
  for the payment. These details are not posted back to the merchant site.
* JavaScript sends the authorisation details entered by the user to the data store
  using AJAX.
* Optionally, the data store JavaScript can provide anonymised versions of the data
  entered, which can be posted back to the merchant site if required.
* The merchant site then posts the authorisation or purchase transaction request to
  the remote gateway, using the `storageId` in place of credit card details.
* The response is handled by the merchant site, which may include a 3D Secure
  redirect when a credit card payment method is used. Even without 3D Secure, the
  gateway will always redirect the user to a site to enact the authorisarion.
* On return to the merchant site, the transaction result can be retrieved from the
  details stored by the notification handler.

Note that there are a dozen or so payment methods that can be used,
and not all need to use the secure storage.
All will involve a redirect, either to a third-party financial service, or to the
Wirecard gateway.

If the data sent via AJAX is malformed or invalid, for example a past expiry date
or a credit card number failing the luhn check, then a list of errors are returned
for informing the end user.

Each payment method will require a different set of fields to be sent to the
data store (where the data store is used).
This driver provides a list of the fields, but constructing the form, applying
validation, handling the AJAX and reporting errors to the user in response to
the AJAX result, is out of scope.

This driver provides a method to initialise the data storage, to POST the
transaction request, to handle the return from a redirect (3D Secure or otherwise)
and to capture the back-channel notifications.

### Seamless Demo Mode Credentials

Demo mode is invoked by using these details:

| Field | Value |
|:----- |:----- |
| customerId | D200001 |
| secret | B8AKTPWBRMNBV455FG6M2DANE99WU2 |
| shopId | seamless |
| password | jcv45z |

The `toolkitPassword` is only needed if you need to `capture` an authorisation
or `refund` a payment (also `void` and a few additional backend commands when
they are implemented).

Demo mode and test mode credentials
[can be found here](https://guides.wirecard.at/demo:wcs_demo_and_test_mode?s[]=seamless3d).

### Seamless Test Mode Credentials

Test mode is invoked by using these details for non-3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ |
| shopId | seamless |
| password | 2g4f9q2m |

Test mode is invoked by using these details for 3D Secure tests:

| Field | Value |
|:----- |:----- |
| customerId | D200411 |
| secret | DP4TMTPQQWFJW34647RM798E9A5X7E8ATP462Z4VGZK53YEJ3JWXS98B9P4F |
| shopId | seamless3D |
| toolkitPassword | 2g4f9q2m |

Demo mode and test mode credentials
[can be found here](https://guides.wirecard.at/demo:wcs_demo_and_test_mode?s[]=seamless3d).

### Initialise the Data Store

*Checkout Seamless* works by providing a temporary store for credit card details
at the gateway. The details entered by the user are sent directly to that store
and do not reach the merchant site.

The process for using *Checkout Seamless* is described below.

First a store for teh credit card details must be initialised. The store will
have a unique ID and will be available for up to 30 minutes before it expires.

```php
$gateway = Omnipay\Omnipay::create('Wirecard_CheckoutSeamless');

$gateway->intitialize([
    'customerId' => 'D200411',
    'shopId' => 'seamless',
    'secret' => 'CHCSH7UGHVVX2P7EHDHSY4T2S4CGYK4QBE4M5YUUG2ND5BEZWNRZW5EJYVJQ',
    'toolkitPassword' => '2g4f9q2m',
    ...
]);

$request = $gateway->storageInit([
    'paymentMethod' => 'CCARD',
    'returnUrl' => $returnUrl,
    'transactionId' => $merchantTransactionId,
]);

$response = $request->send();

// The storageId will be needed by the front end JS library, and also
// when submitting the order at the back end.
$response->getStorageId();
```

Note that not all payment methods require the use of remove storage.
Those that do not, will not return a storageId.

This is the initialising JavaScript needed in the page:

```javascript
<script src="{url}" type="text/javascript"></script>
<script type="text/javascript">
    var dataStorage = new WirecardCEE_DataStorage();
    var paymentInformation = {};
</script>
```

Where {url} is given by `$response->getJavascriptUrl()`

This is where the credit card details need to be copied to:

```javascript
<script type="text/javascript">
    paymentInformation.pan = '5500000000000012';
    paymentInformation.expirationMonth = '01';
    paymentInformation.expirationYear = '2019';
    paymentInformation.cardholdername = 'John Doe';
    paymentInformation.cardverifycode = '012';
</script>
```

Shown above are the gateway test credit card details. How you get these details from
your credit form into this object is up to you, but will involve JavaScript of
some sort.

The callback function get the result from storing the credit card details will look
something like show below.
This will be invoked when the credit card details are sent to storage, after the user
submits their payment form. It can be used to capture anonymised details for the
credit card, or a list of errors that may have occurred while trying to store.

```javascript
<script type="text/javascript">
callbackFunction = function(aResponse) {
    // checks if response status is without errors
    if (aResponse.getStatus() == 0) {
        // Gets all anonymized payment information to a JavaScript object
        var info = aResponse.getAnonymizedPaymentInformation();

        // Each anonymised field is in info.{name}
        // where a list of {name} strings is supplied by
        // $response->getStorageFieldsAnonymous(), a list which will vary
        // depending on the payment type.
    } else {
        // Collects all occurred errors and add them to the result string
        var errors = aResponse.getErrors();
        for (e in errors) {
            // Here you have errors[e].errorCode, errors[e].message and
            // errors[e].consumerMessage to display to the user and/or send
            / back to the merchant site.
        }
    }
}
</script>
```

When the payment form is submitted, and the card details are copied to
the `paymentInformation` object, store the details like this:

```javascript
<script type="text/javascript">
dataStorage.{storageFunction}(paymentInformation, callbackFunction);
</script>
```

Where {storageFunction} is given by `$response->getDataStorageStoreFunctionName()`.

On final submission to the merchant site, the site checks if the storage returned
anonymised card details or a list of errors.

You can then make the payment in the usual way:

```php
$request = $gateway->purchase([
    'paymentMethod' => 'CCARD',
    'transactionId' => $merchantTransactionId,
    'amount' => 3.10,
    'currency' => 'EUR',
    'description' => 'A required description',
    'returnUrl' => $merchantSiteReturnUrl,
    'notifyUrl' => $merchantSiteNotifyUrl,
    'storageId' => $storageId, // This is the key to where the CC details are stored.
    ...
]);

$response = $request->send();
```

The response is then handled in the same way as described in the *Checkout Page*
instructions, which may or may not involve a 3D Secure redirect.

## Note About Order Numbers

A transaction on the gateway is uniquely identified *within an account* by a numeric
seven-digit value. The order number will be generated on the creation of a transaction,
or it can be generated in advance if that helps the merchant site processes.

To generate, i.e. reservce in advance, an order number, use this method:

```php
$response = $gateway->createOrderNumber()->send();
$orderNumber = $response->getOrderNumber();

// This is aliased in more Omnipay terms:
$transactionReference = $response->getTransactionReference();
```

Then when creating an authorisation or payment, send this order number (or
transactionReference) with the transaction request:

```php
$request = $gateway->purchase([
    'transactionReference' => $transactionReference,
    ...
]);
```

So you give the gateway the `transactionReference` to use, but it must be one
you have already reserved with the gateway. Each can only be used once, may or
may not be sequential, and are unique to your account (customerId) only.
The order number range is shared with credit notes (refunds) and payment numbers.
Once reserved, there is no specified expiry time for an orderNumber.

## Extended ItemBag Items

This driver will accept standard OmniPay items in the ItemBag.
When these are supplied, some fields sent to the gateway will be defaulted:

* `articleNumber` will be the sequential order of the item, starting at 1 for the first item.
* `imageUrl` will be left blank.
* `netAmount` and `grossAmount` will be the same as the `amount`.
* `taxRate` will be zero.

An extended Item is created like this example:

```php
$item = new Omnipay\Wirecard\Extend\Item([
    'articleNumber' => 'SKU1',
    'price' => '3.10',
    'quantity' => '1',
    'name' => 'Name One',
    'imageUrl' => 'http://example.com',
    'description' => 'FooBar',
    'netAmount' => '3.00',
    'taxAmount' => '27',
    'taxRate' => '10',
]);
```

# Backend Features Implemented

This is the complete list of transaction-based operations.
The backend feactures are all available for both the *Seamless* and the *Page* variations on
the gateway, and both variations work the same way for the merchant site, just with a slight
variation in endpoints and a single internal parameter.

| Wirecard Operation | Omnipay Operation | Message Class (when complete) |
| ------------------ | ----------------- | ----------------------------- |
| [approveReversal](https://guides.wirecard.at/back-end_operations:transaction-based:approvereversal) | n/a | *VoidAuthorizeRequest |
| [deposit](https://guides.wirecard.at/back-end_operations:transaction-based:deposit) | capture | *CaptureRequest |
| [depositReversal](https://guides.wirecard.at/back-end_operations:transaction-based:depositreversal) | void | *VoidCaptureRequest |
| [getOrderDetails](https://guides.wirecard.at/back-end_operations:transaction-based:getorderdetails) | n/a | *FetchTransactionRequest |
| [recurPayment](https://guides.wirecard.at/back-end_operations:transaction-based:recurpayment) | n/a | *RecurAuthorizeRequest |
| [refund](https://guides.wirecard.at/back-end_operations:transaction-based:refund) | refund | *RefundRequest |
| [refundReversal](https://guides.wirecard.at/back-end_operations:transaction-based:refundreversal) | n/a | *VoidRefundRequest |
| [transferFund](https://guides.wirecard.at/back-end_operations:transaction-based:transferfund) | n/a | TODO |


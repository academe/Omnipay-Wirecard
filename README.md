# Omnipay-Wirecard

Wirecard payment gateway driver for the Omnipay

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

Demo mode is invoked by using these details:

| Field | Value |
|:----- |:----- |
| customerId | D200001 |
| secret | B8AKTPWBRMNBV455FG6M2DANE99WU2 |
| shopId | *not used* |

The list of demo credit cards that 
[can be found](https://guides.wirecard.at/wcp:demo_mode).

Test mode credentials and test cards
[can be found here](https://guides.wirecard.at/wcp:test_mode).

## completePurchase

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

    $complete_purchase_response = $complete_purchase_request->send();
    // $complete_purchase_response == $complete_purchase_request // true

## Notification ("confirm") Handler

The notification URL will be accessed by the following IPv4 addresses.
This driver does not look at the IP address.

* 195.93.244.97
* 185.60.56.35
* 185.60.56.36


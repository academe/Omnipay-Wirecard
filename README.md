# Omnipay-Wirecard

Wirecard payment gateway driver for the Omnipay

## Wirecard Checkout Page

The *Wirecard Checkout Page* mode supports a remote checkout page that
the user is sent to. The user is returned to the merchant site with the
results, after completing their authorisation. Additional details for
the transaction are sent to a backend notification handler.

### Demo Mode and Test Mode

There are no separate endpoints for running tests. Instead, customer IDs
and secrets are published to trigger demo and test mode.

Demo mode does not involve the end merchant banks in any processng.
Test mode does involve the end merchant banks, so can involve 3D Secure
tests, but still no payments are taken.

Demo mode is invoked by using these details:

| ---------- | ------- |
| customerId | D200001 |
| secret | B8AKTPWBRMNBV455FG6M2DANE99WU2 |
| shopId | *not used* |

The list of demo credit cards that 
[can be found](https://guides.wirecard.at/wcp:demo_mode).

Test mode credentials and test cards
[can be found here](https://guides.wirecard.at/wcp:test_mode).



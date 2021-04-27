# Changelog ing-php

** 1.5.1 **

* Add details to the CreditCardPaymentInfo class

** 1.5.0 **

* Remove dependency on Guzzle (replacing it with Httpful, any version).


** 1.4.2 **

* Remove direct dependencies on symfony/translation and symfony/yaml.


** 1.4.1 **

* Added AfterPay and fix for empty array values


** 1.4.0 **

* Added new 'declined' status of Transaction


** 1.3.3 **

* Resolved issue when zero array value was treated as NULL


** 1.3.2 **

* Added Payconiq Payment Method

* Updated License content to reflect ING Bank N.V


** 1.3.1 **

* Fixed issue with Klarna payment shipped state wrong url


** 1.3.0 **

* Added support for orders captured status

* Added endpoint resolver class implemented.


** 1.2.9 **

* Fixed issue with duplicate PayPal payment method details file

* Updated Ramsey\Uuid library to 3.6

* Added Bitbucket Pipelines configuration


** 1.2.8 **

* Fixed issue with PayPal camelcase naming

* Added unit test for PayPal payment method details 


** 1.2.7 **

* Updated unit tests


** 1.2.6 **

* Updated composer library random_int

* Added missing SOFORT product type and removed check for test mode

* Added missing payment method details for PayPal and HomePay

* Added klarna to the allowed payments method check


** 1.2.5 **

* Corrected typo in the PHP doc

* Fixed the PHP5.6 Support

* Correction of more Typos

* Added HomePay

* Added PayPal to the order creation functionality

* Added payment method details for Klarna

* Updated Klarna specific order data

* Added Order lines implementation & unit tests

* Added Klarna to ING API PHP library

* Fixed issue with null values in PUT request method


** 1.2.3 ** 

* Initial Commit

* Added webhook_urk to COD order

* Added webhook_url to order creation

* Updated ing-php library to latest ginger-php release

* Updated README file & .gitignore
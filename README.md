GeoCommunicator Client
======================

This project provides a PHP SOAP client to consume the API provided by the Depart of the Interior's Bureau of Land Management's [GeoCommunicator API](http://www.geocommunicator.gov). The API allows you to translate Latitude and Longitude coordinates within the US into State, Township, Range and Section information.

There is a provided conversion class which traverses through an XLS file and returns it to you with the API results added.

Requirements
------------

* The client uses PHP's SOAPClient class
* The provided convertor uses PHPExcel

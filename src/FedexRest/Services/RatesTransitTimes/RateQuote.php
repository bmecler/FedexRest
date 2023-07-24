<?php

namespace FedexRest\Services\RatesTransitTimes;

use FedexRest\Entity\Item;
use FedexRest\Entity\Address;
use FedexRest\Services\RatesTransitTimes\Entity\EmailNotificationRecipient;
use FedexRest\Services\RatesTransitTimes\Entity\RequestedPackageLineItem;
use FedexRest\Exceptions\MissingAccountNumberException;
use FedexRest\Exceptions\MissingLineItemException;
use FedexRest\Services\AbstractRequest;
use FedexRest\Services\RatesTransitTimes\Type\PickupType;

class RateQuotes extends AbstractRequest
{
    protected int $account_number;
    protected Address $shipper;
    protected Address $recipient;
    protected array $recipients;
    protected string $service_type;
    protected string $packaging_type;
    protected string $ship_datestamp;
    protected EmailNotificationRecipient $email_notification_recipient;
    protected string $pickup_type;
    protected RequestedPackageLineItem $requested_package_line_item;
    protected bool $document_shipment = false;
    protected int $total_package_count;
    protected float $total_weight;

    /**
     * @inheritDoc
     */
    public function setApiEndpoint(): string
    {
        return '/rate/v1/rates/quotes';
    }

    /**
     * @return string
     */
    public function getPackagingType(): string
    {
        return $this->packaging_type;
    }

    /**
     * @param  string  $packaging_type
     * @return RateQuotes
     */
    public function setPackagingType(string $packaging_type): RateQuotes
    {
        $this->packaging_type = $packaging_type;
        return $this;
    }

    /**
     * @param  mixed  $service_type
     * @return RateQuotes
     */
    public function setServiceType(string $service_type): RateQuotes
    {
        $this->service_type = $service_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceType(): string
    {
        return $this->service_type;
    }

    /**
     * @return Address
     */
    public function getShipper(): Address
    {
        return $this->shipper;
    }

    /**
     * @param  Address  $shipper
     * @return $this
     */
    public function setShipper(Address $shipper): RateQuotes
    {
        $this->shipper = $shipper;
        return $this;
    }

    /**
     * @return Address
     */
    public function getRecipient(): Address
    {
        return $this->recipient;
    }

    /**
     * @param  Address  $recipient
     * @return $this
     */
    public function setRecipient(Address $recipient): RateQuotes
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param  int  $account_number
     * @return $this
     */
    public function setAccountNumber(int $account_number): RateQuotes
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param  mixed  $email_notification_recipient
     * @return $this
     */
    public function setEmailNotificationRecipient(EmailNotificationRecipient $email_notification_recipient): RateQuotes
    {
        $this->email_notification_recipient = $email_notification_recipient;
        return $this;
    }

    /**
     * @return EmailNotificationRecipient
     */
    public function getEmailNotificationRecipient(): EmailNotificationRecipient
    {
        return $this->email_notification_recipient;
    }

    /**
     * @param  string  $ship_datestamp
     * @return RateQuotes
     */
    public function setShipDateStamp(string $ship_datestamp): RateQuotes
    {
        $this->ship_datestamp = $ship_datestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getShipDateStamp(): string
    {
        return $this->ship_datestamp;
    }

    /**
     * @param  mixed  $pickup_type
     * @return $this
     */
    public function setPickupType(string $pickup_type): RateQuotes
    {
        $this->pickup_type = $pickup_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getPickupType(): string
    {
        return $this->pickup_type;
    }

    /**
     * @param  mixed  $requested_package_line_item
     * @return $this
     */
    public function setRequestedPackageLineItem(RequestedPackageLineItem $requested_package_line_item): RateQuotes
    {
        $this->requested_package_line_item = $requested_package_line_item;
        return $this;
    }

    /**
     * @return RequestedPackageLineItem
     */
    public function getRequestedPackageLineItem(): RequestedPackageLineItem
    {
        return $this->requested_package_line_item;
    }

    /**
     * @return bool
     */
    public function getDocumentShipment(): bool
    {
        return $this->document_shipment;
    }

    /**
     * @param  bool  $packaging_type
     * @return RateQuotes
     */
    public function setDocumentShipment(bool $document_shipment): RateQuotes
    {
        $this->document_shipment = $document_shipment;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPackageCount(): int
    {
        return $this->total_package_count;
    }

    /**
     * @param  int  $packaging_type
     * @return RateQuotes
     */
    public function setTotalPackageCount(int $total_package_count): RateQuotes
    {
        $this->total_package_count = $total_package_count;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalWeight(): float
    {
        return $this->total_weight;
    }

    /**
     * @param  float  $total_weight
     * @return RateQuotes
     */
    public function setTotalWeight(int $total_weight): RateQuotes
    {
        $this->total_weight = $total_weight;
        return $this;
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface|void
     * @throws MissingAccountNumberException
     * @throws MissingLineItemException
     * @throws \FedexRest\Exceptions\MissingAccessTokenException
     */
    public function request()
    {
        parent::request();
        if (empty($this->account_number)) {
            throw new MissingAccountNumberException('The account number is required');
        }

        $query = $this->http_client->post($this->getApiUri($this->api_endpoint), $this->prepare());
        return ($this->raw === true) ? $query : json_decode($query->getBody()->getContents());
    }


    /**
     * @return array[]
     */
    public function prepare(): array
    {
        $data = [];

       // $data["rateRequestControlParameters"]["returnTransitTimes"] = true;
        //$data["rateRequestControlParameters"]["servicesNeededOnRateFailure"] = true;
        $data["rateRequestControlParameters"]["variableOptions"] = 'FREIGHT_GUARANTEE';
        $data["rateRequestControlParameters"]["rateSortOrder"] = 'SERVICENAMETRADITIONAL';

        if (!empty($this->account_number)) {
            $data['accountNumber']['value'] = $this->account_number;
        }
        if (!empty($this->shipper)) {
            $data['requestedShipment']['shipper'] = $this->shipper->prepare();
        }
        if (!empty($this->recipient)) {
            $data['requestedShipment']['recipient'] = $this->recipient->prepare();
        }
        $data['requestedShipment']['serviceType'] = 'STANDARD_OVERNIGHT';
        if (!empty($this->email_notification_recipient)) {
            $data['requestedShipment']['emailNotificationDetail'] = [$this->email_notification_recipient->prepare()];
        }
        $data['requestedShipment']['preferredCurrency'] = 'USD';
        $data['requestedShipment']['rateRequestType'] = ['ACCOUNT','LIST'];
        if (!empty($this->ship_datestamp)) {
            $data['requestedShipment']['shipDateStamp'] = $this->ship_datestamp;
        }
        if (!empty($this->pickup_type)) {
            $data['requestedShipment']['pickupType'] = $this->pickup_type;
        }
        if (!empty($this->requested_package_line_item)) {
            $data['requestedShipment']['requestedPackageLineItems'] = [$this->requested_package_line_item];
        }
        if (!empty($this->document_shipment)) {
            $data['requestedShipment']['documentShipment'] = $this->document_shipment;
        }
        $data['requestedShipment']['packagingType'] = 'YOUR_PACKAGING';
        if (!empty($this->total_package_count)) {
            $data['requestedShipment']['totalPackageCount'] = $this->total_package_count;
        }
        if (!empty($this->total_weight)) {
            $data['requestedShipment']['totalWeight'] = $this->total_weight;
        }
        //$data['requestedShipment']['groupShipment'] = true;
        $data['carrierCodes'] = ['FDXE'];

       /* return [
            'json' => [$data],
        ]; */

        return [
            'json' => '
            {
                "accountNumber": {
                  "value": "740561073"
                },
                "rateRequestControlParameters": {
                  "returnTransitTimes": false,
                  "servicesNeededOnRateFailure": true,
                  "variableOptions": "FREIGHT_GUARANTEE",
                  "rateSortOrder": "SERVICENAMETRADITIONAL"
                },
                "requestedShipment": {
                  "shipper": {
                    "address": {
                      "streetLines": [
                        "1550 Union Blvd",
                        "Suite 302"
                      ],
                      "city": "Beverly Hills",
                      "stateOrProvinceCode": "TN",
                      "postalCode": "38127",
                      "countryCode": "US",
                      "residential": false
                    }
                  },
                  "recipient": {
                    "address": {
                      "streetLines": [
                        "1550 Union Blvd",
                        "Suite 302"
                      ],
                      "city": "Beverly Hills",
                      "stateOrProvinceCode": "TN",
                      "postalCode": "38127",
                      "countryCode": "US",
                      "residential": false
                    }
                  },
                  "serviceType": "STANDARD_OVERNIGHT",
                  "emailNotificationDetail": {
                    "recipients": [
                      {
                        "emailAddress": "string",
                        "notificationEventType": [
                          "ON_DELIVERY"
                        ],
                        "smsDetail": {
                          "phoneNumber": "string",
                          "phoneNumberCountryCode": "string"
                        },
                        "notificationFormatType": "HTML",
                        "emailNotificationRecipientType": "BROKER",
                        "notificationType": "EMAIL",
                        "locale": "string"
                      }
                    ],
                    "personalMessage": "string",
                    "PrintedReference": {
                      "printedReferenceType": "BILL_OF_LADING",
                      "value": "string"
                    }
                  },
                  "preferredCurrency": "USD",
                  "rateRequestType": [
                    "ACCOUNT",
                    "LIST"
                  ],
                  "shipDateStamp": "2019-09-05",
                  "pickupType": "DROPOFF_AT_FEDEX_LOCATION",
                  "requestedPackageLineItems": [
                    {
                      "subPackagingType": "BAG",
                      "groupPackageCount": 1,
                      "contentRecord": [
                        {
                          "itemNumber": "string",
                          "receivedQuantity": 0,
                          "description": "string",
                          "partNumber": "string"
                        }
                      ],
                      "declaredValue": {
                        "amount": "100",
                        "currency": "USD"
                      },
                      "weight": {
                        "units": "LB",
                        "value": 10
                      },
                      "dimensions": {
                        "length": 10,
                        "width": 8,
                        "height": 2,
                        "units": "IN"
                      },
                      "variableHandlingChargeDetail": {
                        "rateType": "ACCOUNT",
                        "percentValue": 0,
                        "rateLevelType": "BUNDLED_RATE",
                        "fixedValue": {
                          "amount": "100",
                          "currency": "USD"
                        },
                        "rateElementBasis": "NET_CHARGE"
                      },
                      "packageSpecialServices": {
                        "specialServiceTypes": [
                          "DANGEROUS_GOODS"
                        ],
                        "signatureOptionType": [
                          "NO_SIGNATURE_REQUIRED"
                        ],
                        "alcoholDetail": {
                          "alcoholRecipientType": "LICENSEE",
                          "shipperAgreementType": "Retailer"
                        },
                        "dangerousGoodsDetail": {
                          "offeror": "Offeror Name",
                          "accessibility": "ACCESSIBLE",
                          "emergencyContactNumber": "3268545905",
                          "options": [
                            "BATTERY"
                          ],
                          "containers": [
                            {
                              "offeror": "Offeror Name",
                              "hazardousCommodities": [
                                {
                                  "quantity": {
                                    "quantityType": "GROSS",
                                    "amount": 0,
                                    "units": "LB"
                                  },
                                  "innerReceptacles": [
                                    {
                                      "quantity": {
                                        "quantityType": "GROSS",
                                        "amount": 0,
                                        "units": "LB"
                                      }
                                    }
                                  ],
                                  "options": {
                                    "labelTextOption": "Override",
                                    "customerSuppliedLabelText": "LabelText"
                                  },
                                  "description": {
                                    "sequenceNumber": 0,
                                    "processingOptions": [
                                      "INCLUDE_SPECIAL_PROVISIONS"
                                    ],
                                    "subsidiaryClasses": "subsidiaryClass",
                                    "labelText": "labelText",
                                    "technicalName": "technicalName",
                                    "packingDetails": {
                                      "packingInstructions": "instruction",
                                      "cargoAircraftOnly": false
                                    },
                                    "authorization": "Authorization Information",
                                    "reportableQuantity": false,
                                    "percentage": 10,
                                    "id": "ID",
                                    "packingGroup": "DEFAULT",
                                    "properShippingName": "ShippingName",
                                    "hazardClass": "hazardClass"
                                  }
                                }
                              ],
                              "numberOfContainers": 10,
                              "containerType": "Copper Box",
                              "emergencyContactNumber": {
                                "areaCode": "202",
                                "extension": "3245",
                                "countryCode": "US",
                                "personalIdentificationNumber": "9545678",
                                "localNumber": "23456"
                              },
                              "packaging": {
                                "count": 20,
                                "units": "Liter"
                              },
                              "packingType": "ALL_PACKED_IN_ONE",
                              "radioactiveContainerClass": "EXCEPTED_PACKAGE"
                            }
                          ],
                          "packaging": {
                            "count": 20,
                            "units": "Liter"
                          }
                        },
                        "packageCODDetail": {
                          "codCollectionAmount": {
                            "amount": 12.45,
                            "currency": "USD"
                          },
                          "codCollectionType": "ANY"
                        },
                        "pieceCountVerificationBoxCount": 0,
                        "batteryDetails": [
                          {
                            "material": "LITHIUM_METAL",
                            "regulatorySubType": "IATA_SECTION_II",
                            "packing": "CONTAINED_IN_EQUIPMENT"
                          }
                        ],
                        "dryIceWeight": {
                          "units": "LB",
                          "value": 10
                        }
                      }
                    }
                  ],
                  "documentShipment": false,
                  "variableHandlingChargeDetail": {
                    "rateType": "ACCOUNT",
                    "percentValue": 0,
                    "rateLevelType": "BUNDLED_RATE",
                    "fixedValue": {
                      "amount": "100",
                      "currency": "USD"
                    },
                    "rateElementBasis": "NET_CHARGE"
                  },
                  "packagingType": "YOUR_PACKAGING",
                  "totalPackageCount": 3,
                  "totalWeight": 87.5,
                  "shipmentSpecialServices": {
                    "returnShipmentDetail": {
                      "returnType": "PRINT_RETURN_LABEL"
                    },
                    "deliveryOnInvoiceAcceptanceDetail": {
                      "recipient": {
                        "accountNumber": {
                          "value": 123456789
                        }
                      }
                    },
                    "internationalTrafficInArmsRegulationsDetail": {
                      "licenseOrExemptionNumber": "432345"
                    },
                    "pendingShipmentDetail": {
                      "pendingShipmentType": "EMAIL",
                      "processingOptions": {
                        "options": [
                          "ALLOW_MODIFICATIONS"
                        ]
                      },
                      "recommendedDocumentSpecification": {
                        "types": [
                          "ANTIQUE_STATEMENT_EUROPEAN_UNION"
                        ]
                      },
                      "emailLabelDetail": {
                        "recipients": [
                          {
                            "emailAddress": "string",
                            "optionsRequested": {
                              "options": [
                                "PRODUCE_PAPERLESS_SHIPPING_FORMAT"
                              ]
                            },
                            "role": "SHIPMENT_COMPLETOR",
                            "locale": {
                              "country": "string",
                              "language": "string"
                            }
                          }
                        ],
                        "message": "string"
                      },
                      "documentReferences": [
                        {
                          "documentType": "CERTIFICATE_OF_ORIGIN",
                          "customerReference": "string",
                          "description": "ShippingDocumentSpecification",
                          "documentId": "98123"
                        }
                      ],
                      "expirationTimeStamp": "2012-12-31",
                      "shipmentDryIceDetail": {
                        "totalWeight": {
                          "units": "LB",
                          "value": 10
                        },
                        "packageCount": 12
                      }
                    },
                    "holdAtLocationDetail": {
                      "locationId": "YBZA",
                      "locationContactAndAddress": {
                        "address": {
                          "streetLines": [
                            "10 FedEx Parkway",
                            "Suite 302"
                          ],
                          "city": "Beverly Hills",
                          "stateOrProvinceCode": "CA",
                          "postalCode": "38127",
                          "countryCode": "US",
                          "residential": false
                        },
                        "contact": {
                          "personName": "person name",
                          "emailAddress": "email address",
                          "phoneNumber": "phone number",
                          "phoneExtension": "phone extension",
                          "companyName": "company name",
                          "faxNumber": "fax number"
                        }
                      },
                      "locationType": "FEDEX_ONSITE"
                    },
                    "shipmentCODDetail": {
                      "addTransportationChargesDetail": {
                        "rateType": "ACCOUNT",
                        "rateLevelType": "BUNDLED_RATE",
                        "chargeLevelType": "CURRENT_PACKAGE",
                        "chargeType": "COD_SURCHARGE"
                      },
                      "codRecipient": {
                        "accountNumber": {
                          "value": 123456789
                        }
                      },
                      "remitToName": "FedEx",
                      "codCollectionType": "ANY",
                      "financialInstitutionContactAndAddress": {
                        "address": {
                          "streetLines": [
                            "10 FedEx Parkway",
                            "Suite 302"
                          ],
                          "city": "Beverly Hills",
                          "stateOrProvinceCode": "CA",
                          "postalCode": "38127",
                          "countryCode": "US",
                          "residential": false
                        },
                        "contact": {
                          "personName": "person name",
                          "emailAddress": "email address",
                          "phoneNumber": "phone number",
                          "phoneExtension": "phone extension",
                          "companyName": "company name",
                          "faxNumber": "fax number"
                        }
                      },
                      "returnReferenceIndicatorType": "INVOICE"
                    },
                    "shipmentDryIceDetail": {
                      "totalWeight": {
                        "units": "LB",
                        "value": 10
                      },
                      "packageCount": 12
                    },
                    "internationalControlledExportDetail": {
                      "type": "DEA_036"
                    },
                    "homeDeliveryPremiumDetail": {
                      "phoneNumber": {
                        "areaCode": "areaCode",
                        "extension": "extension",
                        "countryCode": "countryCode",
                        "personalIdentificationNumber": "personalIdentificationNumber",
                        "localNumber": "localNumber"
                      },
                      "shipTimestamp": "2020-04-24",
                      "homedeliveryPremiumType": "APPOINTMENT"
                    },
                    "specialServiceTypes": [
                      "BROKER_SELECT_OPTION"
                    ]
                  },
                  "customsClearanceDetail": {
                    "commercialInvoice": {
                      "shipmentPurpose": "GIFT"
                    },
                    "freightOnValue": "CARRIER_RISK",
                    "dutiesPayment": {
                      "payor": {
                        "responsibleParty": {
                          "address": {
                            "streetLines": [
                              "10 FedEx Parkway",
                              "Suite 302"
                            ],
                            "city": "Beverly Hills",
                            "stateOrProvinceCode": "CA",
                            "postalCode": "90210",
                            "countryCode": "US",
                            "residential": false
                          },
                          "contact": {
                            "personName": "John Taylor",
                            "emailAddress": "sample@company.com",
                            "phoneNumber": "1234567890",
                            "phoneExtension": "phone extension",
                            "companyName": "Fedex",
                            "faxNumber": "fax number"
                          },
                          "accountNumber": {
                            "value": "123456789"
                          }
                        }
                      },
                      "paymentType": "SENDER"
                    },
                    "commodities": [
                      {
                        "description": "DOCUMENTS",
                        "weight": {
                          "units": "LB",
                          "value": 10
                        },
                        "quantity": 1,
                        "customsValue": {
                          "amount": "100",
                          "currency": "USD"
                        },
                        "unitPrice": {
                          "amount": "100",
                          "currency": "USD"
                        },
                        "numberOfPieces": 1,
                        "countryOfManufacture": "US",
                        "quantityUnits": "PCS",
                        "name": "DOCUMENTS",
                        "harmonizedCode": "080211",
                        "partNumber": "P1"
                      }
                    ]
                  },
                  "groupShipment": true,
                  "serviceTypeDetail": {
                    "carrierCode": "FDXE",
                    "description": "string",
                    "serviceName": "string",
                    "serviceCategory": "string"
                  },
                  "smartPostInfoDetail": {
                    "ancillaryEndorsement": "ADDRESS_CORRECTION",
                    "hubId": "5531",
                    "indicia": "MEDIA_MAIL",
                    "specialServices": "USPS_DELIVERY_CONFIRMATION"
                  },
                  "expressFreightDetail": {
                    "bookingConfirmationNumber": "string",
                    "shippersLoadAndCount": 0
                  },
                  "groundShipment": false
                },
                "carrierCodes": [
                  "FDXE"
                ]
              }
            '
        ];
    }

}
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
    protected ?Item $line_items;
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
     * @return Item
     */
    public function getLineItems(): Item
    {
        return $this->line_items;
    }

    /**
     * @param  Item  $line_items
     * @return $this
     */
    public function setLineItems(Item $line_items): RateQuotes
    {
        $this->line_items = $line_items;
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
        if (empty($this->getLineItems())) {
            throw new MissingLineItemException('Line items are required');
        }

        $query = $this->http_client->post($this->getApiUri($this->api_endpoint), $this->prepare());
        return ($this->raw === true) ? $query : json_decode($query->getBody()->getContents());
    }


    /**
     * @return array[]
     */
    public function prepare(): array
    {
        return [
            'json' => [
                "accountNumber" => [
                    "value" => $this->account_number
                ],
                "rateRequestControlParameters" => [
                    "returnTransitTimes" => true,
                    "servicesNeededOnRateFailure" => true,
                    "variableOptions" => "FREIGHT_GUARANTEE",
                    "rateSortOrder" => "SERVICENAMETRADITIONAL"
                ],
                "requestedShipment" => [
                    "shipper" => $this->shipper->prepare(),
                    "recipient" => $this->recipient->prepare(),
                   // "serviceType" => "STANDARD_OVERNIGHT",
                    "emailNotificationDetail" => [$this->email_notification_recipient->prepare()],
                    "preferredCurrency" => "USD",
                    "rateRequestType" => [
                        "ACCOUNT",
                        "LIST"
                    ],
                    "shipDateStamp" => $this->ship_datestamp,
                    "pickupType" => $this->pickup_type,
                    "requestedPackageLineItems" => [$this->requested_package_line_item],
                    "documentShipment" => $this->document_shipment,
                    "packagingType" => "YOUR_PACKAGING",
                    "totalPackageCount" => $this->total_package_count,
                    "totalWeight" => $this->total_weight,
                    "groupShipment" => true,
                ],
                "carrierCodes" => [
                    "FDXE"
                ],
            ],
        ];
    }

}
<?php


namespace FedexRest\Services\RatesTransitTimes\Entity;

use FedexRest\Services\RatesTransitTimes\Type\NotificationEventType;
use FedexRest\Services\RatesTransitTimes\Type\EmailNotificationRecipientType;
use FedexRest\Services\RatesTransitTimes\Type\NotificationFormatType;
use FedexRest\Services\RatesTransitTimes\Type\NotificationType;


class EmailNotificationRecipient
{
    public string $emailAddress;
    public ?NotificationEventType $notificationEventTypes;
    public string $notificationFormatType = NotificationFormatType::_TEXT;
    public string $emailNotificationRecipientType = EmailNotificationRecipientType::_RECIPIENT;
    public string $notificationType = NotificationType::_EMAIL;
    public string $phoneNumber;
    public string $phoneNumberCountryCode;

    /**
     * @param  string  $emailAddress
     * @return $this
     */
    public function setEmailAddress(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @param  string  $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @param  string  $phoneCountryCode
     * @return $this
     */
    public function setPhoneNumberCountryCode(string $phoneNumberCountryCode)
    {
        $this->phoneNumberCountryCode = $phoneNumberCountryCode;
        return $this;
    }

    /**
     * @param  array  $notificationEventTypes
     * @return $this
     */
    public function setNotificationEventTypes(array $notificationEventTypes)
    {
        $this->notificationEventTypes = $notificationEventTypes;
        return $this;
    }

    /**
     * @param  string  $notificationFormatType
     * @return $this
     */
    public function setNotificationFormatType(string $notificationFormatType)
    {
        $this->notificationFormatType = $notificationFormatType;
        return $this;
    }

    /**
     * @param  string  $notificationType
     * @return $this
     */
    public function setNotificationType(string $notificationType)
    {
        $this->notificationType = $notificationType;
        return $this;
    }

    /**
     * @param  string  $emailNotificationRecipientType
     * @return $this
     */
    public function setEmailNotificationRecipientType(string $emailNotificationRecipientType)
    {
        $this->emailNotificationRecipientType = $emailNotificationRecipientType;
        return $this;
    }

    /**
     * @return array[]
     */
    public function prepare(): array
    {
        $data = [];
        if (!empty($this->emailAddress)) {
            $data['emailAddress'] = $this->emailAddress;
        }
        if (!empty($this->notificationEventTypes)) {
            $data['notificationEventType'] = $this->notificationEventTypes;
        }
        if (!empty($this->phoneNumber)) {
            $data['smsDetail']['phoneNumber'] = $this->phoneNumber;
        }
        if (!empty($this->phoneNumberCountryCode)) {
            $data['smsDetail']['phoneNumberCountryCode'] = $this->phoneNumberCountryCode;
        }
        if (!empty($this->notificationFormatType)) {
            $data['notificationFormatType'] = $this->notificationFormatType;
        }
        if (!empty($this->emailNotificationRecipientType)) {
            $data['emailNotificationRecipientType'] = $this->emailNotificationRecipientType;
        }
        if (!empty($this->notificationType)) {
            $data['notificationType'] = $this->notificationType;
        }

        return $data;
    }
}

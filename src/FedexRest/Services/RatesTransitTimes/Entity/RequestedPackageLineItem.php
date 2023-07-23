<?php


namespace FedexRest\Services\RatesTransitTimes\Entity;

use FedexRest\Entity\Weight;
use FedexRest\Entity\Dimensions;
use FedexRest\Entity\Value;

class RequestedPackageLineItem
{
    public Weight $weight;
    public Dimensions $dimensions;
    public Value $value;

    /**
     * @param  Weight  $weight
     * @return $this
     */
    public function setWeight(Weight $weight): RequestedPackageLineItem
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param  Dimensions $dimensions
     * @return $this
     */
    public function setDimensions(Dimensions $dimensions): RequestedPackageLineItem
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    /**
     * @param  Value $value
     * @return $this
     */
    public function setValue(Value $value): RequestedPackageLineItem
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array[]
     */
    public function prepare(): array
    {
        $data = [];
        if (!empty($this->dimensions)) {
            $data['dimensions'] = $this->dimensions->prepare();
        }
        if (!empty($this->weight)) {
            $data['weight'] = $this->weight->prepare();
        }
        if (!empty($this->value)) {
            $data['value'] = $this->value->prepare();
        }

        return $data;
    }
}

<?php

class Calculator
{
    private int $price;
    private int $fixDiscount;
    private int $varDiscount;
    private float $priceMinusPercentageGroup;
    private float $priceMinusFixedGroup;
    public const MAGIC_DIVIDER = 100;
    private float $totalDiscount;


    public function getTotalDiscount(): float
    {
        return $this->totalDiscount;
    }


    public function getPriceMinusPercentageGroup(): float
    {
        return $this->priceMinusPercentageGroup;
    }


    public function getPriceMinusFixedGroup(): float
    {
        return $this->priceMinusFixedGroup;
    }


    public function __construct()
    {
        // $this->price = $price;
       // $this->fixDiscount = $fixDiscount;
       // $this->varDiscount = $varDiscount;
    }


    public function getPrice(): int
    {
        return $this->price;
    }

    public function getFixDiscount(): int
    {
        return $this->fixDiscount;
    }

    public function getVarDiscount(): int
    {
        return $this->varDiscount;
    }

    public function totalFixDiscount(PDO $pdo, Customer $customer): int | float
    {
        $fixDiscount = [];
        $customerLoader = new CustomerGroupLoader();
        foreach ($customerLoader->loadGroups($pdo, $customer->getGroupId()) as $group) {
            if (!is_null($group["fixed_discount"])) {
                $fixDiscount[] = $group["fixed_discount"];
            }
        }
        return array_sum($fixDiscount);
    }

    public function maxGroupVarDis(PDO $pdo, Customer $customer): int
    {
        $variableDiscount = [];
        $customerLoader = new CustomerGroupLoader();
        foreach ($customerLoader->loadGroups($pdo, $customer->getGroupId()) as $group) {
            if (!is_null($group["variable_discount"])) {
                $variableDiscount[] = $group["variable_discount"];
            }
        }
        if (empty($variableDiscount)) {
            return 0;
        }
        return max($variableDiscount);
    }

    public function percentIsHighestGroup(PDO $pdo, Product $product, Customer $customer): bool
    {
        $productPrice = (float)$product->getPrice() / self::MAGIC_DIVIDER;

        $percentInDecimal = $this->maxGroupVarDis($pdo, $customer)/ self::MAGIC_DIVIDER;

        $priceMinusFixed = $productPrice -  $this->totalFixDiscount($pdo, $customer);
        $priceMinusPercentage = $productPrice - ($productPrice * $percentInDecimal);

        if ($priceMinusPercentage < $priceMinusFixed) {
            $this->priceMinusPercentageGroup = $priceMinusPercentage;
            return true;
        }
        $this->priceMinusFixedGroup = $priceMinusFixed;
        return false;
    }

    public function checkCustomerDiscount(PDO $pdo, Product $product, Customer $customer): float
    {
        $totalPrice = null;
        $customerFixedDiscount = $customer->getFixDiscount();
        $customerDiscountPercentage = $customer->getVarDiscount();
        $productPrice = (float)$product->getPrice() / self::MAGIC_DIVIDER;

        if ($this->percentIsHighestGroup($pdo, $product, $customer) === true) {
            if ($customerDiscountPercentage > $this->maxGroupVarDis($pdo, $customer)) {
                $percentInDecimal = (float)$customerDiscountPercentage / self::MAGIC_DIVIDER;

                if (!is_null($customerFixedDiscount)) {
                    $fixedDiscount = $customerFixedDiscount;
                    $priceMinusFixed = $productPrice - $customerFixedDiscount;
                } else {
                    $fixedDiscount = 0;
                    $priceMinusFixed = $productPrice;
                }
                $this->totalDiscount= round($fixedDiscount + ($priceMinusFixed * $percentInDecimal));
                $totalPrice = $priceMinusFixed - ($priceMinusFixed * $percentInDecimal);
            } else {
                $discountPercentage = $this->maxGroupVarDis($pdo, $customer);
                $percentInDecimal = (float)$discountPercentage / self::MAGIC_DIVIDER;

                if (!is_null($customerFixedDiscount)) {
                    $fixedDiscount=$customerFixedDiscount;
                    $priceMinusFixed = $productPrice - $customerFixedDiscount;
                } else {
                    $fixedDiscount=0;
                    $priceMinusFixed = $productPrice;
                }
                $this->totalDiscount= round($fixedDiscount + ($percentInDecimal * $priceMinusFixed), 2);
                $totalPrice = $priceMinusFixed - ($percentInDecimal * $priceMinusFixed);
            }
        }

        if ($this->percentIsHighestGroup($pdo, $product, $customer) === false) {
            $percentInDecimal = (float)$customerDiscountPercentage / self::MAGIC_DIVIDER;
            if (!is_null($customerFixedDiscount)) {
                $fixedDiscount = $customerFixedDiscount + $this->totalFixDiscount($pdo, $customer);
                $priceMinusFixed = $productPrice - ($customerFixedDiscount + $this->totalFixDiscount($pdo, $customer));
            } else {
                $fixedDiscount = $this->totalFixDiscount($pdo, $customer);
                $priceMinusFixed = $productPrice - $this->totalFixDiscount($pdo, $customer);
                $this->totalDiscount= $percentInDecimal * $this->totalFixDiscount($pdo, $customer);
            }
            $this->totalDiscount= round($fixedDiscount + ($percentInDecimal * $priceMinusFixed), 2) ;
            $totalPrice = $priceMinusFixed - ($percentInDecimal * $priceMinusFixed);
        }
        if ($totalPrice < 0) {
            $totalPrice = 0;
        }

        if (!$totalPrice) {
            throw new ErrorException('could not calculate');
        }

        return round($totalPrice, 2);
    }
}

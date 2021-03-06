<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AddressingBundle\Validator\Constraints;

use Sylius\Component\Addressing\Model\AddressInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator which validates if a province is valid
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ProvinceAddressConstraintValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof AddressInterface) {
            throw new \InvalidArgumentException(
                'ProvinceAddressConstraintValidator can only validate instances of "Sylius\Component\Addressing\Model\AddressInterface"'
            );
        }

        $propertyPath = $this->context->getPropertyPath();

        foreach (iterator_to_array($this->context->getViolations()) as $violation) {
            if (0 === strpos($violation->getPropertyPath(), $propertyPath)) {
                return;
            }
        }

        if (!$this->isProvinceValid($value)) {
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * Override this method to implement your logic
     *
     * @param AddressInterface $address
     *
     * @return bool
     */
    protected function isProvinceValid(AddressInterface $address)
    {
        if (null === $country = $address->getCountry()) {
            return true;
        }

        if (!$country->hasProvinces()) {
            return true;
        }

        if (null === $address->getProvince()) {
            return false;
        }

        if ($country->hasProvince($address->getProvince())) {
            return true;
        }

        return false;
    }
}

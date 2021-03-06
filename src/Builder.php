<?php
/*
 * This file is part of the Structor package.
 *
 * (c) Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE file 
 * that was distributed with this source code.
 */

namespace PSX\Structor;

use RuntimeException;
use PSX\Structor\Provider\ProviderCollectionInterface;
use PSX\Structor\Provider\ProviderEntityInterface;

/**
 * Main class of structor. The build method resolves the definition and calls 
 * every provider and field. The result is an array in the format of the 
 * definition
 *
 * @author Christoph Kappestein <christoph.kappestein@gmail.com>
 */
class Builder
{
    public function build(array $definition, array $context = null)
    {
        $result = [];

        foreach ($definition as $key => $value) {
            if ($value instanceof FieldInterface) {
                $result[$key] = $value->getResult($context);
            } elseif ($value instanceof ProviderInterface) {
                $result[$key] = $this->getProviderValue($value, $context);
            } elseif (is_array($value)) {
                $result[$key] = $this->build($value, $context);
            } else {
                if ($context !== null) {
                    if (isset($context[$value])) {
                        $result[$key] = $context[$value];
                    } else {
                        throw new RuntimeException('Referenced unknown key "' . $value . '" in context');
                    }
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    protected function getProviderValue(ProviderInterface $provider, array $context = null)
    {
        $data       = $provider->getResult($context);
        $definition = $provider->getDefinition();
        $result     = null;

        if (empty($data)) {
            return null;
        }

        if ($provider instanceof ProviderCollectionInterface) {
            $result = [];
            foreach ($data as $row) {
                if (is_array($row)) {
                    $result[] = $this->build($definition, $row);
                } else {
                    throw new RuntimeException('Collection must contain only array elements');
                }
            }
        } elseif ($provider instanceof ProviderEntityInterface) {
            if (is_array($data)) {
                $result = $this->build($definition, $data);
            } else {
                throw new RuntimeException('Entity must be an array');
            }
        }

        return $result;
    }
}


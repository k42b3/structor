<?php
/*
 * This file is part of the Structor package.
 *
 * (c) Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE file 
 * that was distributed with this source code.
 */

namespace Structor\Field;

/**
 * Boolean
 *
 * @author Christoph Kappestein <christoph.kappestein@gmail.com>
 */
class Boolean extends TransformFieldAbstract
{
    protected function transform($value)
    {
        return $value === 'false' ? false : (bool) $value;
    }
}

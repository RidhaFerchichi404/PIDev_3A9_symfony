<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\PropertyInfo\Extractor;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\TypeInfo\Type;

/**
 * Extracts the constructor argument type using ConstructorArgumentTypeExtractorInterface implementations.
 *
 * @author Dmitrii Poddubnyi <dpoddubny@gmail.com>
 */
final class ConstructorExtractor implements PropertyTypeExtractorInterface
{
    /**
     * @param iterable<int, ConstructorArgumentTypeExtractorInterface> $extractors
     */
    public function __construct(
        private readonly iterable $extractors = [],
    ) {
    }

    public function getType(string $class, string $property, array $context = []): ?Type
    {
        foreach ($this->extractors as $extractor) {
            if (null !== $value = $extractor->getTypeFromConstructor($class, $property)) {
                return $value;
            }
        }

        return null;
    }

    public function getTypes(string $class, string $property, array $context = []): ?array
    {
        foreach ($this->extractors as $extractor) {
            $value = $extractor->getTypesFromConstructor($class, $property);
            if (null !== $value) {
                return $value;
            }
        }

        return null;
    }
}

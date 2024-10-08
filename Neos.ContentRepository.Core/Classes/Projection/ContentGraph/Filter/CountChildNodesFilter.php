<?php

declare(strict_types=1);

namespace Neos\ContentRepository\Core\Projection\ContentGraph\Filter;

use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\NodeType\NodeTypeCriteria;
use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\PropertyValue\Criteria\PropertyValueCriteriaInterface;
use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\PropertyValue\PropertyValueCriteriaParser;
use Neos\ContentRepository\Core\Projection\ContentGraph\Filter\SearchTerm\SearchTerm;

/**
 * Immutable filter DTO for {@see ContentSubgraphInterface::countChildNodes()}
 *
 * Example:
 *
 * // create a new instance and set node type constraints
 * CountChildNodesFilter::create(nodeTypes: 'Some.Included:NodeType,!Some.Excluded:NodeType');
 *
 * // create an instance from an existing FindChildNodesFilter instance
 * CountChildNodesFilter::fromFindChildNodesFilter($filter);
 *
 * @api for the factory methods; NOT for the inner state.
 */
final readonly class CountChildNodesFilter
{
    /**
     * @internal (the properties themselves are readonly; only the write-methods are API.
     */
    private function __construct(
        public ?NodeTypeCriteria $nodeTypes,
        public ?SearchTerm $searchTerm,
        public ?PropertyValueCriteriaInterface $propertyValue,
    ) {
    }

    /**
     * Creates an instance with the specified filter options
     *
     * Note: The signature of this method might be extended in the future, so it should always be used with named arguments
     * @see https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments
     */
    public static function create(
        NodeTypeCriteria|string $nodeTypes = null,
        SearchTerm|string $searchTerm = null,
        PropertyValueCriteriaInterface|string $propertyValue = null,
    ): self {
        if (is_string($nodeTypes)) {
            $nodeTypes = NodeTypeCriteria::fromFilterString($nodeTypes);
        }
        if (is_string($searchTerm)) {
            $searchTerm = SearchTerm::fulltext($searchTerm);
        }
        if (is_string($propertyValue)) {
            $propertyValue = PropertyValueCriteriaParser::parse($propertyValue);
        }
        return new self($nodeTypes, $searchTerm, $propertyValue);
    }

    public static function fromFindChildNodesFilter(FindChildNodesFilter $filter): self
    {
        return new self($filter->nodeTypes, $filter->searchTerm, $filter->propertyValue);
    }

    /**
     * Returns a new instance with the specified additional filter options
     *
     * Note: The signature of this method might be extended in the future, so it should always be used with named arguments
     * @see https://www.php.net/manual/en/functions.arguments.php#functions.named-arguments
     */
    public function with(
        NodeTypeCriteria|string $nodeTypes = null,
        SearchTerm|string $searchTerm = null,
        PropertyValueCriteriaInterface|string $propertyValue = null,
    ): self {
        return self::create(
            $nodeTypes ?? $this->nodeTypes,
            $searchTerm ?? $this->searchTerm,
            $propertyValue ?? $this->propertyValue,
        );
    }
}

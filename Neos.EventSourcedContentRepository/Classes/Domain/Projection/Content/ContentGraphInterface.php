<?php
declare(strict_types=1);
namespace Neos\EventSourcedContentRepository\Domain\Projection\Content;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePointSet;
use Neos\ContentRepository\Domain\Projection\Content\NodeInterface;
use Neos\ContentRepository\Domain\ContentStream\ContentStreamIdentifier;
use Neos\ContentRepository\Domain\NodeAggregate\NodeAggregateIdentifier;
use Neos\ContentRepository\DimensionSpace\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Domain\NodeAggregate\NodeName;
use Neos\ContentRepository\Domain\ValueObject\NodeTypeName;
use Neos\EventSourcedContentRepository\Domain;

/**
 * The interface to be implemented by content graphs
 */
interface ContentGraphInterface
{
    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param DimensionSpacePoint $dimensionSpacePoint
     * @param Domain\Context\Parameters\VisibilityConstraints $visibilityConstraints
     * @return ContentSubgraphInterface|null
     */
    public function getSubgraphByIdentifier(
        ContentStreamIdentifier $contentStreamIdentifier,
        DimensionSpacePoint $dimensionSpacePoint,
        Domain\Context\Parameters\VisibilityConstraints $visibilityConstraints
    ): ?ContentSubgraphInterface;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @param DimensionSpacePoint $originDimensionSpacePoint
     * @return NodeInterface|null
     */
    public function findNodeByIdentifiers(
        ContentStreamIdentifier $contentStreamIdentifier,
        NodeAggregateIdentifier $nodeAggregateIdentifier,
        DimensionSpacePoint $originDimensionSpacePoint
    ): ?NodeInterface;

    /**
     * @param NodeTypeName $nodeTypeName
     * @return NodeInterface|null
     */
    public function findRootNodeByType(NodeTypeName $nodeTypeName): ?NodeInterface;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @param DimensionSpacePointSet $dimensionSpacePointSet
     * @return array|NodeInterface[]
     */
    public function findNodesByNodeAggregateIdentifier(
        ContentStreamIdentifier $contentStreamIdentifier,
        NodeAggregateIdentifier $nodeAggregateIdentifier,
        DimensionSpacePointSet $dimensionSpacePointSet = null
    ): array;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @return NodeAggregate|null
     * @throws Domain\Context\Node\NodeAggregatesTypeIsAmbiguous
     */
    public function findNodeAggregateByIdentifier(ContentStreamIdentifier $contentStreamIdentifier, NodeAggregateIdentifier $nodeAggregateIdentifier): ?NodeAggregate;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @return array|NodeAggregate[]
     */
    public function findParentAggregates(ContentStreamIdentifier $contentStreamIdentifier, NodeAggregateIdentifier $nodeAggregateIdentifier): array;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @return array|NodeAggregate[]
     */
    public function findChildAggregates(ContentStreamIdentifier $contentStreamIdentifier, NodeAggregateIdentifier $nodeAggregateIdentifier): array;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeName $nodeName
     * @param NodeAggregateIdentifier $parentNodeAggregateIdentifier
     * @param DimensionSpacePoint $parentNodeOriginDimensionSpacePoints
     * @param DimensionSpacePointSet $dimensionSpacePointsToCheck
     * @return DimensionSpacePointSet
     */
    public function getDimensionSpacePointsOccupiedByChildNodeName(ContentStreamIdentifier $contentStreamIdentifier, NodeName $nodeName, NodeAggregateIdentifier $parentNodeAggregateIdentifier, DimensionSpacePoint $parentNodeOriginDimensionSpacePoints, DimensionSpacePointSet $dimensionSpacePointsToCheck);

    /**
     * @param NodeInterface $node
     * @return DimensionSpacePointSet
     */
    public function findVisibleDimensionSpacePointsOfNode(NodeInterface $node): DimensionSpacePointSet;

    /**
     * @param ContentStreamIdentifier $contentStreamIdentifier
     * @param NodeAggregateIdentifier $nodeAggregateIdentifier
     * @return DimensionSpacePointSet
     */
    public function findVisibleDimensionSpacePointsOfNodeAggregate(
        ContentStreamIdentifier $contentStreamIdentifier,
        NodeAggregateIdentifier $nodeAggregateIdentifier
    ): DimensionSpacePointSet;

    public function countNodes(): int;

    /**
     * Enable all caches. All READ requests should enable the cache.
     * By default, caches are enabled!
     */
    public function enableCache(): void;

    /**
     * Disable all caches. All WRITE requests should disable the cache.
     */
    public function disableCache(): void;
}

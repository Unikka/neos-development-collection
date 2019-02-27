<?php
declare(strict_types=1);

namespace Neos\EventSourcedContentRepository\Domain\Projection\Workspace;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\ContentRepository\Domain\ValueObject\ContentStreamIdentifier;
use Neos\EventSourcedContentRepository\Domain\ValueObject\WorkspaceName;

/**
 * Workspace Read Model
 */
class Workspace
{

    /**
     * @var string
     */
    public $workspaceName;

    /**
     * @var string
     */
    public $baseWorkspaceName;

    /**
     * @var string
     */
    public $workspaceTitle;

    /**
     * @var string
     */
    public $workspaceDescription;

    /**
     * @var string
     */
    public $workspaceOwner;

    /**
     * @var string
     */
    public $currentContentStreamIdentifier;

    /**
     * Checks if this workspace is shared across all editors
     *
     * @return boolean
     */
    public function isInternalWorkspace()
    {
        return $this->baseWorkspaceName !== null && $this->workspaceOwner === null;
    }

    /**
     * Checks if this workspace is public to everyone, even without authentication
     *
     * @return boolean
     */
    public function isPublicWorkspace()
    {
        return $this->baseWorkspaceName === null && $this->workspaceOwner === null;
    }

    /**
     * Checks if the workspace is the root workspace
     *
     * @return boolean
     */
    public function isRootWorkspace()
    {
        return $this->baseWorkspaceName === null;
    }

    /**
     * @return ContentStreamIdentifier
     */
    public function getCurrentContentStreamIdentifier(): ContentStreamIdentifier
    {
        return ContentStreamIdentifier::fromString($this->currentContentStreamIdentifier);
    }

    /**
     * @return WorkspaceName|null
     */
    public function getBaseWorkspaceName(): ?WorkspaceName
    {
        return $this->baseWorkspaceName ? new WorkspaceName($this->baseWorkspaceName) : null;
    }

    /**
     * @return WorkspaceName
     */
    public function getWorkspaceName(): WorkspaceName
    {
        return new WorkspaceName($this->workspaceName);
    }

    /**
     * Checks if this workspace is a user's personal workspace
     *
     * @return boolean
     * @api
     */
    public function isPersonalWorkspace()
    {
        return $this->workspaceOwner !== null;
    }

    public static function fromDatabaseRow(array $row): self
    {
        $workspace = new Workspace();
        $workspace->workspaceName = $row['workspacename'];
        $workspace->baseWorkspaceName = $row['baseworkspacename'];
        $workspace->workspaceTitle = $row['workspacetitle'];
        $workspace->workspaceDescription = $row['workspacedescription'];
        $workspace->workspaceOwner = $row['workspaceowner'];
        $workspace->currentContentStreamIdentifier = $row['currentcontentstreamidentifier'];
        return $workspace;
    }
}

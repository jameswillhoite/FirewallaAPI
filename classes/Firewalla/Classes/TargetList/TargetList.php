<?php

namespace Firewalla\Classes\TargetList;

use Firewalla\Classes\Category\Category;

class TargetList
{
    /**
     * This target list’s unique identifier. This is generated by system upon creation and cannot be changed
     * @var string
     */
    public string $id;

    /**
     * Readable name of this target list. *Required on creation
     * @required
     * @max 24 characters
     * @var string
     */
    public string $name;

    /**
     * Owner of this target list, either **global** or a box gid. *Required on creation
     * @required
     * @var string
     */
    public string $owner;

    /**
     * List of all targets, supports domain name w/ or w/o wildcard, IP, and IP range (CIDR)
     * @var string[]
     */
    public array $targets;

    /**
     * A tag to easily identify this target list
     * @see Category
     * @var string
     */
    public string $category;

    /**
     * A literal string for more descriptions of this target list
     * @var string
     */
    public string $notes;

    /**
     * A Unix timestamp that shows the last time this target list was changed
     * @var float
     */
    public float $astUpdated;
}
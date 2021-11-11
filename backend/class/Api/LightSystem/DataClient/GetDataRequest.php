<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\DataClient;

use noxkiwi\lightsystem\Api\LightSystem\AbstractRequest;
use noxkiwi\dataabstraction\Entry;
use \DateTime;

/**
 * I am the DataClient interface.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class GetDataRequest extends AbstractRequest
{
    public string $opcItem;
    public int $groupId;
    public Entry $archiveGroup;
    public string $display;
    public string $interval;
    public string $compression;
    public DateTime $begin;
    public DateTime $end;
    public string $sqlFormat;
    public string $phpFormat;
    public array $opcItems;
}

<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Api\LightSystem\AlarmClient;

use DateTime;
use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Helper\DateTimeHelper;
use const E_USER_NOTICE;

/**
 * I am an alarm entry.
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class AlarmOccurance
{
    /** @var string */
    private string $address;
    /** @var string */
    private string $area;
    /** @var string */
    private string $name;
    /** @var \DateTime */
    private ?DateTime $came;
    /** @var \DateTime|null */
    private ?DateTime $gone;
    /** @var \DateTime|null */
    private ?DateTime $ackdate;
    /** @var int */
    private int $ack;

    /**
     * @param array $data
     *
     * @return \noxkiwi\lightsystem\Api\lightsystem\AlarmClient\AlarmOccurance|null
     */
    public static function fromApi(array $data): ?AlarmOccurance
    {
        $return          = new self();
        $return->came    = null;
        $return->gone    = null;
        $return->name    = $data['name'] ?? 'no name';
        $return->address = $data['address'] ?? 'no address';
        $return->area    = $data['area'] ?? '';
        try {
            $return->ackdate = null;
            $return->ack     = 0;
            if (! empty($data['ack'])) {
                $return->ackdate = new DateTime($data['ack']);
                $return->ack     = (int)($data['ack'] ?? 0);
            }
            if (! empty($data['engaged'])) {
                $return->came = new DateTime($data['engaged']);
            }
            if (! empty($data['disengaged'])) {
          #      $return->gone = new DateTime($data['disengaged']);
            }
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);

            return null;
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function isAcknowledged(): bool
    {
        return $this->ack === 1;
    }

    /**
     * @return bool
     */
    public function isGone(): bool
    {
        return $this->getGone() !== null;
    }

    /**
     * @return \DateTime|null
     */
    public function getGone(): ?DateTime
    {
        return $this->gone;
    }

    /**
     * @return \DateTime|null
     */
    public function getCame(): ?DateTime
    {
        return $this->came;
    }

    /**
     * @return \DateTime|null
     */
    public function getAckdate(): ?DateTime
    {
        return $this->ackdate;
    }

    /**
     * @return array
     */
    public function getTableRow(): array
    {
        return [
            'name'    => $this->name,
            'address' => $this->address,
            'area'    => $this->area,
            'ackdate' => $this->ackdate ? DateTimeHelper::toUserFormat($this->ackdate) : null,
            'ack'     => $this->ack,
            'came'    => $this->came ? DateTimeHelper::toUserFormat($this->came) : null,
            'gone'    => $this->gone ? DateTimeHelper::toUserFormat($this->gone) : null
        ];
    }
}


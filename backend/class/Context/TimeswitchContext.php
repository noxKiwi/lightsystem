<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Exception\ConfigurationException;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Model\TimeSwitchModel;
use const E_ERROR;
use const E_WARNING;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan@nox.kiwi>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class TimeswitchContext extends CallbackContext
{
    /** @var \noxkiwi\lightsystem\Model\TimeSwitchModel $timeSwitchModel */
    private TimeSwitchModel $timeSwitchModel;
    /** @var \noxkiwi\lightsystem\Model\OpcItemModel */
    private OpcItemModel $opcItemModel;

    /**
     * TimeswitchContext constructor.
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\dataabstraction\Exception\ModelException
     */
    public function __construct()
    {
        parent::__construct();
        $this->timeSwitchModel = TimeSwitchModel::getInstance();
        $this->opcItemModel    = OpcItemModel::getInstance();
    }

    /**
     * I will
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     */
    protected function viewGet(): void
    {
        $address = $this->request->get('address');
        if (empty($address)) {
            throw new InvalidArgumentException('You must provide the address.', E_WARNING);
        }
        $writeItem = $this->opcItemModel->loadByUnique('opc_item_address', $address);
        if (empty($writeItem)) {
            throw new InvalidArgumentException('The desired address was not found.', E_WARNING);
        }
        $timeSwitchData = $this->timeSwitchModel->addFilter('opc_item_write', $writeItem['opc_item_id'])->search()->getResult();
        if (empty($timeSwitchData)) {
            throw new InvalidArgumentException('The desired address is not controlled by this piece of software.', E_WARNING);
        }
        $timeSwitchData = $timeSwitchData[0];
        $autoItem       = $this->opcItemModel->loadEntry($timeSwitchData['opc_item_write']);
        if (empty($autoItem)) {
            throw new ConfigurationException('Time Switch was found, but the auto-item is invalid.', E_WARNING);
        }
        $timeSwitchData['write_address'] = $writeItem['opc_item_address'];
        $timeSwitchData['auto_address']  = $autoItem->opc_item_address;
        $this->response->set('timeswitch', $timeSwitchData);
    }

    /**
     * I will
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryInconsistentException
     * @throws \noxkiwi\dataabstraction\Exception\EntryMissingException
     */
    protected function viewSet(): void
    {
        if ($this->request->isDefined('timeswitch_id')) {
            $entry = $this->timeSwitchModel->loadEntry(
                $this->request->get('timeswitch_id')
            );
        } else {
            $entry = $this->timeSwitchModel->getEmptyEntry();
        }
        $entry->timeswitch_monday    = $this->request->get('monday');
        $entry->timeswitch_tuesday   = $this->request->get('tuesday');
        $entry->timeswitch_wednesday = $this->request->get('wednesday');
        $entry->timeswitch_thursday  = $this->request->get('thursday');
        $entry->timeswitch_friday    = $this->request->get('friday');
        $entry->timeswitch_saturday  = $this->request->get('saturday');
        $entry->timeswitch_sunday    = $this->request->get('sunday');
        $this->timeSwitchModel->saveEntry($entry);
    }
}

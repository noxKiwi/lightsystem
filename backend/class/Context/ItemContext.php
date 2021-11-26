<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Environment;
use noxkiwi\core\Error;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Traits\TranslationTrait;
use noxkiwi\lightsystem\Model\OpcItemModel;
use noxkiwi\lightsystem\Model\OpcNodeModel;
use noxkiwi\lightsystem\Api\LightSystem\BaseClient;
use noxkiwi\lightsystem\Message\CommandMessage;
use noxkiwi\lightsystem\Path;
use noxkiwi\lightsystem\Queue\CommandQueue;
use noxkiwi\lightsystem\Updatemanager;
use noxkiwi\mailer\Mailer;
use function compact;
use function is_array;
use function var_dump;
use const E_ERROR;

/**
 * I am
 *
 * @package      noxkiwi\lightsystem
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ItemContext extends ApiContext
{
    use TranslationTrait;

    /** @var \noxkiwi\lightsystem\Api\lightsystem\BaseClient */
    private BaseClient $baseClient;
    /** @var \noxkiwi\core\Environment */
    private Environment $environment;

    /**
     * ItemContext constructor.
     * @throws \noxkiwi\core\Exception\ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();
        $this->environment = Environment::getInstance();
    }

    /**
     * I will write data to the process.
     * @throws \noxkiwi\core\Exception\ConfigurationException
     */
    protected function viewWrite(): void
    {
        $this->baseClient = BaseClient::getInstance();
        $address          = $this->request->get('tag', '');
        if (empty($address)) {
            throw new InvalidArgumentException("Missing parameter 'tag'", E_ERROR);
        }
        $writeValue = $this->request->get('value');
        if ($writeValue === null) {
            throw new InvalidArgumentException("Missing parameter 'value'", E_ERROR);
        }
        if ($writeValue === 'VOID') {
            $currentValue = $this->baseClient->read($address);
            if (is_array($currentValue) || $currentValue === null) {
                $error = $this->baseClient->getFirstError();
                if (! empty($error)) {
                    $this->feedbackError($this->translate($error[Error::KEY_CODE], compact('address')));

                    return;
                }
                $this->feedbackError($this->translate('write_invalid_current', compact('address')));

                return;
            }
            $writeValue = $currentValue === 0 ? 1 : 0;
        }

        $name = $address;
        $element = OpcItemModel::getInstance();
        $element->addFilter('opc_item_address', $address);
        $item = $element->search();
        if (! empty($item[0]['opc_node_id'])) {
            $node = OpcNodeModel::getInstance()->loadEntry($item[0]['opc_node_id']);
            if (! empty($node->opc_node_name)) {
                $name = $node->opc_node_name;
            }
        }
        $text                  = "WriteValue: $name was set to $writeValue";
        $mailSubject           = 'Address has been written';
        $mailBody              = <<<HTML
<p>Oh hi,</p>
<p>I am [CORVUS] Automation bot.
<br />
$text
<br /><i>{$address}</i></p>
<p>If you have any concern about this notification, please don't hesitate to catch up with us.</p>
<p>Have a nice day!</p>
HTML;
        $mailContent           = FrontendHelper::parseFile(Path::getInheritedPath(Path::MAIL_TEMPLATE . 'html/template.php'), compact('mailBody', 'mailSubject'));
        $commandMessage        = new CommandMessage();
        $commandMessage->item  = $address;
        $commandMessage->value = $writeValue;
        $mailer                = Mailer::getInstance();
        $mailer->setHtml();
        $mailer->setFrom('no-reply@nox.kiwi', 'Nox Sender');
        $mailer->setBody($mailContent);
        $mailer->setSubject("{$address} geschrieben.");
        $mailer->addTo('jan.nox@pm.me', 'Jan Nox');
        $mailer->send();
        $commandQueue = new CommandQueue('Command');
        $commandQueue->add($commandMessage);
        $this->feedbackSuccess($this->translate('write_success', compact('address')));
    }

    /**
     * I will bind the given tags array to the currently logged in user.
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function viewBind(): void
    {
        Updatemanager::getInstance()->bindTags(
            (array)$this->request->get('tags', [])
        );
    }
}

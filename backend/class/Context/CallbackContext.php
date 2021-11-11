<?php declare(strict_types = 1);
namespace noxkiwi\lightsystem\Context;

use noxkiwi\core\Context;

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
abstract class CallbackContext extends Context
{
    public const REQUEST_KEY_METHOD       = 'method';
    public const REQUEST_KEY_DATA         = 'data';
    public const RESPONSE_KEY_DATA        = 'response';
    public const RESPONSE_KEY_ERRORS      = 'errors';
    public const RESPONSE_KEY_MODAL_TITLE = 'modal>title';
    public const RESPONSE_KEY_MODAL_BODY  = 'modal>body';
    public const RESPONSE_KEY_MODAL_HEAD  = 'modal>head';
    public const RESPONSE_KEY_MODAL_FOOT  = 'modal>foot';
    public const RESPONSE_KEY_MODAL_SIZE  = 'modal>size';
    public const RESPONSE_KEY_MODAL_STYLE = 'modal>style';

    public function __construct()
    {
        parent::__construct();
        $this->request->injectJsonData();
        $this->response->set('jslines', []);
    }

    /**
     * I will show an error message on the client's browser.
     *
     * @param string $text
     */
    final protected function feedbackError(string $text): void
    {
        $this->addCallback('Feedback.Warning(\'' . $text . '\');');
    }

    /**
     * I will add the given $jsLine to the response object.
     *
     * @param string $jsLine
     */
    final protected function addCallback(string $jsLine): void
    {
        $jslines   = $this->response->get('jslines', []);
        $jslines[] = $jsLine;
        $this->response->set('jslines', $jslines);
    }

    /**
     * I will show an info message on the client's browser.
     *
     * @param string $text
     */
    final protected function feedbackInfo(string $text): void
    {
        $this->addCallback('Feedback.Info(\'' . $text . '\');');
    }

    /**
     * I will show a success message on the client's browser.
     *
     * @param string $text
     */
    final protected function feedbackSuccess(string $text): void
    {
        $this->addCallback('Feedback.Success(\'' . $text . '\');');
    }
}

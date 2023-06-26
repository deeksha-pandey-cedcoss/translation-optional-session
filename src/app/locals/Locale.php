<?php

namespace MyApp;

use Phalcon\Di\Injectable;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class Locale extends Injectable
{
    /**
     * @return NativeArray
     */
    public function getTranslator(): NativeArray
    {
        session_start();

        $language = $_SESSION['lang'];

        $messages = [];

        $translationFile = APP_PATH . '/messages/' . $language;

        if (true !== file_exists($translationFile)) {
            $translationFile = APP_PATH . '/messages/en-GB.php';
        }
        
        require $translationFile;

        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);
        $di = $this->getDI();

        if ($di->get('cache')->has('key1')) {
            if ($di->get('cache')->has('key2') == $language) {

                $di->get('cache')->get('key1', $messages);
            }
        } else {
            $di->get('cache')->set('key1', $messages);
            $di->get('cache')->set('key2', $language);
        }

        return $factory->newInstance(
            'array',
            [
                'content' => $messages,
            ]
        );
    }
}

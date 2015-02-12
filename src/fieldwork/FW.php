<?php

namespace fieldwork;
use Exception;

/**
 * Class JF
 * Static class used to globally register and retrieve forms
 * @package fieldwork
 */
class FW
{

    private static $forms       = array();
    private static $ajaxMethods = array();

    /**
     * Registers given form globally
     *
     * @param Form $form
     */
    static function registerForm (Form $form)
    {
        if (!in_array($form, self::$forms))
            array_push(self::$forms, $form);
    }

    /**
     * Registers given ajax method globally.
     *
     * @param Callback $ajaxMethod
     */
    static function registerAjaxMethod (Callback $ajaxMethod)
    {
        self::$ajaxMethods[$ajaxMethod->getSlug()] = $ajaxMethod;
    }

    /**
     * Retrieves a form by its slug
     *
     * @param string $slug
     *
     * @return Form|null form or null if not found
     */
    static function getForm ($slug)
    {
        foreach (self::$forms as $form)
            /* @var $form Form */
            if ($form->getGlobalSlug() == $slug)
                return $form;
        return null;
    }

    /**
     * Retrieves an ajax method by its slug
     *
     * @param string $slug
     *
     * @return Callback ajaxmethod
     */
    static function getCallback ($slug)
    {
        return self::$ajaxMethods[$slug];
    }

    /**
     * Lists slugs of all ajax methods
     * @return array
     */
    static function listAjaxMethods ()
    {
        return array_keys(self::$ajaxMethods);
    }

    /**
     * If any of your form callbacks uses header redirects, make sure to call before_content() and after_content()
     * before output starts and after output ends.
     */
    static function before_content ()
    {
        ob_start();
    }

    /**
     * If any of your form callbacks uses header redirects, make sure to call before_content() and after_content()
     * before output starts and after output ends.
     */
    static function after_content ()
    {
        ob_end_flush();
    }

    /**
     * @return array response
     */
    static function handleAjaxRequest ()
    {
        try {
            $data = new FormResults($_POST);
            if (!isset($_GET['callback']))
                throw new \Exception('No callback provided');
            $callback = FW::getCallback($_GET['callback']);
            $response = $callback->run($data);
            return $response;
        } catch (Exception $e) {
            return array(
                'error'        => true,
                'errorClass'   => get_class($e),
                'errorMessage' => $e->getMessage()
            );
        }
    }

}
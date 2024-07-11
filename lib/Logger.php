<?php

class Logger
{
    /**
     * Logs an activity for the custom module.
     *
     * @param string $message The log message.
     * @param array $data Additional data to log (optional).
     * @param string|null $result The result of the logged action (optional).
     * @return void
     */
    public static function logActivity(string $message, array $data = [], string $result = null): void
    {
        logModuleCall(
            'customModule',
            __FUNCTION__,
            $message,
            $data,
            $result
        );
    }
}

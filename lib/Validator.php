<?php

class Validator
{
    /**
     * Validates storage size format.
     *
     * @param string $storage Storage size to validate (e.g., '100GB').
     * @return bool True if storage size format is valid, false otherwise.
     */
    public static function validateStorage(string $storage): bool
    {
        return (bool) preg_match('/^\d+(GB|TB)$/', $storage);
    }

    /**
     * Validates bandwidth size format.
     *
     * @param string $bandwidth Bandwidth size to validate (e.g., '50GB').
     * @return bool True if bandwidth size format is valid, false otherwise.
     */
    public static function validateBandwidth(string $bandwidth): bool
    {
        return (bool) preg_match('/^\d+(GB|TB)$/', $bandwidth);
    }
}

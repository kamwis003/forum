<?php
declare(strict_types=1);

/**
 * Filtruje przekleństwa w wiadomości.
 *
 * @param string $message Wiadomość do filtrowania
 * @return string Wiadomość po filtracji
 */
function filterMessage(string $message): string
{
    return preg_replace("/\bcholera\b/i", "co przeklinasz?", $message);
}

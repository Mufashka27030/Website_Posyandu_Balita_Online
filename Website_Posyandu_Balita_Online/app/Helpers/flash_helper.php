<?php

if (! function_exists('flash_text')) {
    /**
     * Render one flash-message key as safely escaped HTML.
     *
     * Flash data can hold a plain string OR the array returned by
     * Validator::getErrors(). Echoing either one raw lets attacker-controlled
     * text (exception messages, user-entered names) break out of the HTML
     * context, so escaping happens here and only here.
     */
    function flash_text(string $key, ?string &$has = null): string
    {
        $value = session()->getFlashdata($key);

        if (! is_array($value)) {
            $value = [$value];
        }

        $has  = ! empty(array_filter($value, static fn ($v) => $v !== null && $v !== ''));
        $html = '';

        foreach ($value as $item) {
            if ($item === null || $item === '') {
                continue;
            }

            $html .= '<div>' . esc(is_scalar($item) ? (string) $item : print_r($item, true)) . '</div>';
        }

        return $html;
    }
}

if (! function_exists('flash_block')) {
    /**
     * Alert markup for a flash key. Returns '' when the key is empty so the
     * caller does not emit an empty <div class="alert">.
     */
    function flash_block(string $key, string $class): string
    {
        $body = flash_text($key, $has);

        return $has ? '<div class="alert ' . $class . '">' . $body . '</div>' : '';
    }
}

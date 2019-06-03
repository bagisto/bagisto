<?php

class Welcome {

    /**
     * Render view for class.
     *
     */
    public function render()
    {
        ob_start();

        include __DIR__ . '/../Views/welcome.blade.php';

        return ob_get_clean();
    }
}

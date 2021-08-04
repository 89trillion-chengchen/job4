<?php
namespace framework\mvc\view;

use framework\mvc\IViewFactory;

class StringViewFactory implements IViewFactory {
    public function createView($model) {
        return new StringView($model);
    }
}

?>
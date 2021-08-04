<?php
namespace framework\mvc\view;

use framework\mvc\IViewFactory;

class JSONViewFactory implements IViewFactory {
    public function createView($model) {
        return new JSONView($model);
    }
}

?>
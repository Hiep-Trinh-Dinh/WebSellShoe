<?php
namespace Admin;

class AdminController extends \BaseController {
    public function __construct() {
        $this->requireAdmin();
    }
}
?> 
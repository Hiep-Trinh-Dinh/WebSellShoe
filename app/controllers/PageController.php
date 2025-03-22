<?php
class PageController extends BaseController {
    public function about() {
        $this->view('layouts/main', [
            'content' => 'page/about.php',
            'title' => 'Giới thiệu'
        ]);
    }

    public function contact() {
        $this->view('layouts/main', [
            'content' => 'page/contact.php',
            'title' => 'Liên hệ'
        ]);
    }
}
?> 
<?php

class Machine
{
    public $data_barang;
    public function __construct()
    {
        $this->data_barang = [];
    }

    public function getDataBarang()
    {
        $this->data_barang = [
            'Biskuit' => [6000, 10, "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Wheat_biscuit.jpg/283px-Wheat_biscuit.jpg", 1],
            'Chips' => [8000, 10, "https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Potato-Chips.jpg/320px-Potato-Chips.jpg", 2],
            'Oreo' => [10000, 10, "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Oreo-Two-Cookies.png/320px-Oreo-Two-Cookies.png", 3],
            'Tango' => [12000, 10, "http://www.tango.id/assets/images-product/new-long-wafertango-coklat.png", 4],
            'Cokelat' => [15000, 10, "https://doktersehat.com/wp-content/uploads/2018/01/cokelat-dokrtersehat-864x450.png", 5],
        ];

        return json_encode($this->data_barang);
    }
}


?>
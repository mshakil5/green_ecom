<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductCard extends Component
{
    public $product;
    public $company;

    public function __construct($product, $company)
    {
        $this->product = $product;
        $this->company = $company;
    }

    public function render()
    {
        return view('components.product-card');
    }
}

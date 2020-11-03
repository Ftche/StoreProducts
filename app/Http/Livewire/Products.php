<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use PDORow;

class Products extends Component
{
    public $products, $title, $description, $product_id;
    public $isOpen = 0;

    public function render()
    {
        $this->products = Product::all();
        return view('livewire.products');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->title = '';
        $this->description = '';
        $this->product_id = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Product::updateOrCreate(['id' => $this->product_id], [
            'title' => $this->title,
            'description' => $this->description
        ]);

        session()->flash('message',
            $this->product_id ? 'Product Updated Successfully.' : 'Product Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('message', 'Product Deleted Successfully.');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $this->product_id = $id;
        $this->title = $product->title;
        $this->description = $product->description;

        $this->openModal();
    }
}

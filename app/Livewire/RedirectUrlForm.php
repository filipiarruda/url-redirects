<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Redirect;
use Vinkla\Hashids\Facades\Hashids;

class RedirectUrlForm extends Component
{
    public string $redirect_url = '';
    public $editId = null;
    public $edit_url = '';

    protected $rules = [
        'redirect_url' => 'required|url',
    ];

    public function save()
    {
        $this->validate();
        $lastId = Redirect::max('id') ?? 0;
        $nextId = $lastId + 1;
        $code = Hashids::encode($nextId);
        Redirect::create([
            'code' => $code,
            'redirect_url' => $this->redirect_url,
            'active' => true,
        ]);

        session()->flash('success', 'Nova URL de redirecionamento cadastrada!');
        $this->reset('redirect_url');
    }

    public function delete($id)
    {
        $redirect = Redirect::find($id);
        if ($redirect) {
            $redirect->delete();
            session()->flash('success', 'URL removida com sucesso!');
        }
    }

    public function edit($id)
    {
        $redirect = Redirect::find($id);
        if ($redirect) {
            $this->editId = $redirect->id;
            $this->edit_url = $redirect->redirect_url;
        }
    }

    public function update()
    {
        $this->validate([
            'edit_url' => 'required|url',
        ]);

        $redirect = Redirect::find($this->editId);
        if ($redirect) {
            $redirect->redirect_url = $this->edit_url;
            $redirect->save();
            session()->flash('success', 'URL editada com sucesso!');
        }
        $this->editId = null;
        $this->edit_url = '';
    }

    public function cancelEdit()
    {
        $this->editId = null;
        $this->edit_url = '';
    }

    public function toggleActive($id)
    {
        $redirect = Redirect::find($id);
        if ($redirect) {
            $redirect->active = !$redirect->active;
            $redirect->save();
            session()->flash('success', 'Status alterado com sucesso!');
        }
    }

    public function render()
    {
        return view('livewire.redirect-url-form', [
            'redirects' => Redirect::latest()->paginate(5),
        ]);
    }
}

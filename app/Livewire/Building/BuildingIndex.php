<?php

namespace App\Livewire\Building;

use App\Models\Building;
use Livewire\Component;
use Mary\Traits\Toast;

class BuildingIndex extends Component
{
    use Toast;

    public string $search = '';
    public bool $modal = false;
    public ?Building $building = null;

    public string $code = '';
    public string $name = '';
    public string $location = '';

    public function render()
    {
        $buildings = Building::where('name', 'like', "%{$this->search}%")
            ->orWhere('code', 'like', "%{$this->search}%")
            ->get();

        return view('livewire.building.building-index', compact('buildings'));
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->building = Building::find($id);
            $this->code = $this->building->code;
            $this->name = $this->building->name;
            $this->location = $this->building->location ?? '';
        } else {
            $this->reset(['code', 'name', 'location', 'building']);
        }
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|unique:buildings,code,' . ($this->building->id ?? 'NULL'),
            'name' => 'required',
        ]);

        Building::updateOrCreate(
            ['id' => $this->building->id ?? null],
            ['code' => $this->code, 'name' => $this->name, 'location' => $this->location]
        );

        $this->modal = false;
        $this->reset(['code', 'name', 'location', 'building']);
        $this->success('Building saved successfully!');
    }

    public function delete($id)
    {
        Building::find($id)->delete();
        $this->success('Building deleted successfully!');
    }
}
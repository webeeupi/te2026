<?php

namespace App\Livewire\Classroom;

use App\Models\Classroom;
use App\Models\Building;
use Livewire\Component;
use Mary\Traits\Toast;

class ClassroomIndex extends Component
{
    use Toast;

    public string $search = '';
    public bool $modal = false;
    public ?Classroom $classroom = null;

    public string $code = '';
    public string $name = '';
    public string $floor = '';
    public ?int $building_id = null;

    public function render()
    {
        $classrooms = Classroom::with('building')
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('code', 'like', "%{$this->search}%")
            ->get();

        $buildings = Building::all();

        return view('livewire.classroom.classroom-index', compact('classrooms', 'buildings'));
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->classroom = Classroom::find($id);
            $this->code = $this->classroom->code;
            $this->name = $this->classroom->name;
            $this->floor = $this->classroom->floor ?? '';
            $this->building_id = $this->classroom->building_id;
        } else {
            $this->reset(['code', 'name', 'floor', 'building_id', 'classroom']);
        }
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'building_id' => 'required',
        ]);

        Classroom::updateOrCreate(
            ['id' => $this->classroom->id ?? null],
            [
                'code' => $this->code,
                'name' => $this->name,
                'floor' => $this->floor,
                'building_id' => $this->building_id,
            ]
        );

        $this->modal = false;
        $this->reset(['code', 'name', 'floor', 'building_id', 'classroom']);
        $this->success('Classroom saved successfully!');
    }

    public function delete($id)
    {
        Classroom::find($id)->delete();
        $this->success('Classroom deleted successfully!');
    }
}
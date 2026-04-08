<?php

namespace App\Livewire\Sensor;

use App\Models\Sensor;
use App\Models\Classroom;
use Livewire\Component;
use Mary\Traits\Toast;

class SensorIndex extends Component
{
    use Toast;

    public bool $modal = false;
    public ?Sensor $sensor = null;

    public string $name = '';
    public string $unit = '%';
    public ?int $classroom_id = null;

    public function render()
    {
        $sensors = Sensor::with('classroom.building')->get();
        $classrooms = Classroom::with('building')->get();

        return view('livewire.sensor.sensor-index', compact('sensors', 'classrooms'));
    }

    public function openModal($id = null)
    {
        if ($id) {
            $this->sensor = Sensor::find($id);
            $this->name = $this->sensor->name;
            $this->unit = $this->sensor->unit;
            $this->classroom_id = $this->sensor->classroom_id;
        } else {
            $this->reset(['name', 'unit', 'classroom_id', 'sensor']);
            $this->unit = '%';
        }
        $this->modal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'classroom_id' => 'required',
        ]);

        Sensor::updateOrCreate(
            ['id' => $this->sensor->id ?? null],
            [
                'name' => $this->name,
                'unit' => $this->unit,
                'classroom_id' => $this->classroom_id,
            ]
        );

        $this->modal = false;
        $this->reset(['name', 'unit', 'classroom_id', 'sensor']);
        $this->success('Sensor saved successfully!');
    }

    public function delete($id)
    {
        Sensor::find($id)->delete();
        $this->success('Sensor deleted successfully!');
    }
}
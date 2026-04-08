<?php

Route::livewire('/', 'pages::auth.login')->name('login');
Route::livewire('/admin', 'pages::admin.idx')->name('admin');
Route::livewire('/admin/client', 'pages::admin.client.idx')->name('admin.client');
Route::livewire('/client', 'pages::client.idx')->name('client');
Route::livewire('/admin/building', 'building.building-index')->name('admin.building');
Route::livewire('/admin/classroom', 'classroom.classroom-index')->name('admin.classroom');
Route::livewire('/admin/sensor', 'sensor.sensor-index')->name('admin.sensor');
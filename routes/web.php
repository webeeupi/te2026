<?php

Route::livewire('/', 'pages::auth.login')->name('login');
Route::livewire('/admin', 'pages::admin.idx')->name('admin');
Route::livewire('/admin/program', 'pages::admin.program.idx')->name('admin.program');
Route::livewire('/program', 'pages::program.idx')->name('program');
Route::livewire('/program/schedule', 'pages::program.schedule.idx')->name('program.schedule');
Route::livewire('/program/teacher', 'pages::program.teacher.idx')->name('program.teacher');
Route::livewire('/program/subject', 'pages::program.subject.idx')->name('program.subject');
Route::livewire('/program/assignment', 'pages::program.assignment.idx')->name('program.assignment');
